<?php
  class CenyObjednavky {
       private $id_order;
       private $table;
       
       public function __construct($id_order) {
       $this->id_order = $id_order;
       if (Configuration::get('EETPLUS_SANDBOX') == 1) {
            $this->table = 'eetplus_sandbox';
        } else {
            $this->table = 'eetplus';
        }
       }
       
     
       
       public function cenyFromHistory($jen_trzba = false) {
          $sql ='SELECT id_eetplus, ceny FROM '._DB_PREFIX_.$this->table.' WHERE id_order='.$this->id_order;
          if($jen_trzba) {
            $sql .=' AND action ="trzba"';  
          }
          $skupinyCen = Db::getInstance()->executeS($sql);
          $noveceny = array();
          $transakce = array();
          foreach($skupinyCen as $data) {
              $ceny = json_decode($data['ceny'], true);
              $transakce[$data['id_eetplus']] = $ceny;
              $this->sectiCeny($noveceny, $ceny);
          }
          return array('transakce'=>$transakce, 'bilance'=>$noveceny);
        
       }
       
       public function odectiCeny($cenyObjednavky, $cenyEet) {
          $ceny = array(); 
          $ceny['celk_trzba'] =  $this->odecetPolozky($cenyObjednavky, $cenyEet, 'celk_trzba');
          $sazby = array(1,2,3);
          foreach($sazby as $sazba) {
                   $keys = array('zaklad', 'dan');
                    foreach($keys as $key) {
                      $val = @$this->odecetPolozky($cenyObjednavky['dph'][$sazba], $cenyEet['dph'][$sazba], $key); 
                      if(!($val === false)) {
                          $ceny['dph'][$sazba][$key] = $val;
                      } 
                    }

                $val =  @$this->odecetPolozky($cenyObjednavky['pouzit_zboz'][$sazba], $cenyEet['pouzit_zboz'][$sazba], 'celkem');
                if(!($val === false)) {
                    $ceny['pouzit_zboz'][$sazba]['celkem'] = $val; 
                    $ceny['pouzit_zboz'][$sazba]['rate'] =   Eetplus::sazbaToRate($sazba);
                }
          }
           if(Configuration::get('EETPLUS_CERPANI')) {
               $keys = array('cerp_zuct','urceno_cerp_zuct','cest_sluz');
               foreach($keys as $key) {
                $val =  @$this->odecetPolozky($cenyObjednavky['eet_extra'], $cenyEet['eet_extra'], $key);
                 if(!($val === false)) {
                      $ceny['eet_extra'][$key] = $val; 
                }
               }
           }
           if(Configuration::get('PS_TAX') && Configuration::get('VATNUMBER_MANAGEMENT')) {
               $val =  @$this->odecetPolozky($cenyObjednavky['eet_extra'], $cenyEet['eet_extra'], 'zakl_nepodl_dph');
                if(!($val === false)) {
                      $ceny['eet_extra']['zakl_nepodl_dph'] = $val; 
                } 
           }
           
          $ceny = Eetplus::globalRound($ceny);
          return $ceny;
       }
       
       private function sectiCeny(&$noveceny, $ceny) { 
             $noveceny['celk_trzba'] = @$this->soucetPolozky($noveceny, $ceny, 'celk_trzba');
             $sazby = array(1,2,3);
             foreach($sazby as $sazba) {
                 $keys = array('zaklad', 'dan');
                 foreach($keys as $key) {
                     $val = @$this->soucetPolozky($noveceny['dph'][$sazba], $ceny['dph'][$sazba], $key);
                     if(!($val === false)) {
                         $noveceny['dph'][$sazba][$key] = $val; 
                     }
                 }
                $noveceny['dph'][$sazba]['rate'] = Eetplus::sazbaToRate($sazba);
                
                if(isset($ceny['pouzit_zboz'])) {
                   $val = @$this->soucetPolozky($noveceny['pouzit_zboz'][$sazba], $ceny['pouzit_zboz'][$sazba], 'celkem');
                    if(!($val === false)) {
                      $noveceny['pouzit_zboz'][$sazba]['celkem'] = $val;
                      $noveceny['pouzit_zboz'][$sazba]['rate'] = Eetplus::sazbaToRate($sazba);
                   }  
                }
            }
            $extra_keys = self::get_extra_keys();
             foreach($extra_keys as $key) {
                    $val =  @$this->soucetPolozky($noveceny['eet_extra'], $ceny['eet_extra'], $key);
                    if(!($val === false)) { 
                          $noveceny['eet_extra'][$key] = $val; 
                    }
             }
       
            $noveceny = Eetplus::globalRound($noveceny);
       }
       
       public function cenyFromPostVars($celkem, $dph, $pouzite,$extra) {
           $ceny = array(); 
           $ceny['celk_trzba'] = $this->copyPolozky($celkem);
           if(isset($dph) && !is_null($dph) && Configuration::get('PS_TAX')) {
                  $sazby = array(1,2,3);
                   foreach($sazby as $sazba) {
                     if(isset($dph[$sazba])) {
                        if(isset($dph[$sazba]['zaklad'])) {
                           $ceny['dph'][$sazba]['zaklad'] =  $this->copyPolozky($dph[$sazba], 'zaklad');
                           $ceny['dph'][$sazba]['dan'] =  $this->copyPolozky($dph[$sazba], 'dan'); 
                           $ceny['dph'][$sazba]['rate'] =  Eetplus::sazbaToRate($sazba);
                        }
                     }
                    if(isset($pouzite[$sazba])) {
                          if(isset($pouzite[$sazba]['celkem'])) {
                            $ceny['pouzit_zboz'][$sazba]['celkem'] =  $this->copyPolozky($pouzite[$sazba], 'celkem'); 
                            $ceny['pouzit_zboz'][$sazba]['rate'] =  Eetplus::sazbaToRate($sazba);
                          }
                    } 
                   }
           }
          
           $extra_keys = self::get_extra_keys();
           foreach($extra_keys as $key) {
             $val =  @$this->copyPolozky($extra,  $key);
                    if(!($val === false)) { 
                          $ceny['eet_extra'][$key] = $val; 
                    }  
           }
 
           return $ceny; 
       }
       
       
       
       private function copyPolozky($ceny,  $key = null) {
            if(is_null($key)) {
                if(!isset($ceny)  || empty($ceny)  ) { 
                   return 0;
                } 
                 return str_replace(',','.',$ceny);
            }
            if(!isset($ceny) || !isset($ceny[$key]) || empty($ceny[$key])  ) { 
               return 0;
            }
            
            return str_replace(',','.',$ceny[$key]);
       }
       
       private function odecetPolozky($ceny1, $ceny2, $key) {
          if(isset($ceny1[$key]) && isset($ceny2[$key])) {
              return $ceny1[$key] - ($ceny2[$key]);
          }
          elseif(isset($ceny1[$key])) {
              return $ceny1[$key];
          }
          elseif(isset($ceny2[$key])) {
              return (-$ceny2[$key]);
          }
          return false; 
       }
 
        private function soucetPolozky($ceny1, $ceny2, $key) {
          if(isset($ceny1[$key]) && isset($ceny2[$key])) {
              $retval = $ceny1[$key] + ($ceny2[$key]);
              return $retval;
          }
          elseif(isset($ceny1[$key])) {
              return $ceny1[$key];
          }
          elseif(isset($ceny2[$key])) {
              return ($ceny2[$key]);
          }
          return false; 
       }
       

       
       public static function prohoditZnamenka($ceny) {
          $noveceny = array();
            $noveceny['celk_trzba'] = -$ceny['celk_trzba'];
             while(list($sazba,$val) = each($ceny['dph'])) {
                 $noveceny['dph'][$sazba]['zaklad'] = -$val['zaklad'];
                 $noveceny['dph'][$sazba]['dan'] = -$val['dan'];
                 if(isset($val['rate'])) {
                 $noveceny['dph'][$sazba]['rate'] = $val['rate'];
                 }
            }
            
            if(isset($ceny['pouzit_zboz']) && is_array($ceny['pouzit_zboz'])) {
                while(list($sazba,$val) = each($ceny['pouzit_zboz'])) {
                     $noveceny['pouzit_zboz'][$sazba]['celkem'] = -$val['celkem'];
                     $noveceny['pouzit_zboz'][$sazba]['rate'] = $val['rate'];
                } 
            }
            
          $extra_keys = self::get_extra_keys();
           foreach($extra_keys as $key) {
               if(isset($ceny['eet_extra'][$key])) {
                   $noveceny['eet_extra'][$key]  = -$ceny['eet_extra'][$key];
               }
           }
            return $noveceny;  
       }
       
       public static function get_extra_keys() {
            $keys1 = array();
            $keys2 = array();
            if(Configuration::get('EETPLUS_CERPANI')) {
                 $keys1 = array('cerp_zuct','urceno_cerp_zuct','cest_sluz');
           }
           if(Configuration::get('PS_TAX') && Configuration::get('VATNUMBER_MANAGEMENT')) {
              $keys2 = array('zakl_nepodl_dph');  
           }
           return array_merge($keys1,$keys2);  
       }
      
  }