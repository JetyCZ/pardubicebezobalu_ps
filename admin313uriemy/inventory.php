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
        define('_PS_ROOT_DIR_', getcwd() . "/..");
    }
    require_once _PS_ROOT_DIR_ . '/classes/custom/CustomUtils.php';
    CustomUtils::connect_to_database();
    $reset = (isset($_GET['reset']) && ($_GET['reset'] == 'true'));
    if (isset($_GET['id_product']) && isset($_GET['id_inventory']) && isset($_GET['quantity'])) {
        $dmtSet = "dmt=STR_TO_DATE('".$_GET['dmt']."','%d/%m/%Y'),";
        $sqlUpdate = "update ps_jety_inventory set quantity=";
        if (!$reset) {
            $sqlUpdate .= "quantity+";
        }
        $sqlUpdate .= "?," . $dmtSet . "date_updated=now() where id_product=? and id_inventory=?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $idProduct = $_GET['id_product'];
        $idInventory = $_GET['id_inventory'];
        $quantity = $_GET['quantity'];
        $stmtUpdate->bind_param('sii', $quantity, $idProduct, $idInventory);
        $stmtUpdate->execute();

        if($stmtUpdate->affected_rows == 0) {

            $sql = "insert into ps_jety_inventory set 
                                  date_updated=now(),
                                  quantity=?,
                                  ".$dmtSet."
                                  id_product=?,
                                  id_inventory=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sii', $quantity, $idProduct, $idInventory);
            $stmt->execute();
        }
    }

    $sql = "select * from ps_jety_inventory where id_product=".$idProduct." and id_inventory=".$idInventory;
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        echo $row['quantity'];
    }
    } catch (Throwable $e) {
    var_dump($e);
}
?>