<?php 
/* This is a simple script that I used to check how my website uses the diskspace at my provider: it creates two tables in your database */
/* the script has two modes: stand alone and embedded */
if(isset($_POST["embedded"]) &&($_POST["embedded"] == "1"))
	$modus = "embedded";
else
	$modus = "standalone";
if(!@include 'approve.php') die( "approve.php was not found!");

set_time_limit(300); /* 5 minutes: change this when needed */

$legacy_images = get_configuration_value('PS_LEGACY_IMAGES');
if($legacy_images)
	colordie("This script doesn't work with the legacy image configuration.");

/* by dropping the table we make sure that changes in table layout after an update are implemented */
if((isset($_GET["reset"])) || (isset($_POST["reset"])))
{ $query = 'DROP TABLE IF EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'diskspace';
  $res = dbquery($query);
  $query = 'DROP TABLE IF EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace';
  $res = dbquery($query);
  if($modus == "standalone")
  { header('Location: diskspace.php?new=1'); /* remove the "reset=1" part so that page refresh doesn't cause problems */
    exit();
  }
}
$create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'diskspace( dirname VARCHAR(200) NOT NULL, filecount INT NOT NULL, dircount INT NOT NULL, totsize BIGINT NOT NULL, PRIMARY KEY(dirname))';
$create_tbl = dbquery($create_table);
$create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace( id_image INT NOT NULL, id_product INT, sourcename VARCHAR(200), productname VARCHAR(200), active INT, filecount INT, sourcesize INT, totsize INT, filenames VARCHAR(500), PRIMARY KEY(id_image))';
$create_tbl = dbquery($create_table);

  /* get default language: we use this for the categories, manufacturers */
  $query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
  $res=dbquery($query);
  if(mysqli_num_rows($res)==0) colordie("<h1>No default language available!</h1>");
  $row = mysqli_fetch_array($res);
  $id_lang = $row['value'];

