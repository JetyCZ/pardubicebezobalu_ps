<?php

    
  class Rozpis {
     protected $order;
     protected $rozpis = array();
     protected $currencyData;
     
     protected $pouzite_zbozi = array();
     protected $pouzite_zbozi_rates = array();
     protected $details = array();
     protected $cest_sluz = 0;
/*  Celková částka plateb určená k následnému čerpání nebo zúčtování     urceno_cerp_zuct     */
     protected $urceno_cerp_zuct = 0;  // zbozi  ... kupuje voucher jako zbozi

     /* Celková částka plateb, které jsou následným čerpáním nebo zúčtováním platby    cerp_zuct */
     protected $cerp_zuct = 0;   // kupon  v objednavce     

     protected $tax_exempt = false;
     protected $tax_map = array();
 
      
     protected static $ROUND_ITEM;
     protected static $ROUND_LINE;
     protected static $ROUND_TOTAL;
     protected static $round_mode = null;
     protected static $PS_PRICE_COMPUTE_PRECISION;
     protected static $order_round_mode;
     
     protected $discountShare = array(); 
      
     protected $prefix_urceno_cerp_zuct=array();  // prefixy kuponu
     
     
     public function __construct($order) {
       if(is_object($order))
          $this->order = $order;
       elseif((int)$order)
        $this->order =  new Order($id_order);
        
        $cupons = trim(Configuration::get('EETPLUS_PREFIXCERP'));
        if($cupons && strlen($cupons)) {
           $arr = explode("\n", $cupons); 
           foreach($arr as $arr) {
             if(strlen(trim($arr))) {
                 $this->prefix_urceno_cerp_zuct[] = trim($arr);
             }  
           }
        }
        $addrtype = Configuration::get('PS_TAX_ADDRESS_TYPE');
        if(isset($order->{$addrtype})) {
            $address = new Address($order->{$addrtype});
        } else {
            $address = new Address($order->id_address_delivery);
        }
        

         $this->tax_exempt = Configuration::get('VATNUMBER_MANAGEMENT')
                            && !empty($address->vat_number)
                            && $address->id_country != Configuration::get('VATNUMBER_COUNTRY');
   
       self::$ROUND_ITEM = defined('Order::ROUND_ITEM')?Order::ROUND_ITEM:1;
       self::$ROUND_LINE = defined('Order::ROUND_LINE')?Order::ROUND_LINE:2;
       self::$ROUND_TOTAL = defined('Order::ROUND_ITEM')?Order::ROUND_TOTAL:3; 
       self::$PS_PRICE_COMPUTE_PRECISION = defined('_PS_PRICE_COMPUTE_PRECISION_')?_PS_PRICE_COMPUTE_PRECISION_:2;
       
       
       if(isset($this->order->round_mode)) {
         self::$order_round_mode = $this->order->round_mode;  
       } else {
         self::$order_round_mode  = (int)Configuration::get('PS_PRICE_ROUND_MODE');
       } 
        $currency = new Currency($this->order->id_currency);
        $currency_rate = $this->order->conversion_rate;
        if(Configuration::get('EETPLUS_KURZY') == 1) {
            $existing_id = ReceiptData::getExistingId($this->order->id, 'trzba',  (int) Configuration::get('EETPLUS_SANDBOX'));
            if($existing_id) {
                $currencyData = ReceiptData::getKurzFromExistingId($existing_id, (int) Configuration::get('EETPLUS_SANDBOX')); 
                if($currencyData && is_array($currencyData) && isset($currencyData['currency_rate'])) {
                     if($currencyData['currency_rate'] > 0 && $currencyData['iso_code'] == $currency->iso_code) {
                        $currency_rate = $currencyData['currency_rate']; 
                     }
                }
            }
            else {
                 $cr = Eetplus::ownCurrencyRate($currency);
                 if(!is_null($cr)) {
                     $currency_rate = $cr;
                 }
            }
        }
        $this->currencyData = array('id_currency'=>$this->order->id_currency, 'iso_code'=>$currency->iso_code,   'currency_rate'=> $currency_rate);
     }
     
     protected function translateRate($rate) {
         return Eetplus::rateToSazba($rate, $this->tax_map);
     }
     
   
    protected function finalRound($castka) {
       $precision =     Configuration::get('PS_PRICE_DISPLAY_PRECISION');
       if($precision == false) {
          if(isset($this->currencyData) && $this->currencyData['iso_code'] == 'CZK') {
              $currency = new Currency($this->currencyData['id_currency']);
              $precision = (int)$currency->decimals * _PS_PRICE_DISPLAY_PRECISION_;
          } 
       }
       $castka = self::ps_round($castka, $precision);

        if((int)$castka == $castka)
          return (int)$castka;

        return number_format($castka,2,'.','');
    } 
    
    protected function translateRates($breakdown) {
        $retval = array();
        while(list($key, $val) = each($breakdown)) {
            $retval[$this->translateRate($val['rate'])] = $val;
        } 
        return $retval;
     }
     
    protected function finalDph($taxes) {
      $rozpisDph = array();
      $sazby = array(1 , 2, 3);
      foreach($sazby as $sazba) {
              $rozpisDph[$sazba]['zaklad'] = 0;
              $rozpisDph[$sazba]['dan'] = 0;
              $keys = array('product_tax','shipping_tax','ecotax','wrapping_tax');
              foreach($keys as $key){
                if(isset($taxes[$key][$sazba]['total_price_tax_excl'])) {
                       $rozpisDph[$sazba]['zaklad'] += $taxes[$key][$sazba]['total_price_tax_excl'];
                       $rozpisDph[$sazba]['dan'] += $taxes[$key][$sazba]['total_amount'];
                       $rozpisDph[$sazba]['rate'] = $taxes[$key][$sazba]['rate'];
                    }
                 }
               
            // if(isset($rozpisDph[$sazba]['zaklad'])) {
                $rozpisDph[$sazba]['zaklad'] = Eetplus::convertToCzk($rozpisDph[$sazba]['zaklad'], $this->currencyData);
                $rozpisDph[$sazba]['dan'] =  Eetplus::convertToCzk($rozpisDph[$sazba]['dan'], $this->currencyData);
           //  }
         }   
      return $rozpisDph;  
    }
    

      protected function spreadAmount($amount, $precision, &$rows, $column)
    {
        if (!is_array($rows) || empty($rows)) {
            return;
        }

        $sort_function = create_function('$a, $b', "return \$b['$column'] > \$a['$column'] ? 1 : -1;");

        uasort($rows, $sort_function);

        $unit = pow(10, $precision);

        $int_amount = (int)round($unit * $amount);

        $remainder = $int_amount % count($rows);
        $amount_to_spread = ($int_amount - $remainder) / count($rows) / $unit;

        $sign = ($amount >= 0 ? 1 : -1);
        $position = 0;
        foreach ($rows as &$row) {
            $adjustment_factor = $amount_to_spread;

            if ($position < abs($remainder)) {
                $adjustment_factor += $sign * 1 / $unit;
            }

            $row[$column] += $adjustment_factor;

            ++$position;
        }
        unset($row);
    }
    
    protected function addExtraData($order_detail, $taxes = false) {
       $mappedTax = false;
       $sql = 'SELECT * FROM '._DB_PREFIX_.'product WHERE id_product = '.(int)$order_detail['product_id'];
       $row = Db::getInstance()->getRow($sql); 
       // procenta na cislo dane
      
       if((int)$order_detail['tax_rate']) {
             $rate = $order_detail['tax_rate'];
             $sazba = Eetplus::rateToSazba($rate, $this->tax_map);
       }
       elseif(is_array($taxes) && count($taxes) ) {
            foreach($taxes as $tax) {
                if($tax['id_order_detail'] == $order_detail['id_order_detail']) {
                     $sazba = $this->translateRate($tax['tax_rate']);
                     $rate = $tax['tax_rate'];// Eetplus::sazbaToRate($sazba);
                  //   $mappedTax = $tax; // u storna se nezapocita kupon
                }
            }
       }
       else {
         $rate = (int)($order_detail['unit_price_tax_incl'] -  $order_detail['unit_price_tax_excl'])/($order_detail['unit_price_tax_excl']/100);
         $sazba = Eetplus::rateToSazba($rate, $this->tax_map);
       }
       
   if(is_array($taxes) && count($taxes) ) {
       reset($taxes);
        foreach($taxes as $tax) {
            if($tax['id_order_detail'] == $order_detail['id_order_detail']) {
                 if(isset($tax['quantity']) && (int)$tax['quantity']) {
                   $tax['total_amount'] =   $tax['unit_amount'] * $tax['quantity'];
                   $tax['total_tax_base'] =   $tax['unit_tax_base'] * $tax['quantity'];
                 }
                 $mappedTax = $tax;  
            }
        }
   }
       

       
       
       if($row['condition'] != 'new' && $this->tax_exempt == false) {
         if(isset($this->pouzite_zbozi[$sazba])) {
          if($mappedTax)  {
              $this->pouzite_zbozi[$sazba] +=  $mappedTax['total_tax_base'] + $mappedTax['total_amount'];
              }
          else {
             $this->pouzite_zbozi[$sazba] +=  $order_detail['total_price_tax_incl'];
             }
         }
         else {
           if($mappedTax) {
                $this->pouzite_zbozi[$sazba] =  $mappedTax['total_tax_base'] + $mappedTax['total_amount'];
              }
           else {
                $this->pouzite_zbozi[$sazba] =  $order_detail['total_price_tax_incl'];
              }
         }
         $this->pouzite_zbozi_rates [$sazba]  =  $rate;

       } 
       if(Configuration::get('EETPLUS_CERPANI')) {
           if(isset($row['eet_cerp']) && (int)$row['eet_cerp']) {
                if($mappedTax) {
                   $this->urceno_cerp_zuct +=  $mappedTax['total_tax_base'] + $mappedTax['total_amount'];
                }    
                else {
                  $this->urceno_cerp_zuct += $order_detail['total_price_tax_incl'];  
                }
           }
           if(isset($row['eet_cest']) && (int)$row['eet_cest']) {
               if($mappedTax) {
                   $this->cest_sluz =  $mappedTax['total_tax_base'] + $mappedTax['total_amount'];
                }    
                else {
                 $this->cest_sluz += $order_detail['total_price_tax_incl'];  
                }
           }
       }
    }
    
    protected function getProductTaxesBreakdown( )
    {
        
        $sum_composite_taxes = false;

        // $breakdown will be an array with tax rates as keys and at least the columns:
        //     - 'total_price_tax_excl'
        //     - 'total_amount'
        $breakdown = array();

        $details = $this->getProductTaxesDetails();

        if ($sum_composite_taxes) {
            $grouped_details = array();
            foreach ($details as $row) {
                if (!isset($grouped_details[$row['id_order_detail']])) {
                    $grouped_details[$row['id_order_detail']] = array(
                        'tax_rate' => 0,
                        'total_tax_base' => 0,
                        'total_amount' => 0,
                        'id_tax' => $row['id_tax'],
                    );
                }

                $grouped_details[$row['id_order_detail']]['tax_rate'] += $row['tax_rate'];
                $grouped_details[$row['id_order_detail']]['total_tax_base'] += $row['total_tax_base'];
                $grouped_details[$row['id_order_detail']]['total_amount'] += $row['total_amount'];
            }

            $details = $grouped_details;
        }

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
        
        $this->details = $details;

        foreach ($breakdown as $rate => $data) {
            $breakdown[$rate]['total_price_tax_excl'] = self::ps_round($data['total_price_tax_excl'], self::$PS_PRICE_COMPUTE_PRECISION, self::$order_round_mode);
            $breakdown[$rate]['total_amount'] = self::ps_round($data['total_amount'], self::$PS_PRICE_COMPUTE_PRECISION, self::$order_round_mode);
        }

        ksort($breakdown);

        return $breakdown;
    }
    
     
    
     protected function getProductTaxesDetails($limitToOrderDetails = false)
    {
        $round_type = isset($this->order->round_type)?$this->order->round_type: self::$ROUND_LINE;
        
        if(Configuration::get('EETPLUS_DISCNTERROR') &&  $this->order->total_discounts_tax_excl > 0) {
         $this->order->total_discounts_tax_excl = $this->getCorrectDiscountExcl($this->order);
        }
        // compute products discount
        $order_discount_tax_excl = $this->order->total_discounts_tax_excl;

        $free_shipping_tax = 0;
        $product_specific_discounts = array();

        $expected_total_base = $this->order->total_products - $this->order->total_discounts_tax_excl;

        foreach ($this->order->getCartRules() as $order_cart_rule) {
           $sql = 'SELECT code FROM '._DB_PREFIX_.'cart_rule WHERE id_cart_rule ='.(int)$order_cart_rule['id_cart_rule']; 
           $code = Db::getInstance()->getValue($sql);
           if(is_array($this->prefix_urceno_cerp_zuct) && count($this->prefix_urceno_cerp_zuct)) {
               foreach($this->prefix_urceno_cerp_zuct as $prefix) {
                   $pos = strpos($code, $prefix);
                   if($pos === 0) {
                       $this->cerp_zuct +=  $order_cart_rule['value'];
                   } 
               } 
           }
            if ($order_cart_rule['free_shipping'] && $free_shipping_tax === 0) {
                $free_shipping_tax = $this->order->total_shipping_tax_incl - $this->order->total_shipping_tax_excl;
                $order_discount_tax_excl -= $this->order->total_shipping_tax_excl;
                $expected_total_base += $this->order->total_shipping_tax_excl;
            }

            $cart_rule = new CartRule($order_cart_rule['id_cart_rule']);
            if ($cart_rule->reduction_product > 0) {
                if (empty($product_specific_discounts[$cart_rule->reduction_product])) {
                    $product_specific_discounts[$cart_rule->reduction_product] = 0;
                }

                $product_specific_discounts[$cart_rule->reduction_product] += $order_cart_rule['value_tax_excl'];
                $order_discount_tax_excl -= $order_cart_rule['value_tax_excl'];
            }
        }

       
        
        $products_tax    = $this->order->total_products_wt - $this->order->total_products;
        $discounts_tax    = $this->order->total_discounts_tax_incl - $this->order->total_discounts_tax_excl;

        // We add $free_shipping_tax because when there is free shipping, the tax that would
        // be paid if there wasn't is included in $discounts_tax.
        $expected_total_tax = $products_tax - $discounts_tax + $free_shipping_tax;
        $actual_total_tax = 0;
        $actual_total_base = 0;

        $order_detail_tax_rows = array();

        $breakdown = array();
        
          
         
        // Get order_details
        $order_details = $limitToOrderDetails ? $limitToOrderDetails : $this->order->getOrderDetailList();

        $order_ecotax_tax = 0;

        $tax_rates = array();

        foreach ($order_details as $order_detail) {
            $id_order_detail = $order_detail['id_order_detail'];
            $tax_calculator = OrderDetail::getTaxCalculatorStatic($id_order_detail);
            
           
            $unit_ecotax_tax = $order_detail['ecotax'] * $order_detail['ecotax_tax_rate'] / 100.0;
            $order_ecotax_tax += $order_detail['product_quantity'] * $unit_ecotax_tax;

            $discount_ratio = 0;

            if ($this->order->total_products > 0) {
                $discount_ratio = ($order_detail['unit_price_tax_excl'] + $order_detail['ecotax']) / $this->order->total_products;
            }

            // share of global discount
            $discounted_price_tax_excl = $order_detail['unit_price_tax_excl'] - $discount_ratio * $order_discount_tax_excl;
            // specific discount
            if (!empty($product_specific_discounts[$order_detail['product_id']])) {
                $discounted_price_tax_excl -= $product_specific_discounts[$order_detail['product_id']]/$order_detail['product_quantity'];  // !!
            }

            $quantity = $order_detail['product_quantity'];

            foreach ($tax_calculator->taxes as $tax) {
                $tax_rates[$tax->id] = $tax->rate;
            }

           foreach ($tax_calculator->getTaxesAmount($discounted_price_tax_excl) as $id_tax => $unit_amount) {
                $total_tax_base = 0;    
         
                switch ($round_type) {
                    case self::$ROUND_ITEM:
                        $total_tax_base = $quantity * self::ps_round($discounted_price_tax_excl, self::$PS_PRICE_COMPUTE_PRECISION, self::$order_round_mode);
                        $total_amount = $quantity * self::ps_round($unit_amount, self::$PS_PRICE_COMPUTE_PRECISION, self::$order_round_mode);
                        break;
                    case self::$ROUND_LINE:
                        $total_tax_base = self::ps_round($quantity * $discounted_price_tax_excl, self::$PS_PRICE_COMPUTE_PRECISION,  self::$order_round_mode);
                        $total_amount = self::ps_round($quantity * $unit_amount, self::$PS_PRICE_COMPUTE_PRECISION,  self::$order_round_mode);
                        break;
                    case self::$ROUND_TOTAL:
                        $total_tax_base = $quantity * $discounted_price_tax_excl;
                        $total_amount = $quantity * $unit_amount;
                        break;
                }
                

                if (!isset($breakdown[$id_tax])) {
                    $breakdown[$id_tax] = array('tax_base' => 0, 'tax_amount' => 0);
                }

                $breakdown[$id_tax]['tax_base'] += $total_tax_base;
                $breakdown[$id_tax]['tax_amount'] += $total_amount;

                $order_detail_tax_rows[] = array(
                    'id_order_detail' => $id_order_detail,
                    'id_tax' => $id_tax,
                    'tax_rate' => $tax_rates[$id_tax],
                    'unit_tax_base' => $discounted_price_tax_excl,
                    'total_tax_base' => $total_tax_base,
                    'unit_amount' => $unit_amount,
                    'total_amount' => $total_amount,
                    'quantity' => $quantity
                    
                    
                );
                
                 
                $this->discountShare[$id_order_detail] = array('tax_incl'=> $discounted_price_tax_excl*(100 +$tax_rates[$id_tax])/100, 'tax_excl'=>$discounted_price_tax_excl) ;
               // $this->discountShareExcl[$id_order_detail]  =  $discounted_price_tax_excl;
                $sql = 'SELECT is_virtual FROM '._DB_PREFIX_.'product WHERE id_product = '.(int)$order_detail['product_id'];
                if(Db::getInstance()->getValue($sql)) { 
                  $this->tax_map[$tax_rates[$id_tax]] = $order_detail['id_tax_rules_group'];
                }
                else {
                  unset($this->tax_map);
                  $this->tax_map = array();   
                }
            }
              $this->addExtraData($order_detail, $order_detail_tax_rows);
        }

        if (!empty($order_detail_tax_rows)) {
            foreach ($breakdown as $data) {
                $actual_total_tax += self::ps_round($data['tax_amount'], self::$PS_PRICE_COMPUTE_PRECISION, self::$order_round_mode);
                $actual_total_base += self::ps_round($data['tax_base'], self::$PS_PRICE_COMPUTE_PRECISION, self::$order_round_mode);
            }

            $order_ecotax_tax = self::ps_round($order_ecotax_tax, self::$PS_PRICE_COMPUTE_PRECISION, self::$order_round_mode);

            $tax_rounding_error = $expected_total_tax - $actual_total_tax - $order_ecotax_tax;
            if ($tax_rounding_error !== 0) {
               $this->spreadAmount($tax_rounding_error, self::$PS_PRICE_COMPUTE_PRECISION, $order_detail_tax_rows, 'total_amount');
            }

            $base_rounding_error = $expected_total_base - $actual_total_base;
            $test = (float)$base_rounding_error;
             
            if ($base_rounding_error  || $base_rounding_error < 0) {
                $this->spreadAmount($base_rounding_error, self::$PS_PRICE_COMPUTE_PRECISION, $order_detail_tax_rows, 'total_tax_base');
            }
        }

        return $order_detail_tax_rows;
    }
    
    private function getCorrectDiscountExcl($order) {
      //  return 866.55; 
          $retval = 0;
          $order_details = $order->getOrderDetailList();
          $rate_kuponu = $this->getKuponRate($order_details);
          foreach ($order->getCartRules() as $order_cart_rule) {
         
            if ($order_cart_rule['free_shipping']) {
              ;// $retval +=  $order_cart_rule['value_tax_excl'];
            }

            $cart_rule = new CartRule($order_cart_rule['id_cart_rule']);
            if ($cart_rule->reduction_product > 0) {
                $retval +=  $order_cart_rule['value_tax_excl'];
            }
            else {
              if($cart_rule->reduction_tax == 0) {
                  $retval +=  $order_cart_rule['value_tax_excl'];
              } 
              else{
                 $retval +=  $order_cart_rule['value'] *100/(100 + $rate_kuponu); 
              } 
            }
        }
        $pomer = abs($order->total_discounts_tax_excl - $retval)/$order->total_discounts_tax_excl;
        if($pomer >  0.001) {  // pravdepodobna chyba
           return  $order->total_discounts_tax_excl; 
        }
        return $retval;
    }
    
    private function getKuponRate($order_details) {
       $sum_bez = 0;
       $sum_s = 0;
       $rate = 0;
       foreach($order_details as $order_detail) {
             $sum_s +=  $order_detail['unit_price_tax_incl'] * $order_detail['product_quantity'];
             $sum_bez +=  $order_detail['unit_price_tax_excl'] * $order_detail['product_quantity'];
       }
       if($sum_bez)  {
            $rate = ($sum_s -  $sum_bez)/$sum_bez; 
       }
       return $rate* 100;
    }
    
    
    
    public function getDiscountShare() {
        $ceny = $this->getRozpis();
       return $this->discountShare; 
    }
    public static function ps_round($value, $precision = 0, $round_mode = null) {
       if(Tools::version_compare(_PS_VERSION_, '1.6.1', '>')) {
           return Tools::ps_round($value, $precision, $round_mode);
       }
    if(!defined('PS_ROUND_UP')) define('PS_ROUND_UP', 0);
    if(!defined('PS_ROUND_DOWN')) define('PS_ROUND_DOWN', 1);
    if(!defined('PS_ROUND_HALF_UP')) define('PS_ROUND_HALF_UP', 2);
    if(!defined('PS_ROUND_HALF_DOWN')) define('PS_ROUND_HALF_DOWN', 3);
    if(!defined('PS_ROUND_HALF_EVEN')) define('PS_ROUND_HALF_EVEN', 4);
    if(!defined('PS_ROUND_HALF_ODD')) define('PS_ROUND_HALF_ODD', 5);
    if(!defined('PS_ROUND_HALF')) define('PS_ROUND_HALF_ODD', PS_ROUND_HALF_UP);
 
          
         if ($round_mode === null) {
            if (self::$round_mode == null) {
                self::$round_mode = (int)Configuration::get('PS_PRICE_ROUND_MODE');
            }

            $round_mode = self::$round_mode;
        }

        switch ($round_mode) {
            case PS_ROUND_UP:
                return Tools::ceilf($value, $precision);
            case PS_ROUND_DOWN:
                return Tools::floorf($value, $precision);
            case PS_ROUND_HALF_DOWN:
            case PS_ROUND_HALF_EVEN:
            case PS_ROUND_HALF_ODD:
                return self::math_round($value, $precision, $round_mode);
            case PS_ROUND_HALF_UP:
            default:
                return self::math_round($value, $precision, PS_ROUND_HALF_UP);
        } 
    }
    
        public static function math_round($value, $places, $mode = PS_ROUND_HALF_UP)
    {
        //If PHP_ROUND_HALF_UP exist (PHP 5.3) use it and pass correct mode value (PrestaShop define - 1)
        if (defined('PHP_ROUND_HALF_UP')) {
            return round($value, $places, $mode - 1);
        }

        $precision_places = 14 - floor(log10(abs($value)));
        $f1 = pow(10.0, (double)abs($places));

        /* If the decimal precision guaranteed by FP arithmetic is higher than
        * the requested places BUT is small enough to make sure a non-zero value
        * is returned, pre-round the result to the precision */
        if ($precision_places > $places && $precision_places - $places < 15) {
            $f2 = pow(10.0, (double)abs($precision_places));

            if ($precision_places >= 0) {
                $tmp_value = $value * $f2;
            } else {
                $tmp_value = $value / $f2;
            }

            /* preround the result (tmp_value will always be something * 1e14,
            * thus never larger than 1e15 here) */
            $tmp_value = self::round_helper($tmp_value, $mode);
            /* now correctly move the decimal point */
            $f2 = pow(10.0, (double)abs($places - $precision_places));
            /* because places < precision_places */
            $tmp_value = $tmp_value / $f2;
        } else {
            /* adjust the value */
            if ($places >= 0) {
                $tmp_value = $value * $f1;
            } else {
                $tmp_value = $value / $f1;
            }

            /* This value is beyond our precision, so rounding it is pointless */
            if (abs($tmp_value) >= 1e15) {
                return $value;
            }
        }

        /* round the temp value */
        $tmp_value = self::round_helper($tmp_value, $mode);

        /* see if it makes sense to use simple division to round the value */
        if (abs($places) < 23) {
            if ($places > 0) {
                $tmp_value /= $f1;
            } else {
                $tmp_value *= $f1;
            }
        }

        return $tmp_value;
    }
    
     public static function round_helper($value, $mode)
    {
        if ($value >= 0.0) {
            $tmp_value = floor($value + 0.5);

            if (($mode == PS_ROUND_HALF_DOWN && $value == (-0.5 + $tmp_value)) ||
                ($mode == PS_ROUND_HALF_EVEN && $value == (0.5 + 2 * floor($tmp_value / 2.0))) ||
                ($mode == PS_ROUND_HALF_ODD  && $value == (0.5 + 2 * floor($tmp_value / 2.0) - 1.0))) {
                $tmp_value  = $tmp_value - 1.0;
            }
        } else {
            $tmp_value  = ceil($value - 0.5);

            if (($mode == PS_ROUND_HALF_DOWN && $value == (0.5 + $tmp_value)) ||
                ($mode == PS_ROUND_HALF_EVEN && $value == (-0.5 + 2 * ceil($tmp_value / 2.0))) ||
                ($mode == PS_ROUND_HALF_ODD  && $value == (-0.5 + 2 * ceil($tmp_value / 2.0) + 1.0))) {
                $tmp_value  = $tmp_value + 1.0;
            }
        }

        return $tmp_value;
    }
  }
 
