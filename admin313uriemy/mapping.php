<?php
// header('Content-Type: text/javascript');

try {
    if (!defined('_PS_ADMIN_DIR_')) {
        define('_PS_ADMIN_DIR_', getcwd());
    }
    if (!defined('_PS_ROOT_DIR_')) {
        define('_PS_ROOT_DIR_', getcwd() . "/..");
    }
    require_once _PS_ROOT_DIR_ . '/classes/custom/CustomUtils.php';
    CustomUtils::connect_to_database();

    if (isset($_GET['qrcode'])) {
        $sql = "";
        if (isset($_GET['delete'])) {
            $sql = "delete from ps_jety_map where qrcode='" . $_GET['qrcode'] . "'";
        } else {
            $sql = "insert into ps_jety_map (qrcode, id_product) values ('" . $_GET['qrcode'] . "', " . $_GET['idproduct'] . ")";
        }
        $conn->query($sql);
    }

    $js = <<<'EOD'
var map = {
EOD;
    $sql = <<<'EOD'
    select * from ps_jety_map
EOD;

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {

        $idProduct = $row['id_product'];
        $qrCode = $row['qrcode'];
        $js .= "'" . $qrCode . "' : " . "'" . $idProduct . "',";
    }
    $js .= "\n};";
    echo($js);


} catch (Throwable $e) {
    var_dump($e);
}
?>
