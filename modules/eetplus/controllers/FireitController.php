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
class FireitController extends EetplusController
{
    protected $settings = array();
    
    public function __construct($instance)
    {
        $this->instance = $instance;
    }
    
    public function postProcess()
    {
        Configuration::updateValue('EETPLUS_STATES', json_encode(Tools::getValue('EETPLUS_STATES')));
        Configuration::updateValue('EETPLUS_ALLSTATES', json_encode(Tools::getValue('EETPLUS_ALLSTATES')));
        Configuration::updateValue('EETPLUS_ANYSTATES', json_encode(Tools::getValue('EETPLUS_ANYSTATES')));
        
        
        Configuration::updateValue('EETPLUS_ADDPAYMENT', json_encode(Tools::getValue('EETPLUS_ADDPAYMENT')));
        
        Configuration::updateValue('EETPLUS_ADDPAYMENTON', (int)Tools::getValue('EETPLUS_ADDPAYMENTON'));
        Configuration::updateValue('EETPLUS_ADDPAYMENTDIF', Tools::getValue('EETPLUS_ADDPAYMENTDIF'));
        
         
        
         $sql ='SELECT name FROM '._DB_PREFIX_.'order_state_lang WHERE id_order_state ='.(int)Configuration::get('PS_OS_CANCELED').'
        AND id_lang='.Context::getContext()->language->id;
        $name = Db::getInstance()->getValue($sql);
        if($name && strlen($name)) {
               Configuration::updateValue('EETPLUS_CANCELON', (int)Tools::getValue('EETPLUS_CANCELON'));  
        }
        Configuration::updateValue('EETPLUS_ALERSTATE', (int)Tools::getValue('EETPLUS_ALERSTATE')); 
      //   Configuration::updateValue('EETPLUS_VOUCHFORCE', (int)Tools::getValue('EETPLUS_VOUCHFORCE'));
        
        
        
        return parent::postProcess();
    }
    
