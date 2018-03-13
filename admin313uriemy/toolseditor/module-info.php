<?php 
define('INACTIVE_COLOR', '#cccccc');
define('MISSINGDIR_COLOR', '#ff5555');
define('UNINSTALLED_COLOR', '#11ff11');

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
<title>Active Modules Overview for Prestashop</title>
<style>
option.defcat {background-color: #ff2222;}
</style>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
var prestashop_version = '<?php echo _PS_VERSION_ ?>';

function flip_visibility()
{ var tab = document.getElementById("Maintable");
  if(prestashop_version >= "1.6.0.0")
  { activecol = 4;
	authorcol = 3
  }
  else if(prestashop_version >= "1.5.0.0")
  { activecol = 4;
	authorcol = 3;
  }
  else /* PS 1.4 */
  { authorcol = 3;
	activecol = 4;
  }
  var hookcol = 99;
  for(i=5;i<tab.rows[0].cells.length; i++)
	  if(tab.rows[0].cells[i].firstChild.innerHTML == "hooks")
		  hookcol = i;
  if(hookcol != 99)
  { if (mainform.hookswitch.checked)
	  tab.rows[0].cells[hookcol].style.display = "table-cell";
    else
	  tab.rows[0].cells[hookcol].style.display = "none";
  }
  for(var i=1; i<tab.rows.length; i++)
  { tab.rows[i].style.display = "none";
	if (((!mainform.hidePSswitch.checked) || (tab.rows[i].cells[authorcol].innerHTML!="PrestaShop")) 
//		&& ((mainform.showinactive.checked) || ((tab.rows[i].cells[activecol].innerHTML!="") && (tab.rows[i].cells[activecol].innerHTML!="0"))))
 		&& ((tab.rows[i].style.backgroundColor == "")
		  || ((mainform.showinactive.checked) && (tab.rows[i].style.zIndex=="2"))
 		  || ((mainform.showuninstalled.checked) && (tab.rows[i].cells[0].innerHTML==""))
		  || ((mainform.showmissing.checked) && (tab.rows[i].style.zIndex=="1"))
		))
		    tab.rows[i].style.display = "table-row";
	if(hookcol == 99) continue;
	if (mainform.hookswitch.checked)
	  tab.rows[i].cells[hookcol].style.display = "table-cell";
    else
	  tab.rows[i].cells[hookcol].style.display = "none";		
  }
}
</script>
</head>

<body>
<?php
print_menubar();
echo '<center><b><font size="+1">Active Modules Overview</font></b></center>';
echo "This overview of your active modules is mainly meant for the preparation of upgrades.";
echo "<br>Note that PS changed the way it stores the active status of modules around version 1.6.0. Please doublecheck the reports for around that version.";
echo "<br>These overviews may also help you when requesting support from someone for your shop.";
echo '<br>"Missing" modules are only found in the database but not in the modules directory. Likely something went wrong during their removal.';
echo "<br>The Devices field in PS 1.6 is encoded as the sum of Mob=4|Tab=2|PC=1. No value in this field means 
that the module is enabled for neither of those devices.";
echo "<br>In PS 1.6+ you will also see the hooks. The number after the hook name is the position of the module.";

echo '<p><form name=mainform>
<input type=checkbox name=showinactive onchange="flip_visibility();"> Show inactive modules (grey)<br>
<input type=checkbox name=showmissing onchange="flip_visibility();"> Show missing modules (red)<br>
<input type=checkbox name=showuninstalled onchange="flip_visibility();"> Show not installed modules (green)<br>
<input type=checkbox name=hookswitch onchange="flip_visibility();"> Show hooks<br>
<input type=checkbox name=hidePSswitch onchange="flip_visibility();"> Hide modules with author Prestashop
</form>';
/* note: in the 1.5 version being active is signalled by the active flag in the ps_module table */
/* in PS 1.6 the active flag is always 1 and being active is signalled by being present in the ps_module_shop table */
$totmodules=$prestamodules = $totactmodules=$prestactmodules=$tbeesmodules=$tbeesactmodules=0;
$config_data = array();
if (_PS_VERSION_ >= "1.6.0.0")
{ $query="SELECT m.id_module,name,version,GROUP_CONCAT(id_shop) AS shops,GROUP_CONCAT(enable_device) AS devices FROM ". _DB_PREFIX_."module m";
  $query .= " LEFT JOIN ". _DB_PREFIX_."module_shop ms ON m.id_module=ms.id_module";
  $query .= " GROUP BY m.id_module";
  $query .= " ORDER BY name, enable_device, id_shop";
  $res=dbquery($query);
  echo "XXX".mysqli_num_rows($res)."---";

  $fields = array("id_module","name","version","author","id_shop","devices","displayname","description", "hooks");
  echo '<div id="testdiv"><table id="Maintable" border=1><colgroup id=mycolgroup>';
  foreach($fields AS $field)
     echo '<col></col>'; /* needed for sort */
  echo '</colgroup><thead><tr>';
  $x=0;
  foreach($fields AS $field)
  { $insert = "";
    if($field == "hooks")
	  $insert = 'style="display:none;"';
    echo '<th '.$insert.'><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$x++.', 0);" title="'.$field.'">'.$field.'</a></th
>';
  }
  echo "</tr></thead><tbody id='offTblBdy'>"; /* end of header */

  $modules_on_disk = get_notinstalled(array());
  $dbmodules = array();
  $dbmodulenames = array();
  while ($datarow=mysqli_fetch_array($res))
  { $dbmodules[$datarow['name']] = $datarow;
	$dbmodulenames[] = $datarow['name'];
  }
  $myarray = array_unique(array_merge($dbmodulenames,$modules_on_disk));
  sort($myarray);
  
  $missing = $notinstalled = $inactive = $modactive = 0;
  foreach($myarray AS $module)
  { if(in_array($module, $dbmodulenames))
	  $datarow = $dbmodules[$module];
    else /* not installed. So no data in database */
	  $datarow = array('id_module'=>"",'name'=>$module,'version'=>"",'author'=>"",'shops'=>"",'devices'=>"",'displayname'=>"",'description'=>"",'active'=>"");
  
	$config_data["version"] = $config_data["author"] = $config_data["displayname"] = $config_data["description"] = "";
	$active = "yes"; /* "yes" or "no" */
	if(($datarow['devices']=="") ||($datarow['devices']==0))
	  $active = "no";
	$hasconfig = "yes"; /* "yes" or "no" or "nodir" */
	$status = "active";
    $activecolor = "";
	if(!is_dir($triplepath.'modules/'.$datarow['name']))
	{	$hasconfig = "nodir";
		$active = "no";
	}
	else
	  if(!analyze_configfile($datarow['name']))
		  $hasconfig = "no";
	$style = "";
	$modus = "";
	if($active == "no")
	  $style = "display:none;";
	if ($hasconfig == "nodir")
	{ $style .= "background-color: ".MISSINGDIR_COLOR."; z-index: 1;";
	  $missing++;
	}
    else if(($active == "no") && ($datarow['id_module']!=""))
	{ $style .= "background-color: ".INACTIVE_COLOR."; z-index: 2;";
	  $inactive++;
	}
    else if(!in_array($module, $dbmodulenames))
	{ $style .= "background-color: ".UNINSTALLED_COLOR."; z-index: 3;";
	  $notinstalled++;
	}
	else 
	{ $modactive++;
	  if($config_data["author"] == "PrestaShop")
		$prestactmodules++;
	  if($config_data["author"] == "thirty bees")
		$tbeesactmodules++;
	}
	echo '<tr style="'.$style.'">';
    echo '<td>'.$datarow['id_module'].'</td>';
    echo '<td>'.$datarow['name'].'</td>';
    echo '<td>'.$datarow['version'].'</td>';
	echo '<td>'.$config_data["author"].'</td>';	 
    echo '<td>'.$datarow['shops'].'</td>';		  
    echo '<td>'.$datarow['devices'].'</td>';
    echo '<td>'.$config_data["displayname"].'</td>';	
	echo '<td>'.$config_data["description"].'</td>';
	echo '<td style="display:none">';
	if($datarow['id_module'] != "")
	{ $hquery="SELECT h.name,hm.position FROM ". _DB_PREFIX_."hook_module hm";
      $hquery .= " LEFT JOIN ". _DB_PREFIX_."hook h ON h.id_hook=hm.id_hook";
	  $hquery .= " WHERE id_module=".$datarow['id_module'];
	  $hquery .= " GROUP BY h.id_hook";
	  $hres=dbquery($hquery);
      while ($hrow=mysqli_fetch_array($hres))
	    echo $hrow["name"]."-".$hrow["position"]."<br>"; 
	}
    echo '</td></tr>';
	$shoplist = array();
	$totmodules++;
	if($config_data["author"] == "PrestaShop")
	{ $prestamodules++;
	}
	if($config_data["author"] == "thirty bees")
	{ $tbeesmodules++;
	}
  }
/*  
  $notinstalled = get_notinstalled($dbmodules);
  foreach($notinstalled AS $notter)
  { echo "<tr style='background-color: ".UNINSTALLED_COLOR.";display:none;'><td></td><td>".$notter."</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
  }
  */
  echo '</table></form></div>';
}
else if(_PS_VERSION_ >= "1.5.0.0")
{ $query="select m.id_module,active, name,version, GROUP_CONCAT(id_shop) AS shops FROM ". _DB_PREFIX_."module m";
  $query .= " LEFT JOIN ". _DB_PREFIX_."module_shop ms ON m.id_module=ms.id_module";
  $query .= " GROUP BY id_module";
  $query .= " ORDER BY name, id_shop";
  $res=dbquery($query);

  $fields = array("id_module","name","version","author","shops","active","displayname","description","hooks");
  echo '<div id="testdiv"><table id="Maintable" border=1><colgroup id=mycolgroup>';
  foreach($fields AS $field)
     echo '<col></col>'; /* needed for sort */
  echo '</colgroup><thead><tr>';
  $x=0;
  foreach($fields AS $field)
  { $insert = "";
    if($field == "hooks")
	  $insert = 'style="display:none;"';
    echo '<th '.$insert.'><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$x++.', 0);" title="'.$field.'">'.$field.'</a></th
>';
  }
  echo "</tr></thead><tbody id='offTblBdy'>"; /* end of header */
  
  $modules_on_disk = get_notinstalled(array());
  $dbmodules = array();
  $dbmodulenames = array();
  while ($datarow=mysqli_fetch_array($res))
  { $dbmodules[$datarow['name']] = $datarow;
	$dbmodulenames[] = $datarow['name'];
  }
  $myarray = array_unique(array_merge($dbmodulenames,$modules_on_disk));
  sort($myarray);
  
  $missing = $notinstalled = $inactive = $modactive = 0;
  foreach($myarray AS $module)
  { if(in_array($module, $dbmodulenames))
	  $datarow = $dbmodules[$module];
    else /* not installed. So no data in database */
	  $datarow = array('id_module'=>"",'name'=>$module,'version'=>"",'author'=>"",'shops'=>"",'active'=>"",'displayname'=>"",'description'=>"",'hooks'=>"");

	$config_data["version"] = $config_data["author"] = $config_data["displayname"] = $config_data["description"] = "";
	$active = "yes"; /* "yes" or "no" */
	if(($datarow['active']=="") ||($datarow['active']==0))
	  $active = "no";
	$hasconfig = "yes"; /* "yes" or "no" or "nodir" */
	$status = "active";
    $activecolor = "";
	if(!is_dir($triplepath.'modules/'.$datarow['name']))
	{	$hasconfig = "nodir";
		$active = "no";
	}
	else
	  if(!analyze_configfile($datarow['name']))
		  $hasconfig = "no";
	$style = "";
	$modus = "";
	if($active == "no")
		  $style = "display:none;";
	if ($hasconfig == "nodir")
	{ $style .= "background-color: ".MISSINGDIR_COLOR."; z-index: 1;";
	  $missing++;
	}
    else if(($active == "no") && ($datarow['id_module']!=""))
	{ $style .= "background-color: ".INACTIVE_COLOR."; z-index: 2;";
	  $inactive++;
	}
    else if(!in_array($module, $dbmodulenames))
	{ $style .= "background-color: ".UNINSTALLED_COLOR."; z-index: 3;";
	  $notinstalled++;
	}
	else
	{ $modactive++;
	  if($config_data["author"] == "PrestaShop")
		$prestactmodules++;
	}
	echo '<tr style="'.$style.'">';
    echo '<td>'.$datarow['id_module'].'</td>';
    echo '<td>'.$datarow['name'].'</td>';
    echo '<td>'.$datarow['version'].'</td>';
	echo '<td>'.$config_data["author"].'</td>';	  
    echo '<td>'.$datarow['shops'].'</td>';
    echo '<td>'.$datarow['active'].'</td>';
    echo '<td>'.$config_data["displayname"].'</td>';	
	echo '<td>'.$config_data["description"].'</td>';	 
	echo '<td style="display:none">';
	if($datarow['id_module'] != "")
	{ $hquery="SELECT h.name,hm.position FROM ". _DB_PREFIX_."hook_module hm";
      $hquery .= " LEFT JOIN ". _DB_PREFIX_."hook h ON h.id_hook=hm.id_hook";
	  $hquery .= " WHERE id_module=".$datarow['id_module'];
	  $hquery .= " GROUP BY h.id_hook";
	  $hres=dbquery($hquery);
      while ($hrow=mysqli_fetch_array($hres))
	    echo $hrow["name"]."-".$hrow["position"]."<br>"; 
	}	
    echo '</td></tr>';
	$totmodules++;
	if($config_data["author"] == "PrestaShop")
	{ $prestamodules++;
	}
  }

  echo '</table></form></div>';
}
else /* Prestashop 1.4 */
{ $query="select id_module,active, name FROM ". _DB_PREFIX_."module";
  $query .= " ORDER BY name";
  $res=dbquery($query);

  $fields = array("id_module","name","version","author","active","displayname","description");
  echo '<div id="testdiv"><table id="Maintable" border=1><colgroup id=mycolgroup>';
  foreach($fields AS $field)
     echo '<col></col>'; /* needed for sort */
  echo '</colgroup><thead><tr>';
  $x=0;
  foreach($fields AS $field)
    echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$x++.', 0);" title="'.$field.'">'.$field.'</a></th
>';
  echo "</tr></thead><tbody id='offTblBdy'>"; /* end of header */
 
  $x=0;
  $dbmodules = array();
  while ($datarow=mysqli_fetch_array($res)) {
    /* Note that trid (<tr> id) cannot be an attribute of the tr as it would get lost with sorting */
	$config_data["version"] = $config_data["author"] = $config_data["displayname"] = $config_data["description"] = "";
	$active = "yes"; /* "yes" or "no" */
	if(($datarow['devices']=="") ||($datarow['devices']==0))
	  $active = "no";
	$hasconfig = "yes"; /* "yes" or "no" or "nodir" */
	$status = "active";
    $activecolor = "";
	if(!is_dir($triplepath.'modules/'.$datarow['name']))
	{	$hasconfig = "nodir";
		$active = "no";
	}
	else
	if(!analyze_configfile($datarow['name']))
		  $hasconfig = "no";
	$style = "";
	if($active == "no")
		  $style = "display:none;";
	if ($hasconfig == "nodir")
	  $style .= "background-color: ".MISSINGDIR_COLOR."; z-index: 1;";
    else if($active == "no")
	  $style .= "background-color: ".INACTIVE_COLOR."; z-index: 2;";	  
	echo '<tr style="'.$style.'">';
    echo '<td>'.$datarow['id_module'].'</td>';
    echo '<td>'.$datarow['name'].'</td>';
    echo '<td>'.$config_data["version"].'</td>';	
	echo '<td>'.$config_data["author"].'</td>';	 
	echo '<td>'.$datarow['active'].'</td>';
    echo '<td>'.$config_data["displayname"].'</td>';	
	echo '<td>'.$config_data["description"].'</td>';	 	
    $x++;
    echo '</tr>';
	$totmodules++;
	if($datarow["active"] == "1")
		$totactmodules++;
	if($config_data["author"] == "PrestaShop")
	{ $prestamodules++;
	  if($datarow["active"] == "1")
		$prestactmodules++;
	}
  }
  $notinstalled = get_notinstalled($dbmodules);
  foreach($notinstalled AS $notter)
  { echo "<tr style='background-color: ".UNINSTALLED_COLOR.";display:none;'><td></td><td>".$notter."</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
  }
  echo '</table></form></div>';
}

  if(_PS_VERSION_ < "1.5.0.0")
  { echo $totmodules." modules (".$totactmodules." active), of which ".$prestamodules." 
    (".$prestactmodules." active) by Prestashop and ".$tbeesmodules." 
    (".$tbeesactmodules." active) by Thirty Bees. ";
    echo sizeof($notinstalled)." not installed.";
  }
  else
  { echo $totmodules." modules (".$modactive." active; ".$inactive." inactive; ".$notinstalled." notinstalled;".$missing." missing), of which ".$prestamodules." 
    (".$prestactmodules." active) by Prestashop and ".$tbeesmodules." 
    (".$tbeesactmodules." active) by Thirty Bees. ";
  }	  
    
  if(mysqli_num_rows($res) == 0)
	echo "<strong>No modules found... Strange...</strong>";

  include "footer1.php";
  echo '</body></html>';
  
  function analyze_configfile($modulename)
  { global $triplepath, $iso_code, $config_data;
    $config_file = $triplepath.'modules/'.$modulename.'/config.xml';
    $fp = @fopen($config_file,"r");
	if(!$fp)
	{ $config_file = $triplepath.'modules/'.$modulename.'/config_'.$iso_code.'.xml';
      $fp = @fopen($config_file,"r");
	  if(!$fp)
	  { $config_data["author"] = "No config file";
	    return false;
	  }
	}
	while(!feof($fp))
	{ $line = fgets($fp);
	  if(($pos1 = strpos($line,"<version>"))>0)
	  { $pos2 = strpos($line,"]");
		$config_data["version"] = substr($line, $pos1+18,$pos2-$pos1-18);
	  }
	  if(($pos1 = strpos($line,"<author>"))>0)
	  { $pos2 = strpos($line,"]");
		$config_data["author"] = substr($line, $pos1+17,$pos2-$pos1-17);
	  }
	  if(($pos1 = strpos($line,"<displayName>"))>0)
	  { $pos2 = strpos($line,"]");
		$config_data["displayname"] = substr($line, $pos1+22,$pos2-$pos1-22);
	  }
	  if(($pos1 = strpos($line,"<description>"))>0)
	  { $pos2 = strpos($line,"]");
		$config_data["description"] = substr($line, $pos1+22,$pos2-$pos1-22);
	  }
	}
	return true;
  }
  
  function get_notinstalled($dbmodules)
  { global $triplepath;
	$myfiles = scandir($triplepath.'modules');
    $modules = array_diff($myfiles, array('.','..','__MACOSX'));
	$mymodules = array();
	foreach($modules AS $mydir)
	  if(is_dir($triplepath.'modules/'.$mydir))
		  $mymodules[] = $mydir;
	return array_diff($mymodules, $dbmodules);	
  }

?>
