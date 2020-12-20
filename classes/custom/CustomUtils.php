<?php
require_once 'PriceInfo.php';
require_once 'DeliveryInfo.php';
class CustomUtils {

    const vaziZhrubaLabel = "váží zhruba";
    const czechDateFormat = 'D d.m.Y H:i';
    const czechDateFormatNoYear = 'D d.m. H:i';

    public static function calculateDeliveryInfo($dbRow)
    {

        $result = new DeliveryInfo();

        $cronstr = $dbRow['cronstr'];
        $deliveryDateExact = $dbRow['delivery_date'];
        $orderDeliveryDiffHours = $dbRow["order_delivery_diff_hours"];


        $nextOrder = "";
        $timestamp = (new DateTime())->getTimestamp();
        if (strlen($cronstr) > 0) {
            $cron = Cron\CronExpression::factory($cronstr);
            // $nextOrder = $cron->getNextRunDate()->format(CustomUtils::czechDateFormat);
            $result->deliveryDate = $cron->getNextRunDate()->getTimestamp();
            $result->orderDate = $result->deliveryDate - 60 * 60 * $orderDeliveryDiffHours;
            if ($result->orderDate < $timestamp) {
                $freshFound = false;
                $nth = 1;
                while (!$freshFound) {
                    $result->deliveryDate = $cron->getNextRunDate('now', $nth)->getTimestamp();
                    $result->orderDate = $result->deliveryDate - 60 * 60 * $orderDeliveryDiffHours;
                    $freshFound = $result->orderDate > $timestamp;
                    $nth++;
                }
            }
            $dayOfWeek = date('N', $result->deliveryDate);
            if ($dayOfWeek == 6) {
                $result->deliveryDate += 60 * 60 * 2 * 24;
            } else if ($dayOfWeek == 7) {
                $result->deliveryDate += 60 * 60 * 1 * 24;
            }
        } else if (isset($deliveryDateExact)) {
            // $nextOrder = date(CustomUtils::czechDateFormat, strtotime($orderDate));
            $result->deliveryDate = strtotime($deliveryDateExact);
            $result->orderDate = $result->deliveryDate - 60 * 60 * $orderDeliveryDiffHours;
            if ($result->orderDate<$timestamp) {
                $result->deliveryDate = null;
                $result->orderDate = null;
            }
        }

        return $result;
    }

    public static function priceInfo($productName, $price) {
        $vaziZhrubaPos = strpos($productName, CustomUtils::vaziZhrubaLabel);
        $isWeighted = false;
        $result = new PriceInfo();
        $result->isWeightedKs = false;
        $result->isPoured = false;

        if (CustomUtils::isPouredProduct($productName)) {
            $unitX = "ml ";

            $help = "(1000 = 1 litr)";
            $zaLabelUnit = "za litr";
            $zaLabelPrice = $price*1000;

            $result->isPoured = true;
        } elseif (strpos($productName, 'na váhu') != false) {
            $isWeighted = true;
            $unitX = "g ";
            $help = "(1000 = 1 kg)";

            $pricePer100g = $price * 100;
            if ($pricePer100g<10) {
                $zaLabelPrice = $price*1000;
                $zaLabelUnit = "za Kg";
            } else {
                $zaLabelPrice = $pricePer100g;
                $zaLabelUnit = "za 100 gramů";
            }

        } else if ($vaziZhrubaPos != false) {
            $unitX = "ks ";
            $help = "(kusové zboží)";
            $zaLabelUnit = "za kus";
            $zaLabelPrice = $price;



            $start = $vaziZhrubaPos + strlen(CustomUtils::vaziZhrubaLabel) + 1;
            $length = strlen($productName) - strlen(CustomUtils::vaziZhrubaLabel) - $vaziZhrubaPos - 1;
            $gramPerKs = substr($productName, $start, strlen($productName) - $start - 1);
            $result->gramPerKs = intval(trim($gramPerKs));
            $result->isWeightedKs = true;

        } else {
            $unitX = "ks ";
            $help = "(kusové zboží)";
            $zaLabelUnit = "za&nbsp;kus";
            $zaLabelPrice = round($price,2);

        }

        $result->unitX = $unitX;
        $result->help = $help;
        $result->isWeighted = $isWeighted;
        $result->zaLabelUnit = $zaLabelUnit;
        $result->zaLabelPrice = $zaLabelPrice;
        $result->price = $price;

        return $result;
    }

    public static function isAdmin($context)
    {
        if (!isset($context->customer)) {
            return false;
        }
        $email = $context->customer->email;
        if (!isset($email)) {
            return false;
        }
        return self::isAdminEmail($email);
    }

    public static function ordersWithProductLink($idProduct)
    {
        return
            '<a href="/admin313uriemy/index.php?controller=AdminOrders&idProduct='.$idProduct.'">OBJ</a>';
    }
    public static function productLink($idProduct)
    {
        return
            '<a target="_product" id="productLink_'.$idProduct.'" href="/admin313uriemy/index.php/product/form/'.$idProduct.'">P</a>';
    }
    public static function supplyLink($productName)
    {
        $stripedProductName = str_replace(" - na váhu", "", $productName);
        $stripedProductName = str_replace(" - stáčený produkt", "", $stripedProductName);

        return
            '<a target="_bezobalu" href="https://bezobalu.herokuapp.com/items/search/?productName='
            . urlencode($stripedProductName) .'">C</a>';
    }

