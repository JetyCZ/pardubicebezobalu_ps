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
 
if (!defined('_PS_VERSION_')) {
    exit;
}

class Eetplus extends Module
{
    protected $tabs = array('Settings', 'Tests', 'Fireit',    'Dalsi', 'Crones' );
    protected $tabnames = array('Základní nastavení', 'Okamžité testování', 'Vydávání účtenek',   'Další nastavení', 'Crony');
    protected $currentTab = 0;
    const CERT_SANDBOX = 'EET_CA1_Playground-CZ00000019.p12';
    const CERT_MAXSIZE = 20000;
    protected $messages;
    public static $currencyData = false;
    
    public function __construct()
    {
        register_shutdown_function("eetFatalErrorHandler");
        $this->name          = 'eetplus';
        $this->tab           = 'billing_invoicing';
        $this->version       = '1.4.8';
        $this->author        = 'Prestahost';
        $this->need_instance = 1;
        require_once(_PS_MODULE_DIR_ . $this->name . '/classes/ReceiptData.php');
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName      = $this->l('EET for Prestashop');
        $this->description      = $this->l('EET module for Prestashop');
        $this->confirmUninstall = $this->l('');
    }
    
    public function install()
    {
        Configuration::updateValue('EETPLUS_SANDBOX', true);
        Configuration::updateValue('EETPLUS_CRONEMAIL', Configuration::get('PS_SHOP_EMAIL'));
        Configuration::updateValue('EETPLUS_TEXTCOL', '#000000');
        Configuration::updateValue('EETPLUS_ADDPAYMENTDIF', 1);
        Configuration::updateValue('EETPLUS_DISCNTERROR', 1);
        
        Configuration::updateValue('EETPLUS_TIMEOUT', 2);   
        
        Configuration::updateValue('EETPLUS_VERSION', $this->version);
         
        
        include(dirname(__FILE__) . '/sql/install.php');
        return parent::install() && $this->installModuleTab('AdminOrdersEetplus', 'EET', 'AdminOrders') && 
        $this->copyPdf() &&
        $this->registerHook('actionOrderSlipAdd') && 
        $this->registerHook('hookActionProductUpdate') && 
        $this->registerHook('actionOrderStatusUpdate') &&
        $this->registerHook('actionPaymentCCAdd') &&
         
        
        $this->registerHook('displayBackOfficeHeader') && 
        $this->registerHook('displayAdminOrder')  && 
        //$this->registerHook('displayAdminOrderTabOrder')  && 
        
        $this->registerHook('displayBanner')  && 
        $this->registerHook('displayFooter')  && 
        $this->registerHook('displayLeftColumn')  && 
        $this->registerHook('displayOrderConfirmation')  &&  
        $this->registerHook('displayPDFInvoice') &&
        $this->registerHook('displayRightColumn')  && 
        
        
        $this->registerHook('header') && 
        1;
    }
    
    private function copyPdf() {
       $source = _PS_MODULE_DIR_.$this->name.'/override/classes/pdf/HTMLTemplateEet.php';
       $target = _PS_OVERRIDE_DIR_.'/classes/pdf/HTMLTemplateEet.php';
       if(file_exists($source) && !file_exists($target)) {
         copy($source, $target);
         unlink(_PS_CACHE_DIR_.'class_index.php');
       } 
       
       return true;
    }
       
    public function uninstall()
    {
     $keys = array('EETPLUS_STATES','EETPLUS_SANDBOX','EETPLUS_IDPROVOZ','EETPLUS_IDPOKL','EETPLUS_DICPOPL','EETPLUS_IC','EETPLUS_CERT_PASSWD',
    'EETPLUS_CERT','EETPLUS_TEST','EETPLUS_UCTADDR','EETPLUS_UCTROZPR','EETPLUS_UCTSUMM','EETPLUS_UCTDPH',
    'EETPLUS_CRONEMAIL','EETPLUS_TEXTCOL','EETPLUS_ADDPAYMENTDIF','EETPLUS_TIMEOUT','EETPLUS_TEXT','EETPLUS_ALLSTATES','EETPLUS_ANYSTATES',
'EETPLUS_ADDPAYMENT','EETPLUS_ADDPAYMENTON','EETPLUS_CANCELON','EETPLUS_ALERSTATE','EETPLUS_CERPANI','EETPLUS_MOSS', 
'EETPLUS_MAILRESTR','EETPLUS_PDFHOOK','EETPLUS_SAZBY','EETPLUS_RATECZK','EETPLUS_PREFIXCERP','EETPLUS_VERSION');
    
    foreach($keys as $key) {        
        Configuration::deleteByName($key);
    }
        $this->uninstallModuleTab('AdminOrdersEetplus');
        include(dirname(__FILE__) . '/sql/uninstall.php');
        return parent::uninstall();
    }
    public function installModuleTab($tabClass, $tabName, $parentName)
    {
        $sql         = 'SELECT id_tab FROM ' . _DB_PREFIX_ . 'tab WHERE class_name="' . pSQL($parentName) . '"';
        $idTabParent = Db::getInstance()->getValue($sql);
        if (!$idTabParent) {
            $this->messages[] = 'Failed to find parent tab ' . $parentName;
            return false;
        }
        @copy(_PS_MODULE_DIR_ . $this->module->name . '/logo.gif', _PS_IMG_DIR_ . 't/' . $tabClass . '.gif');
        $tab      = new Tab();
        $tabNames = array();
        foreach (Language::getLanguages(false) as $language) {
            $tabNames[$language['id_lang']] = $tabName;
        }
        $tab->name       = $tabNames;
        $tab->class_name = $tabClass;
        $tab->module     = $this->name;
        $tab->id_parent  = $idTabParent;
        if (!$tab->save()) {
            $this->messages[] = 'Failed save Tab ' . implode(',', $tabNames);
            return false;
        }
        if (!Tab::initAccess($tab->id)) {
            $this->messages[] = 'Failed save init access ' . implode(',', $tabNames);
            return false;
        }
        return true;
    }
    
