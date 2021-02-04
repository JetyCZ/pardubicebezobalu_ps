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
if (isset($_REQUEST['y'])) {
    $yearFilter = $_REQUEST['y'];
} else {
    $yearFilter = "year(now())";
}
    $sql = <<<'EOD'
select sum(op.amount) AS trzba_sum
from ps_orders o       
        join ps_order_payment op on o.reference = op.order_reference
        join ps_order_state_lang osl on (osl.id_order_state = o.current_state and osl.id_lang=2) 
where (              
       (osl.name = 'Zaplaceno snížením dluhu Pavlovi') and
       year(o.date_add) = year() and     
       1 = 1
       )
EOD;
    $sql = str_replace("year()", $yearFilter, $sql);
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
} catch (Throwable $e) {
    var_dump($e);
} ?>