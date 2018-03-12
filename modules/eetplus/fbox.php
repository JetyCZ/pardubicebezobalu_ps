<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
//require_once(dirname(__FILE__).'/../../config/config.inc.php'); 
//require_once(dirname(__FILE__).'/../../init.php');
$id_order = (int)Tools::getValue('id_order');
$id_order_slip = (int)Tools::getValue('id_order_slip');


  $modulename =  'eetplus';
  require_once( _PS_MODULE_DIR_.$modulename.'/'.$modulename.'.php');
   $order_slip = new OrderSlip($id_order_slip);
   $origOrder = new Order($order_slip->id_order);
 
 $currency  = new Currency($origOrder->id_currency); 
require_once(_PS_MODULE_DIR_ .   'eetplus/classes/Rozpis.php');
require_once(_PS_MODULE_DIR_ . 'eetplus/classes/ReceiptData.php');
require_once(_PS_MODULE_DIR_ . 'eetplus/classes/CenyObjednavky.php'); 

if(Tools::issubmit('cmd_rozpis')) {
      require_once(_PS_MODULE_DIR_ .   'eetplus/classes/RozpisSlipDiscounted.php');
      require_once(_PS_MODULE_DIR_ . 'eetplus/classes/ReceiptCreate.php');
      require_once(_PS_MODULE_DIR_ . 'eetplus/classes/ReceiptOutput.php');
      $origOrder->products = OrderSlip::getOrdersSlipProducts($order_slip->id, $origOrder); 
      $rozpis = new RozpisSlipDiscounted($origOrder);
      $instance = Module::getInstanceByName($modulename);  
      $instance->actionOrderSlipAdd($rozpis, $order_slip, $id_order);
}
else {
         require_once(_PS_MODULE_DIR_ . 'eetplus/classes/RozpisCen.php');
         $origRozpis = new RozpisCen($origOrder);
         $discountShare = $origRozpis->getDiscountShare();
          
         $sql = 'SELECT id_order_detail, product_quantity FROM '._DB_PREFIX_.'order_slip_detail  
         WHERE id_order_slip ='.(int)$id_order_slip; 
         
         $product_list = Db::getInstance()->executeS($sql); 
            
         $amount = 0;
         $produkty_celkem = 0;    
             while(list($key, $val) = each($product_list)) {
                $quantity = $val['product_quantity'];
                $id_order_detail = $val['id_order_detail'];
                if(isset($discountShare[$id_order_detail])) {
                    if(isset($discountShare[$id_order_detail]['tax_excl'])) {
                        $amount +=  $discountShare[$id_order_detail]['tax_excl'] * $quantity;
                        $produkty_celkem += $discountShare[$id_order_detail]['tax_incl'] * $quantity;
                    }
                    else {
                        $amount +=  $discountShare[$id_order_detail] * $quantity;
                        $produkty_celkem +=  $discountShare[$id_order_detail] * $quantity;
                    }
                }
            }
            if(isset($order_slip->total_shipping_tax_incl))
              $postovne  =    ($order_slip->shipping_cost?$order_slip->total_shipping_tax_incl:0);
            else
              $postovne =     ($order_slip->shipping_cost?$order_slip->shipping_cost_amount:0);
              
         if(isset($order_slip->total_products_tax_incl)) {
            $refund  =   $amount;    
         }
        else {
            $refund =   $produkty_celkem + $postovne;;
         }
    
}


// pokud resit id_shop tak takto
//$sql = 'SELECT id_shop FROM  '._DB_PREFIX_.'orders WHERE id_order ='.(int)$id_order;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache" />
</head>
<body>
<form method ='post'>
 <input type='hidden' name='id_order' value='<?php echo $id_order;?>' />
  <input type='hidden' name='id_order_slip' value='<?php echo $id_order_slip;?>' />
  <input type='hidden' name ='prepocet_eet' value ='1' />
 <?php if(Tools::issubmit('cmd_rozpis')) { 
   echo $instance->getOrderEetMessage();
   echo '<br />';   
 ?>
  Změna se v EET bilanci v detailu objednávky objeví jakmile stránku znovu otevřete.
 <?php } else { 
 
  
  $postovne = 0;
  if($order_slip->shipping_cost_amount)
  $postovne = $order_slip->shipping_cost_amount;
  
   $produkty =  0;
  if(isset($order_slip->total_products_tax_incl) && $order_slip->total_products_tax_incl) 
    $produkty =  $order_slip->total_products_tax_incl;
  else
    $produkty = $order_slip->amount - $postovne; 
    
  if(empty($produkty)) 
    $produkty = 0;
  
 ?>
 
 <h4>Odeslat EET k dobropisu</h4>
 <table style = "width: 550px;border:1px solid #d0d0d0;">
  <tr><th colspan="2" style="width:48%;text-align:left;border-bottom:1px solid #e0e0e0;">Dobropis</th><th style="width:50px;border-left:1px solid #d0d0d0;border-right:1px solid #d0d0d0;" rowspan="4">&nbsp;</th><th colspan="2" style="width:48%;text-align:left;border-bottom:1px solid #e0e0e0;">Objednávka</th></tr>
  <tr>
  <td>Číslo dobropisu</td><td style="text-align: right"><?php echo $order_slip->id;?></td>
  <td>Číslo objednávky</td><td style="text-align: right"><?php echo $origOrder->id;?></td>
  </tr>
  <tr>
  <td>Produky</td>  <td style="white-space:nowrap;text-align: right"><?php echo Tools::displayPrice($produkty, $currency);?></td>
  <td>Produky</td>  <td style="white-space:nowrap;text-align: right"><?php echo Tools::displayPrice($origOrder->total_products_wt, $currency);?></td>
  </tr>
  <tr>
  <td>Dopravné</td> <td style="text-align: right"><?php echo Tools::displayPrice($postovne, $currency);?></td> 
  <td>Dopravné</td> <td style="text-align: right"><?php echo Tools::displayPrice($origOrder->total_shipping_tax_incl, $currency);?></td>
  </tr>
 </table>
 <br />
<h4 style="margin-bottom:5px;">Do  EET bude vráceno</h4> 
Produkty: <?php echo Tools::ps_round($produkty_celkem,2);?>  Kč (zohledňuje podíl kupónu)<br />
Poštovné:  <?php echo Tools::ps_round($postovne,2);?>  Kč  <br />
Celkem:&nbsp;&nbsp;  <?php echo Tools::ps_round($produkty_celkem + $postovne,2);?> Kč
<input type="hidden" name='refund_prepoctene' value='<?php echo Tools::ps_round($refund,5);?>' />
<br />

<br />
<input type='submit' name='cmd_rozpis' value='Odeslat na EET' />
 <? } ?>
 <br /><br />
</form>
</body>
</html>