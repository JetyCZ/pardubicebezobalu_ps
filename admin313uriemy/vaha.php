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
    if (isset($_GET['vaha'])) {
        $sql = "update ps_jety_vaha set "
        ."vaha=" . $_GET['vaha']
        .",scale_date=" . $_GET['scale_date']
        ." where id=0 AND scale_date<". $_GET['scale_date'];
        if ($conn->query($sql)===TRUE) {
            echo "OK";
        } else {
            echo $conn->error;
        }
    } else {
            $sql = "select * from ps_jety_vaha";
            $result = $conn->query($sql);
            $vaha = -1;
            while ($row = $result->fetch_assoc()) {
                $vaha = $row['vaha'];
            }
            echo $vaha;
    }

} catch (Throwable $e) {
    var_dump($e);
}

