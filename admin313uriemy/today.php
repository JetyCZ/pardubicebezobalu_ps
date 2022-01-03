<?php
if (!defined('_PS_ROOT_DIR_')) {
    define('_PS_ROOT_DIR_',getcwd()."/..");
}
require_once
    _PS_ROOT_DIR_ . '/classes/custom/CustomUtils.php';

try {

    if (!defined('_PS_ADMIN_DIR_')) {
        define('_PS_ADMIN_DIR_', getcwd());
    }
    if (!defined('_PS_ROOT_DIR_')) {
        define('_PS_ROOT_DIR_',getcwd()."/..");
    }
    require_once _PS_ROOT_DIR_ . '/classes/custom/CustomUtils.php';
    require_once _PS_ROOT_DIR_ . '/classes/jety/Cron/CronExpression.php';
    CustomUtils::connect_to_database();

    $sql = <<<'EOD'
select sum(op.amount) AS trzba_sum
from ps_orders o       
        join ps_order_payment op on o.reference = op.order_reference
        join ps_order_state_lang osl on (osl.id_order_state = o.current_state and osl.id_lang=2) 
where (              
       (osl.name = 'OSL_NAME') and
       DATE_ADD_FILTER and     
       1 = 1
       )
EOD;

    if (isset($_REQUEST['d'])) {
        $sql = str_replace("OSL_NAME", 'Dodáno', $sql);
        $dateFilter = "date('".$_REQUEST['d']."')";
        $sql = str_replace("DATE_ADD_FILTER", 'date(o.date_add) = '.$dateFilter, $sql);
    } else {
        $sql = str_replace("OSL_NAME", 'Zaplaceno snížením dluhu Pavlovi', $sql);
        $yearFilter = $_REQUEST['y'];
        $sql = str_replace("DATE_ADD_FILTER", 'year(o.date_add) = '.$yearFilter, $sql);
    }


    $sql = str_replace("now()", $dateFilter, $sql);
//    order by sum(`od`.`total_price_tax_incl`) desc, `p`.`id_product`
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $trzba_sum = $row['trzba_sum'];
        if ($trzba_sum!=null) {
            echo str_replace(".",",", $trzba_sum);
        } else {
            echo 0;
        }
    }
    if (isset($_REQUEST['sql'])) {
        echo "<br>".$sql;
    }
} catch (Throwable $e) {
    var_dump($e);
} ?>