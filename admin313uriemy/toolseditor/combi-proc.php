<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
if(!include 'ps_sourced_code.php') die( "ps_sourced_code.php was not found!");
$mode = "background";
$errstring = "";
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
{ $refscript = str_replace("combi-proc","combi-edit",$_SERVER['REQUEST_URI']);
  if($refscript == "")
    $refscript = "combi-edit.php";
}

$quantity_changed = false;
  
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

 if(isset($_POST['id_product_attribute']))
   echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';
 else
   echo "<br>Go back to the <a href='".$refscript."'>Combi-edit page</a><p/>";

extract($_POST);
// print_r($_POST)."<p>";

 /* get shop group and its shared_stock status */
$query="SELECT s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];
if($verbose == "true")
  echo "<br>Group=".$id_shop_group." AND share_stock=".$share_stock." ";

if($allshops == "1")
{ $shopmask = "";
  $squery="select GROUP_CONCAT(id_shop) AS allshops FROM ". _DB_PREFIX_."shop GROUP BY id_shop";
  $sres=dbquery($squery);
  $srow = mysqli_fetch_assoc($sres);
  $shoplist = $srow["allshops"];
}
else if($allshops == "2")
{ $shopmask = " AND id_shop IN (".$share_stock_shops.")";
  $shoplist = $share_stock_shops;
}
else
{ $shopmask = " AND id_shop='".$id_shop."' ";
  $shoplist = $id_shop;
}
 
 $index_checked = false;
 if(isset($demo_mode) && $demo_mode)
   echo '<script>alert("The script is in demo mode. Nothing is changed!");</script>';
 else if(isset($_POST['id_product_attribute']))
   change_rec("");
 else
 { echo $reccount." Records<p/>";
   $newdefault = 0;
   for($i=0; $i<$reccount; $i++)
   { change_rec($i);
     if((isset($GLOBALS['default_on'.$i])) && ($GLOBALS['default_on'.$i] == 1))
	     $newdefault = $i;
   }
 }
 
