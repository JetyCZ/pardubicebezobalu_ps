<?php
/**
 * eetplus module for Prestashop
 *
 * PHP version 5
 *
 * LICENSE: The buyer can free use/edit/modify this software in anyway
 * The buyer is NOT allowed to redistribute this module in anyway or resell it
 * or redistribute it to third party
 *
 * @package    eetplus
 * @author    Vaclav Mach <info@prestahost.cz>
 * @copyright 2017 Vaclav Mach
 * @license   EULA
 * @link       http://www.prestahost.cz
 */
 
class ReceiptData
{
    public $fik = false;
    public $email = false;
    public $pkp = false;
    public $bkp = false;
    public $date_akc = false;
    public $id_order = 0;
    public $action;
    protected $id;
    protected $receipt;
    protected $table;
    protected $overovaci_mod;
    const FIK_LEN = 39;
    const PKP_LEN = 344;
    protected $updateVars = array();
    
    public function __construct($id = null, $playground = 0, $overeni = false)
    {
        $this->overovaci_mod = $overeni;
        if ($playground == 1) {
            $this->table = 'eetplus_sandbox';
        } else {
            $this->table = 'eetplus';
        }
        if ((int) $id) {
            $this->id = $id;
            $this->init($id);
        }
    }
    
    public function getValue($key, $formated = false)
    {
        if (isset($this->receipt->{$key})) {
            if ($key == 'dat_trzby' && $formated) {
                return $this->receipt->dat_trzby->format('Y-m-d H:i:s');
            }
            return $this->receipt->{$key};
        }
        return '';
    }
    
    public function getParsedEmail()
    {
        $retval = array();
        $a      = array();
        if (!empty($this->email)) {
            $a = explode(' ', $this->email);
        }
        $retval['mailto']   = isset($a[0]) ? $a[0] : '';
        $retval['maildate'] = isset($a[2]) ? $a[2] : '';
        return $retval;
    }
    
    public function getReceipt()
    {
        if (is_object($this->receipt))
            return $this->receipt;
        return false;
    }
    
    public function setUpdateVar($key, $val, $json = false)
    {
        $this->updateVars[$key] = array(
            $val,
            $json
        );
        if (property_exists($this, $key)) {
            $this->{$key} = $val;
        }
    }
    
    public function update()
    {
        if (!(int) $this->id)
            return;
        $sql   = 'UPDATE ' . _DB_PREFIX_ . $this->table . ' SET ';
        $carka = '';
        while (list($key, $val) = each($this->updateVars)) {
            if ($val[1])
                $hodnota = json_encode($val[0], true);
            else
                $hodnota = $val[0];
            $sql .= $carka . '`' . $key . '` = "' . pSQL($hodnota) . '"';
            $carka = ',';
        }
        $sql .= ' WHERE id_eetplus=' . (int) $this->id;
        Db::getInstance()->execute($sql);
        $this->updateVars = array();
    }
    
    public function savePayment($id_eet, $response, $akce)
    {
        if ($this->overovaci_mod == true)
            return;
        $json = json_encode($response);
        $sql  = 'UPDATE ' . _DB_PREFIX_ . $this->table . ' SET 
       response ="' . pSQL($json) . '",
       action = "' . pSQL($akce) . '",
       date_akc = "' . pSQL($response->Hlavicka->dat_prij) . '",
       fik="' . pSQL($response->Potvrzeni->fik) . '" WHERE id_eetplus = ' . (int) $id_eet;
        Db::getInstance()->execute($sql);
    }
    
    private function init($id)
    {
        $sql                        = 'SELECT * FROM ' . _DB_PREFIX_ . $this->table . ' WHERE id_eetplus = ' . (int) $id;
        $data                       = Db::getInstance()->getRow($sql);
        $this->receipt              = new Receipt();
        $arr                        = json_decode($data['input'], true);
        $this->receipt->dic_popl    = $arr['dic_popl'];
        $this->receipt->id_provoz   = $arr['id_provoz'];
        $this->receipt->id_pokl     = $arr['id_pokl'];
        $this->receipt->porad_cis   = $arr['porad_cis'];
        $this->receipt->dat_trzby   = new DateTime($arr['dat_trzby']);
        $this->receipt->celk_trzba  = $arr['celk_trzba'];
        $this->receipt->uuid_zpravy = $arr['uuid_zpravy'];
        
        $this->copyPricesToReceipt($this->receipt, json_decode($data['ceny'], true));
        $this->id_order             = $data['id_order'];
        if (strlen($data['email']))
            $this->email = $data['email'];
        if (strlen($data['date_akc']))
            $this->date_akc = $data['date_akc'];
        $this->fik    = $data['fik'];
        $this->action = $data['action'];
        if (!is_null($data['codes'])) {
            $codes     = json_decode($data['codes'], true);
            $this->pkp = $codes['pkp'];
            $this->bkp = $codes['bkp'];
        }
    }
    
