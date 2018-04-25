<?php
/**
 * Created by PhpStorm.
 * User: jety
 * Date: 1.3.18
 * Time: 21:16
 */

class DeliveryInfo
{
    public $deliveryDate;
    public $orderDate;

    public function deliveryDateStr()
    {
        if ($this->deliveryDate == null) {
            return null;
        }
        return CustomUtils::czechDate($this->deliveryDate);
    }

    public function orderDateStr()
    {
        if ($this->orderDate== null) {
            return null;
        }
        return CustomUtils::czechDate($this->orderDate);
    }

}