function change_rec($x)
{ global $id_lang, $id_shop, $id_shop_group, $errstring, $verbose, $id_product, $conn, $share_stock, $quantity_changed;
  global $shopmask, $index_checked;
  echo "*";
  if((!isset($GLOBALS['id_product_attribute'.$x])) || (!is_numeric($GLOBALS['id_product_attribute'.$x]))) {if ($verbose=="true") echo "No changes"; return;}
  echo $x.": ";
    
  $id_product_attribute = $GLOBALS['id_product_attribute'.$x];

  $paupdates = ""; /* product_attribute table */
  if(isset($GLOBALS['ean'.$x]))  
  { $ean = $GLOBALS['ean'.$x];
    if(($ean!="") && ($ean !=0) && (!is_numeric($ean))) colordie("invalid ean13 for ".$x);
    $paupdates .= " ean13='".mysqli_real_escape_string($conn, $ean)."',";
  }
  if(isset($GLOBALS['upc'.$x]))  
  { $upc = str_replace(",", ".", $GLOBALS['upc'.$x]);
    if(($upc!="") && ($upc !=0) && (!is_numeric($upc))) colordie("invalid upc");
    $paupdates .= " upc='".mysqli_real_escape_string($conn, $upc)."',";
  }
  if(isset($GLOBALS['isbn'.$x]))  
  { $isbn = str_replace(",", ".", $GLOBALS['isbn'.$x]);
    if(($isbn!="") && ($isbn !=0) && (!is_numeric($isbn))) colordie("invalid isbn");
    $paupdates .= " isbn='".mysqli_real_escape_string($conn, $isbn)."',";
  }  
  if(isset($GLOBALS['quantity'.$x]) && ($GLOBALS['quantity'.$x] != ""))
  { $quantity = str_replace(",", ".", $GLOBALS['quantity'.$x]);
    if(!is_numeric($quantity)) colordie("invalid quantity");
    $paupdates .= " quantity='".mysqli_real_escape_string($conn, $quantity)."',";
  }
  if(isset($GLOBALS['supplier_reference'.$x]))
    $paupdates .= " supplier_reference='".mysqli_real_escape_string($conn, $GLOBALS['supplier_reference'.$x])."',";
  if(isset($GLOBALS['reference'.$x]))
    $paupdates .= " reference='".mysqli_real_escape_string($conn, $GLOBALS['reference'.$x])."',";
  if(isset($GLOBALS['location'.$x]))
    $paupdates .= " location='".mysqli_real_escape_string($conn, $GLOBALS['location'.$x])."',";
  if($id_shop == '1')
  { if(isset($GLOBALS['wholesale_price'.$x]))
    { $wholesale_price = str_replace(",", ".", $GLOBALS['wholesale_price'.$x]);
      if(!is_numeric($wholesale_price)) colordie("invalid wholesale_price");
        $paupdates .= " wholesale_price='".mysqli_real_escape_string($conn, $wholesale_price)."',";
    }
    if(isset($GLOBALS['price'.$x]))
    { $price = str_replace(",", ".", $GLOBALS['price'.$x]);
      if(!is_numeric($price)) colordie("invalid price");
      $paupdates .= " price='".mysqli_real_escape_string($conn, $price)."',";
	}
	if(isset($GLOBALS['weight'.$x]))
    { $weight = str_replace(",", ".", $GLOBALS['weight'.$x]);
      if(!is_numeric($weight)) colordie("invalid weight");
      $paupdates .= " weight='".mysqli_real_escape_string($conn, $weight)."',";
	}
    if(isset($GLOBALS['default_on'.$x]) && ($GLOBALS['default_on'.$x] != 1))
    { if (_PS_VERSION_ < "1.6.1")
	    $paupdates .= " default_on='0',";
    }
  }
  if($paupdates != "")
  { $query = "UPDATE ". _DB_PREFIX_."product_attribute SET".substr($paupdates,0,strlen($paupdates)-1)." WHERE id_product='".$id_product."' AND id_product_attribute='".$id_product_attribute."'";
    dbquery($query);
  }

  $pasupdates = "";   /* product_attribute_shop table */
  if(isset($GLOBALS['minimal_quantity'.$x]))  
  { $minimalquantity = $GLOBALS['minimal_quantity'.$x];
    if(!is_numeric($minimalquantity)) colordie("invalid minimal_quantity for ".$x);
    $pasupdates .= " minimal_quantity='".mysqli_real_escape_string($conn, $minimalquantity)."',";
  }
  if(isset($GLOBALS['price'.$x]))
  { $price = str_replace(",", ".", $GLOBALS['price'.$x]);
    if(!is_numeric($price)) colordie("invalid price");
    $pasupdates .= " price='".mysqli_real_escape_string($conn, $price)."',";
  }
  if(isset($GLOBALS['wholesale_price'.$x]))
  { $wholesale_price = str_replace(",", ".", $GLOBALS['wholesale_price'.$x]);
    if(!is_numeric($wholesale_price)) colordie("invalid wholesale_price");
    $pasupdates .= " wholesale_price='".mysqli_real_escape_string($conn, $wholesale_price)."',";
  }
  if(isset($GLOBALS['weight'.$x]))
  { $weight = str_replace(",", ".", $GLOBALS['weight'.$x]);
    if(!is_numeric($weight)) colordie("invalid weight");
    $pasupdates .= " weight='".mysqli_real_escape_string($conn, $weight)."',";
  }
  if(isset($GLOBALS['ecotax'.$x]))
  { $ecotax = str_replace(",", ".", $GLOBALS['ecotax'.$x]);
    if(!is_numeric($ecotax)) colordie("invalid ecotax");
    $pasupdates .= " ecotax='".mysqli_real_escape_string($conn, $ecotax)."',";
  }
  if(isset($GLOBALS['unit_price_impact'.$x]))
  { $unit_price_impact = str_replace(",", ".", $GLOBALS['unit_price_impact'.$x]);
    if(!is_numeric($unit_price_impact)) colordie("invalid unit_price_impact");
    $pasupdates .= " unit_price_impact='".mysqli_real_escape_string($conn, $unit_price_impact)."',";
  }
  if(isset($GLOBALS['minimal_quantity'.$x]))
  { $minimal_quantity = str_replace(",", ".", $GLOBALS['minimal_quantity'.$x]);
    if(!is_numeric($minimal_quantity)) colordie("invalid minimal_quantity");
    $pasupdates .= " minimal_quantity='".mysqli_real_escape_string($conn, $minimal_quantity)."',";
  }
  if(isset($GLOBALS['available_date'.$x]))
  { $available_date = $GLOBALS['available_date'.$x];
    $parts = explode("-", $available_date);
    if(!checkdate($parts[1],$parts[2],$parts[0]))
      colordie("invalid available_date");
    $pasupdates .= " available_date='".mysqli_real_escape_string($conn, $available_date)."',";
  }
  if(isset($GLOBALS['default_on'.$x]) && ($GLOBALS['default_on'.$x] != 1))
  { if (_PS_VERSION_ < "1.6.1")
	  $pasupdates .= " default_on='0',";
  }
  if($pasupdates != "")
  { if(!isset($id_shop)) die("<p><h2>Shop must be provided!</h2>");
    $query = "UPDATE ". _DB_PREFIX_."product_attribute_shop SET".substr($pasupdates,0,strlen($pasupdates)-1)." WHERE id_product_attribute='".$id_product_attribute."'".$shopmask;
    dbquery($query);
  }

  if(isset($GLOBALS['image'.$x]))
  { $query = "SELECT * FROM ". _DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$id_product_attribute."'";
    $res = dbquery($query);
	$id_image = $GLOBALS['image'.$x];
	if(mysqli_num_rows($res) == 0)
	{ $query = "INSERT INTO ". _DB_PREFIX_."product_attribute_image SET id_product_attribute='".$id_product_attribute."', id_image='".$id_image."'";
      dbquery($query);
	}
	else 
	{ if($id_image == 0)
	  { $query = "DELETE FROM ". _DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$id_product_attribute."'";
        dbquery($query);
	  }
	  else
	  { $query = "UPDATE ". _DB_PREFIX_."product_attribute_image SET id_image='".$id_image."' WHERE id_product_attribute='".$id_product_attribute."'";
		dbquery($query);
	  }
	} 
  }
  
  if(isset($GLOBALS['quantity'.$x]) && ($GLOBALS['quantity'.$x] != ""))
  { $quantity_changed = true;
    $quantity = str_replace(",", ".", $GLOBALS['quantity'.$x]);
    if(!is_numeric($quantity)) colordie("invalid quantity");
    if($share_stock)
	{ $q_shop_group = $id_shop_group;
	  $q_shop = '0';
	}
	else
	{ $q_shop_group = '0';
	  $q_shop = $id_shop;
	}
	$query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE id_shop_group = '".$q_shop_group."' AND id_shop='".$q_shop."'";
	$query .= " AND id_product_attribute='".$id_product_attribute."' AND id_product='".$id_product."'";
	$res = dbquery($query);
	  /* the out_of_stock field determines whether ordering is allowed when stock too low: 2=not allowed 1=allowed 0=follow shop preferences */
    if(mysqli_num_rows($res) != 0) /* if found  */
	{ $query = "UPDATE ". _DB_PREFIX_."stock_available SET quantity='".$quantity."' WHERE id_shop_group ='".$q_shop_group."'";
	  $query .= " AND id_shop='".$q_shop."' AND id_product_attribute='".$id_product_attribute."' AND id_product='".$id_product."'";
	  $res = dbquery($query);
	}
	else /* now we check for the case that the id_product is different. This shouldn't happen and might indicate that it is zero */
	{ $query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE id_shop_group = '".$q_shop_group."' AND id_shop='".$q_shop."'";
	  $query .= " AND id_product_attribute='".$id_product_attribute."'";
	  $res = dbquery($query);
	  if(mysqli_num_rows($res) != 0) /* if found: update quantity and id_product */
	  { $query = "UPDATE "._DB_PREFIX_."stock_available SET quantity='".$quantity."', id_product='".$id_product."' WHERE id_shop_group ='".$q_shop_group."'";
	    $query .= " AND id_shop='".$q_shop."' AND id_product_attribute='".$id_product_attribute."'";
	    $res = dbquery($query);
	  }
	  else
	  { $query = "INSERT INTO ". _DB_PREFIX_."stock_available SET quantity='".$quantity."', id_shop_group ='".$q_shop_group."', id_shop='".$q_shop."'";
		$query .= ", id_product_attribute='".$id_product_attribute."', id_product='".$id_product."', out_of_stock='2'";
	    $res = dbquery($query);
	  }
	}
  }

  if(isset($GLOBALS['cimages'.$x]))
  { echo $x."--".$GLOBALS['cimages'.$x]."<br>";
  
    $query = "SELECT GROUP_CONCAT(CONCAT(id_image)) AS images FROM ". _DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$id_product_attribute."' GROUP BY id_product_attribute";
    $res = dbquery($query);
	$row=mysqli_fetch_array($res);
	$oldimages = explode(",",$row["images"]);
    if((sizeof($oldimages) == 1) && ($oldimages[0] == ""))
	   $oldimages = array();  /* empty array */
	$newimages = explode(",",preg_replace("/^,/","",$GLOBALS['cimages'.$x])); /* skip leading comma */
    if((sizeof($newimages) == 1) && ($newimages[0] == ""))
	   $newimages = array();  /* empty array */
	   
	$diff1 = array_diff($oldimages, $newimages); /* get images that are no longer there */
	foreach($diff1 AS $dif)
	{ $dquery = "DELETE FROM ". _DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$id_product_attribute."' AND id_image='".$dif."'";
	  $dres=dbquery($dquery);
	}
	  
	$diff2 = array_diff($newimages, $oldimages);
	foreach($diff2 AS $dif)
	{ $dquery = "INSERT INTO ". _DB_PREFIX_."product_attribute_image SET id_product_attribute='".$id_product_attribute."', id_image='".$dif."'";
      $dres=dbquery($dquery);
	}
  }
  
  if(!$index_checked)
  { $index_checked = true;
  /* prepare for re-indexing */
    if((isset($GLOBALS['ean'.$x])) || (isset($GLOBALS['upc'.$x])) || (isset($GLOBALS['reference'.$x])))
    { $query = "UPDATE ". _DB_PREFIX_."product_shop SET indexed='0' WHERE id_product='".$id_product."'".$shopmask;
      dbquery($query);
	}
  }
}



