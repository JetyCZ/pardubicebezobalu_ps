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
class DalsiController extends EetplusController
{
 
    
    public function __construct($instance)
    {
       $this->instance = $instance; 
    }
    
    public function postProcess()
    {  
        $id_hook = Hook::getIdByName('displayAdminProductsExtra');
        $list = Hook::getModulesFromHook($id_hook, $this->instance->id);
        $hookon = $this->getHookOn($list);
       
        if((int)Tools::getValue('EETPLUS_CERPANI') == 1) {
            if(!$hookon) {
                $this->instance->registerHook('displayAdminProductsExtra'); 
                $this->instance->registerHook('actionProductUpdate'); 
                if(!$this->addSqlField('product', 'eet_cerp', 'int') ||  !$this->addSqlField('product', 'eet_cest', 'int')){
                      $this->instance->setMessage('Nepodařilo se přidat příslušná pole do tabulky product ');
                }
                else {
                  Configuration::updateValue('EETPLUS_CERPANI', 1);  
                }
            }
            else {
                 Configuration::updateValue('EETPLUS_CERPANI', 1);  
            }
        }
        else {
            if($hookon) {
                 $this->instance->unregisterHook('displayAdminProductsExtra');
                 $this->instance->unregisterHook('actionProductUpdate');  
            }
             Configuration::updateValue('EETPLUS_CERPANI', 0);
        } 
             
        $keys   = array(
            'EETPLUS_UCTADDR',
            'EETPLUS_UCTROZPR',
            'EETPLUS_UCTSUMM',
            'EETPLUS_MOSS',
      
            'EETPLUS_DISCNTERROR',
            'EETPLUS_MAILRESTR',
            'EETPLUS_PDFHOOK',
            'EETPLUS_KURZY',
            'EETPLUS_TIMEOUT',
            'EETPLUS_UCTCISINV',
            'EET_USEREF',
            'EETPLUS_SSLVERSION',
            'EETPLUS_CONTEXT'
        );
       
        
        foreach ($keys as $key) {
            Configuration::updateValue($key, (int) Tools::getValue($key));
        }
        Configuration::updateValue('EETPLUS_SAZBY', json_encode(Tools::getValue('EETPLUS_SAZBY')));
        Configuration::updateValue('EETPLUS_RATECZK', json_encode(Tools::getValue('EETPLUS_RATECZK')));
        Configuration::updateValue('EETPLUS_PREFIXCERP',   Tools::getValue('EETPLUS_PREFIXCERP'));
    }
    
