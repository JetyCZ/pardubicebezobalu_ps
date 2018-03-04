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
            $pricePerUnitLabel = $this->zaLabelPrice . ",- Kč&nbsp;<span style='color: #A0A0A0;'>" . $this->zaLabelUnit . "</span>";
        } else {
            $pricePerUnitLabel .= ($this->price * 1000) . ",- Kč&nbsp;<span style='color: #A0A0A0;'>za Kg</span>";
        }
        return $pricePerUnitLabel;
    }
}