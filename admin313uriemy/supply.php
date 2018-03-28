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



    connect_to_database();

    $sql = <<<'EOD'
select
s.id_supplier as sid, s.name as sname,psl.name as pname, p.*, c.*
from ps_supplier s 
left join ps_product p on p.id_supplier = s.id_supplier
left join ps_jety_supplier_cron c on s.id_supplier = c.id_supplier
left join ps_product_lang psl on psl.id_product = p.id_product
where (psl.name <> '') and (psl.id_lang=2) 
order by s.id_supplier
EOD;


    $result = $conn->query($sql);
    $lastIdSupplier = -1;
    $storeQuantities = "";
    while($row = $result->fetch_assoc()) {
        $idSupplier = $row['sid'];
        $name = $row['sname'];
        $pname = $row['pname'];
        $quantity = $row['quantity'];

        if ($idSupplier!=$lastIdSupplier) {
            echo $storeQuantities;
            $storeQuantities = "<br>";

            echo "<hr>";
            echo "<h1>".$name."</h1>";
            echo "<h3>ID: ".$idSupplier."</h3>";
            echo "Příští objednávka: ".CustomUtils::calculateNextSupplyDate($row)."<br>";
        }
        if ($quantity<0) {
            echo "Objednávám " . (-$quantity) ." ". $pname ."<br>";
        } else if ($quantity>0){
            $storeQuantities .= "Na skladě máme: " . ($quantity) ." ". $pname ."<br>";
        }
        $lastIdSupplier = $idSupplier;

    }
    echo $storeQuantities;
} catch (Throwable $e) {
    var_dump($e);
}

function connect_to_database()
{ global $triplepath, $conn, $headermsgs;
    if(file_exists("../images.inc.php"))
        $triplepath = "../";
    else if(file_exists("../../images.inc.php"))
        $triplepath = "../../";
    else if(file_exists("../../../images.inc.php")) /* this is a file in the root */
        $triplepath = "../../../";
    else
        die( "<p><b>Your files should be in a subdirectory of the admin directory of your shop!</b>");

    if(is_dir($triplepath."app/config/")) /* if version 1.7 */
    { $data = file_get_contents($triplepath."app/config/parameters.php");
        $pos = strpos($data, "array");
        $data = substr($data, $pos);
        $data = "\$config=".$data;
        eval($data);

        if (gethostname()=="jety-17") {
            define('_DB_SERVER_', "127.0.0.1");
        }else {
            define('_DB_SERVER_', $config['parameters']['database_host']);
        }
        define('_DB_NAME_', $config['parameters']['database_name']);
        define('_DB_USER_', $config['parameters']['database_user']);
        define('_DB_PASSWD_', $config['parameters']['database_password']);
        define('_DB_PREFIX_',  $config['parameters']['database_prefix']);
        define('_COOKIE_KEY_',  $config['parameters']['cookie_key']);

        /* now get version */
        $data = file($triplepath."config/autoload.php");
        if(!$data) die("Error getting version number.");
        $version = "";
        foreach($data AS $line)
        { if(substr($line,0,22) == "define('_PS_VERSION_',")
        { $subline = substr($line,22);
            $version = preg_replace("/[\';\)\r\n ]*/", "", $subline);
            define('_PS_VERSION_',$version);
        }
        }
        if($version == "") die("Error analysing version number");
    }
    else /* version 1.5/1.6 */
    { if(!@include $triplepath."config/settings.inc.php")
        die("Error loading 1.5/1.6 config file!");
        if (_PS_VERSION_ < "1.5.0")
            die("This version of Prestools Suite is for Prestashop 1.5, 1.6 and 1.7!<p>There is a separate 1.4 version available.");
        if (_PS_VERSION_ >= "1.7.3")
            die("Prestashop 1.7.3 is not yet supported by Prestools.");
    }

    /* with mysqli_connect you cannot keep the socket in the first argument - 1and1 issue */
    if(substr(_DB_SERVER_,(strlen(_DB_SERVER_)-5)) == ".sock")
    { $parts = explode(":",_DB_SERVER_);
        $conn = mysqli_connect($parts[0], _DB_USER_, _DB_PASSWD_, _DB_NAME_, null, $parts[1]) or die ("Error connecting to database server with socket ".$parts[1]);
    }
    else
    { $parts = explode(":",_DB_SERVER_);
        if((sizeof($parts)>1) && is_numeric($parts[1]))  /* port number specified? */
            $conn = mysqli_connect($parts[0], _DB_USER_, _DB_PASSWD_, _DB_NAME_,$parts[1]) or die ("Error Connecting to database server ".$parts[0]." with port ".$parts[1]);
        else
            $conn = mysqli_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_) or die ("Error connecting to Database server");
    }

    $headermsgs = ""; /* will be printed together with menu */
    // mysqli_select_db($conn, _DB_NAME_) or die ("Error selecting database");
    //$res1 = mysqli_query($conn, "SET NAMES 'utf8'");
    $res = mysqli_set_charset($conn, "utf8");
    if(!$res) $headermsgs .= "Error setting charset...";
    /* the following line should prevent MySQL 5.7.5 (and higher) 'ONLY_FULL_GROUP_BY' errors */
    // See http://johnemb.blogspot.nl/2014/09/adding-or-removing-individual-sql-modes.html
    $res4 = mysqli_query($conn, "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    if(!$res4) $headermsgs .= "Error setting session mode";
    /* the following line should prevent MySQL 5.7.5 (and higher) 'STRICT_TRANS_TABLES' errors */
    // dbquery("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'STRICT_TRANS_TABLES',''))");
    $res3 = mysqli_query($conn, "SELECT CHARACTER_SET_NAME,COLLATION_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='"._DB_NAME_."' AND TABLE_NAME='"._DB_PREFIX_."product_lang' AND COLUMN_NAME='description'");
    if(!$res3) die("Error reviewing database charset.");
    $row = mysqli_fetch_array($res3);
    if(($row["CHARACTER_SET_NAME"]!="utf8") && (!defined('_TB_VERSION_')))
        $headermsgs .= "Char set is '".$row["CHARACTER_SET_NAME"]."' instead of 'utf8'.";
    if(($row["CHARACTER_SET_NAME"]!="utf8mb4") && (defined('_TB_VERSION_')))
        $headermsgs .= "Char set is '".$row["CHARACTER_SET_NAME"]."' instead of 'utf8mb4'.";
    if(($row["COLLATION_NAME"]!="utf8_general_ci") && (!defined('_TB_VERSION_')))
        $headermsgs .= "Collation is '".$row["COLLATION_NAME"]."' instead of 'utf8_general_ci'.";
    if(($row["COLLATION_NAME"]!="utf8mb4_unicode_ci") && (defined('_TB_VERSION_')))
        $headermsgs .= "Collation is '".$row["COLLATION_NAME"]."' instead of 'utf8mb4_unicode_ci'.";
}

echo '</html>';