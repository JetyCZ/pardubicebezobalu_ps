<?php 
error_reporting(E_ALL|E_STRICT);
ini_set( 'display_errors', 1);
include("settings1.php");
$usecookies = true; /* sessions are no longer supported */

if((sizeof($ipadresses) > 0) && (!checkIPs($ipadresses)))
{ echo "You may not use this script from IP Adress: ".$_SERVER['REMOTE_ADDR']; exit();}

if (!function_exists('mysqli_connect'))
  die("Your server does not support the PHP MySQLi functions. Ask your hosting provider to add them to your installation! This is essential software - also for other applications.");

date_default_timezone_set(@date_default_timezone_get()); // Suppress DateTime warnings
//  ini_set('date.timezone', @date_default_timezone_get());  //alternative: if(!ini_get('date.timezone')) {date_default_timezone_set('GMT');} 

$sname = str_replace(".","", $_SERVER['SERVER_NAME']);
if(!$usecookies)
{ session_id('t69'.$sname);
  if(!@is_writable(session_save_path()))
    $usecookies = true;
  else
    session_start();
}
connect_to_database();

$url = $_SERVER['REQUEST_URI'];
$validated = false;
if(isset($embedded))
{ check_logincount();
  if($usecookies)
  { $seed = get_seed();
//  $encseed = preg_replace('/'.preg_quote("\\", '\\').'/',"", convert_uuencode(base64_encode($seed)));  // some servers escape cookies
    $encseed = stripslashes(stripslashes(stripslashes(convert_uuencode(base64_encode($seed)))));  // some servers escape cookies
    if(isset($_COOKIE["tripleedit"]) && (stripslashes(stripslashes(stripslashes($_COOKIE["tripleedit"]))) == $encseed))
//  if(isset($_COOKIE["tripleedit"]) && ($_COOKIE["tripleedit"] == "magic"))
// Note: cookie may be replaced with localStorage.setItem('key', 'value') and localStorage.getItem('key'));
      $validated = true;
  } 
  else /* session */
  { if (isset($_SESSION['tripleedit']) && $_SESSION['tripleedit'] == 'open')
	  $validated = true;
  }	
  if (!$validated) 
  { header('Location: login1.php?url='.urlencode($url)); //Replace that if login1.php is somewhere else
  }
  reset_logincount();
}	
else  /* when login1.php is called stand-alone ( = not from approve.php) */
{ if(isset($_POST['username']) && isset($_POST['pswd']))
  { $pswd = $_POST['pswd'];
	check_logincount();
    if(($_POST['username'] == $username) && (($md5hashed && (md5($pswd) == $password )) || (!$md5hashed && ($pswd == $password))))
    { 
      if(!$usecookies)
  	  {	check_for_BOM();
	 	$_SESSION['tripleedit'] = 'open';
		session_write_close();
	  }
	  else
	  { $seed = get_seed();
		$encseed = stripslashes(stripslashes(stripslashes(convert_uuencode(base64_encode($seed)))));  // some servers escape cookies
		setcookie("tripleedit", $encseed);
	  }
	  reset_logincount();
      if(isset($_GET['url']))
        header('Location: '.urldecode($_GET['url'])); //Replace index.php with what page you want to go to after succesful login
      else
        header('Location: product-edit.php'); //Replace index.php with what page you want to go to after succesful login
	  if(isset($_GET['url']))
	    echo "Redirection problem for ".$_GET['url'];
	  else 
	    echo "Redirection to edit pages is impossible.";
      exit;
    } 
	else 
	{ echo '<script type="text/javascript">
         alert(\'Wrong Username or Password, Please Try Again!\');
     </script>';
    }
  }

  echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestools Login</title>
</head>
<body>'; 
  echo "<br/>";
  if($usecookies) echo "Cookie"; else echo "Session";
  echo "<br/>IP address = ".$_SERVER['REMOTE_ADDR'];
  if(!isset($_POST['username'])) $_POST['username'] = "w175205";
  if(!isset($_POST['pswd'])) $_POST['pswd'] = "A5a0b1_570df";
  echo '
<p/>&nbsp;<p/>&nbsp;<p/><p/><p/><p/>
<center>
<form method="post" action="">
<table>
<tr><td>User:</td><td><input type="input" name="username" value = "'.$_POST['username'].'"></td></tr>
<tr><td>Password:</td><td><input type="password" name="pswd" value = "'.$_POST['pswd'].'"></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="login" value="Login"></td></tr>
</table>
</form>
</center>
</body>
</html>';
}

function check_logincount()
{	global $conn;
    $day = date("Ymd");
    $hour = date("H");
    $minute = date("i");
    /* check for too many login attempts */
	/* $parts looks like 20171205_17_30_5_3_1: this means that the last login attempt was 
	  at 17:30 on 5 dec 2017 and that that day there had been 5 login attempts, that hour 3 and that minute 1 */
	$query = "select value FROM `". _DB_PREFIX_."configuration` WHERE name='"._PRESTOOLS_PREFIX_."LOGIN_FREQUENCY'";
	$res = mysqli_query($conn, $query);
	if($row = mysqli_fetch_array($res))
	{ $parts = explode("_",$row["value"]); 
	  if(intval($parts[3]) > 900) die("Too many login attempts for this day!");
	  if(intval($parts[4]) > 200) die("Too many login attempts for this hour!");
	  if(intval($parts[5]) > 25) die("Too many login attempts for this minute!");
	  if($day != $parts[0]) $newval = "1_1_1";
	  else if ($hour != $parts[1]) $newval = (1+intval($parts[3]))."_1_1";
	  else if ($minute != $parts[2]) $newval = (1+intval($parts[3]))."_".(1+intval($parts[4]))."_1";
	  else $newval = (1+intval($parts[3]))."_".(1+intval($parts[4]))."_".(1+intval($parts[5]));
	  $query = "UPDATE `". _DB_PREFIX_."configuration` SET value='".$day."_".$hour."_".$minute."_".$newval."' WHERE name='"._PRESTOOLS_PREFIX_."LOGIN_FREQUENCY'";
	  $res = mysqli_query($conn, $query);
    }
	else
	{ $query = "INSERT INTO `". _DB_PREFIX_."configuration` SET value='".$day."_".$hour."_".$minute."_1_1_1', name='"._PRESTOOLS_PREFIX_."LOGIN_FREQUENCY'";
	  $res = mysqli_query($conn, $query);
	}
}

function reset_logincount()
{ global $conn;
  $day = date("Ymd");
  $hour = date("H");
  $minute = date("i");
  $query = "UPDATE `". _DB_PREFIX_."configuration` SET value='".$day."_".$hour."_".$minute."_0_0_0' WHERE name='"._PRESTOOLS_PREFIX_."LOGIN_FREQUENCY'";
  $res = mysqli_query($conn, $query);
}

function checkIPs($ipadresses)
{ $myip = $_SERVER['REMOTE_ADDR'];
  $myparts = explode(".",$myip);
  foreach($ipadresses AS $ip)
  { if((strpos($myip,":") === false) && (strpos($ip,":") === false))
    { $parts = explode(".",$ip);
	  $allowed = true;
	  for($i=0; $i<4; $i++)
	    if(($myparts[$i]!= $parts[$i]) && ($parts[$i] != "*"))
		  $allowed = false;
	  if($allowed == true)
	    return true;
	}
	else if($myip == $ip)
	  return true;
  }
  return false;
}

function get_seed()
{ $seed = "";
  if(isset($_SERVER['SERVER_SIGNATURE']))   $seed.=$_SERVER['SERVER_SIGNATURE'];
  if(isset($_SERVER['GATEWAY_INTERFACE']))  $seed.=$_SERVER['GATEWAY_INTERFACE'];
  if(isset($_SERVER['SERVER_ADMIN']))       $seed.=$_SERVER['SERVER_ADMIN'];
  return @date("m Y").$seed."random"._COOKIE_KEY_;
}

function check_for_BOM()
{ 	global $session;
	if($session)
	{   $file = @fopen("approve.php", "r"); 
		$bom = fread($file, 3); 
		if ($bom == b"\xEF\xBB\xBF") 
		{ echo '<script type="text/javascript">
				alert(\'BOM header found! Use another text editor!\');
			</script>';
		  exit;
		}
    } 
}

/* in PS 1.5 and 1.6 all constants are in config/settings.inc.php */
/* in PS 1.7 _PS_VERSION_ is in /config/autoload.php and the other constants are in app/config/parameters.php */
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
