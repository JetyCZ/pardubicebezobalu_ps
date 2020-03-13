<?php
require_once _PS_ROOT_DIR_ . '/classes/custom/CustomUtils.php';

class CustomInventory {

    public $html = "";
    public $sumInventory = 0;
    public $sumStock = 0;
    private $idInventory;
    /**
     * @var array
     */
    private $quantities;
    /**
     * @var array
     */
    private $dmts;

    /**
     * CustomInventory constructor.
     */
    public function __construct()
    {
        $this->enabled = isset($_GET["id_inventory"]);
        if ($this->enabled) {
            $this->idInventory = $_GET["id_inventory"];

            $this->html = "<div style='background-color:#00A4E7;position: fixed;top: 40px; left: 140px;'>
<b>DMT pro inventuru (formát 3/22):</b><input type='text' id='inventoryDmt'/>
</div>";
            $this->html .= "<table style='background-color:#FEFEFE;' border='1'>";

            $this->html .= "<tr>";
            $this->html .= "<th>ID product</th>";
            $this->html .= "<th>Product name</th>";
            $this->html .= "<th>Unit Price</th>";
            $this->html .= "<th>Stock quantity</th>";
            $this->html .= "<th>Inventory quantity</th>";
            $this->html .= "<th>Stock Price</th>";
            $this->html .= "<th>Inventory Price</th>";
            $this->html .= "<th>DMT</th>";
            $this->html .= "</tr>";

            $this->sumInventory = 0;
            $this->sumStock = 0;

            /*
            ps_jety_inventory set
            date_updated=now(),
            quantity=?,
            id_product=?,
            id_inventory=
            */

            $sql = "select * from ps_jety_inventory where id_inventory=".$this->idInventory;
            $result = Db::getInstance()->executeS($sql);
            $this->quantities = array();
            $this->dmts = array();
            foreach ($result as $row) {
                $id_product = $row['id_product'];
                $quantity = $row['quantity'];
                $dmt = $row['dmt'];
                $this->quantities[$id_product] = $quantity;
                $this->dmts[$id_product] = $dmt;
            }

        }

    }

    public function invRow($quantity, $price, $idProduct, $productName)
    {
        if ($this->enabled) {
            $inventoryQuantity = $this->quantities[$idProduct];


            $valueProductStock = $quantity > 0 ? ($quantity * $price) : 0;
            $this->sumStock += $valueProductStock;

            $valueProductInventory = $inventoryQuantity > 0 ? ($inventoryQuantity * $price) : 0;
            $this->sumInventory += $valueProductInventory;

            if ($inventoryQuantity==null) {
                $this->html .= "<tr style='background-color:#FFA0A0'>";
            } else {
                $this->html .= "<tr>";
            }
            $this->html .= "<td>" . $idProduct . "</td>";
            $this->html .= "<td>" . $productName . "</td>";
            $this->html .= "<td>" . $price . "</td>";
            $this->html .= "<td>" . $quantity . "</td>";
            $this->html .= "<td>" . $inventoryQuantity . "</td>";
            $this->html .= "<td>" . ($valueProductStock) . "</td>";
            $this->html .= "<td>" . ($valueProductInventory) . "</td>";
            $this->html .= "<td>" . ($this->dmts[$idProduct]) . "</td>";
            $this->html .= "</tr>";
        }
    }

    public function outputHtml()
    {
        if ($this->enabled) {
            return "<hr><h1>Stock value:" . $this->sumStock . "</h1>" .
             "<hr><h1>Inventory value:" . $this->sumInventory . "</h1>" . $this->html . "</table>";
        }
    }
}