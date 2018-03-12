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
class TestsController extends EetplusController
{
    protected $settings = array('EETPLUS_TEST');
    
    public function __construct($instance)
    {
        $this->instance = $instance;
    }
    
    public function postProcess()
    {
        require_once(_PS_MODULE_DIR_ . $this->instance->name . '/classes/ReceiptCreate.php');
        parent::postProcess();
        $keys = array_keys(Tools::getValue('cmd_Tests'));
        if (is_array($keys)) {
            $key = $keys[0];
        }
        $retval  = '';
        $overeni = $key == 0 ? true : false;
        $id_order = (int)Tools::getValue('txt_idorder');
        if($overeni == false) {
            $order = new Order($id_order);
            if((int)$order->id == 0) {
               $this->instance->setMessage($this->instance->l("Objednávka neexistuje: ").Tools::getValue('txt_idorder'));
               return; 
            }
        }
        
        if($overeni == true && (int)$id_order == 0) {
             $sql      = 'SELECT MAX(id_order) FROM ' . _DB_PREFIX_ . 'orders';
             $id_order = (int) Db::getInstance()->getValue($sql);  
        }
        $eet     = new ReceiptCreate($this->instance, $overeni);
      
        $retval = $eet->registerPayment($id_order);
      
    }
    
    public function getContent($tabnum)
    {     
       
        $output = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        $output .= '<input type="hidden" name="currentTab" value="' . $tabnum . '" />';
        $output .= '<fieldset><legend>' . $this->instance->l('Testování', 'TestsController') . '</legend>';
        $output .= $this->generateSubmit('cmd_Tests[0]', $this->instance->l('Otestovat spojení', 'TestsController'));
        if (Configuration::get('EETPLUS_SANDBOX') == 1) {
            $output .= $this->getOrderTextbox();
            $output .= $this->generateSubmit('cmd_Tests[1]', $this->instance->l('Poslat tržbu (sandbox)', 'TestsController'));
        }
        $output .= '</fieldset></form>';
        return $output;
    }
    
    private function getOrderTextbox()
    {
        $sql      = 'SELECT MAX(id_order) FROM ' . _DB_PREFIX_ . 'orders';
        $id_order = (int) Db::getInstance()->getValue($sql);
        if (!$id_order)
            return;
        $retval = '<div class="form-group" style="padding-left:15px;"><label class="control-label">' . $this->instance->l('Číslo objednávky: ', 'TestsController') . ' </label>';
        $retval .= '<div class="" style="display: inline-block;">';
        $retval .= '<input   type="text" class=""  size="6" value="' . $id_order . '" name="txt_idorder" style="width:auto !important;margin-left:10px;position:relative;top:10px;">
        </div>  </div>';
        return $retval;
    }
    
}