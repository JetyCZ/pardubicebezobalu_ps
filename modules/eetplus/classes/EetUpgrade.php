<?php

class EetUpgrade {
    
private $versions = array('1.4.2', '1.4.4','1.4.8');

public function getAvailableMethods($lastVersion, $currentVersion) {
   $retval = array();
   foreach($this->versions as $version) {
       if($lastVersion == false || Tools::version_compare($version, $lastVersion, '>')) {
           if(Tools::version_compare($currentVersion, $version, '>=')) {
              $method ='upgrade_module_'.str_replace('.','_',$version);
              if(method_exists($this, $method)) {
                 $retval[] = $method;
              } 
           }
       }
   }
   return $retval; 
    
}

public function upgrade_module_1_4_8 ($module) {
    $source = _PS_MODULE_DIR_.$module->name.'/override/classes/order/OrderSlip.php';
    $target = _PS_OVERRIDE_DIR_.'/classes/order/OrderSlip.php';
    if(file_exists($source) && file_exists($target)) {
        copy($target, $target.'.'.date('YmdHis'));
        unlink($target);
        copy($source, $target);
    }
}
 
public function upgrade_module_1_4_4 ($module) {
         Configuration::updateValue('EETPLUS_DISCNTERROR', 1);
}
 
public function upgrade_module_1_4_2($module)
{

// PDF  
$source = _PS_MODULE_DIR_.$module->name.'/override/classes/pdf/HTMLTemplateEet.php';
$target = _PS_OVERRIDE_DIR_.'/classes/pdf/HTMLTemplateEet.php';
if(file_exists($target)) {
    unlink($target);
}
if(file_exists($source) && !file_exists($target)) {
copy($source, $target);
} 


$source = _PS_MODULE_DIR_.$module->name.'/override/classes/order/OrderSlip.php';
$target = _PS_OVERRIDE_DIR_.'/classes/order/OrderSlip.php';
if(file_exists($source) && file_exists($target)) {
     copy($target, $target.'.'.date('YmdHis'));
     unlink($target);
     copy($source, $target);
}
 
//$module->addOverride('OrderSlip');

unlink(_PS_CACHE_DIR_.'class_index.php');   

$hooks = array('actionOrderSlipAdd', 'hookActionProductUpdate', 'actionOrderStatusUpdate', 
'actionOrderStatusUpdate', 'actionPaymentCCAdd', 'displayBackOfficeHeader', 'displayAdminOrder',
'displayBanner', 'displayFooter', 'displayLeftColumn', 'displayOrderConfirmation', 'displayPDFInvoice',
'displayRightColumn', 'header');
 
foreach($hooks as $hook_name) {
     $module->registerHook($hook_name);
} 
 


    return true;
}

}