if($modus == "standalone")
{ echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Product Diskspace Analysis</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script>
function delete_images()
{ if(window.confirm("Do you really want to delete all unused images and move their base img to the /img/tmp directory?"))
  { delform.delkey.value="greatmystery";	
    delform.action="TE_plugin_cleanup_images.php";
    delform.submit();
  }
}
</script>
</head><body>';

  print_menubar();
//  echo date('H:i:s', time())." time1=".time()."<br>";

  echo '<center><b><font size="+1">Overview of diskspace use</font></b></center>';
  echo '<table border=1 style="border-collapse: collapse;"><tr><td>The results of this function are stored in the database. <br>
  This function takes a long time (up to 6 minutes) to gather its data. If you get a timeout you should refresh the page to finish.<br>
  During execution the program will produce data about what it is doing. You should do a final refresh to get a clean set of tables. 
  The first table will then start a few lines below this block and the script will finish within seconds.<br>
  To empty the database tables of this script and start from scratch run: diskspace.php?reset=1</td></td></table>';

  $query= 'SELECT COUNT(*) AS dskcount FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'diskspace';
  $result = dbquery($query);
  $row = mysqli_fetch_array($result);
  if($row["dskcount"] == 0)
  {
    $subdirs = array();
    $total_size = $total_files = $total_dirs = 0;
    $mydir = dir($triplepath);
    while(($file = $mydir->read()) !== false) 
	{ if(is_dir($triplepath.$file))
      { if(($file != ".") && ($file != ".."))
        { $subdirs[] = $file;
          $total_dirs++;
	    }
      }
      else
      { $total_size += filesize($triplepath.$file);
        $total_files++;
      }
    }

    $query = "REPLACE INTO "._DB_PREFIX_._PRESTOOLS_PREFIX_."diskspace (dirname,filecount,dircount,totsize) VALUES ('root','".$total_files."','".$total_dirs."','".$total_size."')";
    $result = dbquery($query);
    echo "Root: ".$total_files." files - size: ".number_format($total_size)."<br>";

    foreach($subdirs AS $subdir)
    { // if($subdir == "img") continue;
      $total_files = $total_dirs = 0;
      $total_size = foldersize($triplepath.$subdir);
      $query = "REPLACE INTO "._DB_PREFIX_._PRESTOOLS_PREFIX_."diskspace (dirname,filecount,dircount,totsize) VALUES ('".$subdir."','".$total_files."','".$total_dirs."','".$total_size."')";
      $result = dbquery($query);

      echo $subdir.": ".$total_files." files, ".$total_dirs." dirs, ".number_format($total_size)." bytes<br>";
    }

//    echo (time() - $time1)." seconds passed";

    /* Now the IMG directory */

    $subdirs = array();
    $total_size = $total_files = $total_dirs = 0;
    $mydir = dir($triplepath."img/");
    while(($file = $mydir->read()) !== false) 
    { if(is_dir($triplepath."img/".$file))
      { if(($file != ".") && ($file != ".."))
        { $subdirs[] = $file;
          $total_dirs++;
	    }
      }
      else
      { $total_size += filesize($triplepath."img/".$file);
        $total_files++;
      }
    }

    $query = "REPLACE INTO "._DB_PREFIX_._PRESTOOLS_PREFIX_."diskspace (dirname,filecount,dircount,totsize) VALUES ('img-root','".$total_files."','".$total_dirs."','".$total_size."')";
    $result = dbquery($query);
    echo "<p>IMG-Root: ".$total_files." files - size: ".number_format($total_size)."<br>";

    foreach($subdirs AS $subdir)
    { // if($subdir == "p") continue;
      $total_files = $total_dirs = 0;
      $total_size = foldersize($triplepath."img/".$subdir);
      $query = "REPLACE INTO "._DB_PREFIX_._PRESTOOLS_PREFIX_."diskspace (dirname,filecount,dircount,totsize) VALUES ('img-".$subdir."','".$total_files."','".$total_dirs."','".$total_size."')";
      $result = dbquery($query);

      echo "img-".$subdir.": ".$total_files." files, ".$total_dirs." dirs, ".number_format($total_size)." bytes<br>";
    }  
    echo "Main data finished:<br>";
  }
} /* end if mode == "standalone" */

if($modus == "embedded")
  echo "\n<script>if(parent) parent.clear_reset();</script>";

$imageroot = $triplepath."img/p/";
$query = 'SELECT * FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace WHERE id_image="999999999"';
$result = dbquery($query);
if(mysqli_num_rows($result) == 0)
{ $total_files = $total_size = $last_image = 0;
  $query = 'SELECT SUM(filecount) AS files, SUM(totsize) AS size,MAX(id_image) AS max_image';
  $query .= ' FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace';
  $result = dbquery($query);
  $row = mysqli_fetch_array($result);
  $total_files = $row["files"];
  $total_size = $row["size"];
  $last_image = $row["max_image"];
  $imgpath = array();
  for($i=0; $i<10;$i++)
	  $imgpath[$i] = 0;
  for ($i=0; $i<strlen($last_image); $i++)
  { $imgpath[$i] = substr($last_image,$i,1);
  }
 
  analyze_folder($imageroot, 0);
  echo "<br>";
  $query = "INSERT INTO "._DB_PREFIX_._PRESTOOLS_PREFIX_."imgspace SET id_image='999999999'"; /* insert an end marker */
  $result = dbquery($query);
  if($modus == "standalone")
    echo "Collecting image id's finished: ".(time() - $time1)." seconds passed<br>";
}
else if($modus == "standalone") echo "skipped collecting image id's<br>";

