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
 
class ReceiptCreate {

protected $instance;
protected $overovaci_mod = false;
protected $playground = 0;
protected $receiptData;

public function __construct($instance, $overeni = false)
{
    $this->playground = (int) Configuration::get('EETPLUS_SANDBOX'); 
    $this->overovaci_mod = $overeni;
    $this->instance =  $instance;
}

public function registerPayment($id_order,  $ceny = null, $akce ='trzba', $existing_id = null)
{
 ini_set("soap.wsdl_cache_enabled", "0");    
    $this->init();
  
    if (Shop::isFeatureActive() && !Tools::isSubmit('cmd_Tests')) {  //TODO overit chovani
         $sql ='SELECT id_shop FROM '._DB_PREFIX_.'orders WHERE id_order ='.(int)$id_order;
         if($id_shop = Db::getInstance()->getValue($sql)) {
         Context::getContext()->shop->setContext(Shop::CONTEXT_SHOP, (int)$id_shop);
         }
    }
   
   /*
     if(is_null($existing_id) ) {
         $existing_id = 0;
         $existing_id = ReceiptData::getExistingId($id_order, $akce, $this->playground);
    } 
   */  
    if($existing_id) {
        $this->receiptData = new ReceiptData($existing_id, $this->playground, $this->overovaci_mod);
        $receipt= $this->receiptData->getReceipt();
        
        if($receipt->porad_cis && $this->receiptData->fik && strlen($this->receiptData->fik) == ReceiptData::FIK_LEN &&  $this->overovaci_mod == false) {
        if(Tools::isSubmit('cmd_Tests')) {
            $this->instance->setMessage('Tržba k této objednávce již existuje, č. účtenky '.$receipt->porad_cis.', FIK: '.$this->receiptData->fik);
        } 
        return $receipt->porad_cis;
        }
    } 
   
      
    $crt = $this->getCertifikateSettings();
    try {
        $certifikate = new Certificate($crt['path'], $crt['pass']);
    }
    catch (Exception $exception) {
        if($this->overovaci_mod) {
             $this->instance->setMessage($exception->getMessage());
         }
         else {
            $this->instance->logFailure($receipt->porad_cis, $this->playground,  $exception->getMessage());
               if(Tools::isSubmit('cmd_Tests')) {
                  $this->instance->setMessage($exception->getMessage());
               } 
         }
        return false;
    }
        
    if(!$existing_id) {   
        $this->receiptData= new ReceiptData(null, $this->playground, $this->overovaci_mod);
        $receipt = $this->loadReceiptData(); 
        $receipt->uuid_zpravy =   UUID::v4();
        $order = new Order($id_order);

       
        if(is_null($ceny)) {
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/classes/RozpisCen.php');
            $rozpis = new RozpisCen($order);
            $ceny = $rozpis->getRozpis();
         }
         $this->receiptData->copyPricesToReceipt($receipt, $ceny);
   
 
        $receipt->porad_cis = $this->receiptData->presavePayment($receipt, $id_order);
    }

    $wsdlurl = $this->getWsdlUrl();
    
    $dispatcher = new DispatcherEET($wsdlurl, $certifikate);
    $dispatcher->trace = true;
    try { 
        $dispatcher->checkCodes = $dispatcher->getCheckCodes($receipt);
        $this->receiptData->setUpdateVar('codes', array('pkp'=>base64_encode($dispatcher->checkCodes['pkp']['_']),'bkp'=> $dispatcher->checkCodes['bkp']['_']),true);
        $this->receiptData->setUpdateVar('ceny', $ceny, true);
        $this->receiptData->update();
        $response = $dispatcher->send($receipt,  $this->overovaci_mod); //  , $this->overovaci_mod druhy parametr je overeni v xml
    }
    catch (Exception $exception) {
        $this->instance->logFailure($receipt->porad_cis, $this->playground,  $exception->getMessage());
        if(Tools::isSubmit('cmd_Tests')) {
            $this->instance->setMessage($exception->getMessage());
        } 
        if(Tools::isSubmit('cmd_Tests') && !$this->overovaci_mod) {
             $this->instance->setMessage('Účtenka vystavena s pkp pod číslem '.$receipt->porad_cis);
        }
        if($this->overovaci_mod || $existing_id) {
             return false;
        }
        return $receipt->porad_cis;
    }
 
   if($this->overovaci_mod) {
         $this->analyseResponse($response, null, $dispatcher->responseTime);
         return false;
    }
    if(isset($response->Chyba) && (int)$response->Chyba->kod) {
        $this->instance->logFailure($receipt->porad_cis, $this->playground,  DispatcherEET::processError($response->Chyba));
        if(Tools::isSubmit('cmd_Tests')) {
            $this->instance->setMessage(DispatcherEET::processError($response->Chyba));
        } 
    }
    else {
        $this->receiptData->setUpdateVar('response', (array)$response, true);
        $this->receiptData->setUpdateVar('action', $akce);
        $this->receiptData->setUpdateVar('date_akc', $response->Hlavicka->dat_prij);
        $this->receiptData->setUpdateVar('fik', $response->Potvrzeni->fik);
        $this->receiptData->update();
        if(Tools::isSubmit('cmd_Tests')) {
            $this->instance->setMessage('Tržba byla odeslána č. účtenky '.$receipt->porad_cis.', FIK:'.$this->receiptData->fik, true);
        } 
    }

    return $receipt->porad_cis;
}

protected function analyseResponse($response, $id_eet=null, $responseTime= null)
{
    if($this->overovaci_mod == true) {
        return $this->analyseOvereniResponse($response, $responseTime); 
    }
    else {
        return $this->analyseSavedResponse(null, $id_eet); 
    }
}

protected function analyseOvereniResponse($response, $responseTime)
{
    if(isset($response->Chyba)) {
        if((int)$response->Chyba->kod == 0) {
            $this->instance->setMessage('Test spojení byl úspěšný,  uuid zprávy: '.$response->Hlavicka->uuid_zpravy, true);
            if($responseTime) {
               $this->instance->setMessage('Odezva: '.number_format($responseTime, 2, ',', '').' sec', true);
            }
        }
        else {
             $this->instance->setMessage('Došlo k chybě'.DispatcherEET::processError($response->Chyba));
        }
    }
}

protected function analyseSavedResponse($id_eet)
{
    if(!$id_eet) {
         $this->instance->setMessage('Účtenka nebyla nalezena');
         return;
    }
    
    $sql = 'SELECT response FROM '._DB_PREFIX_.$this->table.' WHERE id_eetplus = '.(int)$id_eet;

    $response= Db::getInstance()->getValue($sql);
    $response = json_decode($response);
    if(isset($response->Potvrzeni) && isset($response->Potvrzeni->fik) && strlen($response->Potvrzeni->fik)) {
        $this->instance->setMessage('Test v pořádku. Vrácený kód potvrzení je: '.$response->Potvrzeni->fik, true);
    }
    if(isset($response->Chyba)) {
     $this->instance->setMessage('Došlo k chybě '.DispatcherEET::processError($response->Chyba));
    }
}

protected function loadReceiptData ()
{
    $receipt = new Receipt();
    $receipt->id_provoz =  $this->instance->getSetting('EETPLUS_IDPROVOZ');
    $receipt->id_pokl = $this->instance->getSetting('EETPLUS_IDPOKL');
    $receipt->dic_popl = $this->instance->getSetting('EETPLUS_DICPOPL');
    //  $receipt->dat_trzby = $this->instance->dateToIso(date('Y-m-d H:i:s'));
    $receipt->dat_trzby = new DateTime();  
    return $receipt; 
}

protected function getWsdlUrl() {
    switch ($this->playground) {
        case 1:  return _PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/Schema/PlaygroundService.wsdl';
        default:    return _PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/Schema/ProductionService.wsdl';  
    }    
}

protected function getCertifikateSettings() 
{
    if ($this->playground == 1) {
      return array('path'=> _PS_MODULE_DIR_.$this->instance->name.'/certs/sandbox/'.Eetplus::CERT_SANDBOX,'pass'=>'eet');  
    }
    else {
      return array('path'=>_PS_MODULE_DIR_.$this->instance->name.'/certs/'.Configuration::get('EETPLUS_CERT'), 'pass'=>Configuration::get('EETPLUS_CERT_PASSWD'));
    }
}

    
     
    protected function init() 
    {
        $version =  phpversion();
        if( version_compare($version, '5.4.0', '<')) {
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy53/Dispatcher.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy53/Receipt.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy53/Certificate.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy53/SoapClient.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy53/Utils/Format.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy53/Utils/UUID.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy53/Exceptions/ClientException.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy53/Exceptions/RequirementsException.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'//lib/filipsedivy53/Exceptions/ServerException.php');    
        }
        else {
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/Dispatcher.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/Receipt.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/Certificate.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/SoapClient.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/Utils/Format.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/Utils/UUID.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/Exceptions/ClientException.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/filipsedivy/Exceptions/RequirementsException.php');
            require_once(_PS_MODULE_DIR_.$this->instance->name.'//lib/filipsedivy/Exceptions/ServerException.php');   
            
        }
         
      
        require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/robrichards/xmlseclibs/XMLSecurityKey.php'); 
        require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/robrichards/xmlseclibs/XMLSecurityDSig.php'); 
        require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/robrichards/xmlseclibs/XMLSecEnc.php');  
        require_once(_PS_MODULE_DIR_.$this->instance->name.'/lib/robrichards/wsephp/WSSESoap.php');    
    }

}