if(isset($GLOBALS['default_on0']))
{ echo "Newdefault = ".$newdefault."<br>";
  if (_PS_VERSION_ < "1.6.1")
  { $query = "UPDATE ". _DB_PREFIX_."product_attribute SET default_on='1' WHERE id_product_attribute='".$GLOBALS['id_product_attribute'.$newdefault]."'";
    dbquery($query);
	$query = "UPDATE ". _DB_PREFIX_."product_attribute_shop SET default_on='1' WHERE id_product_attribute='".$GLOBALS['id_product_attribute'.$newdefault]."'".$shopmask;
    dbquery($query);
  }
  else
  { $query = "UPDATE ". _DB_PREFIX_."product_attribute SET default_on=NULL WHERE id_product='".$id_product."'";
    dbquery($query);
    $query = "UPDATE ". _DB_PREFIX_."product_attribute SET default_on='1' WHERE id_product='".$id_product."' AND id_product_attribute='".$GLOBALS['id_product_attribute'.$newdefault]."'";
    dbquery($query);
	$query = "UPDATE ". _DB_PREFIX_."product_attribute_shop SET default_on=NULL WHERE id_product='".$id_product."'".$shopmask;
    dbquery($query);
    $query = "UPDATE ". _DB_PREFIX_."product_attribute_shop SET default_on='1' WHERE id_product='".$id_product."' AND id_product_attribute='".$GLOBALS['id_product_attribute'.$newdefault]."'".$shopmask;
    dbquery($query);
  } 
  $ruquery = "UPDATE "._DB_PREFIX_."product SET cache_default_attribute='".$GLOBALS['id_product_attribute'.$newdefault]."' WHERE id_product=".$id_product;
  $res = dbquery($ruquery); 
  $ruquery = "UPDATE "._DB_PREFIX_."product_shop SET cache_default_attribute='".$GLOBALS['id_product_attribute'.$newdefault]."' WHERE id_product=".$id_product." ".$shopmask;;
  $res = dbquery($ruquery);  
}