//echo date('H:i:s', time())." time2=".time()."<br>";
$query = 'SELECT * FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace WHERE id_image="999999999"';
$result = dbquery($query);
$row = mysqli_fetch_array($result);
if($row["id_product"] != "777777777")
{ 
  $query = "SELECT i.id_image, i.id_product, ps.active, name FROM "._DB_PREFIX_."image i";
  $query .= " LEFT JOIN "._DB_PREFIX_."product_shop ps ON i.id_product=ps.id_product";
  $query .= " LEFT JOIN "._DB_PREFIX_."product_lang pl ON i.id_product=pl.id_product AND ps.id_shop=pl.id_shop";
  $query .= " LEFT JOIN "._DB_PREFIX_._PRESTOOLS_PREFIX_."imgspace ix ON i.id_image=ix.id_image";  
  $query .= " WHERE pl.id_lang='".$id_lang."' AND ix.id_product IS NULL";
  $query .= " GROUP BY i.id_image"; /* for multi-shop */
  $result = dbquery($query);
  if(($modus == "standalone") || (mysqli_num_rows($result) > 100))
    echo "Setting product data for ".mysqli_num_rows($result)." rows<br>";
  $imgcounter = 0;
  while($row = mysqli_fetch_array($result))
  { $subquery = "UPDATE "._DB_PREFIX_._PRESTOOLS_PREFIX_."imgspace SET id_product='".$row["id_product"]."', productname='".mysqli_real_escape_string($conn,$row["name"])."', active='".$row["active"]."' WHERE id_image = '".$row["id_image"]."'";
    $subresult = dbquery($subquery);
	if(($modus == "standalone") && (mysqli_affected_rows($conn) == 0))
		echo "Image ".$row["id_image"]." for product ".$row["id_product"]." (".$row["name"].") does not exist<br>";
	if(!(++$imgcounter % 10)) echo "*";
	if(!($imgcounter % 2000)) echo "<br>";
  }
  $query = "UPDATE "._DB_PREFIX_._PRESTOOLS_PREFIX_."imgspace SET id_product='777777777' WHERE id_image = '999999999'";
  $result = dbquery($query);
  if($modus == "standalone") echo "Collecting product data finished<br>";
}
else if($modus == "standalone") echo "skipped collecting product data<br>";

