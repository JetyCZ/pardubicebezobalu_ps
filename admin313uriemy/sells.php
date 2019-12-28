<?php
if (!defined('_PS_ROOT_DIR_')) {
    define('_PS_ROOT_DIR_',getcwd()."/..");
}
require_once _PS_ROOT_DIR_ . '/classes/custom/CustomUtils.php';

try {

    if (!defined('_PS_ADMIN_DIR_')) {
        define('_PS_ADMIN_DIR_', getcwd());
    }
    if (!defined('_PS_ROOT_DIR_')) {
        define('_PS_ROOT_DIR_',getcwd()."/..");
    }
    require_once _PS_ROOT_DIR_ . '/classes/custom/CustomUtils.php';
    require_once _PS_ROOT_DIR_ . '/classes/jety/Cron/CronExpression.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<meta charset="utf-8">
</head>
<body>
<?php
    CustomUtils::connect_to_database();

    $sql = <<<'EOD'
select p.active,
       p.id_product                                                          AS id_product,
       s.name as sname,
       s.id_supplier as id_supplier,
       sum(od.total_price_tax_incl)                                          AS trzba_sum,
       count(od.id_order)                                                    AS order_count,
       (select pl.name AS pname
        from ps_product_lang pl
        where ((p.id_product = pl.id_product) and (pl.id_lang = 2))) AS pname,
       sum(od.product_quantity)                                              AS mnozstvi_sum,
       (sum(od.product_quantity) /
        12)                               AS mnozstviMesic,
       (sum(od.product_quantity) /12*6)                               AS mnozstviPulrok,
       pss.quantity                                                          AS quantity,
        (pss.quantity / (sum(od.product_quantity) /12))                                 AS zasobNaMesicu
from ps_order_detail od 
        join ps_orders o on (o.id_order = od.id_order) 
        join ps_order_state_lang osl on (osl.id_order_state = o.current_state) 
        left join ps_customer c on (c.id_customer = o.id_customer)
        join ps_product p on (p.id_product = od.product_id) 
        join ps_product_lang pl on (p.id_product = pl.id_product) 
        left join ps_stock_available pss on (pss.id_product = p.id_product)
        left join ps_product_supplier ps on (p.id_product = ps.id_product)
        left join ps_supplier s on (ps.id_supplier = s.id_supplier)        
where (
       ((osl.name = 'Dodáno') or (osl.name = 'Zaplaceno snížením dluhu Pavlovi') or (osl.name like '%Dodavatele%')) and
       (not s.name='Farma rodiny Němcovy') and
       (not s.name='Mléčná farma Jehnědí') and
       (not s.name='Pekařství Vlastimil Holub') and
       (pl.id_lang = 2) and
       (not ((c.lastname like '%stnanec%'))) and
       (not ((c.lastname like '%Soukromé%'))) and
       (not ((c.lastname like '%-dobrovolník%'))) and
       -- idSupplierFilter
       -- activeFilter
       -- bioFilter
       o.date_add > DATE_ADD(now(), INTERVAL -12 MONTH)
       ) 
       -- and pss.quantity>=0
group by p.id_product
-- having mnozstvi_sum>1000 and mnozstviPulrok>=2500
order by trzba_sum desc, id_product

EOD;
    if (isset($_REQUEST['idSupplier'])) {
        $sql = str_replace("-- idSupplierFilter", "s.id_supplier=".$_GET['idSupplier']." and"."\n", $sql);
    }
    if (isset($_REQUEST['bio'])) {
        $bioF = $_REQUEST['bio'];
        if ($bioF!="all") {
            if ($bioF=='true') {
                $sql = str_replace("-- bioFilter", "pname like '%BIO%' and"."\n", $sql);
            } else if ($bioF=='false') {
                $sql = str_replace("-- bioFilter", "pname not like '%BIO%' and"."\n", $sql);
            }
        }
    } else {
        $bioF = "all";
    }
    if (isset($_REQUEST['active'])) {
        $activeF = $_REQUEST['active'];
        if ($activeF!="all") {
            $sql = str_replace("-- activeFilter", "p.active=".$activeF." and"."\n", $sql);
        }
    } else {
        $activeF = "all";
    }
    if (isset($_REQUEST['m'])) {
        $m = $_REQUEST['m'];
        if ($m=='') $m=12;
        $sql = str_replace("12", $m, $sql);
    } else {
        $m=12;
    }
//    order by sum(`od`.`total_price_tax_incl`) desc, `p`.`id_product`

    echo("<textarea>".$sql."</textarea>");
    $emails = "";

    $result = $conn->query($sql);
    $lastIdSupplier = -1;
    $lastIdProduct = -1;
    ?>

    <div id="form_container">

        <form id="form_93127" class="appnitro" method="get" action="sells.php">
            <div class="form_description">
                <h2>Co objednat (posledních <?= $m ?> měsíců)</h2>
                <p>Tato stránka zobrazuje červeně podbarvené zboží, které nám brzy dojde a je potřeba jej doobjednat</p>
            </div>
            <ul>

                <li id="li_2">
                    <label class="description" for="element_2">Dodavatelé </label>
                    <div>
                        <select class="element select medium" id="element_2" name="element_2">
                            <option value="" selected="selected"></option>
                            <option value="1">First option</option>
                            <option value="2">Second option</option>
                            <option value="3">Third option</option>

                        </select>
                    </div>
                </li>		<li id="li_3">
                    <label class="description" for="element_3">BIO </label>
                    <span>
			<input name="bio" class="element radio" type="radio" value="true" <?= $bioF=="true"?"checked":"" ?>>
<label class="choice" for="element_3_1">Pouze BIO</label>
<input name="bio"  class="element radio" type="radio" value="false" <?= $bioF=="false"?"checked":"" ?>>
<label class="choice" for="element_3_2">Pouze ne-BIO</label>
<input name="bio"  class="element radio" type="radio" value="all" <?= $bioF=="all"?"checked":"" ?>>
<label class="choice" for="element_3_3">Vše</label>

		</span>
                </li>		<li id="li_4">
                    <label class="description" for="element_4">Aktivní </label>
                    <span>
			<input name="active" class="element radio" type="radio" value="true" <?= $activeF=="true"?"checked":"" ?>>
<label class="choice" for="element_4_1">Pouze aktivní</label>
<input name="active" class="element radio" type="radio" value="false" <?= $activeF=="false"?"checked":"" ?>>
<label class="choice" for="element_4_2">Pouze ne-aktivní</label>
<input name="active"  class="element radio" type="radio" value="all" <?= $activeF=="all"?"checked":"" ?>>
<label class="choice" for="element_4_3">Vše</label>

		</span>
                </li>		<li id="li_1">
                    <label class="description" for="element_1">Objednávky jen za posledních X měsíců </label>
                    <div>
                        <input id="m" name="m" class="element text medium" type="text" maxlength="255" value="<?= $m ?>">
                    </div>
                </li>

                <li class="buttons">
                    <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit">
                </li>
            </ul>
        </form>
    </div>

<?php
    $storeQuantities = "<table border=0 cellpadding=2 cellspacing=0>";
    $productQuantityOrder = 0;

    $x = Cron\CronExpression::factory("0 15 * * *")->getNextRunDate('now', 1);

    $supplierEmail = "blabla";
    $storeQuantities .= "<tr style='background-color: #50AFAF'>\n".
        "<th>Dodavatel <a href='sells.php'>Zruš filter</a> </th>\n".
        "<th></th>\n".
        "<th>ID Produktu</th>\n".
        "<th>Produkt</th>\n".
        "<th>Akce</th>\n".
        "<th>Tržba celkem Kč</th>\n".
        "<th>Počet objednávek</th>\n".
        "<th>Prodáno celkem Kg/l</th>\n".
        "<th>Prodáme měsíčně Kg/l</th>\n".
        "<th>Prodáme půlročně Kg/l</th>\n".
        "<th>Zásobu na měsíců</th>\n".
        "<th>Na skladě Kg/l</th>\n".
        "</tr>\n\n";

    while($row = $result->fetch_assoc()) {

        $zasobNaMesicu = $row['zasobNaMesicu'];
        $quantity = $row['quantity'];
        $active = $row['active'];

        $color = "50FFFF";
        if ($quantity<=0) {
            $color = "FF0000";
        } else if ($zasobNaMesicu<1) {
            $color = "FF5555";
        } else if ($zasobNaMesicu<2) {
            $color = "FFCCCC";
        } else if ($zasobNaMesicu<3) {
            $color = "FFEEEE";
        }
        $idProduct = $row['id_product'];
        $productCss = ($active) ? "":" style='color:gray'";
        $storeQuantities .= "<tr style='background-color: #".$color."'>\n".
                "<td>".$row['sname']."</td>\n".
                "<td><a href='sells.php?idSupplier=".$row['id_supplier']."'>Filtr</a></td>\n".
                "<td>".$idProduct."</td>\n".
                "<td".$productCss.">".$row['pname']."</td>\n".
                "<td".$productCss.">".
                    CustomUtils::productLink($idProduct). "&nbsp;|&nbsp;".
                    CustomUtils::supplyLink($row['pname']).
                "</td>\n".
                "<td>".round($row['trzba_sum'],0)."</td>\n".
                "<td>".round($row['order_count'],0)."</td>\n".
                "<td>".round($row['mnozstvi_sum']/1000,1)."</td>\n".
                "<td>".round($row['mnozstviMesic']/1000,1)."</td>\n".
                "<td style='font-size:120%;color:navy;'>".round($row['mnozstviPulrok']/1000,1)."</td>\n".
                "<td><b>" . round($zasobNaMesicu, 1) . "</b></td>\n".
                "<td>".round($quantity /1000,1)."</td>\n".
                "</tr>\n\n";
    }
    $storeQuantities.="</table>";
    echo $storeQuantities;
} catch (Throwable $e) {
    var_dump($e);
}

echo '</body></html>';

function replaceProductRow($lastIdProduct, $productQuantityOrder, $storeQuantities): string
{
    return str_replace("productQuantityOrder" . $lastIdProduct, $productQuantityOrder, $storeQuantities);
}
?>