<?php

try {

    if (!defined('_PS_ADMIN_DIR_')) {
        define('_PS_ADMIN_DIR_', getcwd());
    }
    if (!defined('_PS_ROOT_DIR_')) {
        define('_PS_ROOT_DIR_',getcwd()."/..");
    }


    require_once _PS_ROOT_DIR_ . '/classes/custom/CustomUtils.php';
    require_once _PS_ROOT_DIR_ . '/classes/jety/Cron/CronExpression.php';


    echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">';


    CustomUtils::connect_to_database();

    $sql = <<<'EOD'
select 
  p.id_product, cr.*,c.id_customer as cid,
  s.id_supplier, s.name as sname, 
  pl.name as pname,pl.link_rewrite, 
  o.id_order,
  od.product_quantity as mnozstvi_objednavane, 
  p.quantity as mnozstvi_nasklade, o.date_add, c.firstname, c.lastname, osl.name from ps_order_detail od
  join ps_orders o on o.id_order = od.id_order
  join ps_order_state_lang osl on osl.id_order_state = o.current_state
  join ps_customer c on c.id_customer = o.id_customer
  join ps_product p on p.id_product = od.product_id
  join ps_product_lang pl on p.id_product = pl.id_product
  join ps_product_supplier ps on p.id_product = ps.id_product
  join ps_supplier s on ps.id_supplier = s.id_supplier
  left join ps_jety_supplier_cron cr on s.id_supplier = cr.id_supplier
where
  osl.id_lang = 2
  and (osl.name='Probíhá příprava' or osl.name like '%Dodavatele%' or osl.name like 'Stálá objednávka%')
  and pl.id_lang = 2
  and lastname not like '%stnanec%' 
  and lastname not like '% Soukromé%' 
  and lastname not like '%- dobrovolník%' 
order by p.id_supplier, p.id_product
EOD;

    $emails = "";

    $result = $conn->query($sql);
    $lastIdSupplier = -1;
    $lastIdProduct = -1;
    $storeQuantities = "<table border=1 cellpadding=2 cellspacing=0>";
    $productQuantityOrder = 0;

    $x = Cron\CronExpression::factory("0 15 * * *")->getNextRunDate('now', 1);

    $supplierEmail = "blabla";
    while($row = $result->fetch_assoc()) {

        $idSupplier = $row['id_supplier'];
        $idProduct = $row['id_product'];
        $idOrder = $row['id_order'];

        $name = $row['sname'];
        $pname = $row['pname'];
        $mnozstviObjednavane = $row['mnozstvi_objednavane'];



        try {
            $nextD = CustomUtils::calculateDeliveryInfo($row)->deliveryDateStr();
        } catch (Exception $e) {
            $nextD = "ERROR" . $row['cronstr'];
        }

        if ($idSupplier!=$lastIdSupplier) {

            if ($lastIdSupplier!=-1) {
                $storeQuantities.= "<tr><td colspan='3'>EMAIL pro ".$supplierEmail."</td>";
            }


            $supplierEmail = "";
            $storeQuantities .= "<tr style='background-color: #50FFFF'>\n".
                "<td>".$name." ID: ".$idSupplier."</td>\n".
                "<td>Příští doručení: ".$nextD."</td>\n".
                "<td colspan='3'>Příští doručení: ".$row['cronstr']."</td>\n".
                "</tr>\n\n";
        }

        if ($idProduct!=$lastIdProduct) {
            $storeQuantities = replaceProductRow($lastIdProduct, $productQuantityOrder, $storeQuantities);
            $storeQuantities .= "<tr style='background-color: #C0FFFF'>\n".
                // https://pardubicebezobalu.cz/admin313uriemy/index.php/product/form/97?_token=6adX_6N5uHX1gPxK7cpllNSUhcD9GWDJ1umavpLA2s8#tab-step1
                "<td><a href='/admin313uriemy/index.php/product/form/".$idProduct."'>".$row['pname']."</a></td>\n".
                "<td>Objednat celkem: productQuantityOrder".$idProduct."</td>\n".
                "<td colspan='3'>Množství v e-shopu: ".$row["mnozstvi_nasklade"]."</td>\n".
                "</tr>";
            $productQuantityOrder = $mnozstviObjednavane;
        } else {
            $productQuantityOrder += $mnozstviObjednavane;
        }

        $orderDateStr = $row["date_add"];
        //  2018-09-13 07:10:29
        $orderDate = DateTime::createFromFormat("Y-m-d H:i:s", $orderDateStr);
        $orderAge = date_diff($orderDate, new DateTime())->format('%a');
        $storeQuantities .= "<tr>\n".
            "<td></td>\n".
            "<td>".$row["mnozstvi_objednavane"]."</td>\n".
            "<td>".CustomUtils::orderLink($idOrder, $orderAge." dní (".$idOrder.")")."</td>\n".
            "<td title='".$row["cid"]."'>".$row["firstname"]."</td>\n".
            "<td>".$row["lastname"]."</td>\n".
            "<td>".$row["name"]."</td>\n".
            "</tr>\n\n";



        $lastIdSupplier = $idSupplier;
        $lastIdProduct = $idProduct;

    }
    $storeQuantities = replaceProductRow($lastIdProduct, $productQuantityOrder, $storeQuantities);
    $storeQuantities.="</table>";
    echo $storeQuantities;
} catch (Throwable $e) {
    var_dump($e);
}

echo '</html>';

function replaceProductRow($lastIdProduct, $productQuantityOrder, $storeQuantities): string
{
    return str_replace("productQuantityOrder" . $lastIdProduct, $productQuantityOrder, $storeQuantities);
}
