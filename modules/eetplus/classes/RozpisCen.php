<?php
    require_once(_PS_MODULE_DIR_.'eetplus/classes/Rozpis.php');
 
  class RozpisCen  extends Rozpis {
     
 
     public function getRozpis() {
         $this->rozpis['celk_trzba'] =  $this->finalRound(Eetplus::convertToCzk($this->order->total_paid, $this->currencyData));
        
         $taxes = array();
        
         $taxes['product_tax']   = $this->translateRates($this->getProductTaxesBreakdown());  
         $taxes['shipping_tax']  = $this->translateRates($this->getShippingTaxesBreakdown($this->order));
         $taxes['ecotax']   = $this->translateRates($this->getEcoTaxTaxesBreakdown());
         $taxes['wrapping_tax'] = $this->translateRates($this->getWrappingTaxesBreakdown());
         
         if($this->tax_exempt &&   Configuration::get('PS_TAX')) {
             $this->rozpis['eet_extra']['zakl_nepodl_dph'] = $this->rozpis['celk_trzba'];
         } 
         elseif(Configuration::get('PS_TAX')) {
            $this->rozpis['dph'] = $this->finalDph($taxes);
         }
                  
        
        
          if(count($this->pouzite_zbozi) &&   Configuration::get('PS_TAX')) {
            while(list($sazba,$val) = each($this->pouzite_zbozi)) {
               $this->rozpis['pouzit_zboz'][$sazba]['celkem'] = $this->finalRound(Eetplus::convertToCzk($val,$this->currencyData));
               $this->rozpis['pouzit_zboz'][$sazba]['rate']  =$this->pouzite_zbozi_rates[$sazba];
            }  
          }
          if(Configuration::get('EETPLUS_CERPANI')) {
              if(! $this->urceno_cerp_zuct == 0) {
                  $this->rozpis['eet_extra']['urceno_cerp_zuct'] =  $this->finalRound(Eetplus::convertToCzk($this->urceno_cerp_zuct,$this->currencyData)); 
              }
              if(! $this->cerp_zuct == 0) {
                  $this->rozpis['eet_extra']['cerp_zuct'] = $this->finalRound(Eetplus::convertToCzk($this->cerp_zuct,$this->currencyData));  
              }  
               if(! $this->cest_sluz == 0) {
                  $this->rozpis['eet_extra']['cest_sluz'] =   $this->finalRound(Eetplus::convertToCzk($this->cest_sluz,$this->currencyData));   
              } 
          }

          
          return $this->rozpis;
     } 


    
    private function getShippingTaxesBreakdown()
    {
        // No shipping breakdown if no shipping!
        if ($this->order->total_shipping_tax_excl == 0) {
            return array();
        }

        // No shipping breakdown if it's free!
        foreach ($this->order->getCartRules() as $cart_rule) {
            if ($cart_rule['free_shipping']) {
                return array();
            }
        }

        $shipping_tax_amount = $this->order->total_shipping_tax_incl - $this->order->total_shipping_tax_excl;

        if (Configuration::get('PS_INVOICE_TAXES_BREAKDOWN') || Configuration::get('PS_ATCP_SHIPWRAP') &&  (int)$this->order->invoice_number) {
            $shipping_breakdown = Db::getInstance()->executeS(
                'SELECT t.id_tax, t.rate, oit.amount as total_amount
                 FROM `'._DB_PREFIX_.'tax` t
                 INNER JOIN `'._DB_PREFIX_.'order_invoice_tax` oit ON oit.id_tax = t.id_tax
                 WHERE oit.type = "shipping" AND oit.id_order_invoice = '.(int)$this->order->invoice_number
            );

            $sum_of_split_taxes = 0;
            $sum_of_tax_bases = 0;
            foreach ($shipping_breakdown as &$row) {
                if (Configuration::get('PS_ATCP_SHIPWRAP')) {
                    $row['total_price_tax_excl'] = self::ps_round($row['total_amount'] / $row['rate'] * 100, self::$PS_PRICE_COMPUTE_PRECISION, $this->order->round_mode);
                    $sum_of_tax_bases += $row['total_tax_excl'];
                } else {
                    $row['total_price_tax_excl'] = $this->order->total_shipping_tax_excl;
                }

                $row['total_amount'] = self::ps_round($row['total_amount'], self::$PS_PRICE_COMPUTE_PRECISION, $this->order->round_mode);
                $sum_of_split_taxes += $row['total_amount'];
            }
            unset($row);

            $delta_amount = $shipping_tax_amount - $sum_of_split_taxes;

            if ($delta_amount != 0) {
                $this->spreadAmount($delta_amount, self::$PS_PRICE_COMPUTE_PRECISION, $shipping_breakdown, 'total_amount');
            }

            $delta_base = $this->total_shipping_tax_excl - $sum_of_tax_bases;

            if ($delta_base != 0) {
                $this->spreadAmount($delta_base, self::$PS_PRICE_COMPUTE_PRECISION, $shipping_breakdown, 'total_tax_excl');
            }
        } else {
            $shipping_breakdown = array(
                array(
                    'total_price_tax_excl' => $this->order->total_shipping_tax_excl,  
                    'rate' => $this->order->carrier_tax_rate,
                    'total_amount' =>$shipping_tax_amount,  
                    'id_tax' => null,
                )
            );
        }

        return $shipping_breakdown;
    }
    
     private function getWrappingTaxesBreakdown()
    {
        if ($this->order->total_wrapping_tax_excl == 0) {
            return array();
        }

        $wrapping_tax_amount = $this->order->total_wrapping_tax_incl - $this->order->total_wrapping_tax_excl;

        $wrapping_breakdown = Db::getInstance()->executeS(
            'SELECT t.id_tax, t.rate, oit.amount as total_amount
            FROM `'._DB_PREFIX_.'tax` t
            INNER JOIN `'._DB_PREFIX_.'order_invoice_tax` oit ON oit.id_tax = t.id_tax
            WHERE oit.type = "wrapping" AND oit.id_order_invoice = '.(int)$this->id
        );

        $sum_of_split_taxes = 0;
        $sum_of_tax_bases = 0;
        $total_tax_rate = 0;
        foreach ($wrapping_breakdown as &$row) {
            if (Configuration::get('PS_ATCP_SHIPWRAP')){
                $row['total_tax_excl'] = self::ps_round($row['total_amount'] / $row['rate'] * 100, self::$PS_PRICE_COMPUTE_PRECISION, $this->order->round_mode);
                $sum_of_tax_bases += $row['total_tax_excl'];
            } else {
                $row['total_tax_excl'] = $this->order->total_wrapping_tax_excl;
            }

            $row['total_amount'] = self::ps_round($row['total_amount'], self::$PS_PRICE_COMPUTE_PRECISION, $this->order->round_mode);
            $sum_of_split_taxes += $row['total_amount'];
            $total_tax_rate += (float)$row['rate'];
        }
        unset($row);

        $delta_amount = $wrapping_tax_amount - $sum_of_split_taxes;

        if ($delta_amount != 0) {
            $this->spreadAmount($delta_amount, self::$PS_PRICE_COMPUTE_PRECISION, $wrapping_breakdown, 'total_amount');
        }

        $delta_base = $this->order->total_wrapping_tax_excl - $sum_of_tax_bases;

        if ($delta_base != 0) {
            $this->spreadAmount($delta_base, self::$PS_PRICE_COMPUTE_PRECISION, $wrapping_breakdown, 'total_tax_excl');
        }

        if (!Configuration::get('PS_INVOICE_TAXES_BREAKDOWN') && !Configuration::get('PS_ATCP_SHIPWRAP')) {
            $wrapping_breakdown = array(
                array(
                    'total_price_tax_excl' => $this->total_wrapping_tax_excl,
                    'rate' => $total_tax_rate,
                    'total_amount' => $wrapping_tax_amount,
                )
            );
        }

        return $wrapping_breakdown;
    }

    /**
     * Returns the ecotax taxes breakdown
     *
     * @since 1.5
     * @return array
     */
    private function getEcoTaxTaxesBreakdown()
    {
        $result = Db::getInstance()->executeS('
        SELECT `ecotax_tax_rate` as `rate`, SUM(`ecotax` * `product_quantity`) as `ecotax_tax_excl`, SUM(`ecotax` * `product_quantity`) as `ecotax_tax_incl`
        FROM `'._DB_PREFIX_.'order_detail`
        WHERE `id_order` = '.(int)$this->order->id.'
        GROUP BY `ecotax_tax_rate`');

        $taxes = array();
        foreach ($result as $row) {
            if ($row['ecotax_tax_excl'] > 0) {
                $row['total_price_tax_excl'] = $row['ecotax_tax_excl'];  
                $row['ecotax_tax_incl'] = self::ps_round($row['ecotax_tax_excl'] + ($row['ecotax_tax_excl'] * $row['rate'] / 100), _PS_PRICE_DISPLAY_PRECISION_);
                $row['total_amount'] = self::ps_round($row['ecotax_tax_excl'], _PS_PRICE_DISPLAY_PRECISION_);
                $taxes[] = $row;
            }
        }
        return $taxes;
    }
    
    
   
  }
 
