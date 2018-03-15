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
 
class ReceiptOutput
{
    private $receiptData;
    private $instance;
    
    public function __construct($id_eetplus, $instance)
    {
        $this->receiptData = new ReceiptData($id_eetplus, (int) Configuration::get('EETPLUS_SANDBOX'), false);
        $this->instance    = $instance;
        require_once(_PS_MODULE_DIR_.$this->instance->name.'/eetplus.php');
    }
    
    public function getPdfVars() {
        $receipt = $this->receiptData->getReceipt();
        if (!(int) $receipt->porad_cis)
            return;
       
        $vars                     = array();
        $vars['poradove_cislo'] = (int) $receipt->porad_cis;
         Context::getContext()->smarty->assign(array('eetplus'=>$this->instance));
        
      
        if (isset($this->receiptData->fik) && (strlen($this->receiptData->fik) == ReceiptData::FIK_LEN)) {
            $vars['fik']     = $this->receiptData->fik;
            $vars['typkodu'] = 'FIK';
        } elseif (isset($this->receiptData->pkp) && (strlen($this->receiptData->pkp) == ReceiptData::PKP_LEN)) {
            $vars['pkp']     = $this->receiptData->pkp;
            $vars['typkodu'] = 'PKP';
        }
        $vars['bkp']        = $this->receiptData->bkp;
       
        $date_trzby           = $receipt->dat_trzby;
        $date_akc             = new DateTime($this->receiptData->date_akc);
        if ($date_akc < $date_trzby) {
            $vars['datum'] = $date_akc->format('d.m.Y H:i:s');
        } else {
            $vars['datum'] = $date_trzby->format('d.m.Y H:i:s');
        }
        $order = new Order($this->receiptData->id_order);
        if (Configuration::get('EET_USEREF'))
            $kod_objednavky = $order->reference;
        else
            $kod_objednavky = $order->id;
            
         $vars['celk_trzba'] = Eetplus::finalRound($receipt->celk_trzba);    
        $currencyData = array('id_currency'=>$order->id_currency, 'currency_rate'=> $order->conversion_rate);

        $vars['kod_objednavky'] = $kod_objednavky;
        $customer                 = new Customer($order->id_customer);
        $vars['id_provoz']      = $receipt->id_provoz;
        $vars['id_pokl']        = $receipt->id_pokl;
        $vars['dic_popl']       = $receipt->dic_popl;
        $vars['ic_popl']        = Configuration::get('EETPLUS_IC');
                  
        if (Configuration::get('EETPLUS_UCTADDR')) {
           $vars['adresa'] = array(); 
           $vars['adresa']['adresa'] =    Configuration::get('PS_SHOP_ADDR1'); 
           $vars['adresa']['psc'] =    Configuration::get('PS_SHOP_CODE'); 
           $vars['adresa']['city'] =    Configuration::get('PS_SHOP_CITY');  
           $adresa = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->instance->name . '/views/templates/mail_address.tpl');
        }
       
     
        
        if (Configuration::get('EETPLUS_UCTROZPR')) {
            $products = OrderDetail::getList($order->id);
            
            foreach ($products as &$product) {
                $product['unit_price_tax_incl']  = Eetplus::finalRound(Eetplus::convertToCzk($product['unit_price_tax_incl'], $currencyData));
                $product['total_price_tax_incl'] = Eetplus::finalRound(Eetplus::convertToCzk($product['total_price_tax_incl'], $currencyData));
            }
            $vars['produkty'] = $products; 
        }
       
       
        if (Configuration::get('EETPLUS_UCTSUMM')) {
            $keys = array(
                'total_discounts_tax_excl',
                'total_discounts_tax_incl',
                'total_shipping_tax_excl',
                'total_shipping_tax_incl',
                'total_products',
                'total_products_wt',
                'total_paid_tax_excl',
                'total_paid_tax_incl'
            );
            foreach ($keys as $key) {
                $val          = $order->{$key};
                $vars[$key] = Eetplus::globalRound(Eetplus::convertToCzk($val, $currencyData));
            }
                    }
         
        $vars['tax_tab'] = $this->receiptData->copyPricesFromReceipt();
     
        if(Configuration::get('EETPLUS_UCTCISINV')) {
           $vars['cisinv'] = $this->getInvoiceNumber($order); 
         
        }
     
