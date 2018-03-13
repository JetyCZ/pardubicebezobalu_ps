<?php
$embedded = "1";
include("login1.php");
include("functions1.php");

/* check values from settings1.php file */
$initwarnings = "";
if($username == "demo@demo.com") 
	$initwarnings .= "Change the username in the file \\'settings1.php\\'!\\n\\n";
if($password == "opensecret") 
	$initwarnings .= "Change the password in the file \\'settings1.php\\'!\\n\\n";
if(sizeof($ipadresses)==0) 
	$initwarnings .= "Set safe IP addresses in the file \\'settings1.php\\'!";
if(rand(0,6)!=4)		/* show this 1 in 4 times */
	$initwarnings = "";

// Note: we check not for "TE_plugin_combi_delete.php". When the copy file is present it is assumed that this one is too
$prestools_plugins = array("carrier"=>"TE_plugin_carriers.php","combinations"=>"TE_plugin_combi_copy.php","discounts"=>"TE_plugin_discounts.php",
	"features"=>"TE_plugin_features.php","suppliers"=>"TE_plugin_suppliers.php","tags"=>"TE_plugin_tags.php",
	"image cleanup"=>"TE_plugin_cleanup_images.php","shopz"=>"TE_plugin_shopz.php","virtual products"=>"TE_plugin_virtual.php" );
$prestools_notbought = array();
foreach($prestools_plugins AS $key => $plugin)
  if(!file_exists($plugin))
  { $prestools_notbought[]=$key;
  }
