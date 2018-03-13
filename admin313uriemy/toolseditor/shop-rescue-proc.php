<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$mode = "background";

if(isset($_POST['id_lang']))
  $id_lang = strval(intval($_POST['id_lang']));
$verbose = $_POST['verbose'];
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
 else if(isset($_POST['subject']) && ($_POST['subject'] == "configuration"))
 { $id_shop = intval($_POST['id_shop']); 
   if($id_shop == 0) $id_shop = "NULL";
   $id_shop_group = intval($_POST['id_shop_group']);
   if($id_shop_group == 0) $id_shop_group = "NULL"; 
   $allowed_fieldnames = array("PS_SHOP_ENABLE","PS_REWRITING_SETTINGS","PS_ALLOW_ACCENTED_CHARS_URL","PS_DISABLE_OVERRIDES",
     "PS_SSL_ENABLED","PS_SSL_ENABLED_EVERYWHERE",
	 "PS_DISABLE_NON_NATIVE_MODULE","PS_CSS_THEME_CACHE","PS_JS_THEME_CACHE","PS_JS_DEFER","PS_HTML_THEME_COMPRESSION",
     "PS_JS_HTML_THEME_COMPRESSION","PS_HTACCESS_CACHE_CONTROL","PS_SMARTY_CACHE","PS_SMARTY_CLEAR_CACHE","PS_SMARTY_FORCE_COMPILE",
     "PS_HTACCESS_DISABLE_MULTIVIEWS","PS_HTACCESS_DISABLE_MODSEC");
   if(!in_array($_POST['fieldname'], $allowed_fieldnames))
	   colordie("Illegal fieldname ".$_POST['fieldname']);
   $cvalue = mysqli_real_escape_string($conn, $_POST['cvalue']);
   $fieldname = mysqli_real_escape_string($conn, $_POST['fieldname']); 
   $id_configuration = intval($_POST['id_configuration']);   
   if($id_configuration == "0")
   { /* the user may press enter more than once without refreshing the shop_rescue page */
	 /* the second time there will be a value and we need to update it */
     $gquery = "SELECT id_configuration FROM ". _DB_PREFIX_."configuration";
     $gquery .= ' WHERE name="'.$fieldname.'" ORDER BY id_shop_group,id_shop';
     $gres = dbquery($gquery);
	 if(mysqli_num_rows($gres)> 0)
	 { $grow = mysqli_fetch_array($gres);
	   $id_configuration = $grow["id_configuration"];
	 }
	 else
	 { $cquery="INSERT INTO ". _DB_PREFIX_."configuration";
       $cquery .= ' SET id_shop=NULL, id_shop_group=NULL, name="'.$fieldname.'", value="'.$cvalue.'", date_add=NOW(), date_upd=NOW()';
	 }
   }
   if($id_configuration != "0")
   { $cquery="UPDATE ". _DB_PREFIX_."configuration";
     $cquery .= ' SET value="'.$cvalue.'", date_upd=NOW() WHERE id_configuration='.$id_configuration;
   }
   dbquery($cquery);
   if(($fieldname == "PS_DISABLE_OVERRIDES") && ($cvalue=="1")) /* the class index should be rebuilt to exclude the overrides */
	 del_class_index();
   echo "<script>parent.change_rec('".$_POST['id_row']."');</script>Finished";
 }
 else if(isset($_POST['subject']) && ($_POST['subject'] == "resetcacheflags"))
 { $fquery = "SELECT id_product FROM ". _DB_PREFIX_."product p";
   $fquery .= ' WHERE p.cache_has_attachments=1 ';
   $fquery .= ' AND id_product NOT IN (SELECT DISTINCT id_product FROM '. _DB_PREFIX_.'product_attachment)';
   $fres = dbquery($fquery);
   if(mysqli_num_rows($fres)>0)
     echo "Products with attachement cacheflags set to 0:";
   while($frow = mysqli_fetch_array($fres))
   { $query = "UPDATE "._DB_PREFIX_."product SET cache_has_attachments='0' WHERE id_product='".$frow["id_product"]."'";
	 $res = dbquery($query); 
	 echo $frow["id_product"].", ";
   }
   $fquery = "SELECT id_product FROM ". _DB_PREFIX_."product p";
   $fquery .= ' WHERE p.cache_has_attachments=0 ';
   $fquery .= ' AND id_product IN (SELECT DISTINCT id_product FROM '. _DB_PREFIX_.'product_attachment)';
   $fres = dbquery($fquery);
   if(mysqli_num_rows($fres)>0)
     echo "Products with attachement cacheflags set to 1:";
   while($frow = mysqli_fetch_array($fres))
   { $query = "UPDATE "._DB_PREFIX_."product SET cache_has_attachments='1' WHERE id_product='".$frow["id_product"]."'";
	 $res = dbquery($query); 
	 echo $frow["id_product"].", ";
   } 
   /* now handle the cache_default_attribute: first check for products without attribute that have a value for this */
   $squery = "SELECT id_shop FROM ". _DB_PREFIX_."shop";
   $squery .= ' WHERE deleted=0 AND active=1';
   $sres = dbquery($squery);
   $shops = array();
   while($srow = mysqli_fetch_array($sres))  
      $shops[] = $srow["id_shop"];
   foreach($shops AS $shop)
   { $fquery = "SELECT id_product FROM ". _DB_PREFIX_."product_shop p";
     $fquery .= " WHERE p.cache_default_attribute!=0 AND id_shop='".$shop."'";
     $fquery .= " AND id_product NOT IN (SELECT DISTINCT id_product FROM ". _DB_PREFIX_."product_attribute WHERE id_shop='".$shop."')";
     $fres = dbquery($fquery);
     if(mysqli_num_rows($fres)>0)
       echo "Products with default attribute cacheflags set to 0:";
     while($frow = mysqli_fetch_array($fres))
     { $query = "UPDATE "._DB_PREFIX_."product_shop SET cache_default_attribute='0' WHERE id_product='".$frow["id_product"]."' AND id_shop='".$shop."'";
	   $res = dbquery($query); 
	   echo $frow["id_product"].", ";
	   if($shop == "1")
	   { $query = "UPDATE "._DB_PREFIX_."product SET cache_default_attribute='0' WHERE id_product='".$frow["id_product"]."'";
	     $res = dbquery($query); 
	   }
	 }
   }
   /* cache_default_attribute part 2: check that same default is in cache as in ps_product_attribute table */
   foreach($shops AS $shop)
   { $fquery = "SELECT id_product,cache_default_attribute FROM ". _DB_PREFIX_."product_shop";
     $fquery .= " WHERE cache_default_attribute!=0 AND id_shop='".$shop."'";
     $fres = dbquery($fquery);
     while($frow = mysqli_fetch_array($fres))
     { $query = "SELECT pas.id_product_attribute, pas.default_on FROM "._DB_PREFIX_."product_attribute_shop pas";
       $query .= " LEFT JOIN "._DB_PREFIX_."product_attribute pa on pas.id_product_attribute=pa.id_product_attribute";
       $query .= " WHERE id_shop='".$shop."' AND pa.id_product='".$frow["id_product"]."'";
	   $query .= " ORDER BY pas.default_on DESC";
	   $res = dbquery($query); 
	   if(mysqli_num_rows($res)==0)
	   { echo "unexpected end"; exit();  /* this should not happen */
	   }   
	   $row = mysqli_fetch_array($res);
	   if($row["default_on"]==0) /* nothing selected */
       { $squery = "UPDATE "._DB_PREFIX_."product_attribute_shop SET default_on='1'";
	     $squery .= " WHERE id_product_attribute='".$row["id_product_attribute"]."' AND id_shop='".$shop."'";
	     $sres = dbquery($squery); 
	     echo "attr-".$row["id_product_attribute"].", ";
	     if($shop == "1")
	     { $squery = "UPDATE "._DB_PREFIX_."product_attribute SET default_on='1'";
	       $squery .= " WHERE id_product_attribute='".$row["id_product_attribute"]."'";
		   $sres = dbquery($squery); 
	     }
	   }
	   if($frow["cache_default_attribute"] != $row["id_product_attribute"])
       { $squery = "UPDATE "._DB_PREFIX_."product_shop SET cache_default_attribute='".$row["id_product_attribute"]."'";
		 $squery .= " WHERE id_product='".$frow["id_product"]."' AND id_shop='".$shop."'";
	     $sres = dbquery($squery); 
	     echo $frow["id_product"].", ";
	     if($shop == "1")
	     { $squery = "UPDATE "._DB_PREFIX_."product SET cache_default_attribute='".$row["id_product_attribute"]."'";
		   $squery .= " WHERE id_product='".$frow["id_product"]."'";
	       $sres = dbquery($squery); 
	     }
	   }
	   echo $frow["id_product"].", ";
	 }  /* END while($frow) */
   } /* end foreach shops */
 }  /* end resetcacheflags */
 
 else if(isset($_POST['subject']) && ($_POST['subject'] == "dbrestore"))
 {  $timeout = intval($_POST['timeout']);
    if($timeout < 20) $timeout = 20;
	set_time_limit($timeout);
	if(!isset($_POST['restoredb'])) die("No database provided");
    if(!isset($_POST['restorefiles'])) die("No files provided");
	if(isset($_POST['skipstats'])) $skipstats = true; else $skipstats = false;
	if(isset($_POST['savesql'])) $savesql = true; else $savesql = false;	
    if(file_exists("tmp"))
    { if(!is_dir("tmp")) colordie("Cannot create tmp directory in Prestools directory as there is a file with the same name");
      $files = glob('tmp/*'); // get all file names
      foreach($files as $file)
		unlink($file); // delete file
    }
    else 
	   $newdir = mkdir("tmp");
    $db = $_POST['restoredb'];
    $filesdir = $_POST['restorefiles'];
    
    $backuppath = "../autoupgrade/backup";
    $files = scandir($backuppath."/".$filesdir);
    foreach($files AS $file)
    { if(($file == ".") || ($file=="..")) continue;
	  if(is_dir($backuppath."/".$filesdir."/".$file)) continue;
	  if(substr($file,0,4) != "auto") continue;
	 
	  $dot_pos = strrpos($file, '.');
	  $fileext = substr($file, $dot_pos+1);
	  $newfilename = substr($file, 0, $dot_pos-1);
	  $content = '';
	  echo "<br>".$file." ";
	 /* Note: I tried bzopen() and bzread() but at least on Windows it doesn't work. */
	  if ($fp = fopen($backuppath."/".$filesdir."/".$file, 'r'))
	  { while(!feof($fp))
		  $content .= fread($fp, 4096);
        fclose($fp);
	  }
	  if (empty($content)) colordie("Error reading file");
	  switch ($fileext)
	  { case 'bz': case 'bz2':
	      $data = bzdecompress($content);
	      break;
        case 'gz':
	      $data = gzuncompress($content);
	      break;
	  }
	  file_put_contents("tmp/".$newfilename, $data);
    }
    dbquery("USE ".$db);
	$res = dbquery("SHOW TABLES");
	if(mysqli_num_rows($res) != 0) colordie("Table ".$db." is not empty!");
	
    $files = scandir("tmp"); /* scandir is defined to keep alphabetical order */
    foreach($files AS $file)
    { if(($file == ".") || ($file=="..")) continue;
	  if(is_dir("tmp/".$file)) continue;
	  if(substr($file,0,4) != "auto") continue;
	  $data = file_get_contents("tmp/".$file);
      $listQuery = preg_split('/;[\n\r]+/Usm', $data);
      foreach($listQuery AS $cmd)
      { if(trim($cmd) == "") continue;
	    $cmd = ltrim($cmd); /* sometimes there are spaces before "insert into" */
	    if($skipstats && (!isset($prefix)) && (substr($cmd, 0, 12) == "INSERT INTO "))
		{ $pos = strpos($cmd, " ", 13);
		  if(substr($cmd, $pos-7, 6) == "access")
		  { $prefix = substr($cmd, 13, $pos-20);
			$start = 13+strlen($prefix);
		  }
		}
		if($skipstats && (substr($cmd, 0, 12) == "INSERT INTO ") 
			&& ((substr($cmd, $start, strlen("connections`")) == "connections`")
			|| (substr($cmd, $start, strlen("connections_source`")) == "connections_source`")
			|| (substr($cmd, $start, strlen("guest`")) == "guest`")
			|| (substr($cmd, $start, strlen("page_viewed`")) == "page_viewed`")))
		{ 
		  continue;
		}
		dbquery($cmd);
	  }
    }
	if(!$savesql)
	{ $files = glob('tmp/*'); // get all file names
      foreach($files as $file)
		unlink($file); // delete file
	}
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
	     echo "error cleaning the class index. Try manually deleting /cache/class_index.php<br>";
	 }
 }

 /*
 function delete_directory($dirname, $excluders)
 { $dir = realpath($triplepath.$dirname);
   if(!$dir)
     colordie("Invalid directory ".$dirname);
   $files = scandir($dir);
   if(!$files) 
	   colordie("Empty dir ".$dirname);
   foreach ($files AS $file)
   { if(($file=="..") || ($file==".") || ($file == '.svn') || ($file=="index.php")) continue;
	 @chmod($rootlink, 0777); // is this needed?
	 if(unlink($rootlink))
   }
 }
 */
