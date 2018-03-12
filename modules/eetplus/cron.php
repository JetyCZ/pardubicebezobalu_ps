<?php

 
require_once(dirname(__FILE__).'/../../config/config.inc.php'); 
require_once(dirname(__FILE__).'/../../init.php');

   
$instance = Module::getInstanceByName('eetplus');
 
if(Tools::getValue('hash') != $instance->getHash()) {
 exit;
}
 
 $tabulka ='eetplus';
     if (Configuration::get('EETPLUS_SANDBOX') == 1) {
            $tabulka = 'eetplus_sandbox';
 }
   
 
if(Configuration::get('EETPLUS_CRONPKP')) {
   
    $sql ='SELECT id_eetplus, id_order, action, ceny FROM `'._DB_PREFIX_.$tabulka .'` 
 
    WHERE 
  
     (fik is null || fik = "") AND
    DATE_SUB(NOW(),INTERVAL 2 DAY) <= `date_trzby`'; 
    $rows = Db::getInstance()->executeS($sql);
    if(is_array($rows) && count($rows)) {
        require_once(_PS_MODULE_DIR_ . $instance->name . '/classes/ReceiptCreate.php');
        require_once(_PS_MODULE_DIR_ . $instance->name . '/classes/ReceiptOutput.php');
        foreach($rows as $row) {
            $ceny = json_decode($row['ceny'], true);
            $eet           = new ReceiptCreate($instance, false);
            $id_eet        = $eet->registerPayment($row['id_order'], $ceny, $row['action'], $row['id_eetplus']);   
        }
    }

} 
    
 if(Configuration::get('EETPLUS_CRONWARN3H')) {
   
    $sql ='SELECT id_order FROM `'._DB_PREFIX_.$tabulka .'` 
 
    WHERE 
   
     fik is null AND
    DATE_SUB(NOW(),INTERVAL 2 DAY) <= `date_trzby`
    AND  DATE_SUB(NOW(),INTERVAL 3 HOUR) >= `date_trzby`
    '; 
     $rows = Db::getInstance()->executeS($sql);
 if(is_array($rows) && count($rows)) {

        $message  = '<table class="table table-recap">';
        $message.='<th>ID objednávky</th>Reference<th></th>Platba<th></th><th>Datum objednávky</th><th>Částka</th>';
        foreach($rows as $row) {
            $message.='<tr>';
            $order = new Order($row['id_order']);
            $message.='<td>'.$order->id.'</td>';
            $message.='<td>'.$order->reference.'</td>';
            $message.='<td>'.$order->payment.'</td>';
            $message.='<td>'.$order->date_add.'</td>';
            $message.='<td>'.$order->total_paid_tax_incl.'</td>';
            $message.='</tr>';
        }
        $message.='</table>';
        
        $instance->cronMail($message, 'EETPLUS_CRONWARN3H');
    }
 }
 
 if(Configuration::get('EETPLUS_CRONKURZ')) {
    $shop_ids = Shop::getCompleteListOfShopsID();
        foreach ($shop_ids as $shop_id) {
            Shop::setContext(Shop::CONTEXT_SHOP, (int)$shop_id);
            refreshEetCurrencies();
        }
 }
 
 function refreshEetCurrencies()
    {
        // Parse
        if (!$feed = Tools::simplexml_load_file(_PS_CURRENCY_FEED_URL_)) {
            return Tools::displayError('Cannot parse feed.');
        }

        // Default feed currency (EUR)
        $isoCodeSource = strval($feed->source['iso_code']);

        if (!$default_currency = Currency::getDefaultCurrency()) {
            return Tools::displayError('No default currency');
        }

        $currencies = Currency::getCurrencies(true, false, true);
        foreach ($currencies as $currency) {
            /** @var Currency $currency */
            if ($currency->id != $default_currency->id) {
               $rates[$currency->iso_code] = refreshEetCurrency($feed->list, $isoCodeSource, $default_currency, $currency->iso_code);
            }
        }
        Configuration::updateValue("EETPLUS_CURRENCYRATES", json_encode($rates));
    }
    
 function refreshEetCurrency($data, $isoCodeSource, $defaultCurrency, $this_iso_code)
    {
        // fetch the exchange rate of the default currency
        $exchange_rate = 1;
        
        if ($defaultCurrency->iso_code != $isoCodeSource) {
            foreach ($data->currency as $currency) {
                if ($currency['iso_code'] == $defaultCurrency->iso_code) {
                    $exchange_rate = round((float)$currency['rate'], 6);
                    break;
                }
            }
        }

       if ($defaultCurrency->iso_code == $this_iso_code) {
            $this->conversion_rate = 1;
        } else {
            if ($this_iso_code == $isoCodeSource) {
                $rate = 1;
            } else {
                foreach ($data->currency as $obj) {
                    if ($this_iso_code == strval($obj['iso_code'])) {
                        $rate = (float)$obj['rate'];
                        break;
                    }
                }
            }

            if (isset($rate)) {
                $conversion_rate = round($rate / $exchange_rate, 6);
            }
        }

        return $conversion_rate;
    }
    
    
 
 
 
