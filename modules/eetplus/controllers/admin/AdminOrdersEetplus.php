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
 
class AdminOrdersEetplusController extends AdminOrdersControllerCore
{
    protected $tabulka = 'eetplus';
    protected $instance;  
    
    public function __construct()
    {
    
        $this->instance = Module::getInstanceByName('eetplus');
        require_once(_PS_MODULE_DIR_.$this->instance->name.'/eetplus.php');
        if (Configuration::get('EETPLUS_SANDBOX') == 1) {
            $this->tabulka = 'eetplus_sandbox';
        }
        
        $this->show_toolbar = false;
        parent::__construct();
        
         
            if ($this->context->cookie->{'orderseetplusorderOrderby'}) {
                $this->_orderBy = $this->context->cookie->{'orderseetplusorderOrderby'};
            }else   {
                $this->_orderBy = 'id_eetplus';
            }  
         
        $this->_join .= ' LEFT JOIN ' . _DB_PREFIX_ . $this->tabulka . ' eetplus ON (eetplus.id_order = a.id_order)';
        $this->_where .= 'AND eetplus.id_order IS NOT NULL';
        $this->_select .= ' ,DATE_FORMAT(eetplus.date_trzby, "%Y-%m-%d %H:%i:%s") as date_trzby, eetplus.action, eetplus.castka, eetplus.id_eetplus, eetplus.fik,   eetplus.action, eetplus.codes';
        $this->tpl_folder                         = 'eetplus/';
        $this->fields_list                        = array();
        $this->fields_list['id_eetplus']          = array(
            'title' => $this->l('ID EET
    '),
            'align' => 'center',
            'width' => 25
        );
        $this->fields_list['id_order']            = array(
            'title' => $this->l('ID obj.'),
            'align' => 'center',
            'width' => 25
        );
        $this->fields_list['reference']           = array(
            'title' => $this->l('Ref.'),
            'align' => 'center',
            'width' => 65
        );
       /* $this->fields_list['customer']            = array(
            'title' => $this->l('Customer'),
            'havingFilter' => true
        );
        */
       $this->fields_list['action']            = array(
            'title' => $this->l('Typ účtenky'),
            'havingFilter' => true
        ); 
        $this->fields_list['total_paid_tax_incl'] = array(
            'title' => $this->l('Celkem'),
            'width' => 70,
            'align' => 'right',
            'prefix' => '<b>',
            'suffix' => '</b>',
            'type' => 'price',
            'currency' => true,
            'callback' => 'setOrderCurrency'
        );
        $this->fields_list['castka']              = array(
            'title' => $this->l('Odeslaná tržba'),
            'width' => 70,
            'align' => 'right',
            'prefix' => '<b>',
            'suffix' => '</b>',
            'type' => 'text',
            'currency' => true
        );
        $this->fields_list['payment']             = array(
            'title' => $this->l('Platba'),
            'width' => 70
        );
        if(!isset($this->statuses_array)) {
            $statuses_array = array();
            $statuses = OrderState::getOrderStates((int)$this->context->language->id);
                foreach ($statuses as $status) {
                    $statuses_array[$status['id_order_state']] = $status['name'];
                }
            $this->statuses_array = $statuses_array;
        }
        $this->fields_list['osname']              = array(
            'title' => $this->l('Stav objednávky'),
            'color' => 'color',
            'width' => 100,
            'type' => 'select',
            'list' => $this->statuses_array,
            'filter_key' => 'os!id_order_state',
            'filter_type' => 'int',
            'order_key' => 'osname'
        );
        $this->fields_list['fik']                 = array(
            'title' => $this->l('Stav účtenky'),
            'width' => 120
        );
        $this->fields_list['date_trzby']            = array(
            'title' => $this->l('Datum tržby'),
            'width' => 100,
            'align' => 'right',
            'type' => 'datetime'
        );
       
        return;
    }
    
    public function initPageHeaderToolbar() {
       parent::initPageHeaderToolbar();
       $this->page_header_toolbar_btn = array();
    }
    
     protected function addToolBarModulesListButton() {
          $this->toolbar_btn = array();
       
          return;
     }
     
    public function postProcess()
    {
     
        
        if ((Tools::isSubmit('submitEmail')) && Tools::getValue('id_eetplus')) {
            require_once(_PS_MODULE_DIR_ . $this->instance->name . '/lib/filipsedivy/Receipt.php');
            require_once(_PS_MODULE_DIR_ . $this->instance->name . '/classes/ReceiptCreate.php');
            require_once(_PS_MODULE_DIR_ . $this->instance->name . '/classes/ReceiptOutput.php');
            $receiptOutput         = new ReceiptOutput((int) Tools::getValue('id_eetplus'), $this->instance);
            $this->confirmations[] = $receiptOutput->sendMail(true, Tools::getValue('emailto'));
        }
        if ((Tools::isSubmit('submitPdf')) && Tools::getValue('id_eetplus')) {
    
             require_once(_PS_MODULE_DIR_ . $this->instance->name . '/lib/filipsedivy/Receipt.php');
            require_once(_PS_MODULE_DIR_ . $this->instance->name . '/classes/ReceiptCreate.php');
            require_once(_PS_MODULE_DIR_ . $this->instance->name . '/classes/ReceiptOutput.php'); 
        
            $receiptOutput         = new ReceiptOutput((int) Tools::getValue('id_eetplus'), $this->instance);
           

          $pdf = new PDF($receiptOutput, 'Eet', Context::getContext()->smarty);
        
          $pdf->render();

          exit;
          
        }
        
        return parent::postProcess();
    }
    
    public function setHelperDisplay(Helper $helper)
    {
        $obj            = new stdClass();
        $obj->name      = 'eetplus';
        $helper->module = $obj;
       
        parent::setHelperDisplay($helper);
        if((int) Configuration::get('EETPLUS_SANDBOX'))
            $helper->title = "EET ZKUŠEBNÍ PROVOZ";
        else
            $helper->title = "EET OSTRÝ PROVOZ";
    }
    
    public function renderView()
    {
        $smarty     = Context::getContext()->smarty;
        require_once(_PS_MODULE_DIR_ . 'eetplus/eetplus.php');
        $tpl        = _PS_MODULE_DIR_ . 'eetplus/views/templates/admin/detail.tpl';
        $id_eetplus = Tools::getValue('id_eetplus');
        $id_order   = Tools::getValue('id_order');
        require_once(_PS_MODULE_DIR_ . $this->instance->name . '/lib/filipsedivy/Receipt.php');
        require_once(_PS_MODULE_DIR_ . 'eetplus/classes/ReceiptData.php');
        $receiptData        = new ReceiptData($id_eetplus, (int) Configuration::get('EETPLUS_SANDBOX'), false);
        $receipt            = $receiptData->getReceipt();
        $order              = new Order((int) $id_order);
        $sandbox            = (int) Configuration::get('EETPLUS_SANDBOX');
        
        $email              = $receiptData->getParsedEmail();
        $data['datum']      = $receiptData->getValue('dat_trzby', true);
        $data['mailto']     = $email['mailto'];
        $data['maildate']   = $email['maildate'];
        $data['castka']     = $receiptData->getValue('celk_trzba');
        $ceny = $receiptData->copyPricesFromReceipt();
        Context::getContext()->smarty->assign(array('data'=>$ceny,'eetplus'=>$this->instance));
        $tax_tab = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->instance->name . '/views/templates/tax-tab.tpl');
        $data['ceny'] = $tax_tab;
        
         
    $kurz = $order->conversion_rate;        
    $currencyData = ReceiptData::getKurzFromExistingId($id_eetplus, (int) Configuration::get('EETPLUS_SANDBOX')); 
    if($currencyData && is_array($currencyData) && isset($currencyData['currency_rate'])) {
         if($currencyData['currency_rate'] > 0 ) {
            $kurz = $currencyData['currency_rate']; 
         }
    }
          
       

        $currency           = new Currency($order->id_currency);
        $c_decimals         = (int) $currency->decimals * _PS_PRICE_DISPLAY_PRECISION_;
        $data['total_paid'] = Tools::ps_round($order->total_paid, $c_decimals) . ' ' . $currency->sign;
        $data['currency']   = $currency->sign;
        $rozdil             = ($order->total_paid / $order->conversion_rate) - $data['castka'];
        $linkorder =  Context::getContext()->link->getAdminLink('AdminOrders', true).'&amp;id_order='.$id_order.'&vieworder';
   
        $smarty->assign(array(
            'id_eetplus' => $id_eetplus,
            'linkorder' => $linkorder,
            'order' => $order,
            'kurz' => $kurz,
            'eet' => $receiptData,
            'data' => $data,
            'rozdil' => number_format($rozdil, 2, '.', ''),
            'sandbox' => $sandbox
        ));
       
        $output = $smarty->fetch($tpl);
        return $output;
    }
    
      public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = null)
    {
        require_once(_PS_MODULE_DIR_.'eetplus/classes/ReceiptData.php');
         
        AdminOrdersControllerCore::getList($id_lang, $orderBy, $orderWay, $start, $limit, $id_lang_shop);
        foreach ($this->_list as &$item) {
                if (strlen($item['fik']) == ReceiptData::FIK_LEN) {
                     $item['fikcolor'] ='00CC66';
                     $item['fikmessage'] =  $this->l('V pořádku');
                } else {
                 $item['fik']="";
                 $date = new DateTime($item['date_trzby']);
                 $diff = $date->diff(new DateTime("now"));
                 $hours = $diff->h + ($diff->days*24);  
                 if($hours > 48) {
                      $item['fikcolor'] ='CC0000';
                      $item['fikmessage'] =  $this->l('CHYBA !!');
                 } 
                 elseif($hours > 3) {
                       $item['fikcolor'] ='ED6218';
                       $item['fikmessage'] =  $this->l('Zpoždění !!');
                 }
                 else {
                       $item['fikcolor'] ='B84000';
                       $item['fikmessage'] =  $this->l('Zpoždění');
                 }   
                }
                $item['castka'].=' Kč';
        }
    }
}