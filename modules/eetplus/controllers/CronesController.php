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
class CronesController extends EetplusController
{
    protected $settings = array('EETPLUS_CRONEMAIL');
    
    public function __construct($instance)
    {
        $this->instance = $instance;
    }
    
    public function postProcess()
    {
        require_once(_PS_MODULE_DIR_ . $this->instance->name . '/classes/ReceiptCreate.php');
        parent::postProcess();
        $retval = '';
        $keys   = array('EETPLUS_CRONPKP', 'EETPLUS_CRONWARN3H', 'EETPLUS_CRONKURZ');
        foreach ($keys as $key) {
            Configuration::updateValue($key, (int) Tools::getValue($key));
        }
        return $retval;
    }
    
    public function getContent($tabnum)
    {
        $output = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        $output .= '<input type="hidden" name="currentTab" value="' . $tabnum . '" />';
        $output .= '<fieldset><legend>' . $this->instance->l('Nastavení kronu', 'CronesController') . '</legend>';
        $output .= '<div class="form-group"><label class="control-label col-lg-3">';
        $output .=$this->instance->l('Skript ke spouštění:', 'CronesController');
        $module_url = $this->instance->getBaseUrl() . 'modules/' . $this->instance->name.'/cron.php';
        $module_url.='?hash='.$this->instance->getHash();
        $output .= '</label>';        
        $output.='<div class="col-lg-9 "><a href="'.$module_url.'" target="_blank">'.$module_url.'</a></div>'; 
        $output .= '</div>';
        $output .= '<br />';         
        $checked = Configuration::get('EETPLUS_CRONPKP') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_CRONPKP', $this->instance->l('Automaticky doplňovat FIK', 'CronesController'), $this->instance->l('Pro účtenky s chybějícím FIK se opakovaně pokouší o jeho získání', 'CronesController'), $checked);
        $output .= '';
        
        $output .= $this->generateTextBox('EETPLUS_CRONEMAIL', $this->instance->l('E mail pro odesílání informaci', 'CronesController'), $this->instance->l('Další emailové adresy oddělte čárkou', 'CronesController'), 6);
        $output .= '<br />';       
        $checked = Configuration::get('EETPLUS_CRONWARN3H') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_CRONWARN3H', $this->instance->l('Informovat o účtenkách s chybějícím FIK', 'CronesController'), $this->instance->l('Zašle seznam účtenek s chybějícím FIK ve stáří 3 hod a až 2 dny', 'CronesController'), $checked);
        $output .= '<br />';
        
        $output .= '<br />';         
        $checked = Configuration::get('EETPLUS_CRONKURZ') == 1 ? ' checked ="checked"' : '';
        $output .= $this->generateCheckBox('EETPLUS_CRONKURZ', $this->instance->l('Aktualizovat kurz podle ČNB', 'CronesController'), $this->instance->l('Pokud jste zvolili Aktuální kurz ČNB, záložka Další', 'CronesController'), $checked);
         $output .= '<br />';     
        
        $output .= $this->generateSubmit('cmd_Crones[0]', $this->instance->l('Uložit', 'CronesController'));
        $output .= '</fieldset></form>';
        return $output;
    }
    
}