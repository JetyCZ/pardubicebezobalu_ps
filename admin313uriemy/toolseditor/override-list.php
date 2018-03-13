<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

/* get default language: we use this for the categories, manufacturers */
$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Override List</title>
<style>
.comment {background-color:#aabbcc}
#delbutton:disabled {background-color: #aabbcc}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>

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
</script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<div style="float:right; "><iframe name=tank width=230 height=93></iframe></div>
<h1>Override List</h1>
This page gives an overview of your installed overrides.<br>
Prestools is not capable of determining with certainty which module placed an override. So when
there is more than one module that might have done so they are listed all. 
<p>
Prestools has several ways to determine which overrides belong to which modules. Standard the 
light way is chosen. The heavy option takes a bit longer and makes a more thorough search.
<form name=methform method=get>
<?php
  $methode = "heavy";
  if(isset($_GET["methode"]) && ($_GET["methode"] == "light"))
	  $methode = "light";
  $checked = "";
  if($methode == "light") $checked = " checked";
  echo '<input type=radio name=methode value="light" '.$checked.'> light &nbsp; &nbsp;';
  $checked = "";
  if($methode == "heavy") $checked = " checked";
  echo '<input type=radio name=methode value="heavy" '.$checked.'> heavy &nbsp; &nbsp;
<input type=submit>
</form><p>';

  $mod_overrrides = array();
  $activemodules = $inactivemodules = array();
  $query= 'SELECT name, GROUP_CONCAT(ms.id_shop) AS shops FROM '._DB_PREFIX_.'module m';
  $query .= ' LEFT JOIN '._DB_PREFIX_.'module_shop ms';
  $query .= ' ON m.id_module=ms.id_module GROUP BY m.id_module';
  $result = dbquery($query);
  while($row=mysqli_fetch_assoc($result))
  { if($row["shops"]!="")
		$activemodules[] = $row["name"];
	else
		$inactivemodules[] = $row["name"];
  }

if($methode == "light")
{ $activemods = $inactivemods = $notinstalledmods = array();
  $mydir = dir($triplepath."/modules");
  while(($file = $mydir->read()) !== false) 
  { if(!is_dir($triplepath."/modules/".$file)) continue;
    if(($file == ".") || ($file == "..")) continue;

	$module = $file;
    if((is_dir($triplepath."/modules/".$module."/override")) || (is_dir($triplepath."/modules/".$module."/public/override")))
	{ if(in_array($module, $activemodules))
		$activemods[] = $module;
	  else if(in_array($module, $inactivemodules))
		$inactivemods[] = $module;
	  else
		$notinstalledmods[] = $module;  
	  if(is_dir($triplepath."/modules/".$module."/override"))
		$basepath = $triplepath."/modules/".$module."/override/";
	  else 
		$basepath = $triplepath."/modules/".$module."/public/override/";	 /* onepagecheckout */  

	  $overrides = array();
	  analyze_folder("");
	  $mod_overrides[$module] = $overrides;
	}
  }
  
  /* now we gather the override directory */
  $basepath = $triplepath."/override/";	 
  $overrides = $ovfilenames = array();
  analyze_folder("");
  echo "<table border=1><tr><td>Override</td><td>Module(s)</td></tr>";
  foreach($overrides AS $override)
  { echo '<tr><td>'.$override.'</td><td>';
	foreach($activemods AS $activemod)
	{ if(in_array($override, $mod_overrides[$activemod]))
		echo $activemod."<br>";		
	}
	foreach($inactivemods AS $inactivemod)
	{ if(in_array($override, $mod_overrides[$inactivemod]))
		echo "<i>".$inactivemod."</i><br>";		
	}
	foreach($notinstalledmods AS $notinstalledmod)
	{ if(in_array($override, $mod_overrides[$notinstalledmod]))
		echo '<span style="color:#CCCC00"><i>'.$notinstalledmod."</i></span><br>";		
	}	
  }
}
else if ($methode == "heavy")
{ /* first we look in the override directory for all filenames */
  set_time_limit(300);
  $basepath = $triplepath."/override/";	 
  $overrides = $ovfilenames = array();
  $locations = array();
  analyze_folder("");
  foreach($ovfilenames AS $ovfilename)
    $locations[$ovfilename] = array();
  /* next we check each module whether it contains any of the names */
  $basepath = $triplepath."modules/";
  $mydir = dir($triplepath."modules");
  while(($file = $mydir->read()) !== false) 
  { if(!is_dir($triplepath."/modules/".$file)) continue;
    if(($file == ".") || ($file == "..")) continue;
	$basemodule = $file;
	search_module("");
  }
  echo "<table border=1><tr><td>Override</td><td>Module(s)</td></tr>";
  for($i=0; $i < sizeof($ovfilenames); $i++)
  { echo '<tr><td>'.$overrides[$i].'</td><td>';
    foreach($locations[$ovfilenames[$i]] AS $modloc)
	{ if(in_array($modloc, $activemodules))
		  echo $modloc."<br>";
	  else if(in_array($modloc, $inactivemodules))
		echo "<i>".$modloc."</i><br>";
	  else 
		echo '<span style="color:#CCCC00"><i>'.$modloc."</i></span><br>";	
    }
  }
}    
  echo '</table><br><i>Modules in italics are not active in any of your shops.</i><br>
  <span style="color:#CCCC00"><i>Modules in color are not installed</i></span></body></html>';
  

function analyze_folder($subpath)
{ global $basepath, $overrides, $ovfilenames;
  $mydir = dir($basepath."/".$subpath);
  while(($file = $mydir->read()) !== false) 
  { $cleanpath = rtrim($basepath."/".$subpath, '/'). '/';
	if(is_dir($cleanpath.$file))
    { if(($file != ".") && ($file != ".."))
  	  { analyze_folder($subpath."/".$file);
	  }
    }
    else
    { $len = strlen($file);
      if(($file != "index.php") && (substr($file,$len-4) == ".php"))
	  { $overrides[] = $subpath."/".$file;
		$ovfilenames[] = $file;
	  }
    } 
  }
}

function search_module($subpath)
{ global $basepath, $basemodule, $overrides, $ovfilenames, $locations;
  $cleanpath = rtrim($basepath.$basemodule, '/').'/';
  $cleanpath = rtrim($cleanpath.$subpath, '/').'/';
  $mydir = dir($cleanpath);
  while(($file = $mydir->read()) !== false) 
  { if(is_dir($cleanpath.$file))
    { if(($file != ".") && ($file != ".."))
  	  { if($subpath == "")
		  search_module($file);
	    else
		  search_module($subpath."/".$file);
	  }
    }
    else
    { $len = strlen($file);
      if(($file != "index.php") && (substr($file,$len-4) == ".php"))
	  { if(in_array($file,$ovfilenames))
		{ $locations[$file][] = $basemodule;
		}
	  }
    } 
  }
}