    public function getContent($tabnum)
    {
        $output = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        $output .= '<input type="hidden" name="currentTab" value="' . $tabnum . '" />';
        $output .= '<fieldset><legend>' . $this->instance->l('Další nastavení', 'DalsiController') . '</legend>';
        
        $checked = Configuration::get('EETPLUS_CERPANI') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_CERPANI', $this->instance->l('Rozšíření produktu', 'DalsiController'),  $this->instance->l('Záložka produktu pro "Platby určené k následnému čerpání" a "Cestovní služby"', 'DalsiController'), $checked);
    
    
   
        
        $output .='<br /><br />'; 
        // odsud   
       
 $output .='        
<div class="form-group">
<label class="control-label col-lg-3">'.
 $this->instance->l('Prefix kodu kuponů které jsou zůčtováním následného čerpání', 'DalsiController').'
</label>
<div class="col-lg-3">
<textarea name="EETPLUS_PREFIXCERP"  rows=7 cols=7 >'.Configuration::get('EETPLUS_PREFIXCERP').'
</textarea>
</div>
<div class="col-lg-3">
<p class="help-block">'.$this->instance->l('Každý prefix na jeden řádek, jedná se zejména o kupony dárkových poukazů', 'DalsiController').'</p></div></div>
';
  
//sem 
        $output .= '<div style="clear:both"><br />'; 


         // opravy
        $output.='<fieldset><legend>'.$this->instance->l('Opravy výpočtů', 'DalsiController').'</legend>';
        
        $checked = Configuration::get('EETPLUS_DISCNTERROR') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_DISCNTERROR', $this->instance->l('Oprava DPH v objednávkách v kupony', 'DalsiController'),  $this->instance->l('Přesnější rozpočet DPH v objednávkách s kupony, viz nápověda', 'DalsiController'), $checked);

        $output.='<fieldset>';
        
       // kurzy
        $output.='<fieldset><legend>'.$this->instance->l('Kurzy', 'DalsiController').'</legend>';
        $options = array(0=>$this->instance->l('kurz objednávky', 'DalsiController'), 1=>$this->instance->l('aktuální kurz ČNB (nastavte kron v záložce Krony modulu)', 'DalsiController'));
        
        $output.=$this->generateRadio('EETPLUS_KURZY', "Používaný kurz", null, $options);
        if(Configuration::get('EETPLUS_KURZY') == 1) {
        $output .= '<div style="clear:both"><br />'; 
            $currencyrates = Configuration::get('EETPLUS_CURRENCYRATES');
            if($currencyrates) {
                $currencyrates = json_decode($currencyrates, true);
                while(list($key, $val) = each($currencyrates)) {
                  $output.= $key.': '.$val.'<br />'; 
                }
            }
            else {
             $output.= $this->instance->l('Kurzy nejsou nahrány, opravte v záložce "Krony"', 'DalsiController');
            }
        $output.='<br /><br />';
        }
        $output.='</fieldset>';
        
        // danove sazby
        $output.='<fieldset><legend>'.$this->instance->l('Daňové sazby', 'DalsiController').'</legend>';
        if(Configuration::get('VATNUMBER_MANAGEMENT') == 1) {  
          $output.=$this->instance->l('Osvobození od DPH - bude respektováno nastavení modulu Evropské DIČ (vatnumber)').'<br /><br />'; 
    } else {
          $output.=$this->instance->l('Osvobození od DPH: nejprve aktivujte modul Evropské DIČ (vatnumber)').'<br /><br />';    
  }
        $output .= '</div>';
        $output.='<br />'; 
        if(Tools::version_compare(_PS_VERSION_,'1.6.0.10', '>')) {
        $sql = 'SELECT id_tax_rules_group, name FROM '._DB_PREFIX_.'tax_rules_group WHERE deleted = 0';
        }
        else {
          $sql = 'SELECT id_tax_rules_group, name FROM '._DB_PREFIX_.'tax_rules_group WHERE 1';    
        }
        $sazby = Db::getInstance()->executeS($sql);
        $selsazby = json_decode(Configuration::get('EETPLUS_SAZBY'), true);
        $selrates = json_decode(Configuration::get('EETPLUS_RATECZK'), true);

        $checked = Configuration::get('EETPLUS_MOSS') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_MOSS', $this->instance->l('Aktivovat MOSS', 'DalsiController'),  $this->instance->l('Prodej virtuálních produktů neplátcům daně v jiných státech EU', 'DalsiController'), $checked);
     
        $translations = array(1=>$this->instance->l('Základní sazba', 'DalsiController'), 
                              2=>$this->instance->l('Snížená sazba', 'DalsiController'),
                              3=>$this->instance->l('Druhá snížená sazba', 'DalsiController'));
  
        $output .= '<table class="table" style="width:auto;">';
        $output .= '<thead>';
      
        $output .= '<tr><th>'.$this->instance->l('Název sazby', 'DalsiController').'</th>';
        if(Configuration::get('EETPLUS_MOSS')) {
            $output .= '<th>'.$this->instance->l('Daňové pravidlo MOSS','DalsiController').'</th>';
        }
        
        $output .= '<th>'.$this->instance->l('Hodnota % ČR', 'DalsiController').'</th></tr>';
        while(list($key, $translation) = each($translations)) {
            $valrate = isset($selrates[$key])?$selrates[$key]:'';
            $output .= '<tr><td>'.$translation.'</td>'; 
              if(Configuration::get('EETPLUS_MOSS')) {
            $output .= '<td>'.$this->sazbaSelect($key, $sazby, $selsazby).'</td>';
              }
            $output .= '<td><input type = "text" name="EETPLUS_RATECZK['.$key.']" value="'.$valrate.'" />
                        <tr>';
        }
              
        $output .= '</table></fieldset>';
       
        $output .= '<br /><fieldset><legend>' . $this->instance->l('Nepovinné údaje na účtence', 'DalsiController') . '</legend>';
        $checked = Configuration::get('EETPLUS_UCTADDR') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_UCTADDR', $this->instance->l('Adresa provozovny', 'DalsiController'), null, $checked);
        $output .= '<br />';
        $checked = Configuration::get('EETPLUS_UCTROZPR') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_UCTROZPR', $this->instance->l('Rozpis produktů', 'DalsiController'), null, $checked);
        $output .= '<br />';
        $checked = Configuration::get('EETPLUS_UCTSUMM') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_UCTSUMM', $this->instance->l('Souhrn objednávky', 'DalsiController'), null, $checked);
        
          
        $checked = Configuration::get('EETPLUS_UCTCISINV') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_UCTCISINV', $this->instance->l('Číslo faktury', 'DalsiController'), null, $checked);
      
        $checked = Configuration::get('EET_USEREF') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EET_USEREF', $this->instance->l('Reference místo čísla objednávky', 'DalsiController'), null, $checked);
  
        
        
        $output .= '<br /><br />';
         $checked = Configuration::get('EETPLUS_MAILRESTR') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_MAILRESTR', $this->instance->l('Neposílat Email k opravám', 'DalsiController'), null, $checked);
 
      
        $output .= '<br /><br />';
        $output .= '<h4>PDF</h4>';
        $checked = Configuration::get('EETPLUS_PDFHOOK') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_PDFHOOK', $this->instance->l('Zobrazovat v PDF faktuře', 'DalsiController'), null, $checked);
        
        $output .= '<br /> ';
         $output .= '<h4>Spojení</h4>';
        $options = array(
        2=>$this->instance->l('2 sec', 'EetplusController'),
        5=>$this->instance->l('5 sec', 'EetplusController'),
        20=>$this->instance->l('20 sec', 'EetplusController')
        );
     
        $output.= $this->generateRadio('EETPLUS_TIMEOUT',   $this->instance->l('Timeout pro spojení s EET', 'DalsiController'), null, $options); 
        
        $options = array(
        0=>$this->instance->l('Výchozí', 'EetplusController'),
        6=>$this->instance->l('TLS 1.2', 'EetplusController'),
        5=>$this->instance->l('TLS 1.1', 'EetplusController')
        );
        $output.= $this->generateRadio('EETPLUS_SSLVERSION',   $this->instance->l('Verze SSL', 'DalsiController'), null, $options); 
       
        $output .= '<br />';
        $checked = Configuration::get('EETPLUS_CONTEXT') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_CONTEXT', $this->instance->l('Rozšířená kompatibilita', 'DalsiController'),  $this->instance->l('Opravuje chování v určitých konfiguracích', 'DalsiController'), $checked);
    
       
        $output .= '<br /><br />';   
    
        
        $output .= $this->generateSubmit('cmd_Dalsi', $this->instance->l('Uložit', 'DalsiController'));
           $output .= '</fieldset>';
       
        $output .='
        </form>';
        return $output;
    }
    