        return $vars;
       
    }
    
    public function sendMail($force = false, $mailto = false, $id_order_state = false)
    {
        $receipt = $this->receiptData->getReceipt();
        if (!(int) $receipt->porad_cis)
            return;
        if ($this->receiptData->email && strlen($this->receiptData->email) && $force == false)
            return;
            
        if(!($this->receiptData->action == 'trzba') && Configuration::get('EETPLUS_MAILRESTR') && $force == false) {
           return; 
        }
        $vars                     = array();
        $vars['{poradove_cislo}'] = (int) $receipt->porad_cis;
        $template                 = 'eetplus';
        
        Context::getContext()->smarty->assign(array('eetplus'=>$this->instance));
        
        if (isset($this->receiptData->fik) && (strlen($this->receiptData->fik) == ReceiptData::FIK_LEN)) {
            $vars['{fik}']     = $this->receiptData->fik;
            $vars['{typkodu}'] = 'FIK';
        } elseif (isset($this->receiptData->pkp) && (strlen($this->receiptData->pkp) == ReceiptData::PKP_LEN)) {
            $vars['{pkp}']     = $this->receiptData->pkp;
            $vars['{typkodu}'] = 'PKP';
        }
        $vars['{bkp}']        = $this->receiptData->bkp;
       
        $date_trzby           = $receipt->dat_trzby;
        $date_akc             = new DateTime($this->receiptData->date_akc);
        if ($date_akc < $date_trzby) {
            $vars['{datum}'] = $date_akc->format('d.m.Y H:i:s');
        } else {
            $vars['{datum}'] = $date_trzby->format('d.m.Y H:i:s');
        }
        $order = new Order($this->receiptData->id_order);
        if (Configuration::get('EET_USEREF'))
            $kod_objednavky = $order->reference;
        else
            $kod_objednavky = $order->id;
            
        $vars['{celk_trzba}'] = $this->instance->toSmarty(Eetplus::finalRound($receipt->celk_trzba));    
        $currencyData = array('id_currency'=>$order->id_currency, 'currency_rate'=> $order->conversion_rate);

        $vars['{kod_objednavky}'] = $kod_objednavky;
        
        $vars['{cisinv}'] = '';
     
        if(Configuration::get('EETPLUS_UCTCISINV')) {
            $inv_number = $this->getInvoiceNumber($order, $id_order_state);
           if($inv_number) {
              Context::getContext()->smarty->assign('cisinv', $inv_number);
              $vars['{cisinv}'] = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->instance->name . '/views/templates/mail_cislofaktury.tpl'); 
           }
        }
        
        $customer                 = new Customer($order->id_customer);
        $vars['{id_provoz}']      = $receipt->id_provoz;
        $vars['{id_pokl}']        = $receipt->id_pokl;
        $vars['{dic_popl}']       = $receipt->dic_popl;
        $vars['{ic_popl}']        = Configuration::get('EETPLUS_IC');
        $adresa                   = '';
        if (Configuration::get('EETPLUS_UCTADDR')) {
            Context::getContext()->smarty->assign('adresa', Configuration::get('PS_SHOP_ADDR1'));
            Context::getContext()->smarty->assign('psc', Configuration::get('PS_SHOP_CODE'));
            Context::getContext()->smarty->assign('city', Configuration::get('PS_SHOP_CITY'));
            $adresa = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->instance->name . '/views/templates/mail_address.tpl');
        }
        $vars['{adresa}'] = $adresa;
        $produkty         = '';
        
        if (Configuration::get('EETPLUS_UCTROZPR')) {
            $products = OrderDetail::getList($order->id);
            
            foreach ($products as &$product) {
                $product['unit_price_tax_incl']  = Eetplus::finalRound(Eetplus::convertToCzk($product['unit_price_tax_incl'], $currencyData));
                $product['total_price_tax_incl'] = Eetplus::finalRound(Eetplus::convertToCzk($product['total_price_tax_incl'], $currencyData));
            }
            Context::getContext()->smarty->assign('products', $products);
            $produkty = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->instance->name . '/views/templates/mail_products.tpl');
        }
        $vars['{produkty}'] = $produkty;
        $souhrn             = [];
        $summary ='';
        if (Configuration::get('EETPLUS_UCTSUMM')) {
            $keys = array(
                'total_discounts_tax_excl',
                'total_discounts_tax_incl',
                'total_shipping_tax_excl',
                'total_shipping_tax_incl',
                'total_products',
                'total_products_wt',
                'total_paid_tax_excl',
                'total_paid_tax_incl'
            );
            foreach ($keys as $key) {
                $val          = $order->{$key};
                try {
                    $valInCzk = Eetplus::convertToCzk($val, $currencyData);
                    $rounded = Eetplus::globalRound($valInCzk);
                    $souhrn[$key] = $rounded;
                } catch (Throwable $e) {
                    var_dump($e);
                    throw $e;


                }
            }
            Context::getContext()->smarty->assign('summary', $souhrn);
            $summary = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->instance->name . '/views/templates/mail_summary.tpl');
        }
        $vars['{summary}'] = $summary;

        $data = $this->receiptData->copyPricesFromReceipt();
        Context::getContext()->smarty->assign(array('data'=>$data));
     
        $tax_tab = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . $this->instance->name . '/views/templates/tax-tab.tpl');
        $vars['{tax_tab}'] = $tax_tab;
       
        $template_path     = _PS_MODULE_DIR_ . $this->instance->name . '/views/mails/';
        $subject           = $this->instance->l("Elektronická účtenka k objednávce ") . $kod_objednavky;
        if ($mailto == false) {
            $mailto = $customer->email;
        }
        if (Mail::send(Context::getContext()->language->id, $template, $subject, $vars, $mailto, null, null, null, null, null, $template_path) && $force == false) {
            $this->receiptData->setUpdateVar('email', $customer->email . '  ' . date('d.m.Y H:i:s'), false);
            $this->receiptData->update();
            return $this->instance->l("Zpráva byla odeslána");
        }
        return '';
    }
    
    private function getInvoiceNumber($order, $id_order_state) {
           if(isset($order->invoice_number) && (int)$order->invoice_number ) {
              $vars['cisinv']  = $order->invoice_number; 
           }
           if(!$id_order_state) {
               $id_order_state = $order->current_state;
           }
           $state = new OrderState($id_order_state);
           if((int)$state->invoice) {
              $sql ='SELECT MAX(invoice_number) FROM '._DB_PREFIX_.'orders WHERE 1';
              $id_invoice = Db::getInstance()->getValue($sql);
              return ((int)$id_invoice + 1); 
           }
    }
}