    public function uninstallModuleTab($tabClass)
    {
        $idTab = Tab::getIdFromClassName($tabClass);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $tab->delete();
            return true;
        }
        return true; // true even on failed
    }
    
    public function getContent()
    {
        $this->context->controller->addCSS($this->_path . 'css/back.css', 'all');
        $this->currentTab = (int)Tools::getValue('currentTab');
        $this->_html = '<h2>' . $this->displayName . '</h2><br />';
        $module_url  = $this->getBaseUrl() . 'modules/' . $this->name;
        $this->_html .= '<script language="JavaScript">
        <!--
        var currentTab=' . (int) $this->currentTab . ';    

        function showTab(num) {
        currentTab=num;
        var tabcount=' . count($this->tabs) . '
        for(i=0;i< tabcount;i++) {
          var idtab= "#moduletab"+i;
          $(idtab).hide();
           var navtab= "#navtab"+i;
           $(navtab).removeClass("red"); 
        }
        var idtab= "#moduletab"+num;
        $(idtab).show(); 
          var navtab= "#navtab"+num;
          $(navtab).addClass("red"); 
        return false;
        }

        //-->
        </script>';
       
        $this->_html .= '<div id="navcontainer"><ul id="navlist">';
        $class = '';
        for ($i = 0; $i < count($this->tabs); $i++) {
            if($i == $this->currentTab) { 
              $class='red';
            }
            $this->_html .= '<li style="float:left;padding-right:20px;" ><a  class="' . $class . '" id=navtab' . $i . ' href="#" onClick="showTab(' . $i . '); return false;">' . $this->tabnames[$i] . '</a></li>';
            $class = '';
        }
        $this->_html .= '</ul></div><div style="clear:left"></div><br /><br />';
        $seltab = 0;
        if ((int) Tools::getValue('currentTab'))
            $seltab = (int) Tools::getValue('currentTab');
        $output = '';
        $tabnum = 0;
        require_once(_PS_MODULE_DIR_ . $this->name . '/controllers/EetplusController.php');
        foreach ($this->tabs as $tab) {
            $classname = ucfirst($tab) . 'Controller';
            if (file_exists(_PS_MODULE_DIR_ . $this->name . '/controllers/' . $classname . '.php')) {
                require_once(_PS_MODULE_DIR_ . $this->name . '/controllers/' . $classname . '.php');
                $controller = new $classname($this);
                if ($seltab == $tabnum) {
                    $output .= '<div id="moduletab' . $tabnum . '" style="display:block">';
                } else {
                    $output .= '<div id="moduletab' . $tabnum . '" style="display:none">';
                }
                if (Tools::isSubmit('cmd_' . $tab)) {
                    $result = $controller->postProcess($this);
                }
                
                $output .= $controller->getContent($tabnum++);
                $output .= '</div>' . "\n";
            }
        }
        return $this->_html .$this->displayMessages(). $output;
    }
    
    public function getBaseUrl()
    {
        if ($_SERVER['HTTP_HOST'] == 'localhost:8080')
            $module_url = '/';
        else {
            $sql     = 'SELECT id_shop_url FROM ' . _DB_PREFIX_ . 'shop_url
    WHERE id_shop=' . (int) Context::getContext()->shop->id . ' AND active=1 AND main="1"';
            $shopUrl = new ShopUrl(Db::getInstance()->getValue($sql));
            if (!$shopUrl || empty($shopUrl)) {
                $sql     = 'SELECT id_shop_url FROM ' . _DB_PREFIX_ . 'shop_url
    WHERE id_shop=' . (int) Context::getContext()->shop->id . ' AND active=1';
                $shopUrl = new ShopUrl(Db::getInstance()->getValue($sql));
            }
            $module_url = $shopUrl->getURL($this->use_ssl());
        }
        return $module_url;
    }
    public function hookDisplayBackOfficeHeader() {
       if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }
    
    public function hookBackOfficeHeader()
    { 
     return $this->hookDisplayBackOfficeHeader(); 
       
    }
    
 
  
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }
    
    public function hookActionOrderSlipAdd($params) {
         //  $params['order']->id = 64;
         //  params['productList] = array(133=>133);   //id_order_detail
            if(!$this->isEetTrzba($params['order']->id)) { 
                 return;
            }

            $id_order_detail = 0;
            if(is_array($params['productList'])) {
                $keys = array_keys($params['productList']);
                $id_order_detail = $keys[0];
            }
            
            if($id_order_detail) {
               $sql = 'SELECT o.id_order_slip FROM  '._DB_PREFIX_.'order_slip o LEFT JOIN  '._DB_PREFIX_.'order_slip_detail d
            ON o.id_order_slip = d.id_order_slip 
             WHERE o.id_order ='.(int)$params['order']->id.' AND d.id_order_detail ='.(int)$id_order_detail.' ORDER BY id_order_slip DESC';
            }
            else {
                $sql = 'SELECT o.id_order_slip FROM  '._DB_PREFIX_.'order_slip o  WHERE o.id_order ='.(int)$params['order']->id.' ORDER BY
            id_order_slip DESC';
            }
            $row = Db::getInstance()->getRow($sql);
            $id_order_slip = $row['id_order_slip'];

            
            $order_slip = new OrderSlip((int)$id_order_slip);
            $order = new Order((int)$order_slip->id_order);
  
            $order->products = OrderSlip::getOrdersSlipProducts($order_slip->id, $order);
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/ReceiptCreate.php');
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/ReceiptOutput.php');
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/CenyObjednavky.php');
            
            if($params['order']->total_discounts > 0) {  // discounted
              if(!$this->canLocalProcess((int)$order_slip->id, (int)$params['order']->id)) {
                 $vals = array('id_order_slip'=>$order_slip->id, 'id_order'=>$order->id);
                 Context::getContext()->cookie->eetmessage = 'A: '.json_encode($vals);
                 return; 
              }
              else {
                    require_once(_PS_MODULE_DIR_ . $this->name . '/classes/RozpisSlipDiscounted.php');
                    $rozpis = new RozpisSlipDiscounted($order);  
              }
            }
            else { // not discounted
               require_once(_PS_MODULE_DIR_ . $this->name . '/classes/RozpisSlip.php');
               $rozpis = new RozpisSlip($order);   
            }
            $this->actionOrderSlipAdd($rozpis, $order_slip, $params['order']->id);
           
        
    }
    
    private function canLocalProcess($id_order_slip, $id_order) {
        if(Tools::getValue('refund_total_voucher_off') == 2 )
          return true;
          
         $sql = 'SELECT SUM(product_quantity - product_quantity_refunded) FROM '._DB_PREFIX_.'order_detail WHERE id_order = '.(int)$id_order;
                    $total_details = (int)Db::getInstance()->getValue($sql);
                    $sql = 'SELECT SUM(product_quantity) FROM '._DB_PREFIX_.'order_detail WHERE id_order = '.(int)$id_order_slip;
                    $slip_details = (int)Db::getInstance()->getValue($sql);
                    if($total_details - $slip_details == 0) {
                        // Context::getContext()->cookie->eetmessage = 'W: EET k dobropisu nebylo vygenerováno';
                         return true;
                    }
        return false;               
    }
    
    public function  actionOrderSlipAdd($rozpis, $order_slip, $id_order) {
            $ceny = $rozpis->getRozpis($order_slip);
            $ceny = cenyObjednavky::prohoditZnamenka($ceny);
            $eet           = new ReceiptCreate($this, false);
            $id_eet        = $eet->registerPayment($id_order, $ceny, 'slip');
            $receiptOutput = new ReceiptOutput($id_eet, $this);
            $receiptOutput->sendMail();
            if($id_eet) {
               Context::getContext()->cookie->eetmessage = 'I: EET k dobropisu bylo vygenerováno'; 
            }
            else {
                Context::getContext()->cookie->eetmessage = 'W: EET k dobropisu nebylo vygenerováno';
            }
    }
    
    private function isEetTrzba($id_order) {
        $table = self::getTableUsed();
        $sql = 'SELECT id_eetplus FROM '._DB_PREFIX_.$table.' WHERE id_order ='.(int)$id_order.' 
        AND action="trzba"';
        $id_eetplus = Db::getInstance()->getValue($sql);
        if($id_eetplus && (int)$id_eetplus) {
            return true;    
        }
        return false;
    }
    
    public static function getTableUsed() {
         if (Configuration::get('EETPLUS_SANDBOX') == 1) {
            return 'eetplus_sandbox';
        } else {
            return 'eetplus';
        }
    }
    
    public function hookActionOrderStatusUpdate($params)
    {
        $id_order   = $params['id_order'];
        $order      = new Order($id_order);
        $modulename = $order->module;
        $this->isEetModule($modulename);   // TODO - chyba ??
        require_once(_PS_MODULE_DIR_ . $this->name . '/classes/ReceiptCreate.php');
        require_once(_PS_MODULE_DIR_ . $this->name . '/classes/ReceiptOutput.php');
        
        if (self::isEetStateModule($modulename, $params['newOrderStatus']->id)) {
            $existing_id = ReceiptData::getExistingId($id_order, 'trzba', Configuration::get('EETPLUS_SANDBOX'));
            if ((int) $existing_id) {
            return;
            }
             if ($order->total_paid == 0) {
                return;
             }
            if(Configuration::get('EETPLUS_CONTEXT')) {
                $this->checkContextReady($order);
            }
            $eet           = new ReceiptCreate($this, false);
            $id_eet        = $eet->registerPayment($id_order, null, 'trzba');
            $receiptOutput = new ReceiptOutput($id_eet, $this);
            $receiptOutput->sendMail(null, null, $params['newOrderStatus']->id);
        }
         
        if($this->isCancelRequested($id_order,  $params['newOrderStatus']->id)) {
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/CenyObjednavky.php');
            $cenyObjednavky = new CenyObjednavky($id_order);
            $ceny = $cenyObjednavky->cenyFromHistory();
            $ceny = cenyObjednavky::prohoditZnamenka($ceny['bilance']);
            $eet           = new ReceiptCreate($this, false);
            $id_eet        = $eet->registerPayment($id_order, $ceny, 'storno');
            $receiptOutput = new ReceiptOutput($id_eet, $this);
            $receiptOutput->sendMail();
        }
         
    }
    
    private function checkContextReady($order) {
       if(!isset(Context::getContext()->currency) || !is_object(Context::getContext()->currency)) {
          Context::getContext()->currency = new Currency($order->id_currency);
       } 
       if(!isset(Context::getContext()->cart) || !is_object(Context::getContext()->cart)) {
          Context::getContext()->cart = new Cart($order->id_cart);
       } 
    }
    
    private function isCancelRequested($id_order, $new_order_state_id) {
        if((int)Configuration::get('EETPLUS_CANCELON') == 0 || !(int) $new_order_state_id || !$this->isEetTrzba($id_order)) {
           return false; 
        }
         
        if($new_order_state_id == Configuration::get('PS_OS_CANCELED')) {
            return true;
        }
        return false;  
         
    }
    
    
   
   // pojistka pokud brana zmeni stav bez spusteni hooku 
    public function hookDisplayOrderConfirmation($params)
    {
        if (isset($params['objOrder'])) {
            $order      = $params['objOrder'];
        } else {
            $order      = $params['order'];
        }

        $id_order   = $order->id;
        $modulename = $order->module;
        if ($order->total_paid <= 0) {
            return;
        }
        if (!$this->isEetModule($modulename)) {
            return;
        }
        $existing_id = ReceiptData::getExistingId($id_order, 'trzba', Configuration::get('EETPLUS_SANDBOX'));
        if ((int) $existing_id) {
            return;
        }
        $sql            = 'SELECT id_order_state FROM ' . _DB_PREFIX_ . 'order_history WHERE id_order = ' . (int) $id_order;
        $history_states = Db::getInstance()->executeS($sql);
        foreach ($history_states as $id_order_state) {
            if (self::isEetStateModule($modulename, $id_order_state['id_order_state'])) {
                $params                     = array();
                $params['id_order']         = $id_order;
                $params['newOrderStatus'] = new OrderState($id_order_state['id_order_state']);
                return $this->hookActionOrderStatusUpdate($params);
                //$params['newOrderStatus']->id
            }
        }
    }
    
   
    
    public function hookDisplayLeftColumn() {
       if((int)Configuration::get('EETPLUS_TEXT') == 0) {
         return $this->displayRequiredText('title');
       }
    }
    
    public function hookDisplayRightColumn() {
       if((int)Configuration::get('EETPLUS_TEXT') == 1) {
         return $this->displayRequiredText('title');
       }  
    }
    
    public function hookDisplayFooter() {
       if((int)Configuration::get('EETPLUS_TEXT') == 2) {
         return $this->displayRequiredText('clear');
       }  
    }
    
    public function hookDisplayBanner() {
       if((int)Configuration::get('EETPLUS_TEXT') == 3) {
         return $this->displayRequiredText();
       }  
    }
      public function hookDisplayAdminProductsExtra($params) { 
           $sql = 'SELECT   eet_cerp, eet_cest FROM '._DB_PREFIX_.'product WHERE
           id_product = '.(int)Tools::getValue('id_product'); 
           $row = Db::getInstance()->getRow($sql);
           $eet_cerp = 0;
           if($row && isset($row['eet_cerp'])) {
                $eet_cerp = (int) $row['eet_cerp'];
           } 
            $eet_cest = 0;
           if($row && isset($row['eet_cest'])) {
                $eet_cest = (int) $row['eet_cest'];
           }
           Context::getContext()->smarty->assign(array('eet_cerp'=>$eet_cerp, 'eet_cest'=>$eet_cest));  
           return Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/producttab.tpl');

      }
       public function hookActionProductUpdate($params)  {
            if(isset($params['id_product'])) {
                $id_product = $params['id_product'];    
           }
           else {
               $id_product = $params['product']->id;
           }
           $sql ='UPDATE '._DB_PREFIX_.'product SET 
           eet_cest = '.(int)Tools::getValue('eet_cest').',
           eet_cerp = '.(int)Tools::getValue('eet_cerp').' WHERE id_product ='.(int)$id_product;
           Db::getInstance()->execute($sql); 
      }
      
      public function hookActionPaymentCCAdd($params) {
            if (Tools::isSubmit('submitAddPayment') && Tools::getValue('id_order') && Configuration::get('EETPLUS_ADDPAYMENTON')) {
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/CenyObjednavky.php');
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/ReceiptCreate.php');
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/ReceiptOutput.php');
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/CenyObjednavky.php');
             
               $amount = $params['paymentCC']->amount;
               $id_order = Tools::getValue('id_order');
                
              // $reference = $params['order_reference'];  
                $payment_used = self::getPaymentModuleFromName(Tools::getValue('payment_method'));
                if($this->isEetAddPaymentModule($payment_used) == false) {
                     return;
                }
                $order = new Order($id_order);
                if($order->total_discounts) {
                // TODO ... ajax 
                // problem je v tom ze kupon se rozpocte do vsech produktu, nove je potreba vynechat
                 Context::getContext()->cookie->eetmessage = 'W: EET k platbě nebylo vygenerováno'; 
                 return;
                }
                $cenyObjednavky = new CenyObjednavky($id_order);
                $data = $cenyObjednavky->cenyFromHistory(true);
                $sazby = array('zakladni'=>Eetplus::sazbaToRate(1), 'snizena1'=>Eetplus::sazbaToRate(2), 'snizena2'=>Eetplus::sazbaToRate(3));
                require_once(_PS_MODULE_DIR_.$this->name.'/classes/RozpisCen.php');
                $rozpis = new RozpisCen(new Order($id_order));
                $aktualniCeny = $rozpis->getRozpis();
                $bilance = $data['bilance']; 
                $rozdilCen = $cenyObjednavky->odectiCeny($aktualniCeny, $bilance);
                $amountConverted = 0;
                if($this->odpovidajiCeny($rozdilCen['celk_trzba'], $amount,   $amountConverted, $params['paymentCC']->id_currency)) {
                    $eet           = new ReceiptCreate($this, false);
                    $id_eet        = $eet->registerPayment($id_order, $rozdilCen, 'doplatek');
                    $receiptOutput = new ReceiptOutput($id_eet, $this);
                    $receiptOutput->sendMail(); 
                }
            }
      }
      
      private function getPaymentModuleFromName($name) {
         foreach (PaymentModule::getInstalledPaymentModules() as $payment) {
            $module = Module::getInstanceByName($payment['name']);
            if (Validate::isLoadedObject($module) && $module->active) {
               if($module->displayName == $name) {
                    return $module->name;
               }
            }
        }  
      }
      
      public function hookDisplayPDFInvoice($params)  {
          $id_order = $params['object']->id_order;
          if(!Configuration::get('EETPLUS_PDFHOOK') || !$id_order) { 
              return;
           }   
          $vars = self::getCodesForOrder($id_order);    
          
          Context::getContext()->smarty->assign('vars', $vars);
          $output = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/pdf.tpl');
         return $output;
      }
      
      public static function getCodesForOrder($id_order) {
            $table = self::getTableUsed();
            $sql = 'SELECT id_eetplus FROM '._DB_PREFIX_.$table.' WHERE id_order ='.(int)$id_order.' AND action ="trzba"';
            $id_eetplus = Db::getInstance()->getValue($sql);
            if(!$id_eetplus) {
            return; 
            }
            require_once(_PS_MODULE_DIR_. 'eetplus/classes/ReceiptData.php');
            require_once(_PS_MODULE_DIR_.'/eetplus/lib/filipsedivy/Receipt.php');
            $receiptData = new ReceiptData($id_eetplus, (int) Configuration::get('EETPLUS_SANDBOX'), false);
            if (isset($receiptData->fik) && (strlen($receiptData->fik) == ReceiptData::FIK_LEN)) {
            $vars['fik']     = $receiptData->fik;
            } elseif (isset($receiptData->pkp) && (strlen($receiptData->pkp) == ReceiptData::PKP_LEN)) {
            $vars['pkp']     = $receiptData->pkp;
            }
            $vars['bkp'] =   $receiptData->bkp;
            return $vars;
      }
      
      // used with Pohoda
      public static function getEet($id_order) {
            $table = self::getTableUsed();
            $sql = 'SELECT id_eetplus FROM '._DB_PREFIX_.$table.' WHERE id_order ='.(int)$id_order.' AND action ="trzba"';
            $eets = Db::getInstance()->executeS($sql);
            if($eets == false || ! is_array($eets) || !count($eets)) {
              return false; 
            }
            $retval = array();
            require_once(_PS_MODULE_DIR_. 'eetplus/classes/ReceiptData.php');
            require_once(_PS_MODULE_DIR_.'/eetplus/lib/filipsedivy/Receipt.php');
            
            foreach($eets as $id_eetplus) {
                $receiptData = new ReceiptData($id_eetplus['id_eetplus'], (int) Configuration::get('EETPLUS_SANDBOX'), false);
                $receipt = $receiptData->getReceipt();
                    
                    
                if (isset($receiptData->fik) && (strlen($receiptData->fik) == ReceiptData::FIK_LEN)) {
                $retval[$id_eetplus['id_eetplus']]['FIK']     = $receiptData->fik;
                }  
                
                if(isset($receiptData->pkp) && (strlen($receiptData->pkp) == ReceiptData::PKP_LEN)) {
                $retval[$id_eetplus['id_eetplus']]['PKP']   = $receiptData->pkp;
                }
                
                if(isset($receiptData->bkp)) {
               $retval[$id_eetplus['id_eetplus']]['BKP']  =   $receiptData->bkp;
                }
                $date_akc             = new DateTime($receiptData->date_akc);
               
                $retval[$id_eetplus['id_eetplus']]['dateOfSend']  = $date_akc->format('Y-m-d\TH:i:s'); 
                $retval[$id_eetplus['id_eetplus']]['dateOfAcceptance']  = $date_akc->format('Y-m-d\TH:i:s'); 
            
                if(is_object($receipt)) {
                  $retval[$id_eetplus['id_eetplus']]['dateOfSale']  = $receipt->dat_trzby->format('Y-m-d\TH:i:s');  
                  $retval[$id_eetplus['id_eetplus']]['establishment'] = $receipt->id_provoz;
                  $retval[$id_eetplus['id_eetplus']]['price'] = $receipt->celk_trzba;
                  $retval[$id_eetplus['id_eetplus']]['numberOfDocument'] = $receipt->porad_cis;
                  $retval[$id_eetplus['id_eetplus']]['cashDevice']   = $receipt->id_pokl;
                   
                }
                if(Configuration::get('EETPLUS_SANDBOX') == 1) {
                $retval[$id_eetplus['id_eetplus']]['testMode'] = 'true';
                }
                else  {
                $retval[$id_eetplus['id_eetplus']]['testMode'] = 'false'; 
                }  
                
           
           }
           return $retval;
      }
      
      public function hookDisplayAdminOrder($params)
    {   
        /**
        * bylo
        * AdminOrdersController extends AdminOrdersControllerCore::postProcess
        */
        require_once(_PS_MODULE_DIR_.$this->name.'/classes/RozpisCen.php');
        require_once(_PS_MODULE_DIR_ . $this->name . '/classes/CenyObjednavky.php');
        require_once(_PS_MODULE_DIR_ . $this->name . '/eetplus.php');
       
       $id_order = $params['id_order'];
       $order = new Order($id_order); 
        
        
        
        if(Tools::isSubmit('submitEet')) {
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/ReceiptCreate.php');
            require_once(_PS_MODULE_DIR_ . $this->name . '/classes/ReceiptOutput.php');
     
             if(Tools::getValue('chckClearEet') == 1) {
                $cenyObjednavky = new CenyObjednavky($id_order);
                $data = $cenyObjednavky->cenyFromHistory();
                $bilance = $data['bilance']; 
                $ceny = cenyObjednavky::prohoditZnamenka($bilance);
                $eet           = new ReceiptCreate($this, false);
                $id_eet        = $eet->registerPayment(Tools::getValue('id_order'), $ceny, 'oprava');
             }
            else {
                $cenyObjednavky = new CenyObjednavky(Tools::getValue('id_order'));
                $ceny= $cenyObjednavky->cenyFromPostVars(
                Tools::getValue('celk_trzba'),
                Tools::getValue('dph'),
                Tools::getValue('pouzit_zboz'),
                Tools::getValue('eet_extra')
                );
                $eet           = new ReceiptCreate($this, false);
                $id_eet        = $eet->registerPayment(Tools::getValue('id_order'), $ceny, 'oprava');
            }
            $receiptOutput = new ReceiptOutput($id_eet, $this);
            $receiptOutput->sendMail();
        }
        
 
        $cenyObjednavky = new CenyObjednavky($id_order);
        $data = $cenyObjednavky->cenyFromHistory();
        $sazby = array('zakladni'=>Eetplus::sazbaToRate(1), 'snizena1'=>Eetplus::sazbaToRate(2), 'snizena2'=>Eetplus::sazbaToRate(3));
        
      
        $rozpis = new RozpisCen($order);
        $aktualniCeny = $rozpis->getRozpis();
        $bilance = $data['bilance']; 
        $rozdilCen = $cenyObjednavky->odectiCeny($aktualniCeny, $bilance);
        $linkeet =  Context::getContext()->link->getAdminLink('AdminOrdersEetplus', true).'&id_order='.$id_order.'&vieworder&id_eetplus=';
        $base_url  = $this->getBaseUrl() . 'modules/' . $this->name;
        Context::getContext()->smarty->assign(
        array('order'=>$order,
            //  'current_index' => parent::$currentIndex,
              'sazby' =>$sazby,
              'linkeet' =>$linkeet,
              'rozdil' =>'',
              'base_url' =>$base_url,
              'eetplus'=>$this,
              'smarty' => Context::getContext()->smarty
        )); 
         if(is_array($data['transakce']) && count($data['transakce'])) {
              Context::getContext()->smarty->assign( array( 'haseet' => ' checked="checked"')); 
         }
        if(Configuration::get('VATNUMBER_MANAGEMENT')) {
           Context::getContext()->smarty->assign( array( 'nepodl' => 1));    
        }
        if(Configuration::get('PS_TAX')) {
           Context::getContext()->smarty->assign( array( 'use_tax' => 1));    
        }

        if(Configuration::get('EETPLUS_CERPANI')) {
           Context::getContext()->smarty->assign( array( 'cerpani' => 1));    
        } 
        if(isset($data['transakce']) && is_array($data['transakce']) && count($data['transakce'])) {
              Context::getContext()->smarty->assign(
           array( 
              'history' => $data['transakce']
           )); 
           if(count($data['transakce']) > 1 && is_array($bilance)) {
              Context::getContext()->smarty->assign(
           array( 
              'bilance' => $bilance
           ));  
           }
        }
        
        $eettab = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/order_detail.tpl');
        return  $this->getOrderEetMessage().$eettab;
    }
    
    private function odpovidajiCeny($evidovanyRozdil, $doplatek,   &$amountConverted, $id_currency) {
        //  $id_currency = Tools::getValue('payment_currency');
          $diff = (int)Configuration::get('EETPLUS_ADDPAYMENTDIF');
          $currency = new Currency($id_currency);
           $currencyData = array('id_currency'=>$currency->id_currency, 'currency_rate'=>$currency->conversion_rate);
          $amountConverted = Eetplus::convertToCzk($doplatek, $currencyData);
          
          if(($evidovanyRozdil - $diff) < $amountConverted && ($evidovanyRozdil + $diff) > $amountConverted) {
               return true;
          }
          return false;
    }
 
    private function displayRequiredText($mode = false) {
        $color= Configuration::get('EETPLUS_TEXTCOL');
        if($color && strlen($color))
          $barva = $color;
        else 
          $barva ='#000000';
        Context::getContext()->smarty->assign(array('barva'=>$barva));
        if($mode == 'clear') {
           Context::getContext()->smarty->assign(array('clear'=>1));  
        }
        elseif($mode == 'title') {
              Context::getContext()->smarty->assign(array('eettitle'=>1));  
        }
        return Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/required_text.tpl');
    }
 
    private function isEetModule($modulename)
    {
        $eetstates = json_decode(Configuration::get('EETPLUS_STATES'), true);
        if(is_array($eetstates)) {
            while (list($key, $val) = each($eetstates)) {
                if ($this->modulenameFromKey($val) == $modulename) {
                    return true;
                }
            }
        }
        return false;
    }
    
    private function isEetAddPaymentModule($modulename) {
          $eetstates = json_decode(Configuration::get('EETPLUS_ADDPAYMENT'), true);
        if(is_array($eetstates)) {
            while (list($key, $val) = each($eetstates)) {
                if ($key == $modulename) {
                    return true;
                }
            }
        }
        return false; 
    }
    
    private function modulenameFromKey($arr)
    {
        $keys = array_keys($arr);
        if (is_array($keys) && isset($keys[0])) {
            return $keys[0];
        }
        return false;
    }
    
    public static function isEetStateModule($modulename, $id_order_state)
    {   
        $anystates = json_decode(Configuration::get('EETPLUS_ANYSTATES'), true);
        if(isset($anystates[$modulename])) {
            return true;  
        }
        
        $allstates = json_decode(Configuration::get('EETPLUS_ALLSTATES'), true);
        if(isset($allstates[$modulename])) {
          $order_state = new OrderState($id_order_state);
          if($order_state->paid) {
             return true;
          }  
        }
        
         $eetstates = json_decode(Configuration::get('EETPLUS_STATES'), true);
        if (isset($eetstates[$id_order_state][$modulename]))
            return true;
        return false;
    }
    
    public function logFailure($id_eetplus, $sandbox, $error)
    {
        $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'eetplus_error SET
    id_eetplus = ' . (int) $id_eetplus . ', sandbox=' . (int) $sandbox . ', error ="' . pSQL($error) . '"';
        Db::getInstance()->execute($sql);
    }
    
    public function getSetting($key)
    {
        if (Configuration::get('EETPLUS_SANDBOX') == 1) {
            switch ($key) {
                case 'EETPLUS_IDPROVOZ':
                    return 11;
                case 'EETPLUS_IDPOKL':
                    return 'IP105';
                case 'EETPLUS_DICPOPL':
                    return 'CZ1212121218';
                default:
                    return;
            }
        } else {
            switch ($key) {
                case 'EETPLUS_IDPROVOZ':
                    return Configuration::get('EETPLUS_IDPROVOZ');
                case 'EETPLUS_IDPOKL':
                    return Configuration::get('EETPLUS_IDPOKL');
                case 'EETPLUS_DICPOPL':
                    return Configuration::get('EETPLUS_DICPOPL');
                default:
                    return;
            }
        }
    }
    
    private function use_ssl()
    {
        if (Configuration::get('PS_SSL_ENABLED'))
            return true;
        if (!empty($_SERVER['HTTPS']) && Tools::strtolower($_SERVER['HTTPS']) != 'off')
            return true;
        return false;
    }
    
  
    
    public function setMessage($message, $success = false) {
        $message = str_replace('Could not resolve host', 'Nepodařilo se připojit k', $message);
        $this->messages[] = array($message, $success);
    }
    
    public function getHash() {
        return substr(_COOKIE_KEY_,3,5);
    }
    
    private function displayMessages() {
         $retval = '';
         if(is_array($this->messages) && count($this->messages)) {
            while(list($key,$val) = each($this->messages)) {
                $color = $val[1] == true?'green':'red';
                $retval .= '<span style="color:'.$color.';font-size:15px">'.$val[0].'</span><br />';
            }  
        }
        return $retval;
    }
    
     public function cronMail($message, $key_subject)
    {
        $vars['{message}'] = $message;
        switch($key_subject) {
            case 'EETPLUS_CRONWARN3H': $subject=$this->l('Účtenky s chybějícím FIK starší než 3 hodiny');  break;
            default: $subject = 'Zpráva cronu EETplus';
        } 
        $template                 = 'eetpluscron';
        $template_path     = _PS_MODULE_DIR_ . $this->name . '/views/mails/';
        $merchant_mails = explode(',', Configuration::get('EETPLUS_CRONEMAIL'));
        $sql ='SELECT id_lang FROM '._DB_PREFIX_.'lang WHERE active = 1 AND iso_code="cs"';
        $id_lang = Db::getInstance()->getValue($sql);
        if(!$id_lang) {
            $sql ='SELECT id_lang FROM '._DB_PREFIX_.'employee WHERE id_profile = 1 AND active = 1';
            $id_lang = Db::getInstance()->getValue($sql);
        }
        $send = 0;
        foreach ($merchant_mails as $merchant_mail) {
            if(strlen(trim($merchant_mail)) && strpos($merchant_mail,'@') > 0) {
                $mailto= trim($merchant_mail);
                if (Mail::send(Context::getContext()->language->id, $template, $subject, $vars, $mailto, null, null, null, null, null, $template_path)) {
                $send++;
             }   
            }
        }
        if($send == 0) {
          Mail::send(Context::getContext()->language->id, $template, $subject, $vars, Configuration::get('PS_SHOP_EMAIL'), null, null, null, null, null, $template_path);  
        }
    }
    
    public static function sazbaToRate($sazba) {
        $czrates = json_decode(Configuration::get('EETPLUS_RATECZK'), true);
         if(is_array($czrates)) {
            while(list($selsazba, $procenta) = each($czrates)) {
                if((float)$procenta > 0 && ($selsazba == $sazba)) {
                   return number_format($procenta, 0, '.','');; 
                }
            }  
         }
        
        
       switch($sazba) {
            case 1: return 21;
            case 2: return 15;
            case 3: return 10;
            default: return 0;
       } 
    }
    /**
    *  tax_map musi byt vyplnena pouze u virtualniho kosiku
    */
    public static function rateToSazba($rate, $tax_map = null) {
        $rate = number_format($rate, 2, '.', '');
        if(isset($tax_map) && Configuration::get('EETPLUS_MOSS')) {
            $selsazby = json_decode(Configuration::get('EETPLUS_SAZBY'), true);
            if(is_array($selsazby) && is_array($selsazby)) {
            while(list($sazba, $sel_tax_rules_group) = each($selsazby)){
                while(list($procenta, $idtax_rules_group) = each($tax_map)) {
                    //$procenta = number_format($procenta, 2, '.','');
                        if($sel_tax_rules_group && ($sel_tax_rules_group == $idtax_rules_group)) {
                            return $sazba; 
                        }
                    }
                }
            } 
        }    
         $czrates = json_decode(Configuration::get('EETPLUS_RATECZK'), true);
         if(is_array($czrates)) {
            while(list($sazba, $procenta) = each($czrates)) {
                $procenta = trim($procenta);
                if(!empty($procenta)) {
                    $procenta = number_format($procenta, 2, '.','');
                    if((float)$procenta > 0 && $procenta == $rate) {
                       return $sazba; 
                    }
                }
            }  
         }
        
         if($rate > 19)
            return 1;
         if($rate >= 14)
            return 2;
          if($rate >= 10)
           return 3;
           
         return 0; 
    }
    
    /**
    * @param mixed $amount
    * @param mixed $currencyData  ...id_currency ve ktere je castka a currency_rate kurz objednavky
    */
    public static function convertToCzk($amount, $currencyData) {
     self::initCurrencyData();
     if($currencyData['id_currency'] == self::$currencyData['iso_czk']) {
        return $amount;   
     }
     if(self::$currencyData['iso_default'] ==   self::$currencyData['iso_czk']) {
         return $amount / $currencyData['currency_rate'];
     }
     else {
         return $amount * self::$currencyData['currency_rate']/$currencyData['currency_rate']; 
     }
      
    }
    
    public static function initCurrencyData() {
         if(self::$currencyData == false) {
      if (   !Shop::isFeatureActive()) {
            $id_shop = Shop::getContextShopID(true);
        }
        else {
            $id_shop =Context::getContext()->shop->id;
        }
      $currencies = Currency::getCurrenciesByIdShop($id_shop);
       
      foreach($currencies as $currency) {
        if($currency['iso_code'] == 'CZK') {
           self::$currencyData['iso_czk'] = $currency['id_currency'];
           self::$currencyData['sign_czk'] =   $currency['sign'];
           self::$currencyData['currency_rate']  =   $currency['conversion_rate'];
           if(Configuration::get('EETPLUS_KURZY') == 1)  {
               $cr = self::ownCurrencyRate($currency);
                 if(!is_null($cr)) {
                     self::$currencyData['currency_rate'] = $cr;
                 }
           }
        }   
      }
      self::$currencyData['iso_default'] = (int)Configuration::get('PS_CURRENCY_DEFAULT');   
     }
    }
 
    public static function ownCurrencyRate($currency) {
                $iso_code = is_object($currency)?$currency->iso_code:$currency['iso_code'];
                $currencyrates = Configuration::get('EETPLUS_CURRENCYRATES');
                if($currencyrates) {
                    $currencyrates = json_decode($currencyrates, true);
                    while(list($key, $val) = each($currencyrates)) {
                      if($key ==$iso_code  && $val > 0) {
                            return $val; 
                      }
                    }
                }
    }
    
    public static function globalRound($ceny) {
             if(isset($ceny['celk_trzba'])) {
                 $ceny['celk_trzba'] = self::finalRound($ceny['celk_trzba']);
             }
              $sazby = array(1,2,3);
              foreach($sazby as $sazba) {
                 if(isset($ceny['dph'][$sazba]) && isset($ceny['dph'][$sazba]['zaklad'])) {
                    $ceny['dph'][$sazba]['zaklad'] =  self::finalRound($ceny['dph'][$sazba]['zaklad']); 
                 }
                  if(isset($ceny['dph'][$sazba]) && isset($ceny['dph'][$sazba]['dan'])) {
                            $ceny['dph'][$sazba]['dan'] =  self::finalRound($ceny['dph'][$sazba]['dan']);     
                  }
                 if(isset($ceny['pouzit_zboz']) && isset($ceny['pouzit_zboz'][$sazba])) {
                       $ceny['pouzit_zboz'][$sazba]['celkem'] =  self::finalRound($ceny['pouzit_zboz'][$sazba]['celkem']);  
                 }
              }
              
              if(Configuration::get('EETPLUS_CERPANI')) {
                if(isset($ceny['eet_extra']) && isset($ceny['eet_extra']['cerp_zuct'])) {
                       $ceny['eet_extra']['cerp_zuct'] =  self::finalRound($ceny['eet_extra']['cerp_zuct']);  
                }
                if(isset($ceny['eet_extra']) && isset($ceny['eet_extra']['urceno_cerp_zuct'])) {
                       $ceny['eet_extra']['urceno_cerp_zuct'] =  self::finalRound($ceny['eet_extra']['urceno_cerp_zuct']);  
                }
                if(isset($ceny['eet_extra']) && isset($ceny['pouzit_zboz']['cest_sluz'])) {
                       $ceny['eet_extra']['cest_sluz'] =  self::finalRound($ceny['eet_extra']['cest_sluz']);  
                }      
            }
             if(Configuration::get('PS_TAX') && Configuration::get('VATNUMBER_MANAGEMENT')) {
                 if(isset($ceny['eet_extra']) && isset($ceny['eet_extra']['zakl_nepodl_dph'])) {
                       $ceny['eet_extra']['zakl_nepodl_dph'] =  self::finalRound($ceny['eet_extra']['zakl_nepodl_dph']);  
                }
             }
            
            
              
              return $ceny;
    }

    public static function finalRound($castka) {
          $castka = bcdiv($castka, 1, 8);   
          
          if((int)$castka == $castka)
            return (int)$castka;
               
            return number_format($castka,2,'.','');
    }
    
    public  function toSmarty($castka, $mena = true) {
        if($mena) {
           self::initCurrencyData();
           $symbol = ' '.self::$currencyData['sign_czk']; 
        }
        else {
           $symbol =''; 
        }
       
         if((int)$castka == $castka) {
            return number_format($castka, 0, ',', " ").$symbol;
         }
         return number_format($castka, 2, ',', " ").$symbol;
    }
    
    public function getOrderEetMessage() {
      if(isset(  Context::getContext()->cookie->eetmessage) && strlen(Context::getContext()->cookie->eetmessage)) {
          $retval =  Context::getContext()->cookie->eetmessage;
          $pos = strpos($retval, 'W:');
          if($pos === 0) {
             $retval = str_replace('W:', '', $retval);
             $retval = '<span style = "color:red">'.$retval.'</span>'; 
          }
          $pos = strpos($retval, 'I:');
          if($pos === 0) {
             $retval = str_replace('I:', '', $retval);
             $retval = '<span style = "color:green">'.$retval.'</span>'; 
          }
           $pos = strpos($retval, 'A:');
            if($pos === 0) {
              $vals = str_replace('A:', '', $retval);
              $vals = trim($vals);
              $vals = json_decode($vals, true);
              
              ?>
               <script language="JavaScript">
                $( document ).ready(function() {
                    fbox(<?php echo $vals['id_order'];?>,<?php echo $vals['id_order_slip'];?>);
                 })
               </script>
              <?php
              $retval ='';  
            }
          
          Context::getContext()->cookie->eetmessage = '';
          unset(Context::getContext()->cookie->eetmessage);
          return $retval; 
      }
       
    }
    
 
   
}
function eetFatalErrorHandler()
{
    if (($error = error_get_last()) && $error['type'] != 'E_NOTICE' && $error['type'] != 'E_USER_NOTICE ') {
        $pos = strpos($error['file'], 'eetplus');
        if ($pos === false)
            return;
        $fp = fopen(_PS_MODULE_DIR_ . 'eetplus/log.txt', 'a+');
        if ($fp) {
            fputs($fp, date('d.m.Y H:i:s') . '  line:| ' . $error['line'] . ' | ' . $error['file'] . ' |: ' . $error['message'] . '|' . "\n");
            fclose($fp);
        }
    }
}