    private function getHookOn($list) {
        if($list == false || !is_array($list)) {
          return false;
        }
        
         while(list($id_hook, $module) = each($list)) {
            if($module['name'] == $this->instance->name) {
               return true;
            } 
         }
         return false;
    } 
    
    private function addSqlField($tablename, $columnname, $type = null) {
      $tablename=pSQL($tablename);
      $columnname=pSQL($columnname);
        $sql='SELECT column_name
                    FROM information_schema.columns 
                    WHERE table_schema = "'._DB_NAME_.'" 
                    AND table_name   = "'._DB_PREFIX_.$tablename.'"
                    AND column_name  = "'.$columnname.'"';
                    $column_exists=Db::getInstance()->getValue($sql);

                    if($column_exists == false && $type == 'int') {
                    $sql='ALTER TABLE '._DB_PREFIX_.$tablename.' ADD '.$columnname.' int(10) unsigned DEFAULT NULL';
                    Db::getInstance()->execute($sql);
                    }


                    $sql='SELECT    '.$columnname.' FROM '._DB_PREFIX_.$tablename.'   WHERE 1 LIMIT 1';
                    $test=Db::getInstance()->Execute($sql); 


                    if(!$test) {
                     return false;
                    }
     return true;
}

   private function sazbaSelect($sazba,$sazby, $selsazby) {
         $selected = 0;
         if(isset($selsazby)) {
             if(isset($selsazby[$sazba])) {
                $selected = $selsazby[$sazba];
                
             }
         }
         $retval = '<select style="width:140px" name ="EETPLUS_SAZBY['.$sazba.']">'; 
        $retval.='<option value="0">'.$this->instance->l('nenastaveno').'</option>';
        foreach($sazby as $s) {              
             $retval.='<option value="'.$s['id_tax_rules_group'].'"';
             if($s['id_tax_rules_group'] == $selected) {
              $retval.= ' selected="selected"';
             }
             $retval.='>'.$s['name'].'</option>';
        }
        $retval.='</select>';
        return $retval; 
     }
 
}