    public function copyPricesToReceipt(&$receipt, $ceny) {
         $receipt->celk_trzba = $ceny['celk_trzba'];
         if(isset($ceny['dph'])) {
             while(list($sazba,$val) = each($ceny['dph'])) {
                 $receipt->{'zakl_dan'.$sazba} = $val['zaklad'];
                 $receipt->{'dan'.$sazba} = $val['dan'];
             }
         }
         if(isset($ceny['pouzit_zboz'])){
          $sazby = array(1,2,3);
          foreach($sazby as $sazba) {
              if(isset($ceny['pouzit_zboz'][$sazba]['celkem'])) {
                 $receipt->{'pouzit_zboz'.$sazba} = $ceny['pouzit_zboz'][$sazba]['celkem'];
              }
          }
          
         if(Configuration::get('EETPLUS_CERPANI') && isset($ceny['eet_extra'])) {
            if(isset($ceny['eet_extra']['urceno_cerp_zuct'])) {
                $receipt->urceno_cerp_zuct = $ceny['eet_extra']['urceno_cerp_zuct'];
            }
            if(isset($ceny['eet_extra']['cerp_zuct'])) {
                $receipt->cerp_zuct = $ceny['eet_extra']['cerp_zuct'];
            }
            if(isset($ceny['eet_extra']['cest_sluz'])) {
                $receipt->cest_sluz = $ceny['eet_extra']['cest_sluz'];
            }
         }
        if(Configuration::get('PS_TAX') && Configuration::get('VATNUMBER_MANAGEMENT')) {
             if(isset($ceny['eet_extra']['zakl_nepodl_dph'])) {
                    $receipt->zakl_nepodl_dph = $ceny['eet_extra']['zakl_nepodl_dph'];
             }
           }
         }
     }
     
    public function copyPricesFromReceipt() {
       $ceny = array();
       $sazby = array(1,2,3);
       $ceny['celk_trzba'] = $this->receipt->celk_trzba;
       foreach($sazby as $sazba) {
         if($this->receipt->{'zakl_dan'.$sazba} != 0) {
              $ceny['dph'][$sazba]['zaklad'] = $this->receipt->{'zakl_dan'.$sazba};
              $ceny['dph'][$sazba]['dan'] = $this->receipt->{'dan'.$sazba};
              $ceny['dph'][$sazba]['rate'] = Eetplus::sazbaToRate($sazba);
          }
         if($this->receipt->{'pouzit_zboz'.$sazba} != 0) {
             $ceny['pouzit_zboz'][$sazba]['celkem'] = $this->receipt->{'pouzit_zboz'.$sazba};
             $ceny['pouzit_zboz'][$sazba]['rate']  = Eetplus::sazbaToRate($sazba);
         }
       }
       
       if(Configuration::get('EETPLUS_CERPANI')) {
            $keys = array('urceno_cerp_zuct','cerp_zuct','cest_sluz');
            foreach($keys as $key) {
                if($this->receipt->{$key} != 0) {
                    $ceny['eet_extra'][$key] =  $this->receipt->{$key};
                } 
            }
       }
 
       if(Configuration::get('PS_TAX') && Configuration::get('VATNUMBER_MANAGEMENT')) {
             if($this->receipt->zakl_nepodl_dph != 0) {
                  $ceny['eet_extra']['zakl_nepodl_dph'] =  $this->receipt->zakl_nepodl_dph;
             }
           }
       return $ceny;
    }
    
    public function presavePayment($receipt, $id_order)
    {
        if ($this->overovaci_mod == true) {
            return rand(100, 1100);
        }
        $sql = 'INSERT INTO ' . _DB_PREFIX_ . $this->table . ' SET
        id_order =' . $id_order . ',
        castka =' . $receipt->celk_trzba . ',
        date_trzby ="' . pSQL($receipt->dat_trzby->format('c')) . '"';
        Db::getInstance()->execute($sql);
        $porad_cis          = Db::getInstance()->Insert_ID();
        $arr                = Array();
        $arr['dic_popl']    = $receipt->dic_popl;
        $arr['id_provoz']   = $receipt->id_provoz;
        $arr['id_pokl']     = $receipt->id_pokl;
        $arr['porad_cis']   = $porad_cis;
        $arr['dat_trzby']   = $receipt->dat_trzby->format('c');
        $arr['celk_trzba']  = $receipt->celk_trzba;
        $arr['uuid_zpravy'] = $receipt->uuid_zpravy;
        $sql                = 'UPDATE ' . _DB_PREFIX_ . $this->table . ' SET input="' . pSQL(json_encode($arr)) . '" WHERE id_eetplus=' . (int) $porad_cis;
        Db::getInstance()->execute($sql);
        $this->id = $porad_cis;
        return $porad_cis;
    }
    
    public static function getExistingId($id_order, $akce, $playground)
    {
        if ($playground == 1) {
            $table = 'eetplus_sandbox';
        } else {
            $table = 'eetplus';
        }
        $sql      = 'SELECT id_eetplus FROM ' . _DB_PREFIX_ . $table . ' WHERE id_order = ' . $id_order . ' AND
        action ="' . pSQL($akce) . '"';
        $existing = Db::getInstance()->getValue($sql);
        return $existing;
    }
    
    public static function getKurzFromExistingId($id_existing, $playground) {
         if ($playground == 1) {
            $table = 'eetplus_sandbox';
        } else {
            $table = 'eetplus';
        }
        $sql      = 'SELECT ceny FROM ' . _DB_PREFIX_ . $table . ' WHERE id_eetplus= ' . $id_existing;
        $ceny = Db::getInstance()->getValue($sql);
        if($ceny) {
           $ceny = json_decode($ceny, true);
           if(is_array($ceny) && isset($ceny['currencyData'])) {
                return $ceny['currencyData'];
           } 
        }
        return false;
    }
    
}
 
