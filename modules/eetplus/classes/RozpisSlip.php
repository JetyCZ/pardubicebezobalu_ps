<?php
  require_once(_PS_MODULE_DIR_.'eetplus/classes/Rozpis.php');
 class RozpisSlip extends Rozpis{

     private $order_slip;
      
     public function getRozpis($order_slip) {
     // see HTMLTemplateOrderSlipCore :: getContent    
       $this->order_slip = $order_slip; 
       $this->order->total_paid_tax_excl = $this->order->total_paid_tax_incl = $this->order->total_products = $this->order->total_products_wt = 0;

        if ($this->order_slip->amount > 0) {
            foreach ($this->order->products as &$product) {
                $product['total_price_tax_excl'] = $product['unit_price_tax_excl'] * $product['product_quantity'];
                $product['total_price_tax_incl'] = $product['unit_price_tax_incl'] * $product['product_quantity'];

                if ($this->order_slip->partial == 1) {
                    $order_slip_detail = Db::getInstance()->getRow('
                        SELECT * FROM `'._DB_PREFIX_.'order_slip_detail`
                        WHERE `id_order_slip` = '.(int)$this->order_slip->id.'
                        AND `id_order_detail` = '.(int)$product['id_order_detail']);

                    $product['total_price_tax_excl'] = $order_slip_detail['amount_tax_excl'];
                    $product['total_price_tax_incl'] = $order_slip_detail['amount_tax_incl'];
                }

                $this->order->total_products += $product['total_price_tax_excl'];
                $this->order->total_products_wt += $product['total_price_tax_incl'];
                $this->order->total_paid_tax_excl = $this->order->total_products;
                $this->order->total_paid_tax_incl = $this->order->total_products_wt;
            }
        } else {
            $this->order->products = null;
        } 
        unset($product); // remove reference
        if ($this->order_slip->shipping_cost == 0) {
            $this->order->total_shipping_tax_incl = $this->order->total_shipping_tax_excl = 0;
        } 
      
  
       if(isset($order_slip->total_products_tax_incl)) {
           //amount - jen produkty bez DPH
            $celk_trzba =   $order_slip->total_products_tax_incl + ($order_slip->shipping_cost?$order_slip->total_shipping_tax_incl:0);
         }
        else {
            $celk_trzba =   $order_slip->amount;
         }
       
        
        $this->rozpis['celk_trzba'] =  $this->finalRound(Eetplus::convertToCzk($celk_trzba, $this->currencyData));
        
 
      
         $taxes = array();
        
             $taxes['product_tax']   = $this->translateRates($this->getProductTaxesBreakdown());   
             $taxes['shipping_tax']  = $this->translateRates($this->getShippingTaxesBreakdown()); 
             $taxes['ecotax']   = $this->translateRates($order_slip->getEcoTaxTaxesBreakdown());
             $this->rozpis['dph'] = $this->finalDph($taxes);     
         
         if($this->tax_exempt &&   Configuration::get('PS_TAX')) {
             $this->rozpis['eet_extra']['zakl_nepodl_dph'] = $this->rozpis['celk_trzba'];
         } 
         elseif(Configuration::get('PS_TAX')) {
            $this->rozpis['dph'] = $this->finalDph($taxes);
         }
 
          if(count($this->pouzite_zbozi) &&   Configuration::get('PS_TAX')) {
            while(list($sazba,$val) = each($this->pouzite_zbozi)) {
               $this->rozpis['pouzit_zboz'][$sazba]['celkem'] = $this->finalRound(Eetplus::convertToCzk($val, $this->currencyData));
               $this->rozpis['pouzit_zboz'][$sazba]['rate']  =$this->pouzite_zbozi_rates[$sazba];
            }  
          }
            if(Configuration::get('EETPLUS_CERPANI')) {
              if(! $this->urceno_cerp_zuct == 0) {
                  $this->rozpis['eet_extra']['urceno_cerp_zuct'] =  $this->finalRound(Eetplus::convertToCzk($this->urceno_cerp_zuct, $this->currencyData));
              }
              if(! $this->cerp_zuct == 0) {
                  $this->rozpis['eet_extra']['cerp_zuct'] =   $this->finalRound(Eetplus::convertToCzk($this->cerp_zuct, $this->currencyData));
              }  
               if(! $this->cest_sluz == 0) {
                  $this->rozpis['eet_extra']['cest_sluz'] =  $this->finalRound(Eetplus::convertToCzk($this->cest_sluz, $this->currencyData));
              } 
          }
          
          return $this->rozpis;
     }
     
     
     // zatim nelze prvest na rodice
     protected function getProductTaxesBreakdown()
    {
        // $breakdown will be an array with tax rates as keys and at least the columns:
        //     - 'total_price_tax_excl'
        //     - 'total_amount'
        $breakdown = array();
        // TADY SE Z VNITRNI FUNKCE VRACI SPATNE
        $details = $this->getProductTaxesDetails($this->order->products);

        foreach ($details as $row) {
            $rate = sprintf('%.3f', $row['tax_rate']);
            if (!isset($breakdown[$rate])) {
                $breakdown[$rate] = array(
                    'total_price_tax_excl' => 0,
                    'total_amount' => 0,
                    'id_tax' => $row['id_tax'],
                    'rate' =>$rate,
                );
            }

            $breakdown[$rate]['total_price_tax_excl'] += $row['total_tax_base'];
            $breakdown[$rate]['total_amount'] += $row['total_amount'];
        }

        foreach ($breakdown as $rate => $data) {
            $breakdown[$rate]['total_price_tax_excl'] = self::ps_round($data['total_price_tax_excl'], self::$PS_PRICE_COMPUTE_PRECISION, self::$order_round_mode);
            $breakdown[$rate]['total_amount'] = self::ps_round($data['total_amount'], self::$PS_PRICE_COMPUTE_PRECISION, self::$order_round_mode);
        }

        ksort($breakdown);

        return $breakdown;
    }

    /**
     * Returns Shipping tax breakdown elements
     *
     * @return Array Shipping tax breakdown elements
     */
    private function getShippingTaxesBreakdown()
    {
        $taxes_breakdown = array();
        $tax = new Tax();
        $tax->rate = $this->order->carrier_tax_rate;
        $tax_calculator = new TaxCalculator(array($tax));
            if(!empty($this->order_slip->total_shipping_tax_excl) && !empty($this->order_slip->total_shipping_tax_incl)
              &&  ( $this->order_slip->total_shipping_tax_incl >= $this->order_slip->total_shipping_tax_excl )
              &&   $this->order_slip->total_shipping_tax_incl > $this->order_slip->shipping_cost_amount
            ) {
                $total_tax_excl =  $this->order_slip->total_shipping_tax_excl;
                $shipping_tax_amount= $this->order_slip->total_shipping_tax_incl - $this->order_slip->total_shipping_tax_excl;
               
            }
              else {
                $total_tax_excl = $tax_calculator->removeTaxes($this->order_slip->shipping_cost_amount);
                $shipping_tax_amount = $this->order_slip->shipping_cost_amount - $total_tax_excl;
            }
            
        

        if ($shipping_tax_amount > 0) {
            $taxes_breakdown[] = array(
                'rate' =>  $this->order->carrier_tax_rate,
                'total_amount' => $shipping_tax_amount,
                'total_price_tax_excl' => $total_tax_excl
            );
        }

        return $taxes_breakdown;
    } 
 }
