<?php
  require_once(_PS_MODULE_DIR_.'eetplus/classes/Rozpis.php');
  require_once(_PS_MODULE_DIR_.'eetplus/classes/RozpisCen.php');
 class RozpisSlipDiscounted extends Rozpis{

     private $order_slip;
      
     public function getRozpis($order_slip) {
   
      $origOrder = new Order($order_slip->id_order); 
      $origRozpis = new RozpisCen($origOrder);
      $discountShare = $origRozpis->getDiscountShare();
        
       $this->order_slip = $order_slip; 
       $this->order->total_paid_tax_excl = $this->order->total_paid_tax_incl = $this->order->total_products = $this->order->total_products_wt = 0;
       
      if(Tools::getValue('prepocet_eet')) {
          $amount = Tools::getValue('refund_prepoctene'); 
          $amount = (float)str_replace(',','.',$amount);
          $order_slip->amount =  $amount;  // musi byt spravne poslano podle verze 
      } 
      
 
        $productBreakdown = array();
        $produktyCelkem = 0;
        $total_products = 0;
         foreach ($this->order->products as $product) {
                $sazba = Eetplus::rateToSazba($product['tax_rate']);
                $share = $this->getProducTotalShare($discountShare, $product);
                $productBreakdown[$sazba]['total_price_tax_excl'] = $share['total_tax_base'];
                $productBreakdown[$sazba]['total_amount']  = $share['total_amount'];
                $productBreakdown[$sazba]['rate'] = $product['tax_rate'];
                $this->addExtraData($product, array(0=>$share));
                $total_products += $share['total_tax_base'] + $share['total_amount'];
         }
         
        if(isset($order_slip->total_products_tax_incl)) {
            $celk_trzba =   $total_products + ($order_slip->shipping_cost?$order_slip->total_shipping_tax_incl:0);
         }
        else {
            $celk_trzba =   $order_slip->amount;
         }
         
         $this->rozpis['celk_trzba'] =  $this->finalRound(Eetplus::convertToCzk($celk_trzba, $this->currencyData));
        
         $taxes = array();
        
             $taxes['product_tax']   = $this->translateRates($productBreakdown);   
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
                  $this->rozpis['eet_extra']['urceno_cerp_zuct'] =   $this->finalRound(Eetplus::convertToCzk($this->urceno_cerp_zuct, $this->currencyData));
              }
              if(! $this->cerp_zuct == 0) {
                  $this->rozpis['eet_extra']['cerp_zuct'] =    $this->finalRound(Eetplus::convertToCzk($this->cerp_zuct, $this->currencyData));
              }  
               if(! $this->cest_sluz == 0) {
                  $this->rozpis['eet_extra']['cest_sluz'] =   $this->finalRound(Eetplus::convertToCzk($this->cest_sluz, $this->currencyData));
              } 
          }
          
          return $this->rozpis;
     }
     
     private function  getProducTotalShare($discoutShare, $product) {
          while(list($id_order_detail, $val) = each($discoutShare)) {
             if($id_order_detail == $product['id_order_detail']) {
                 $retval['id_order_detail'] =$product['id_order_detail'];
                 $retval['tax_rate'] =$product['tax_rate'];
                 $retval['unit_tax_base'] =$val['tax_excl'];
                 $retval['total_tax_base'] = $val['tax_excl']*$product['product_quantity'];
                 $retval['unit_amount'] = $val['tax_excl']*($product['tax_rate']/100);
                 $retval['total_amount'] = $val['tax_excl']*$product['product_quantity']*($product['tax_rate']/100);
                 $retval['quantity'] =$product['product_quantity'];
                 return $retval;
             } 
          }
       
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
