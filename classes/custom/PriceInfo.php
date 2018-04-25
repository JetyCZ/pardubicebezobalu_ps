<?php
/**
 * Created by PhpStorm.
 * User: jety
 * Date: 1.3.18
 * Time: 21:16
 */

class PriceInfo
{
    public $unitX;
    public $help;
    public $isWeightedKs;
    public $isWeighted;
    public $zaLabelUnit;
    public $zaLabelPrice;
    public $price;
    public $gramPerKs;

    public function pricePerUnitLabel() {
        $pricePerUnitLabel = "";
        if (!$this->isWeightedKs) {
            $pricePerUnitLabel = "\n".$this->zaLabelPrice . ",- Kč&nbsp;".
                "\n"."<span style='color: #909090;'>" . $this->zaLabelUnit . "</span>";
        } else {

                $ksPrice = $this->gramPerKs * $this->price;
                $ksPriceRounded = round($ksPrice, 1);
                $pricePerUnitLabel .= "\n" . $ksPriceRounded . ",- Kč " .
                    "\n<span style='color: #909090;'>za ks</span>" .
                    "\n<br><span style='color: #909090;'>" . ($this->price * 1000) . ",- Kč&nbsp;za Kg</span>";


        }
        return $pricePerUnitLabel;
    }

    public function quantityToAmountAndUnit($quantity, $mult) {
        if ($this->isWeightedKs) {
            $result = round($mult*($quantity/$this->gramPerKs),1) . " " . $this->unitX . " (".$mult*$quantity." g)";
        } else {
            $result = $mult*$quantity . " " . $this->unitX;
        }
        return $result;
    }
}