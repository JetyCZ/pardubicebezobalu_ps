<?php
require_once 'PriceInfo.php';
class CustomUtils {

    const vaziZhrubaLabel = "váží zhruba";
    const czechDateFormat = 'd.m.Y H:i';

    public static function calculateNextSupplyDate($dbRow)
    {
        $cronstr = $dbRow['cronstr'];
        $orderDate = $dbRow['order_date'];


        $nextOrder = "";
        if (strlen($cronstr) > 0) {
            $cron = Cron\CronExpression::factory($cronstr);
            $nextOrder = $cron->getNextRunDate()->format(CustomUtils::czechDateFormat);
        } else if (isset($orderDate)) {
            $nextOrder = date(CustomUtils::czechDateFormat, strtotime($orderDate));
        }
        return $nextOrder;
    }

    public static function priceInfo($productName, $price) {
        $vaziZhrubaPos = strpos($productName, CustomUtils::vaziZhrubaLabel);
        $isWeighted = false;
        $result = new PriceInfo();
        $result->isWeightedKs = false;

        if (strpos($productName, 'stáčený produkt') != false) {
            $unitX = "ml ";

            $help = "(1000 = 1 litr)";
            $zaLabelUnit = "za litr";
            $zaLabelPrice = $price*1000;
        } elseif (strpos($productName, 'na váhu') != false) {
            $isWeighted = true;
            $unitX = "g ";
            $help = "(1000 = 1 kg)";

            $pricePer100g = $price * 100;
            if ($pricePer100g<10) {
                $zaLabelPrice = $price*1000;
                $zaLabelUnit = "za Kg";
            } else {
                $zaLabelPrice = $pricePer100g;
                $zaLabelUnit = "za 100 gramů";
            }

        } else if ($vaziZhrubaPos != false) {
            $unitX = "ks ";
            $help = "(kusové zboží)";
            $zaLabelUnit = "za kus";
            $zaLabelPrice = $price;



            $start = $vaziZhrubaPos + strlen(CustomUtils::vaziZhrubaLabel) + 1;
            $length = strlen($productName) - strlen(CustomUtils::vaziZhrubaLabel) - $vaziZhrubaPos - 1;
            $gramPerKs = substr($productName, $start, strlen($productName) - $start - 2);
            $result->gramPerKs = $gramPerKs;
            $result->isWeightedKs = true;

        } else {
            $unitX = "ks ";
            $help = "(kusové zboží)";
            $zaLabelUnit = "za&nbsp;kus";
            $zaLabelPrice = round($price,2);

        }

        $result->unitX = $unitX;
        $result->help = $help;
        $result->isWeighted = $isWeighted;
        $result->zaLabelUnit = $zaLabelUnit;
        $result->zaLabelPrice = $zaLabelPrice;
        $result->price = $price;

        return $result;
    }

    public static function isAdmin($context)
    {
        if (!isset($context->customer)) {
            return false;
        }
        if (!isset($context->customer->email)) {
            return false;
        }
        return ($context->customer->email == 'hhrom@email.cz' || $context->customer->email == 'pavel.jetensky@seznam.cz');
    }

    public static function ordersWithProductLink($idProduct)
    {
        return '<a href="/admin313uriemy/index.php?controller=AdminOrders&idProduct='.$idProduct.'">OBJ</a>';
    }

    public static function orderLink($idOrder, $linkBody)
    {
        return '<a href="/admin313uriemy/index.php?controller=AdminOrders&id_order='.$idOrder.'&&vieworder">'.
        $linkBody.
    '</a>';
    }

}