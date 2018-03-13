 <?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$mode = "background";
//print_r($_POST);

 /* Get the arguments */
if(!isset($_POST['id_lang']))
{ echo "No language";
  return;
}
$id_lang = strval(intval($_POST['id_lang']));

if(isset($_POST['id_shop']))
  $id_shop = strval(intval($_POST['id_shop']));
else	
  colordie("No shop provided");

if(isset($_POST['id_product']))
  $id_product = strval(intval($_POST['id_product']));
else	
  colordie("No product provided");
  
if(isset($_SERVER['HTTP_REFERER']))
  $refscript = $_SERVER['HTTP_REFERER'];
else
{ $refscript = str_replace("image-proc","image-edit",$_SERVER['REQUEST_URI']);
  if($refscript == "")
    $refscript = "image-edit.php";
}
  
echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<script>
function newwin()
{ nwin = window.open("","_blank", "scrollbars,menubar,toolbar, status,resizable,location");
  content = document.body.innerHTML;
  if(nwin != null)
  { nwin.document.write("<html><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' /></head><body>"+content+"</body></html>");
    nwin.document.close();
  }
}
</script></head><body>';

extract($_POST);
 
/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];
  
echo $reccount." Records<br/>";
 if(isset($demo_mode) && $demo_mode)
   echo '<script>alert("The script is in demo mode. Nothing is changed!");</script>';
 else if(isset($_POST['id_image']))
 { echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';
   change_rec("");
 }
 else
 { echo "<br>Go back to the <a href='".$refscript."'>Image-edit page</a><p/>";
   echo $reccount." Records<br/>";
   $newcover = 0;
   for($i=0; $i<$reccount; $i++)
   { change_rec($i);
     if((isset($GLOBALS['cover'.$i])) && ($GLOBALS['cover'.$i] == 1))
	     $newcover = $i;
   }
 }
 
function change_rec($x)
{ global $id_lang, $id_shop, $id_shop_group, $errstring, $verbose, $id_product, $conn;
  echo "*";
  if((!isset($GLOBALS['id_image'.$x])) || (!is_numeric($GLOBALS['id_image'.$x]))) {if ($verbose=="true") echo "No changes"; return;}
  echo $x.": ";
    
  $id_image = $GLOBALS['id_image'.$x];
  
  if(isset($GLOBALS['position'.$x]))  
  { $position = $GLOBALS['position'.$x];
    if(!is_numeric($position)) colordie("invalid position for ".$x);
    $query = "UPDATE ". _DB_PREFIX_."image SET position='".mysqli_real_escape_string($conn, $position)."' WHERE id_product='".$id_product."' AND id_image='".$id_image."'";
    dbquery($query);
  }
  
  if ((_PS_VERSION_ < "1.6.1") && (isset($GLOBALS['cover'.$x])) && ($GLOBALS['cover'.$x] != '1'))  
  { $query = "UPDATE ". _DB_PREFIX_."image SET cover='0' WHERE id_product='".$id_product."' AND id_image='".$id_image."'";
	dbquery($query);
	$query = "UPDATE ". _DB_PREFIX_."image_shop SET cover='0' WHERE id_image='".$id_image."'";
	dbquery($query);
  }
  
  if(isset($GLOBALS['legend'.$x]))  
  { $legend = $GLOBALS['legend'.$x];
    $legend = preg_replace('/[<>={}]+/', '', $legend);
    $query = "UPDATE ". _DB_PREFIX_."image_lang SET legend='".mysqli_real_escape_string($conn, $legend)."' WHERE id_image='".$id_image."' AND id_lang='".$id_lang."'";
    dbquery($query);
  }
}

if(isset($GLOBALS['cover0']))
{ echo "New cover = ".$newcover."<br>";
  if (_PS_VERSION_ < "1.6.1")
  { $query = "UPDATE ". _DB_PREFIX_."image SET cover='1' WHERE id_product='".$id_product."' AND id_image='".$GLOBALS['id_image'.$newcover]."'";
	dbquery($query);
	$query = "UPDATE ". _DB_PREFIX_."image_shop SET cover='1' WHERE id_image='".$GLOBALS['id_image'.$newcover]."'";
    dbquery($query);
  }
  else
  { $query = "UPDATE ". _DB_PREFIX_."image SET cover=NULL WHERE id_product='".$id_product."'";
    dbquery($query);
    $query = "UPDATE ". _DB_PREFIX_."image SET cover='1' WHERE id_product='".$id_product."' AND id_image='".$GLOBALS['id_image'.$newcover]."'";
    dbquery($query);
	$query = "UPDATE ". _DB_PREFIX_."image_shop SET cover=NULL WHERE id_product='".$id_product."' AND id_shop='".$id_shop."'";
    dbquery($query);
    $query = "UPDATE ". _DB_PREFIX_."image_shop SET cover='1' WHERE id_product='".$id_product."' AND id_image='".$GLOBALS['id_image'.$newcover]."' AND id_shop='".$id_shop."'";
    dbquery($query);
  } 
}

if($errstring != "")
{ echo "<script>alert('There were errors: ".$errstring."');</script>!";
  echo str_replace("\n","<br>",$errstring);
}

echo "<br>Finished successfully!";
if(!isset($_POST['id_image'])) /* if submit all */
  echo "<p>Go back to <a href='".$refscript."'>Product Image Edit page</a></body></html>";
  
if($verbose!="true")
{ echo "<script>location.href = '".$refscript."';</script>";
}
  
function strip($txt)
{ if (get_magic_quotes_gpc()) 
   $txt = stripslashes($txt);
  return $txt;
}

?>
