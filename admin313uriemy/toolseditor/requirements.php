<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

$rewrite_settings = get_rewrite_settings();
$base_uri = get_base_uri();

/* get language iso_code */
$query = "select iso_code from ". _DB_PREFIX_."configuration c";
$query .= " LEFT JOIN ". _DB_PREFIX_."lang l ON c.value=l.id_lang";
$query .= " WHERE c.name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$iso_code = $row['iso_code'];

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Server requirements for Prestashop and Thirty Bees</title>
<style>
option.defcat {background-color: #ff2222;}
</style>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
</head>

<body>
<?php
print_menubar();
echo '<center><b><font size="+1">Server requirements for Prestashop and Thirty Bees</font></b></center>';
echo "This page provides an easy overview to see whether the modules and settings of your server are adequate for your webshop.";
echo "<br>Note that even if a module is loaded its settings may be wrong.<p>";

/* the array has three fields: ps1.7 - PS 1.6 - TB */
/* value options are: 0=not applicable; 1=recommended; 2 required */
$reqvalues = array(0=>"", 1=>"recommended",2=>"required");
$reqmodules = array( /* ps1.7 - PS 1.6 - TB */
"bcmath" => array(0,0,2),
"curl" => array(2,2,1),
"dom" => array(2,2,2),
"fileinfo" => array(2,0,0),
"gd" => array(2,2,1),
"imap" => array(0,0,1),
"json" => array(0,0,2),
"mbstring" => array(0,0,1),
"mcrypt" => array(2,2,0),
"opcache" => array(0,2,1),
"openssl" => array(2,2,0),
"pdo_mysql" => array(2,2,2),
"simpleXML" => array(2,2,2),
"SOAP" => array(2,2,1),
"zip" => array(2,2,2),
);

echo '<table class="triplemain"><tr><td colspan=5 align="center">Required PHP Modules</td></tr>';
echo '<tr><td></td><td>You</td><td>PS 1.7</td><td>PS 1.6</td><td>30bees</td></tr>';
foreach($reqmodules AS $key => $reqs)
  { if(extension_loaded($key))
  	  $flag = "&#x2714";
    else
  	  $flag = "&#x2716";		
  echo '<tr><td>'.$key.'</td><td>'.$flag.'</td><td>'.$reqvalues[$reqs[0]].'</td><td>'.$reqvalues[$reqs[1]].'</td><td>'.$reqvalues[$reqs[2]].'</td></tr>';
}
echo '</table><p>';

/* now the Apache modules */
$apvalues = array(0=>"",1=>"recommended",2=>"required",3=>"rather not",4=>"forbidden");
$apmodules = array( /* ps1.7 - PS 1.6 - TB */
"mod_auth_basic" => array(3,3,4),
"mod_rewrite" => array(1,1,2),
"mod_security" => array(3,3,4));

if(function_exists('apache_get_modules'))
{ $loadedmodules = apache_get_modules();
  if(sizeof($loadedmodules)>0)
  { echo '<table class="triplemain"><tr><td colspan=5 align="center">Apache Modules</td></tr>';
	echo '<tr><td></td><td>You</td><td>PS 1.7</td><td>PS 1.6</td><td>30bees</td></tr>';
	foreach($apmodules AS $key => $reqs)
	{ if(in_array($key,$loadedmodules))
		$flag = "loaded";
	  else
		$flag = "not loaded";		
	  echo '<tr><td>'.$key.'</td><td>'.$flag.'</td><td>'.$apvalues[$reqs[0]].'</td><td>'.$apvalues[$reqs[1]].'</td><td>'.$apvalues[$reqs[2]].'</td></tr>';
	}
  }
}
echo '</table><p>';

/* now the PHP.INI settings */
$inilist = array( /* ps1.7 - PS 1.6 - TB */
"allow_url_fopen" => array("On","On","On"),
"allow_url_include" => array("","","Off"),
"expose_php" => array("","","Off"),
"max_input_vars" => array("","","10000"),
"post_max_size" => array("","","32M"),
"register_globals" => array("Off","Off",""),
"safe_mode" => array("","Off",""),
"upload_max_filesize" => array(">=16M",">=16M",">=16M"));

echo '<table class="triplemain"><tr><td colspan=5 align="center">Recommended PHP.INI Settings</td></tr>';
echo '<tr><td></td><td>You</td><td>PS 1.7</td><td>PS 1.6</td><td>30bees</td></tr>';
foreach($inilist AS $key => $reqs)
{ echo '<tr><td>'.$key.'</td><td>'.ini_get($key).'</td><td>'.$reqs[0].'</td><td>'.$reqs[1].'</td><td>'.$reqs[2].'</td></tr>';
}
echo '</table><p>';

?>