    public static function orderLink($idOrder, $linkBody)
    {
        return '<a href="/admin313uriemy/index.php?controller=AdminOrders&id_order='.$idOrder.'&&vieworder">'.
        $linkBody.
    '</a>';
    }

    public static function czechDate($date)
    {
        $result = date(CustomUtils::czechDateFormatNoYear, $date);
        $result = str_replace('Mon', 'Po', $result);
        $result = str_replace('Tue', 'Út', $result);
        $result = str_replace('Wed', 'St', $result);
        $result = str_replace('Thu', 'Čt', $result);
        $result = str_replace('Fri', 'Pá', $result);
        $result = str_replace('Sat', 'So', $result);
        $result = str_replace('Sun', 'Ne', $result);
        return $result;
    }

    /**
     * @param $email
     * @return bool
     */
    public static function isAdminEmail($email): bool
    {
        return ($email == 'hhrom@email.cz' ||
            $email == 'pavel.jetensky@seznam.cz' ||
            $email == 'KatkaMartincova@email.cz' ||
            $email == 'sona.zavacka@seznam.cz' ||
            $email == 'iva.velkomarsovska@gmail.com' ||
            $email == 'marketka.rakova@seznam.cz' ||
            $email == 'lada.hrochova@mailinator.com'
        );
    }

    public static function isPouredProduct($productName):bool
    {
        $isPoured = !(strpos($productName, 'stáčený produkt') === false) || !(strpos($productName, 'stá?ený produkt') === false);
        return $isPoured;
    }
    public static function contains($hay, $needle):bool {
        return !(strpos($hay, $needle) === false);
    }

    public static function connect_to_database()
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

    /**
     * @param $result
     * @param $deliveryToHome
     * @return string
     */
    public static function addDeliveryToHomeNote($result, $deliveryToHome): string
    {
        $result .=
            '<div class="alert alert-info">';

        if ($deliveryToHome) {
            $result .= '<span class="glyphicon glyphicon-home"></span>&nbsp;';
        }

        $result .=
            '<span style="font-size: 120%;"><b>Poznámka: </b>Zboží, které si objednáte, <b style="color:darkgreen">NEPOSÍLÁME</b> poštou k vám domů.</span><br>'
            .'<i>Nevnímáme to jako ekologicky udržitelnou variantu (uhlíková stopa a nutné obaly). Zboží si tedy vyzvednete na kamenné prodejně v Brozanech u Pardubic.' .
            ' Další možností je vyzvednutí každou středu v Pardubicích v 8 hodin na Dukle u Waldorfské školy.<br>'
            .' Zároveň připravujeme projekt <b>BIO kurýr</b> - komunitní dopravu zboží k vám domů po Pardubicích na kole :)</i>';
        /*
        if ($deliveryToHome) {
            $result .=
                'Tato stránka umožňuje objednávat pouze nechlazené zboží, které je možné doručit k vám domů.' .
                '<br><a href="?deliveryToHome=false" style="text-decoration: underline;">Přejít na stránku s veškerým zbožím pro osobní odběr na prodejně</a>';
        } else {
            $result .=
                'Tato stránka umožňuje objednávat veškeré zboží, chlazené i nechlazené, které je možné odebrat pouze <i>osobně na prodejně</i>. Chlazené zboží domů nevozíme.' .
                '<br><a href="?deliveryToHome=true" style="text-decoration: underline;"><span >Přejít na stránku s pouze nechlazeným zbožím pro dodání k vám domů</span></a>';
        }
        */

        $result .= '</div>';
        return $result;
    }

    // $stmt = The SQL Statement Object
// $param = Array of the Parameters
    public static function DynamicBindVariables($stmt, $params)
    {
        if ($params != null)
        {
            // Generate the Type String (eg: 'issisd')
            $types = '';
            foreach($params as $param)
            {
                if(is_int($param)) {
                    // Integer
                    $types .= 'i';
                } elseif (is_float($param)) {
                    // Double
                    $types .= 'd';
                } elseif (is_string($param)) {
                    // String
                    $types .= 's';
                } else {
                    // Blob and Unknown
                    $types .= 'b';
                }
            }

            // Add the Type String as the first Parameter
            $bind_names[] = $types;

            // Loop thru the given Parameters
            for ($i=0; $i<count($params);$i++)
            {
                // Create a variable Name
                $bind_name = 'bind' . $i;
                // Add the Parameter to the variable Variable
                $$bind_name = $params[$i];
                // Associate the Variable as an Element in the Array
                $bind_names[] = &$$bind_name;
            }

            // Call the Function bind_param with dynamic Parameters
            call_user_func_array(array($stmt,'bind_param'), $bind_names);
        }
        return $stmt;
    }


    public static function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }


}