    public function getContent($tabnum)
    {
        $output = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        $output .= '<input type="hidden" name="currentTab" value="' . $tabnum . '" />';
        
       
     
        $output .= '<fieldset><legend>' . $this->instance->l('Vydávání účtenek', 'FireitController') . '</legend>';
        $modules   = Module::getPaymentModules();
        $states    = OrderState::getOrderStates(Context::getContext()->language->id);
        $states    = $this->filterStates($states);
        $eetstates = json_decode(Configuration::get('EETPLUS_STATES'), true);        
        $addstates = json_decode(Configuration::get('EETPLUS_ADDPAYMENT'), true);
        $allstates = json_decode(Configuration::get('EETPLUS_ALLSTATES'), true);
        $anystates = json_decode(Configuration::get('EETPLUS_ANYSTATES'), true);
        
        $history = $this->getStatesHistory($allstates, $anystates); 
        $output .= '<div class="row table-responsive clearfix "><div class="">';
        
        $pocet = count($modules) +1;
        
        $output .= '<table class="table" style="width:auto;">';
        $output .= '<thead>';
        $output .= '<tr><th  style="text-align:center;font-weight:800;text-align:left;" colspan='.$pocet.'>'.$this->instance->l('Prvotní vydání   účtenky při změně stavu objednávky (účtenka se vydá pouze 1x i pokud zvolíte více stavů)', 'FireitController').'</th></tr>';
        $output .= '<tr><th>Stav objednávky</th>';
        foreach ($modules as $module) {
            $mod = Module::getInstanceByName($module['name']);
            $output .= '<th>' .  $mod->displayName. '</th>';
        }
        $output .= '</tr>';
       
        $output .= '</thead>';
        $output .= '<tr><td style="background-color:#dddddd;"><b>' .$this->instance->l('Vždy', 'FireitController'). '</b></td>';
         reset($modules);
            foreach ($modules as $module) {
            $checked = $this->isAllStateMod( $module['name'], $anystates);
            $output .= '<td style="background-color:#dddddd;"><input name="EETPLUS_ANYSTATES[' . $module['name'] . ']" value="1"   type="checkbox" ' . $checked . '></td>'; 
        }
        $output .='</tr>'; 
        
         $output .= '<tr><td style="background-color:#dddddd;"><b>' .$this->instance->l('Vždy pokud je uhrazeno', 'FireitController'). '</b></td>';
         reset($modules);
            foreach ($modules as $module) {
            $checked = $this->isAllStateMod( $module['name'], $allstates);
            $output .= '<td style="background-color:#dddddd;"><input name="EETPLUS_ALLSTATES[' . $module['name'] . ']" value="1"   type="checkbox" ' . $checked . '></td>'; 
        }
        $output .='</tr>'; 
        
        foreach ($states as $state) {
            $output .= '<tr><td>' . $state['name'] . '</td>';
            reset($modules);
            foreach ($modules as $module) {
                $checked = $this->isStateEet($state['id_order_state'], $module['name'], $eetstates);
                $output .= '<td><input name="EETPLUS_STATES[' . $state['id_order_state'] . '][' . $module['name'] . ']" value="1"   type="checkbox" ' . $checked . '></td>';
            }
            $output .= '</tr>';
        }
        $output .= '<tr><th  style="text-align:center;font-weight:800;text-align:left;border-top:2px solid #707070;padding-top:40px;" colspan='.$pocet.'>'.$this->instance->l('Vydání účtenky při ručním přidání   platby v administraci', 'FireitController').'</th></tr>';
      
        reset($modules);
        $output .= '<tr><th>&nbsp;</th>';
        foreach ($modules as $module) {
             try {
                 $mod = Module::getInstanceByName($module['name']);
                 $output .= '<th style="font-weight:400;">' .  $mod->displayName. '</th>';
             } catch (Exception $exception) {
                 $output .= '<th style="font-weight:400;">' .  "ERROR". '</th>';
             }

          
        }
        $output .= '</tr>';
        
        $output .= '<tr><td>&nbsp;</td>';
        reset($modules);
            foreach ($modules as $module) {
            $checked = $this->isAddPaymentMod( $module['name'], $addstates);
            $output .= '<td><input name="EETPLUS_ADDPAYMENT[' . $module['name'] . ']" value="1"   type="checkbox" ' . $checked . '></td>'; 
        }
        $output .='</tr>'; 
        $output .= '</table>';
        $output .= '</div></div>';
        
        $output .='<div>';
        $checked = Configuration::get('EETPLUS_ADDPAYMENTON') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_ADDPAYMENTON', $this->instance->l('Aktivovat  vydávání účtenek při ručním přidání platby v administraci', 'FireitController'), null, $checked, 3, 3);
        $output .= $this->generateTextBox('EETPLUS_ADDPAYMENTDIF', $this->instance->l('Povolená odchylka v Kč', 'FireitController'), null, 2, false);
         $output .= '<br />'.$this->instance->l('Účtenka při přidání platby v administraci se tedy vydá pokud je to zde povoleno, odpovídá platební modul 
         a přidávaná částka je v zadané toleranci k hodnotě dodatečně přidaných produktů',  'FireitController');
        $output .='</div>';
        $output.='<br /> ';
           $checked = Configuration::get('EETPLUS_ALERSTATE') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_ALERSTATE', $this->instance->l('Nezobrazovat varování k platebním stavům', 'FireitController'), null, $checked);
         $output.='<br /> '; $output.='<br /> ';
 
        
        $sql ='SELECT name FROM '._DB_PREFIX_.'order_state_lang WHERE id_order_state ='.(int)Configuration::get('PS_OS_CANCELED').'
        AND id_lang='.Context::getContext()->language->id;
        $name = Db::getInstance()->getValue($sql);
        if($name && strlen($name)) {
         $output.='<br /><div>';
        $checked = Configuration::get('EETPLUS_CANCELON') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_CANCELON', $this->instance->l('Aktivovat stav ','FireitController').'<b>'.$name.'</b>',  $this->instance->l('Při převedení objednávky do stavu Zrušeno se pro ni vynuluje EET','FireitController'), $checked, 3, 3);
        $output.='</div>';
        }
        $output.='<div>';
        $output.='<h4 style = "font-weight:800">'.$this->instance->l('Storna','FireitController').'</h4> ';
        
  
        
        $output.='<ul>';
        $output.='<li>'.$this->instance->l('Pro storna která mají být automaticky odeslána do EET používejte ','FireitController').'<b>'.$this->instance->l('DOBROPIS','FireitController').'</b></li>';
        $output.='<li>'.
        $this->instance->l('Pokud objednávka obsahuje ','FireitController').'<b>'.
        $this->instance->l('slevový kupón','FireitController').'</b>'.
        $this->instance->l('   použijte volbu','FireitController').
        '<b>'.
        $this->instance->l(' "Částka dle Vašeho výběru" ','FireitController').'</b>'
        .$this->instance->l('   ale částku nevyplňujte, ','FireitController').
         '<b>'.$this->instance->l('  modul částku    dopočte','FireitController'). '</b></li>';
         $this->instance->l('Pokud uvedená volba není k dispozici nebo ji nezvolíte, provede se výpočet 
         ve vyskakovacím okně ','FireitController'); 
         $output.='</ul>';
         
      
    
      //  $checked = Configuration::get('EETPLUS_VOUCHFORCE') == 1 ? ' checked ="checked"' : '';
     //   $output .= $this->generateCheckBox('EETPLUS_VOUCHFORCE', $this->instance->l('Dobropisy automaticky i pokud je zahrnuta částka původního slevového kuponu', 'FireitController'), null, $checked);
        
        
        $output.='</div><br />';
               $output .= $this->generateSubmit('cmd_Fireit[0]', $this->instance->l('Uložit', 'FireitController'));
        $output .= '</fieldset></form>';
        return $output;
    }
    
       private function isAddPaymentMod  ($modulename, $addstates)
    {
        if (isset($addstates[$modulename]))
            return ' checked="checked"';
        return '';
    }
    
    private function getStatesHistory($paidstates, $anystates) {
        if(Configuration::get('EETPLUS_ALERSTATE'))
          return;
          
        $counter = 0;
        if(is_array($paidstates)) {
            while(list($modulename, $val) = each($paidstates)){
                if(!is_array($anystates) || !key_exists($modulename, $anystates)) {
                  $sql = 'SELECT id_order FROM '._DB_PREFIX_.'orders WHERE module ="'.pSQL($modulename).'" ORDER BY id_order DESC';
                      $id_order = Db::getInstance()->getValue($sql);
                      if((int)$id_order) {
                         $sql ='SELECT id_order_history FROM  '._DB_PREFIX_.'order_history h LEFT JOIN '._DB_PREFIX_.'order_state s
                         on h.id_order_state = s.id_order_state WHERE h.id_order ='.(int)$id_order .' AND s.paid = 1';
                         $history = Db::getInstance()->executeS($sql);
                         if($history == false || ! is_array($history) || !count($history)) {
                           $this->instance->setMessage ($modulename.': '.$this->instance->l('neprošel v poslední objednávce žádným placeným stavem, nastavení "Vždy pokud je uhrazeno" nemá efekt! Upravte stavy objednávek.'));  
                           $counter++;
                         }
                      }
                      else {
                        $this->instance->setMessage($modulename.': '.$this->instance->l('Prosím ujistěte se, že při platbě prochází placeným stavem objednávky')); 
                        $counter++;
                      }
                }
            }
        }
        if($counter) {
         $this->instance->setMessage('Viz. Objednávky - Stavy objednávek - Považovat objednávku za zaplacenou');   
        }                                          
    }
      private function isAllStateMod  ($modulename, $allstates)
    {
        if (isset($allstates[$modulename]))
            return ' checked="checked"';
        return '';
    }
    
    private function isStateEet($id_order_state, $modulename, $eetstates)
    {
        if (isset($eetstates[$id_order_state][$modulename]))
            return ' checked="checked"';
        return '';
    }
    
    private function filterStates($states)
    {
        $retval = array();
        foreach ($states as $state) {
            
            if ($state['deleted'] == 0 && $state['paid'] == 1)
                $retval[] = $state;
        }
        return $retval;
    }
    
}