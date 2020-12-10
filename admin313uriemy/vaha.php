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
            . "vaha=" . $_GET['vaha']
            . ",scale_date=" . $_GET['scale_date']
            . ",date=now()"
            . " where id=0 AND scale_date<" . $_GET['scale_date'];
        if ($conn->query($sql) === TRUE) {
            echo "OK";
        } else {
            echo $conn->error;
        }
    } else {
        $sql = "select *,TIMESTAMPDIFF(SECOND, date, now()) as dursec from ps_jety_vaha";
        $result = $conn->query($sql);
        $vaha = -1;
        while ($row = $result->fetch_assoc()) {
            $vaha = $row['vaha'];
            if ($row['dursec'] > 10) {
                echo -1;
            } else {
                echo $vaha;
            }
            break;
        }

    }

} catch (Throwable $e) {
    var_dump($e);
}

