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
        $isWeightedKs = false;
        $isWeighted = false;

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

            $isWeightedKs = true;
        } else {
            $unitX = "ks ";
            $help = "(kusové zboží)";
            $zaLabelUnit = "za&nbsp;kus";
            $zaLabelPrice = round($price,2);

        }

        $result = new PriceInfo();
        $result->unitX = $unitX;
        $result->help = $help;
        $result->isWeightedKs = $isWeightedKs;
        $result->isWeighted = $isWeighted;
        $result->zaLabelUnit = $zaLabelUnit;
        $result->zaLabelPrice = $zaLabelPrice;
        $result->price = $price;

        $result->gramPerKs = substr($productName, $vaziZhrubaPos + strlen(CustomUtils::vaziZhrubaLabel), strlen($productName) - strlen(CustomUtils::vaziZhrubaLabel) - $vaziZhrubaPos - 1);

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
}