// Now start displaying the data
// First general table
if($modus == "standalone")
{ $totalsize = $totfilecount = $totdircount = 0;
  $query = 'SELECT * FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'diskspace';
  $result = dbquery($query);
  echo "<table border=1><tr><td>dirname</td><td>filecount</td><td>dircount</td><td>totsize</td></tr>";
  while($row = mysqli_fetch_array($result))
  { echo "<tr><td>".$row["dirname"]."</td><td align=right>".$row["filecount"]."</td><td align=right>".$row["dircount"];
	echo "</td><td align=right>".number_format($row["totsize"])."</td></tr>";
    if(substr($row["dirname"],0,4) != "img-")
    { $totalsize += $row["totsize"];
      $totfilecount += $row["filecount"];
	  $totdircount += $row["dircount"];
    }
  }
  echo "<tr><td></td></tr>";
  echo "<tr><td>Total</td><td align=right>".$totfilecount."</td><td align=right>".$totdircount."</td><td align=right>".number_format($totalsize)."</td></tr>";
  echo "</table><p>";
}
// now display images without product
// See here for a suggestion how to delete them: https://www.prestashop.com/forums/topic/383776-how-to-remove-images-that-are-not-anymore-exist-in-products/
$totalsize = $filecount = $imgcount = 0;
$query = 'SELECT count(*) AS imgcount, sum(totsize) AS totalsize FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace WHERE id_product IS NULL AND sourcename!="DIRONLY"';
$result = dbquery($query);
$xrow = mysqli_fetch_array($result);
echo $xrow["imgcount"]." different images without product (".$xrow["totalsize"]." bytes)";
$dquery = 'SELECT * FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace WHERE id_product IS NULL AND sourcename="DIRONLY"';
$dresult = dbquery($dquery);
echo " and ".mysqli_num_rows($dresult)." empty image directories<br>";
$query = 'SELECT * FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace WHERE id_product IS NULL';
$result = dbquery($query);
if($modus == "standalone")
{ echo "<table border=1><tr><td colspan=4>Images without product</td></tr>";
  echo "<tr><td>image</td><td>filecount</td><td>totsize</td><td>image</td><td>id_product</td><td>image legend</td></tr>";
  while($row = mysqli_fetch_array($result))
  { if($row["sourcename"] == "DIRONLY") continue;
    echo "<tr><td>".$row["id_image"]."</td><td align=right>".$row["filecount"]."</td><td align=right>";
	echo number_format($row["totsize"])."</td><td>".get_product_image(0,$row["id_image"],$row["id_image"])."</td>";
    $iquery = 'SELECT id_product FROM '._DB_PREFIX_.'image WHERE id_image='.$row["id_image"];
    $ires = dbquery($iquery);
    if(mysqli_num_rows($ires)>0)
    { $irow = mysqli_fetch_array($ires);
      echo "<td>.".$irow["id_product"]."</td>";
    }
    else
	  echo "<td></td>";
    $iquery = 'SELECT legend FROM '._DB_PREFIX_.'image_lang WHERE id_image='.$row["id_image"]." AND id_lang=".$id_lang;
    $ires = dbquery($iquery);
    if(mysqli_num_rows($ires)>0)
    { $irow = mysqli_fetch_array($ires);
      echo "<td>".$irow["legend"]."</td>";
    }
    else
	  echo "<td></td>";
    echo "</tr>";
    $totalsize += $row["totsize"];
    $filecount += $row["filecount"];
    $imgcount++;
  }
  echo "<tr><td>".$imgcount." images</td><td align=right>".$filecount."</td><td align=right>".number_format($totalsize)."</td><td></td></tr>";
  echo "</table><p>";
}
else /* if($modus == "embedded") */
{ $imgctr = 0;
  while($row = mysqli_fetch_array($result))
  { if($row["sourcename"] == "DIRONLY") continue;
    echo get_product_image(0,$row["id_image"],$row["id_image"]);
	if(++$imgctr > 1000 ) 
	{ echo "<br>Not all images were displayed";
	  break;
	}
  }
  mysqli_data_seek($result, 0);
  echo "<br>Image directories without content: ";
  $imgctr = 0;
  while($row = mysqli_fetch_array($result))
  { if($row["sourcename"] != "DIRONLY") continue;
    echo $row["id_image"].", ";
	if(++$imgctr > 1000 ) 
	{ echo "<br>Not all directories were listed";
	  break;
	}
  }
}

if($modus == "embedded")
  echo "\n<script>if(parent) parent.enable_delbutton();</script>";

if($modus == "standalone")
{ echo "<form name=delform method=post><input type=hidden name=delkey></form>";
  echo "<table border=1><tr><td colspan=2>Delete Images without Product?</td></tr>";
  if(file_exists("TE_plugin_cleanup_images.php"))
    echo '<tr><td>Delete '.$filecount.' Unused Images? </td><td style="text-align:right"><button onclick="delete_images(); return false;">Delete Unused Images</button></td></tr>';
  else 
    echo '<tr><td colspan=2>For this function you need to buy a plugin at <a href="https://www.prestools.com/12-prestools-suite-plugins">Prestools.com</a></td></tr>';
  echo "<tr><td colspan=2>The base files of the deleted images will be transfered to the \\img\\tmp directory of your shop.
    You can delete them there if you want.</td></tr></table><p>";
   
// now look how many is occupied by inactive products
  $query = 'SELECT count(*) AS imgcount, SUM(filecount) AS files, COUNT(DISTINCT id_product) AS prodcount, SUM(totsize) AS size';
  $query .= ' FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace WHERE active=0 GROUP BY active LIMIT 50';
  $result = dbquery($query);  
  echo "<table border=1><tr><td colspan=4>Images from inactive products</td></tr>";
  echo "<tr><td>image count</td><td>product count</td><td>total files</td><td>total size</td></tr>";
  $row = mysqli_fetch_array($result);
  echo "<tr><td>".$row["imgcount"]."</td><td>".$row["prodcount"]."</td><td align=right>".$row["files"]."</td><td align=right>".number_format($row["size"])."</td></tr>";
  echo "</table><p>";

// now show the products that consume most space
  $query = 'SELECT id_product, productname, count(*) AS imgcount, SUM(filecount) AS files, SUM(totsize) AS size';
  $query .= ' FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'imgspace WHERE active=0 GROUP BY id_product ORDER BY size DESC LIMIT 150';
  $result = dbquery($query);
  echo "<table border=1><tr><td colspan=5>Images from inactive products</td></tr>";
  echo "<tr><td>id_product</td><td>productname</td><td>image count</td><td>total files</td><td>total size</td></tr>";
  while($row = mysqli_fetch_array($result))
  { echo "<tr><td>".$row["id_product"]."</td><td>".$row["productname"]."</td><td align=right>".$row["imgcount"]."</td><td align=right>".number_format($row["files"])."</td><td align=right>".number_format($row["size"])."</td></tr>";
  }
  echo "</table><p>";
}