/* following section takes care that when individual stock quantities for the combinations are updated the total is updated too */
  if($quantity_changed)
  { if($share_stock)
	{ $q_shop_group = $id_shop_group;
	  $q_shop = '0';
	}
	else
	{ $q_shop_group = '0';
	  $q_shop = $id_shop;
	}
    $query = "SELECT SUM(quantity) AS quantsum FROM ". _DB_PREFIX_."stock_available WHERE id_shop_group = '".$q_shop_group."'";
	$query .= " AND id_shop = '".$q_shop."' AND id_product='".$id_product."' AND id_product_attribute!='0'";
    $res = dbquery($query);
	$row=mysqli_fetch_array($res);
	$quantsum = $row["quantsum"];
	
	$query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE id_shop_group = '".$q_shop_group."' AND id_shop='".$q_shop."'";
	$query .= " AND id_product_attribute='0' AND id_product='".$id_product."'";
	$res = dbquery($query);
    if(mysqli_num_rows($res) != 0) /* if found  */
	{ $query = "UPDATE ". _DB_PREFIX_."stock_available SET quantity='".$quantsum."' WHERE id_shop_group ='".$q_shop_group."'";
	  $query .= " AND id_shop='".$q_shop."' AND id_product_attribute='0' AND id_product='".$id_product."'";
	  $res = dbquery($query);
	}
    else
	{ $query = "INSERT INTO ". _DB_PREFIX_."stock_available SET quantity='".$quantsum."', id_shop_group ='".$q_shop_group."'";
	  $query .= ", id_shop='".$q_shop."', id_product_attribute='0', id_product='".$id_product."', out_of_stock='2'";
	  $res = dbquery($query);
	}
  }
  
echo "<br>";
update_shop_index(2);

if($errstring != "")
{ echo "<script>alert('There were errors: ".$errstring."');</script>!";
  echo str_replace("\n","<br>",$errstring);
}

echo "<br>Finished successfully!";
if(!isset($_POST['id_product_attribute'])) /* if submit all */
{ echo "<p>Go back to <a href='".$refscript."'>Combination Edit page</a></body></html>";
  if($verbose!="true")
    echo "<script>location.href = '".$refscript."';</script>";
}

if(isset($_POST['id_row']))
{ $row = substr($_POST['id_row'], 4);
  echo "<script>if(parent) parent.reg_unchange(".$row.");</script>";
}

function strip($txt)
{ if (get_magic_quotes_gpc()) 
   $txt = stripslashes($txt);
  return $txt;
}

