<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

/* Interesting info
 - shop-enable  PS_SHOP_ENABLE
 - enable SSL
 - enable friendly url  PS_REWRITING_SETTINGS
 - enable cache
 - empty cache
 - multishop
 - disable overrides  PS_DISABLE_OVERRIDES
 - disable non-ps modules  PS_DISABLE_NON_NATIVE_MODULE
 - CCC PS_CSS_THEME_CACHE  PS_JS_THEME_CACHE   PS_JS_DEFER   PS_HTML_THEME_COMPRESSION  PS_JS_HTML_THEME_COMPRESSION
 PS_HTACCESS_CACHE_CONTROL
 PS_LOGS_BY_EMAIL
*/

/* get default language: we use this for the categories, manufacturers */
$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Shop Rescue</title>
<style>
.comment {background-color:#aabbcc}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
function change_rec(row)
{
	
}

function translate() { 
		var val = document.translateform.source.value;
		if(val == "") return;
		LoadPage("shop-rescue-proc.php?myids="+val+"&id_lang=<?php echo $id_lang; ?>&task=prodcopycombis",dynamo3);
}

function LoadPage(url, callback)
{ var request =  new XMLHttpRequest("");
  request.open("GET", url, true); /* delaypage must be a global var; changed from POST to GET */
  request.onreadystatechange = function() 
  { if (request.readyState == 4 && request.status == 404) /* done = 4, ok = 200 */
	alert("ERROR "+request.status+" "+request.responseText) 
    if (request.readyState == 4 && request.status == 200) /* done = 4, ok = 200 */
    { if (request.responseText) 
        callback(request.responseText);
    };
  }
  request.send(null);
}

function dynamo1(data)  /* get product name */
{ var prodname=document.getElementById("prodname");
  prodname.innerHTML = data;
}

function clearlog()
{ var copylist=document.getElementById("copylist");
  copylist.innerHTML = "";
  return false;
}

function RowSubmit(idx)
{ rowform.id_shop.value = eval("configform.id_shop"+idx+".value");
  rowform.id_shop_group.value = eval("configform.id_shop_group"+idx+".value");
  rowform.cvalue.value = eval("configform.cvalue"+idx+".value");
  rowform.fieldname.value = eval("configform.fieldname"+idx+".value");
  rowform.id_configuration.value = eval("configform.id_configuration"+idx+".value"); 
  rowform.verbose.value = configform.verbose.checked;  
  rowform.id_row.value = idx;
  document.rowform.submit();
}

/* prepare submission of cacheform */
function cfprepare()
{ cacheform.verbose.value = configform.verbose.checked;  
}

function cffprepare()
{ cacheflagsform.verbose.value = configform.verbose.checked;  
}

</script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<div style="float:right; "><iframe name=tank width=230 height=93></iframe></div>
<h1>Shop Rescue</h1>
This page provides a set of functions to help you when your shop is stuck behind a PHP or javascript error and have no 
longer access to your back office. These are powerful functions and should only be used when you know what you are doing.<p>
<table border=1><tr><td><form name=cookieform><center><input type=button value="Manage Prestashop cookies"></center></td></tr><tr><td id="cookielist">
<table border=1>
<?php 
foreach($_COOKIE AS $key => $value)
  echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
echo '</table></td></tr><tr><td>
<input type=button value="Delete Prestashop cookies"></table>';

$index = 0;
echo '<form name=configform><p>';
echo '<input type=checkbox name=verbose> verbose';
echo '<table class="triplemain" style="margin-top:-15px">';
echo '<thead><tr><td colspan=6><b>Configuration flags</b></td></tr>';
echo '<tr><td colspan=6>By default only settings for all shops/shopgroups (NULL-NULL) are shown. Only when some of them are already in the database will they be shown too.
The default options - as set when you install Prestashop - are underlined.</td></tr>';
echo '<tr><td>Field</td><td>shopgroup</td><td>shop</td><td>Value</td><td></td><td>Comment</td></tr></thead><tbody>';

  print_config_row("PS_SHOP_ENABLE","",1,"Enable shop");
  $index++;
  print_config_row("PS_REWRITING_SETTINGS","",1,"Friendly url");
  $index++;
  print_config_row("PS_ALLOW_ACCENTED_CHARS_URL","",0,"Accented url");
  $index++;
  print_config_row("PS_DISABLE_OVERRIDES","",0,"Disable all overrides");  
  $index++;
  print_config_row("PS_DISABLE_NON_NATIVE_MODULE","",0,"Disable non PrestaShop modules");  
  $index++;
  print_config_row("PS_CSS_THEME_CACHE","",0,"Smart cache for CSS");  
  $index++;
  print_config_row("PS_JS_THEME_CACHE","",0,"Smart cache for JavaScript");  
  $index++;
  print_config_row("PS_JS_DEFER","",0,"Move JavaScript to the end");  
  $index++;
  print_config_row("PS_HTML_THEME_COMPRESSION","",0,"Minify HTML");  
  $index++;
  print_config_row("PS_JS_HTML_THEME_COMPRESSION","",0,"Compress inline JavaScript in HTML");
  $index++;
  print_config_row("PS_HTACCESS_CACHE_CONTROL","",0,"Apache optimization"); 
  $index++;
  print_config_row("PS_SMARTY_CACHE","",1,"enable cache"); 
  $index++;  
  print_config_row("PS_SMARTY_CLEAR_CACHE",array("everytime"=>"Clear cache everytime something has been modified","never"=>"Never clear cache files"),"everytime",""); 
  $index++;  
  print_config_row("PS_SMARTY_FORCE_COMPILE",array("0"=>"Never recompile template files","1"=>"Recompile templates if the files have been updated","2"=>"Force compilation"),0,""); 
  $index++; 
  print_config_row("PS_HTACCESS_DISABLE_MULTIVIEWS","",0,"Disable Apache's MultiViews option");
  $index++;
  print_config_row("PS_HTACCESS_DISABLE_MODSEC","",0,"Disable Apache's mod_security module"); 
  
  echo '<tr><td></form></td></tr>';
  echo '</tbody></table>';
  
  echo '<p>';

  /* The following duplicates AdminPerformanceController.php that contains the following code in its postProcess() function:
  		if ((bool)Tools::getValue('empty_smarty_cache'))
		{
			$redirectAdmin = true;
			Tools::clearSmartyCache();
			Tools::clearXMLCache();
			Media::clearCache();
			Tools::generateIndex();
		}
  */
  
  echo '<br><table class="triplemain" style="margin-top:-15px">';
  echo '<thead><tr><td style="text-align:center"><b>Empty cache</b></td></tr></thead>';
  echo '<tbody><tr><td>Pressing the button below will immediately empty the cache by <a href="http://doc.prestashop.com/display/PS15/Creating+a+PrestaShop+module#CreatingaPrestaShopmodule-Aboutthecache">emptying</a> the /cache/smarty/cache
  and /cache/smarty/compile directories.</td></tr>';
  echo '<tr><td style="text-align:center"><form name=cacheform action="shop-rescue-proc.php" method=post target=tank onsubmit=cfprepare()>';
  echo '<input type=hidden name="subject" value="emptycache" ><input type=hidden name=verbose>';
  echo '<input type=submit value="empty cache"></form></td></tr></table>';
  
  echo '<p><br><table class="triplemain" style="margin-top:-15px">';
  echo '<thead><tr><td style="text-align:center"><b>Reset Cacheflags</b></td></tr></thead>';
  echo '<tbody><tr><td>Prestashop maintains cache flags in the product tables that check whether a product has attachments and what its default attribute is.
  This function will check and correct those values.</td></tr>';
  echo '<tr><td style="text-align:center"><form name=cacheflagsform action="shop-rescue-proc.php" method=post target=tank onsubmit="cffprepare()">';
  echo '<input type=hidden name="subject" value="resetcacheflags" ><input type=hidden name=verbose>';
  echo '<input type=submit value="reset cacheflags"></form></td></tr></table>';

  echo '<div style="display:block;"><form name=rowform action="shop-rescue-proc.php" method=post target=tank>
  <table id=subtable></table>';
  echo '<input type=hidden name=fieldname><input type=hidden name=id_shop><input type=hidden name=id_shop_group>';
  echo '<input type=hidden name=cvalue><input type=hidden name=id_configuration>'; 
  echo '<input type=hidden name="subject" value="configuration">';
  echo '<input type=hidden name=id_row><input type=hidden name=verbose>';
  echo '</form></div>';
  echo '</body></html>';

function print_options($index, $options, $default, $value)
{ if($options == "") /* no options => no/yes */
    $options = array("No","Yes");
  if(isset($options[$default]))
    $options[$default] = "<u>".$options[$default]."</u>"; /* default should be underlined */
  if((sizeof($options)>2) || (strlen(implode($options)) > 17))  /* note that underline code takes 7 positions */
  { $tmp = "";
    $i = 0;
    foreach($options AS $key => $option)
	{ if($i>0) $tmp .= "<br>";
	  $tmp .= '<input type="radio" name="cvalue'.$index.'" value='.$key;
      if($value == $key) $tmp .= " checked";
	  $tmp .= '> '.$option;
	  $i++;
	}
  }
  else
  { $tmp = $options[0].' <input type="radio" name="cvalue'.$index.'" value=0';
    if($value == "0") $tmp .= " checked";
    $tmp .= '> &nbsp; &nbsp; <input type="radio" name="cvalue'.$index.'" value=1';
    if($value == "1") $tmp .= " checked";
	$tmp .= '> '.$options[1];
  }
  return $tmp;
}

function print_config_row($configfield, $options, $default, $comment)
{ global $index;
  $cquery="select id_configuration,id_shop_group,id_shop,value FROM ". _DB_PREFIX_."configuration";
  $cquery .= " WHERE name='".$configfield."' ORDER BY id_shop_group,id_shop";
  $cres=dbquery($cquery);
  $nnfound = false;
  if(mysqli_num_rows($cres) > 0)
  { $crow = mysqli_fetch_array($cres);
	if (($crow["id_shop_group"] == NULL) && ($crow["id_shop"] == NULL))
		$nnfound = true;
	else
		mysqli_data_seek($cres, 0);
  }
  echo '<tr><td>'.$configfield.'</td><td>NULL</td><td>NULL</td>';
  if($nnfound)
  { echo '<td>'.print_options($index, $options, $default, $crow["value"]);
    echo '<input type=hidden name="id_configuration'.$index.'" value="'.$crow["id_configuration"].'"></td>';
  }
  else
  { echo '<td>'.print_options($index, $options, $default, "0");
    echo '<input type=hidden name="id_configuration'.$index.'" value="0"></td>';
  }
  echo '<td><input type=hidden name="fieldname'.$index.'" value="'.$configfield.'">';
  echo '<input type=hidden name="id_shop_group'.$index.'" value="NULL"><input type=hidden name="id_shop'.$index.'" value="NULL">';

  echo '<img src="enter.png" title="submit row '.$index.'" onclick="RowSubmit('.$index.')"></td><td>'.$comment.'</td></tr>';
  while($crow = mysqli_fetch_array($cres))
  { $index++;
	echo '<tr><td>'.$configfield.'</td><td>'.$crow["id_shop_group"].'</td><td>'.$crow["id_shop"].'</td><td>'.print_options($index, $options, $default, $crow["value"]).'</td>';
    echo '<td><input type=hidden name="fieldname'.$index.'" value="'.$configfield.'">';
    echo '<input type=hidden name="id_configuration'.$index.'" value="'.$crow["id_configuration"].'">';
    echo '<input type=hidden name="id_shop_group'.$index.'" value="'.$crow["id_shop_group"].'"><input type=hidden name="id_shop'.$index.'" value="'.$crow["id_shop"].'">';
    echo '<img src="enter.png" title="submit row '.$index.'" onclick="RowSubmit('.$index.')"></td></tr>';
  }
}