/* analyze picture folder */
$imgcounter=0;
function analyze_folder($path, $level)
{ global $total_files, $total_size, $imageroot, $triplepath, $modus, $imgpath, $imgcounter;
  $filecount = 0;
  $sourcesize = 0;
  $sourcename = "";
  $hasdirs = false;
  $filenames = array();
  $totsize = 0;
  $files = scandir($path);
  $cleanPath = rtrim($path, '/'). '/';
  foreach($files as $t) {
        if (($t<>".") && ($t<>"..")) {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
				if($t < $imgpath[$level]) continue; /* skip dirs that were scanned previously */
				$imgpath[$level] = 0;
				$hasdirs = true;
                $size = analyze_folder($currentFile, $level+1);
                $total_size += $size;
            }
            else {
				if($path == $imageroot) continue;
                $size = filesize($currentFile);
                $total_size += $size;
				$total_files++;
				if($t == "index.php") continue;
				$totsize += $size;
				$filecount++;
				$filenames[] = $t;
				if(!strpos($t,"-"))
				{ $sourcename = $t;
				  $sourcesize = $size;
				}
            }
        }   
    }
  if($filecount == 0)
  { if(!$hasdirs)
	{ $id_image = str_replace("/","",substr($path,strlen( $triplepath."img/p/")));
	  if($modus == "standalone")
        echo "No image ".$id_image." in ".$path."<br>";
	  $query = "REPLACE INTO "._DB_PREFIX_._PRESTOOLS_PREFIX_."imgspace (id_image, sourcename, filecount, sourcesize, totsize, filenames) ";
	  $query .= "VALUES ('".$id_image."','DIRONLY','0','0','0','')";
	  $result = dbquery($query);
	}
    return; /* these are directories without image but with subdirectories */
  }
  $id_image = substr($sourcename,0,strpos($sourcename, "."));
  if(!is_numeric($id_image))
  { if($modus == "standalone")
      echo "skipping non-numeric image ".$sourcename."<br>";
	return;  
  }
  $query = "REPLACE INTO "._DB_PREFIX_._PRESTOOLS_PREFIX_."imgspace (id_image, sourcename, filecount, sourcesize, totsize, filenames) ";
  $query .= "VALUES ('".$id_image."','".$sourcename."','".$filecount."','".$sourcesize."','".$totsize."','".implode(",",$filenames)."')";
  $result = dbquery($query);
  if($id_image != "")
  { if($modus == "standalone")
	{ echo $id_image.", ";
	  if(!(++$imgcounter % 30)) echo "<br>";
	}
	else
	{ if(!(++$imgcounter % 10)) echo ".";
	  if(!($imgcounter % 2000)) echo "<br>";
	}
  }
}
// id_image INT NOT NULL, id_product INT, sourcename VARCHAR(200), productname VARCHAR(200), active INT, filecount INT, sourcesize INT, totsize, filenames

function foldersize($path) {
    global $total_files, $total_dirs, $modus;
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/'). '/';

    foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
				$total_dirs++;
            }
            else {
                $size = filesize($currentFile);
                $total_size += $size;
				$total_files++;
            }
        }   
    }
    return $total_size;
}
   
?>