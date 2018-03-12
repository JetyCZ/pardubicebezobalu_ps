<?php
class OrderSlip  extends OrderSlipCore
{
    public static function create(Order $order, $product_list, $shipping_cost = false, $amount = 0, $amount_choosen = false, $add_tax = true)
    {
        /**
        Zahrnout částku původního slevového kupónu:
        amount = null
        amount_choosen = false
        Nezahrnovat částku původního slevového kupónu 
        amount: zbozi ponizene o efekt kuponu cely aplikovany jen na tento produkt
        amount_choosen: null 
        Částka dle Vašeho výběru
        amount: zadaná částka, i nula
        amount_choosen: true 
        */                
        if($amount_choosen == true) {
            require_once(_PS_MODULE_DIR_ . 'eetplus/classes/RozpisCen.php');
            $rozpis = new RozpisCen($order);
            $amount = 0;
            $discountShare = $rozpis->getDiscountShare();    // je na 1 ks
                while(list($id_order_detail, $val) = each($product_list)) {
                if(isset($discountShare[$id_order_detail])) {
                    if(isset($discountShare[$id_order_detail]['tax_excl'])) {
                        $amount +=  $discountShare[$id_order_detail]['tax_excl'] * $val['quantity'];
                    }
                    else {
                        $amount +=  $discountShare[$id_order_detail] * $val['quantity'];
                    }
                }
            }
        }
       
         
        return parent::create($order,  $product_list, $shipping_cost, $amount, $amount_choosen, $add_tax);
    }
}
