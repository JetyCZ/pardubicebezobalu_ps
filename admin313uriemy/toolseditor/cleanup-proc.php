<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$mode = "background";
if(isset($_POST['id_lang']))
  $id_lang = strval(intval($_POST['id_lang']));
$errstring = "";

echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<script>
function newwin()
{ nwin = window.open("","NewWindow", "scrollbars,menubar,toolbar, status,resizable,location");
  content = document.body.innerHTML;
  if(nwin != null)
  { nwin.document.write("<html><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' /></head><body>"+content+"</body></html>");
    nwin.document.close();
  }
}
</script></head><body>';
   echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';

 if(isset($demo_mode) && $demo_mode)
   echo '<script>alert("The script is in demo mode. Nothing is changed!");</script>';
 else if(isset($_POST['subject']) && ($_POST['subject'] == "emptycache"))
 { if($verbose=="true") echo "<br>Del Smarty cache";
   delTree($triplepath.'cache/smarty/cache', false, array('index.php'));
   if($verbose=="true") echo "<br>Del Smarty compile";
   delTree($triplepath.'cache/smarty/compile', false, array('index.php'));
   if($verbose=="true") echo "<br>XML cache - skipped";
   $excluders = array('index.php','default.xml','themes','.htaccess');
   $tquery="select directory, id_theme FROM ". _DB_PREFIX_."theme";
   $tres=dbquery($tquery);
   while($trow = mysqli_fetch_array($tres))
	  $excluders[] = $trow["directory"].".xml";		/* such files are in the themes directory and not at risk, but as PS does this I added these too */
//   delTree($triplepath.'config/xml', false, $excluders);  // skipped as I am not sure that PS may not change content in future versions
   // Media::clearCache() clear the caches of the templates
   mysqli_data_seek($tres, 0);
   while($trow = mysqli_fetch_array($tres))
   { /* some themes are in the database but no longer in the file system. */
	 if(!is_dir($triplepath.'themes/'.$trow["directory"]))
	 { $cquery="select id_shop, active FROM ". _DB_PREFIX_."shop WHERE id_theme=".$trow['id_theme']." ORDER BY active DESC";
       $cres=dbquery($cquery);
       if(mysqli_num_rows($cres) == 0) continue; /* no shop. this should be harmless */
	   $crow = mysqli_fetch_array($cres);
	   $active = "";
	   if($crow["active"] == "0")
		   $active = "inactive";
	   echo "<br><b>No directory found for ".$active." theme ".$trow["directory"]."</b>";
	   continue; 
	 }
	 if($verbose=="true") echo "<br>Del ".$trow["directory"]." theme cache";
	 delTree($triplepath.'themes/'.$trow["directory"].'/cache', false, array('ie9','index.php'));
   }
   dbquery("UPDATE ". _DB_PREFIX_."configuration SET value=value+1 WHERE name='PS_CCCJS_VERSION'");
   dbquery("UPDATE ". _DB_PREFIX_."configuration SET value=value+1 WHERE name='PS_CCCCSS_VERSION'"); 
   del_class_index();
   
  /* clear cache_default_attribute. They may cause faulty prices. However, normally it should stay around */
  /* See http://stackoverflow.com/questions/21694442/prestashop-product-showing-wrong-price-in-category-page-but-right-in-the-produc */
//  $query = "UPDATE "._DB_PREFIX_."product SET cache_default_attribute='0'";
//  $res = dbquery($query); 
//  $query = "UPDATE "._DB_PREFIX_."product_shop SET cache_default_attribute='0'";
//  $res = dbquery($query);  
 }
 else if(isset($_POST['subject']) && ($_POST['subject'] == "emptyimagecache"))
 { if($verbose=="true") echo "<br>Del Image cache";
   delTree($triplepath.'img/tmp', false, array('index.php'));
 }
 else if(isset($_POST['subject']) && ($_POST['subject'] == "removetranslations"))
 {  if($verbose=="true") echo "<br>Remove unused translations";
	$query = "SELECT id_lang FROM ". _DB_PREFIX_."lang ORDER BY id_lang";
	$res = dbquery($query);
	$languages = array();
	while($row = mysqli_fetch_array($res))
		$languages[] = $row['id_lang'];

	$language_tables = array();
	$query = "SHOW TABLES";
    $res = dbquery($query); 
	while($row = mysqli_fetch_array($res))
	{ $table = $row[0];
	  $tquery = "SHOW COLUMNS FROM `".$table."`"; /* we still may need to do language transformation on this table */
      $tres = dbquery($tquery); 
      if(!$tres) continue;
      while($trow = mysqli_fetch_array($tres))
      { if($trow[0] == "id_lang")
          $language_tables[] = $table;
	  }
    }
    foreach($language_tables AS $langtable)
	{ $dquery = "DELETE FROM `".$langtable."` WHERE id_lang NOT IN (".implode(",",$languages).")";
	  $dres = dbquery($dquery); 		
	}
 }
 
  function delTree($dir, $delself, $excluders = array()) 
 {  $mydir = scandir($dir);
    $files = array_diff($mydir, array('.','..','.svn'));
    if(!is_array($files)) colordie("Error scanning dir ".$dir);
    foreach ($files as $file) 
	{ if(in_array($file,$excluders)) continue;
      if (is_dir("$dir/$file")) 
		 delTree("$dir/$file", true);
	  else
		 unlink("$dir/$file"); 
    } 
	if($delself)
	  rmdir($dir); 
 } 
 
  function del_class_index()
 {   global $triplepath;
     $rootlink = realpath($triplepath."cache/class_index.php");
    /* Note: do we need here something like PS's normalizeDirectory($directory) {return rtrim($directory, '/\\').DIRECTORY_SEPARATOR;} from Prestashopautoload.php? */
	 if($rootlink && file_exists($rootlink))
	 { @chmod($rootlink, 0777); // is this needed?
	   if(unlink($rootlink))
	     echo "cleaned class index<br>";
	   else
	     echo "error cleaning the class index. Try manually deleting \cache\class_index.php<br>";
	 }
 }