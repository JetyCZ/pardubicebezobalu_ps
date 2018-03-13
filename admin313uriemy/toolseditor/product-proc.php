<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
if(!include 'ps_sourced_code.php') die( "ps_sourced_code.php was not found!");
$mode = "background";
//print_r($_POST);
if(!isset($_POST["id_lang"]))
  $_POST = $_GET;
set_time_limit ( 60 );
 /* Get the arguments */
if(!isset($_POST['id_lang']))
{ echo "No language";
  return;
}
$id_lang = strval(intval($_POST['id_lang']));

/* keep track which packs are loaded: needed for post-processing */
$carrierpack_loaded = $tagspack_loaded = $supplierpack_loaded = $discountpack_loaded = $featurespack_loaded = $virtualpack_loaded = false;
$tags_changed = array();

if(isset($_POST['id_shop']))
  $id_shop = strval(intval($_POST['id_shop']));
else	/* this happens only with sort. We set id_shop at 1 to prevent error message with the shared_stock query */
  $id_shop = '1'; 
$changed_categories = array();
$valid_products = array(); // used for accessories
$invalid_products = array(); // used for accessories
$deleted_tags = array();
$errstring = "";
$updateblock = array();  /* this will be sent back with rowsubmit */

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

if(isset($_POST['urlsrc']) && ($_POST['urlsrc'] != "")) // note that for security reason we disabled the referrer [for some browsers] in product-edit
{ $refscript = $_POST['urlsrc'];
}
else if((isset($_SERVER['HTTP_REFERER'])) && ($_SERVER['HTTP_REFERER'] != ""))
  $refscript = $_SERVER['HTTP_REFERER'];
else
{ $refscript = "";
}
if(strpos($refscript,"product-sort"))
  $srcscript = "product-sort";
else if((isset($_POST['methode'])) && ($_POST['methode'] == "vissort"))
  $srcscript = "product-vissort";
else
  $srcscript = "product-edit";
if($refscript == "")
{ $refscript = str_replace("product-proc","product-edit",$_SERVER['REQUEST_URI']);
  if($refscript == "")
    $refscript = "product-edit.php";
}

  extract($_POST);
  
  if(($srcscript == "product-vissort") || ($srcscript == "product-sort"))
  { $id_category = intval($id_category); /* doing it here is optimization */
	if(!isset($skipindexation)) $skipindexation = "on";
  }
  else
	if(!isset($skipindexation)) $skipindexation = "off"; 

 if(isset($_POST['submittedrow']))
   echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';
 else
 { echo "<br>Go back to <a href='".$refscript."'>".$srcscript." page</a><p/>".$reccount." Records";
   if(isset($_GET['d']))
     echo " - of which ".$_GET["d"]." submitted.<br/>";
 }
 
/* get list of features */
$query = "SELECT id_feature, name FROM ". _DB_PREFIX_."feature_lang";
$query .= " WHERE id_lang='".$id_lang."'";
$query .= " ORDER BY id_feature";
$res = dbquery($query);
$features = array();
while($row = mysqli_fetch_array($res))
  $features[$row['id_feature']] = $row['name'];

/* get list of language id's */
$query = "SELECT id_lang FROM ". _DB_PREFIX_."lang";
$res = dbquery($query);
$languages = array();
while($row = mysqli_fetch_array($res))
  $languages[] = $row['id_lang'];
  
/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];
if($share_stock)
{ $gquery="select GROUP_CONCAT(id_shop) AS shared_shops FROM ". _DB_PREFIX_."shop WHERE id_shop_group='".$id_shop_group."' GROUP BY id_shop_group";
  $gres=dbquery($gquery);
  $grow = mysqli_fetch_array($gres);
  $share_stock_shops = $grow['shared_shops'];
  if ($verbose=="true") echo "Shared stock for shops ".$share_stock_shops."<br>";
}
if($share_stock)
  $wherestock = "id_shop_group = '".$id_shop_group."'";		
else
  $wherestock = "id_shop = '".$id_shop."'";

$squery="select GROUP_CONCAT(id_shop) AS allshops FROM ". _DB_PREFIX_."shop";
$sres=dbquery($squery);
$srow = mysqli_fetch_assoc($sres);
$myshops = $srow["allshops"];

if(isset($allshops)) /* not set for product-sort */
{ if($allshops == "1")
  { $shopmask = "";
	$shoplist = $myshops;
  }
  else if($allshops == "2")
  {	$shopmask = " AND id_shop IN (".$share_stock_shops.")";
    $shoplist = $share_stock_shops;
  }
  else
  {	$shopmask = " AND id_shop='".$id_shop."' ";
    $shoplist = $id_shop;
  }  
}

/* prepare for catalog rules */
$modified_products_for_cpr = array(); /* for catalogue price rules */
$changed_cr_fields = array();
$changed_cr_fields["category"] = $changed_cr_fields["manufacturer"] = $changed_cr_fields["supplier"] = $changed_cr_fields["features"] = false;

 if(isset($demo_mode) && $demo_mode)
   echo '<script>alert("The script is in demo mode. Nothing is changed!");</script>';
 else if(isset($_POST['submittedrow']))
   change_rec($_POST['submittedrow']); 
 else
 { for($i=0; $i<$reccount; $i++)
     change_rec($i);
 }

function change_rec($x)
{ global $id_lang, $id_shop, $changed_categories, $features, $languages, $id_shop_group;
  global $valid_products, $invalid_products, $share_stock, $share_stock_shops, $myshops;
  global $updateblock, $errstring, $verbose, $deleted_tags, $conn, $srcscript, $id_category;
  global $modified_products_for_cpr, $changed_cr_fields, $shopmask,$shoplist,$wherestock, $triplepath;
  global $carrierpack_loaded, $tagspack_loaded, $supplierpack_loaded, $discountpack_loaded, $virtualpack_loaded;
  global $featurespack_loaded;
  
  echo "*";
  if((!isset($GLOBALS['id_product'.$x])) || (!is_numeric($GLOBALS['id_product'.$x]))) {if ($verbose=="true") echo "No changes"; return;}
  echo $x.": ";
  $modified_products_for_cpr[] = $id_product = $GLOBALS['id_product'.$x];

  $deleted_categories = $added_categories = array();
  if(isset($GLOBALS['mycats'.$x]))  /* my categories */
  { $changed_cr_fields["category"] = true;
    $cquery = "select id_category, position from ". _DB_PREFIX_."category_product WHERE id_product='".$GLOBALS['id_product'.$x]."' ORDER BY id_category";
	$cres=dbquery($cquery);
	$carray = array();
	$parray = array();
	while ($crow=mysqli_fetch_array($cres)) 
	{  $carray[] = $crow['id_category'];
	   $parray[$crow['id_category']] = $crow['position']; /* can be used for optimizations */
	}
	$mycats = substr($GLOBALS['mycats'.$x], 1); /* remove leading comma */
	$mycat_arr = explode(",", $mycats);
	if(sizeof($mycat_arr) == 0) return; /* extra safety valve to prevent deleting all categories */

	$deleted_categories = array_diff($carray, $mycat_arr);
	foreach($deleted_categories AS $dif)
	{ $dquery = "DELETE from ". _DB_PREFIX_."category_product WHERE id_product='".$GLOBALS['id_product'.$x]."' AND id_category='".$dif."'";
	  $dres=dbquery($dquery);
	  $changed_categories[] = $dif; // prepare for later sorting of this category
	}
	  
	$added_categories = array_diff($mycat_arr, $carray);
	foreach($added_categories AS $dif)
	{ $dquery = "SELECT MAX(position) AS mposition FROM "._DB_PREFIX_."category_product WHERE id_category='".$dif."'";
	  $dres=dbquery($dquery);
	  $drow=mysqli_fetch_array($dres);
	  $dquery = "INSERT INTO "._DB_PREFIX_."category_product SET id_product='".$GLOBALS['id_product'.$x]."', id_category='".$dif."', position='".($drow['mposition']+1)."'";
	  $dres=dbquery($dquery);
	}
  }
  
    if(isset($GLOBALS['myattachments'.$x]))  /* my attachments */
  { $cquery = "select id_attachment from ". _DB_PREFIX_."product_attachment WHERE id_product='".$GLOBALS['id_product'.$x]."' ORDER BY id_attachment";
	$cres=dbquery($cquery);
	$carray = array();
	while ($crow=mysqli_fetch_array($cres)) 
	{  $carray[] = $crow['id_attachment']; 
	}

	if(strlen($GLOBALS['myattachments'.$x])==0)
	{ $myattas_arr = array();
	}
	else
	{ $myattas = substr($GLOBALS['myattachments'.$x], 1); echo "DD".$myattas."EE";
	  $myattas_arr = explode(",", $myattas);
	}

	$diff1 = array_diff($carray, $myattas_arr);
	foreach($diff1 AS $dif)
	{ $dquery = "DELETE from ". _DB_PREFIX_."product_attachment WHERE id_product='".$GLOBALS['id_product'.$x]."' AND id_attachment='".$dif."'";
	  $dres=dbquery($dquery);
	}
	   
	$diff2 = array_diff($myattas_arr, $carray);
	foreach($diff2 AS $dif)
	{ $dquery = "INSERT INTO "._DB_PREFIX_."product_attachment SET id_product='".$GLOBALS['id_product'.$x]."', id_attachment='".$dif."'";
	  $dres=dbquery($dquery);
	}

	if((sizeof($carray)==0) && (sizeof($myattas_arr)>0))
	{ $dquery = "UPDATE ". _DB_PREFIX_."product SET cache_has_attachments='1' WHERE id_product='".$GLOBALS['id_product'.$x]."'";
	  $dres=dbquery($dquery);
	}
	else if((sizeof($carray)>0) && (sizeof($myattas_arr)==0))
	{ $dquery = "UPDATE ". _DB_PREFIX_."product SET cache_has_attachments='0' WHERE id_product='".$GLOBALS['id_product'.$x]."'";
	  $dres=dbquery($dquery);
	}
  }
  
  if(isset($GLOBALS['mycars'.$x]))  /* my carriers */
  { if(file_exists("TE_plugin_carriers.php"))
	{ include "TE_plugin_carriers.php";
	  $carrierpack_loaded = true;
	}
	else 
	  $errstring .= "\\nProcessing carriers is in a plugin that you need to buy separately! Your carrier changes will be ignored.";
  }

  $pupdates = "";
  if($srcscript == "product-edit")
    $pupdates .= " date_upd='".date("Y-m-d H:i:s", time())."',";
  if((isset($GLOBALS['name'.$x])) || (isset($GLOBALS['description'.$x])) || (isset($GLOBALS['description_short'.$x]))
	|| (isset($GLOBALS['category_default'.$x])) || (isset($GLOBALS['manufacturer'.$x])) || (isset($GLOBALS['active'.$x]))
	|| (isset($GLOBALS['ean'.$x])) || (isset($GLOBALS['upc'.$x])) || (isset($GLOBALS['reference'.$x]))
	|| $tagspack_loaded || $featurespack_loaded)
	$pupdates .= " indexed=0,";  
  if(isset($GLOBALS['active'.$x]))  
  { $active = $GLOBALS['active'.$x];
    if(($active != "0") && ($active != "1")) colordie("invalid active for ".$x);
    $pupdates .= " active='".mysqli_real_escape_string($conn, $active)."',";
  }
  if(isset($GLOBALS['on_sale'.$x]))  
  { $onsale = $GLOBALS['on_sale'.$x];
    if(($onsale != "0") && ($onsale != "1")) colordie("Invalid onsale for line ".$x.". The onsale flag can only be 0 or 1.");
    $pupdates .= " on_sale='".mysqli_real_escape_string($conn, $onsale)."',";
  }
  if(isset($GLOBALS['online_only'.$x]))  
  { $onlineonly = $GLOBALS['online_only'.$x];
    if(($onlineonly != "0") && ($onlineonly != "1")) colordie("invalid online_only for ".$x);
    $pupdates .= " online_only='".mysqli_real_escape_string($conn, $onlineonly)."',";
  }
  if(isset($GLOBALS['available'.$x]))  
  { $available = $GLOBALS['available'.$x];
    if(($available != "0") && ($available != "1")) colordie("invalid available for ".$x);
    $pupdates .= " available_for_order='".mysqli_real_escape_string($conn, $available)."',";
  }
  if(isset($GLOBALS['available_date'.$x]))  
  { $available_date = $GLOBALS['available_date'.$x];
    $pupdates .= " available_date='".mysqli_real_escape_string($conn, $available_date)."',";
  }
  if(isset($GLOBALS['date_add'.$x]))  
  { $date_add = $GLOBALS['date_add'.$x];
    $pupdates .= " date_add='".mysqli_real_escape_string($conn, $date_add)."',";
  }
  if(isset($GLOBALS['isbn'.$x]))  
  { $isbn = $GLOBALS['isbn'.$x];
    $pupdates .= " isbn='".mysqli_real_escape_string($conn, $isbn)."',";
  }
  if(isset($GLOBALS['stockflags'.$x]))
  { if($GLOBALS['stockflags'.$x] == 1) $advanced_stock_management = "0"; else $advanced_stock_management = "1";
      $pupdates .= " advanced_stock_management='".mysqli_real_escape_string($conn, $advanced_stock_management)."',";
	if ((version_compare(_PS_VERSION_ , "1.6.0.12", ">=")) && ($advanced_stock_management == "1"))
	  $pupdates .= " pack_stock_type='3',";
  } 
  if(isset($GLOBALS['availorder'.$x]))
  { $availorder = $GLOBALS['availorder'.$x];
    if($availorder == 0) 
	  $pupdates .= "available_for_order=1,show_price=1,";
	else
	{ $pupdates .= "available_for_order=0,";
	  if($availorder == 1) 
		$pupdates .= "show_price=1,";
	  else
		$pupdates .= "show_price=0,";		  
    }
  } 
  if(isset($GLOBALS['minimal_quantity'.$x]))  
  { $minimalquantity = $GLOBALS['minimal_quantity'.$x];
    if(!is_numeric($minimalquantity)) colordie("invalid minimal_quantity for ".$x);
    $pupdates .= " minimal_quantity='".mysqli_real_escape_string($conn, $minimalquantity)."',";
  }
  if(isset($GLOBALS['shipweight'.$x]))  
  { $weight = str_replace(",", ".", $GLOBALS['shipweight'.$x]);
    if(!is_numeric($weight)) colordie("invalid weight");
    $pupdates .= " weight='".mysqli_real_escape_string($conn, $weight)."',";
  }
  if(isset($GLOBALS['shipheight'.$x]))  
  { $height = str_replace(",", ".", $GLOBALS['shipheight'.$x]);
    if(!is_numeric($height)) colordie("invalid height");
    $pupdates .= " height='".mysqli_real_escape_string($conn, $height)."',";
  }
  if(isset($GLOBALS['shipdepth'.$x]))  
  { $depth = str_replace(",", ".", $GLOBALS['shipdepth'.$x]);
    if(!is_numeric($depth)) colordie("invalid depth");
    $pupdates .= " depth='".mysqli_real_escape_string($conn, $depth)."',";
  }
  if(isset($GLOBALS['shipwidth'.$x]))  
  { $width = str_replace(",", ".", $GLOBALS['shipwidth'.$x]);
    if(!is_numeric($width)) colordie("invalid width");
    $pupdates .= " width='".mysqli_real_escape_string($conn, $width)."',";
  }
  if(isset($GLOBALS['ean'.$x]))
    $pupdates .= " ean13='".mysqli_real_escape_string($conn, $GLOBALS['ean'.$x])."',";
  if(isset($GLOBALS['upc'.$x]))
    $pupdates .= " upc='".mysqli_real_escape_string($conn, $GLOBALS['upc'.$x])."',";
  if(isset($GLOBALS['unit'.$x]))
    $pupdates .= " unity='".mysqli_real_escape_string($conn, $GLOBALS['unit'.$x])."',";
  if((isset($GLOBALS['unitPrice'.$x])) && (isset($GLOBALS['price'.$x]))) 
  { if($GLOBALS['unitPrice'.$x] == 0)
	  $unit_price_ratio = "0.000000";
    else
	  $unit_price_ratio = round($GLOBALS['price'.$x] / $GLOBALS['unitPrice'.$x], 6);
    $pupdates .= " unit_price_ratio='".mysqli_real_escape_string($conn, $unit_price_ratio)."',";
  }
  if(isset($GLOBALS['aShipCost'.$x]))
    $pupdates .= " additional_shipping_cost='".mysqli_real_escape_string($conn, $GLOBALS['aShipCost'.$x])."',";
  if(isset($GLOBALS['price'.$x]))
  { $price = str_replace(",", ".", $GLOBALS['price'.$x]);
    if(!is_numeric($price)) colordie("invalid price");
    $pupdates .= " price='".mysqli_real_escape_string($conn, $price)."',";
  }
  if(isset($GLOBALS['ecotax'.$x]))
  { $ecotax = str_replace(",", ".", $GLOBALS['ecotax'.$x]);
    if(!is_numeric($ecotax)) colordie("invalid ecotax");
    $pupdates .= " ecotax='".mysqli_real_escape_string($conn, $ecotax)."',";
  }
  if(isset($GLOBALS['visibility'.$x]))
  { $pupdates .= " visibility='".mysqli_real_escape_string($conn, $GLOBALS['visibility'.$x])."',";
  }
  if(isset($GLOBALS['condition'.$x]))
  { $pupdates .= " `condition`='".mysqli_real_escape_string($conn, $GLOBALS['condition'.$x])."',";
  }
  if(isset($GLOBALS['pack_stock_type'.$x]))
  { $pack_stock_type = $GLOBALS['pack_stock_type'.$x];
    if(!is_numeric($pack_stock_type)) colordie("invalid pack_stock_type");
	if(($pack_stock_type <0) || ($pack_stock_type > 3)) colordie("invalid pack_stock_type");
    $pupdates .= " pack_stock_type='".mysqli_real_escape_string($conn, $GLOBALS['pack_stock_type'.$x])."',";
  }  
  if(isset($GLOBALS['manufacturer'.$x]))
  { $pupdates .= " id_manufacturer='".mysqli_real_escape_string($conn, $GLOBALS['manufacturer'.$x])."',";
	$changed_cr_fields["manufacturer"] = true;
  }
  if(isset($GLOBALS['category_default'.$x]) && ($GLOBALS['category_default'.$x] != '0'))
    $pupdates .= " id_category_default='".mysqli_real_escape_string($conn, $GLOBALS['category_default'.$x])."',";
  if(isset($GLOBALS['reference'.$x]))
    $pupdates .= " reference='".mysqli_real_escape_string($conn, $GLOBALS['reference'.$x])."',";
  if($pupdates != "")
  { $query = "UPDATE ". _DB_PREFIX_."product SET".substr($pupdates,0,strlen($pupdates)-1)." WHERE id_product='".$id_product."'";
    dbquery($query);
  }

  $plupdates = "";
  if(isset($GLOBALS['name'.$x]))
    $plupdates .= " name='".mysqli_real_escape_string($conn, strip($GLOBALS['name'.$x]))."',";
  if(isset($GLOBALS['description'.$x]))
  { $description = $GLOBALS['description'.$x];
    if(!isCleanHtml($description)) colordie("Description contains illegal javascript or iframes in ".$x);
    $plupdates .= " description='".mysqli_real_escape_string($conn, $description)."',";
  }
  if(isset($GLOBALS['description_short'.$x]))
  { $description_short = $GLOBALS['description_short'.$x];
    if(!isCleanHtml($description_short)) colordie("Description_short contains illegal javascript or iframes in ".$x);
    $plupdates .= " description_short='".mysqli_real_escape_string($conn, $description_short)."',";
  }
  if(isset($GLOBALS['available_now'.$x]))
    $plupdates .= " available_now='".mysqli_real_escape_string($conn, $GLOBALS['available_now'.$x])."',";
  if(isset($GLOBALS['available_later'.$x]))
    $plupdates .= " available_later='".mysqli_real_escape_string($conn, $GLOBALS['available_later'.$x])."',";
  if(isset($GLOBALS['link_rewrite'.$x]))
    $plupdates .= " link_rewrite='".mysqli_real_escape_string($conn, $GLOBALS['link_rewrite'.$x])."',";
  if(isset($GLOBALS['meta_title'.$x]))
    $plupdates .= " meta_title='".mysqli_real_escape_string($conn, strip($GLOBALS['meta_title'.$x]))."',";
  if(isset($GLOBALS['meta_keywords'.$x]))
  { $keywords = preg_replace("/,$/", "", strip($GLOBALS['meta_keywords'.$x])); /* comma at end gives problems in PS backoffice SEO page */
    $plupdates .= " meta_keywords='".mysqli_real_escape_string($conn, $keywords)."',";
  }
  if(isset($GLOBALS['meta_description'.$x]))
    $plupdates .= " meta_description='".mysqli_real_escape_string($conn, strip($GLOBALS['meta_description'.$x]))."',";
  if($plupdates != "")
  { if($pupdates != "") echo "<br> &nbsp; &nbsp; ";
    if(!isset($id_shop)) die("<p><h2>Shop must be provided!</h2>");
    $query = "UPDATE ". _DB_PREFIX_."product_lang SET".substr($plupdates,0,strlen($plupdates)-1)." WHERE id_product='".$id_product."' AND id_lang='".$id_lang."'".$shopmask;
    dbquery($query);
  }
  
  $discupdates = "";
  if(isset($GLOBALS['discount_count'.$x]) && ($GLOBALS['discount_count'.$x] != ""))
  { if(file_exists("TE_plugin_discounts.php"))
    { include "TE_plugin_discounts.php";
	  $discountpack_loaded = true; 
	}
	else 
	  $errstring .= "\\nProcessing discounts is in a plugin that you need to buy separately! Your discount changes will be ignored.";
  }
  
  if(isset($GLOBALS['is_virtual'.$x]))
  { if(file_exists("TE_plugin_virtual.php"))
    { include "TE_plugin_virtual.php";
	  $virtualpack_loaded = true; 
	}
	else 
	  $errstring .= "\\nProcessing virtual products is in a plugin that you need to buy separately! Your virtual product changes will be ignored.";
  }
  
  $supupdates = "";
  if(isset($GLOBALS['mysups'.$x]) && ($GLOBALS['mysups'.$x] != ""))
  { if(file_exists("TE_plugin_suppliers.php"))
	{ include "TE_plugin_suppliers.php";
	  $changed_cr_fields["supplier"] = true;
	  $supplierpack_loaded = true; 
    }
	else 
	  $errstring .= "\\nProcessing suppliers is in a plugin that you need to buy separately! Your supplier changes will be ignored.";
  }
  
  $stockupdates = "";
  if(isset($GLOBALS['quantity'.$x]) && ($GLOBALS['quantity'.$x] != ""))
  { if(!is_numeric($GLOBALS['quantity'.$x])) colordie("invalid quantity");
    $quantity = intval($GLOBALS['quantity'.$x]);
	$attrquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."product_attribute WHERE id_product='".$id_product."'";
	$attrres = dbquery($attrquery);
	if(mysqli_num_rows($attrres) == 0) /* don't change stock for products with attributes */
		$stockupdates .= " quantity='".$quantity."',";
  }
  if(isset($GLOBALS['out_of_stock'.$x])) 
  { $out_of_stock = intval($GLOBALS['out_of_stock'.$x]);
    $stockupdates .= " out_of_stock='".$out_of_stock."',";
  }
  if(isset($GLOBALS['stockflags'.$x])) 
  { if($GLOBALS['stockflags'.$x] == 3) $depends_on_stock = "1"; else $depends_on_stock = "0";
    $stockupdates .= " depends_on_stock='".$depends_on_stock."',";
  }  
  if($stockupdates != "")
  { if($share_stock)
	{ $query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE id_shop_group = '".$id_shop_group."' AND id_product='".$id_product."' AND id_product_attribute=0";
	  $res = dbquery($query);
	  /* the out_of_stock field determines whether ordering is allowed when stock too low: 2=not allowed 1=allowed 0=follow shop preferences */
	  if(mysqli_num_rows($res) == 0) /* no quantity entered yet  */
	  { if(strpos($stockupdates,"quantity") === false) $stockupdates .= " quantity='0',";
	    $query = "INSERT INTO ". _DB_PREFIX_."stock_available SET ".substr($stockupdates,0,strlen($stockupdates)-1).", id_shop_group ='".$id_shop_group."', id_shop=0, id_product='".$id_product."', id_product_attribute=0";
	    if(!isset($GLOBALS['out_of_stock'.$x])) 
			$query .= ", out_of_stock='2'";
		$res = dbquery($query);
	  }
	  else 
	  { $query = "UPDATE ". _DB_PREFIX_."stock_available SET ".substr($stockupdates,0,strlen($stockupdates)-1)." WHERE id_shop_group ='".$id_shop_group."' AND id_product='".$id_product."' AND id_product_attribute=0";
	    $res = dbquery($query);
	  }
	}
	else
	{ $query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE id_shop = '".$id_shop."' AND id_product='".$id_product."' AND id_product_attribute=0";
	  $res = dbquery($query);
	  /* the out_of_stock field determines whether ordering is allowed when stock too low: 2=not allowed 1=allowed 0=follow shop preferences */
	  if(mysqli_num_rows($res) == 0)  /* no quantity entered yet  */
	  { if(strpos($stockupdates,"quantity") === false) $stockupdates .= " quantity='0',";
	    $query = "INSERT INTO ". _DB_PREFIX_."stock_available SET ".substr($stockupdates,0,strlen($stockupdates)-1).", id_shop ='".$id_shop."', id_product='".$id_product."', id_product_attribute=0";
	    if(!isset($GLOBALS['out_of_stock'.$x]))
			$query .= ", out_of_stock='2'";
	    $res = dbquery($query);
	  }
	  else
	  { $query = "UPDATE ". _DB_PREFIX_."stock_available SET ".substr($stockupdates,0,strlen($stockupdates)-1)." WHERE id_shop ='".$id_shop."' AND id_product='".$id_product."' AND id_product_attribute=0";
	    $res = dbquery($query);
	  }
	}
  }

  if((isset($GLOBALS['image_list'.$x])) && ($GLOBALS['image_list'.$x] != ""))
  { $images = explode("-",$GLOBALS['image_list'.$x]);
	foreach($images AS $image)
	{ if(!isset($GLOBALS['image_legend'.(int)$image.'s'.$x]))
		continue; /* happens when we have legend only and there are more images */
	  $legend = $GLOBALS['image_legend'.(int)$image.'s'.$x];
	  $query = "UPDATE ". _DB_PREFIX_."image_lang SET legend='".mysqli_real_escape_string($conn, $legend)."' WHERE id_image=".(int)$image." AND id_lang=".$id_lang;
	  $res = dbquery($query);
	}
  }
  
  /* now handle the case that we switch to warehousing. */
  if((isset($GLOBALS['stockflags'.$x])) && ($GLOBALS['stockflags'.$x] == 3))
  { if((isset($GLOBALS['stockflags_warehouse'.$x])) && ($GLOBALS['stockflags_warehouse'.$x] != 0))
	{ $aquery = "SELECT * FROM ". _DB_PREFIX_."product_attribute WHERE id_product = '".$id_product."'";
	  $ares = dbquery($aquery);
	  if(mysqli_num_rows($ares) == 0)  /* if has no attributes */
	  { $wquery = "SELECT * FROM ". _DB_PREFIX_."warehouse_product_location WHERE id_product = '".$id_product."'";
		$wres = dbquery($wquery);
		if(mysqli_num_rows($wres) == 0) 
	    { $wquery = "INSERT INTO ". _DB_PREFIX_."warehouse_product_location SET id_product = '".$id_product."', id_product_attribute='0', id_warehouse='".mysqli_real_escape_string($conn,$GLOBALS['stockflags_warehouse'.$x])."',location=''";
		  $wres = dbquery($wquery);
		  add_qty_to_warehouse($id_product, 0, $x);
		}
	  }
	  else /* there are attributes; so only they (and not the prod itself) need to have warehouse locations. */
	  { while ($arow=mysqli_fetch_array($ares))
		{ $wquery = "SELECT * FROM ". _DB_PREFIX_."warehouse_product_location WHERE id_product = '".$id_product."' AND id_product_attribute='".$arow["id_product_attribute"]."'";
		  $wres = dbquery($wquery);
		  if(mysqli_num_rows($wres) == 0)
		  {	$wquery = "INSERT INTO ". _DB_PREFIX_."warehouse_product_location SET id_product = '".$id_product."', id_product_attribute='".$arow["id_product_attribute"]."', id_warehouse='".mysqli_real_escape_string($conn, $GLOBALS['stockflags_warehouse'.$x])."',location=''";
		    $wres = dbquery($wquery);
		    add_qty_to_warehouse($id_product, $arow["id_product_attribute"], $x);
		  }
		}  
	  }
	}
	else if ((isset($GLOBALS['stockflags_warehouse'.$x])) && ($GLOBALS['stockflags_warehouse'.$x] == 0)) /* no warehouse allocated: set quantity to zero */
	{ $query = "UPDATE ". _DB_PREFIX_."stock_available SET quantity=0 WHERE id_product='".$id_product."' AND ".$wherestock;
	  $res = dbquery($query);
	}
	else if (isset($GLOBALS['stock_reinstate'.$x])) /* if there is already stock in the warehouses we set the stock to that value */
	{ $aquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."stock_available";
	  $aquery .= " WHERE id_product = '".$id_product."' AND ".$wherestock;
	  $ares = dbquery($aquery);
	  $totquantity = 0;
	  while($arow = mysqli_fetch_array($ares))
	  { $squery = "SELECT id_product_attribute, SUM(usable_quantity) AS quantity FROM ". _DB_PREFIX_."stock s";
	    $squery .= " LEFT JOIN ". _DB_PREFIX_."warehouse_shop ws on ws.id_warehouse=s.id_warehouse";
	    $squery .= " LEFT JOIN ". _DB_PREFIX_."warehouse w on ws.id_warehouse=w.id_warehouse";	  
	    $squery .= " WHERE s.id_product = '".$id_product."' AND ws.id_shop =".$id_shop." AND id_product_attribute=".$arow["id_product_attribute"]." AND w.deleted=0";
	    $squery .= " GROUP BY id_product_attribute";
	    $sres = dbquery($squery); /* note that we made no condition on attribute. So we get them too */
	    if(mysqli_num_rows($sres) > 0)
		{ $srow = mysqli_fetch_array($sres);
		  $uquery = "UPDATE ". _DB_PREFIX_."stock_available SET quantity=".$srow["quantity"]." WHERE id_product = '".$id_product."' AND id_product_attribute='".$srow["id_product_attribute"]."' AND ".$wherestock;
	      $ures = dbquery($uquery);
		  $totquantity += (int)$srow["quantity"];
		}
		else /* not in ps_stock: set to zero */
		{ $uquery = "UPDATE ". _DB_PREFIX_."stock_available SET quantity=0 WHERE id_product = '".$id_product."' AND id_product_attribute='".$arow["id_product_attribute"]."' AND ".$wherestock;
	      $ures = dbquery($uquery);
		}
      }
	  /* note that with attributes the ps_stock table does not contain a attribute=0 row while ps_stock_available does */
	  /* note also that neither ps_stock_available nor ps_stock is certain to contain all attributes */
	  if(mysqli_num_rows($ares) > 1) /* has attributes */
	  { $uquery = "UPDATE ". _DB_PREFIX_."stock_available SET quantity=".$totquantity." WHERE id_product = '".$id_product."' AND id_product_attribute='0' AND ".$wherestock;
	    $ures = dbquery($uquery);
      }
	}
  }
  
  if(($srcscript == "product-vissort") || ($srcscript == "product-sort"))
  { $query = "UPDATE ". _DB_PREFIX_."category_product SET position='".intval($x)."' WHERE id_product='".$id_product."' AND id_category='".$id_category."'";
    dbquery($query);
  }
  
  if(isset($GLOBALS['tags'.$x]))
  { if(file_exists("TE_plugin_tags.php"))
	{ include "TE_plugin_tags.php";
	  $tagspack_loaded = true;
	}
	else 
	  $errstring .= "\\nProcessing tags is in a plugin that you need to buy separately! Your tag changes will be ignored.";
  }
  
  if(isset($GLOBALS['accessories'.$x]))
  { $query ="SELECT GROUP_CONCAT(id_product_2) AS accessories FROM "._DB_PREFIX_."accessory";
	$query.=" WHERE id_product_1='".$id_product."' GROUP BY id_product_1";
	$res = dbquery($query);
	$row = mysqli_fetch_array($res);
	if($row["accessories"] != $GLOBALS['accessories'.$x])
	{ $oldaccs = explode(",", $row["accessories"]);
	  $newaccs = explode(",", $GLOBALS['accessories'.$x]);
	  foreach($oldaccs AS $oldacc)
	    if(!in_array($oldacc, $newaccs))
		{ $query = "DELETE FROM "._DB_PREFIX_."accessory WHERE id_product_1='".$id_product."' AND id_product_2='".$oldacc."'";
		  dbquery($query);
		}
	  foreach($newaccs AS $newacc)
	    if(!in_array($newacc, $oldaccs))
		{ if(!in_array($newacc, $valid_products))
		  { $query = "SELECT id_product FROM "._DB_PREFIX_."product WHERE id_product='".$newacc."'";
		    $res = dbquery($query);
			if(mysqli_num_rows($res) == 0)
			{ echo "<p><b>".$newacc." is not a valid article number!!!</b><br/>";
			  if(!in_array($newacc, $invalid_products))
			  { $invalid_products[] = $newacc;
				$errstring .= "\\n".$newacc." is not a valid product id number!";
			  }
			  continue;
			}
			$valid_products[] = $newacc;
		  }
		  $query = "INSERT INTO "._DB_PREFIX_."accessory SET id_product_1='".$id_product."', id_product_2='".$newacc."'";
		  dbquery($query);
		}
	}
  }

  if(isset($_POST["featuresset"]) && ($_POST["featuresset"] == 1))
  { if(file_exists("TE_plugin_features.php"))
	{ include "TE_plugin_features.php";
	  $changed_cr_fields["features"] = true;
	  $featurespack_loaded = true;
    }
  }
  
  $psupdates = "";
  if($srcscript == "product-edit")
    $psupdates .= " date_upd='".date("Y-m-d H:i:s", time())."',";
  if((isset($GLOBALS['name'.$x])) || (isset($GLOBALS['description'.$x])) || (isset($GLOBALS['description_short'.$x]))
	|| (isset($GLOBALS['active'.$x])))
	$psupdates .= " indexed=0,";
  if(isset($GLOBALS['active'.$x]))  
  { $active = $GLOBALS['active'.$x];
    if(($active != "0") && ($active != "1")) colordie("invalid active for ".$x);
    $psupdates .= " active='".mysqli_real_escape_string($conn, $active)."',";
  }
  if(isset($GLOBALS['on_sale'.$x]))  
  { $onsale = $GLOBALS['on_sale'.$x];
    if(($onsale != "0") && ($onsale != "1")) colordie("invalid onsale for ".$x);
    $psupdates .= " on_sale='".mysqli_real_escape_string($conn, $onsale)."',";
  }
  if(isset($GLOBALS['online_only'.$x]))  
  { $onlineonly = $GLOBALS['online_only'.$x];
    if(($onlineonly != "0") && ($onlineonly != "1")) colordie("invalid online_only for ".$x);
    $psupdates .= " online_only='".mysqli_real_escape_string($conn, $onlineonly)."',";
  }
  if(isset($GLOBALS['stockflags'.$x]))
  { if($GLOBALS['stockflags'.$x] == 1) $advanced_stock_management = "0"; else $advanced_stock_management = "1";
    if($share_stock)
	{ $packsegment = "";
	  if ((version_compare(_PS_VERSION_ , "1.6.0.12", ">=")) && ($advanced_stock_management == "1"))
	    $packsegment = " pack_stock_type='3',";
      $query = "UPDATE ". _DB_PREFIX_."product_shop SET ".$packsegment."advanced_stock_management='".$advanced_stock_management."', date_upd='".date("Y-m-d H:i:s", time())."' WHERE id_product='".$id_product."' AND id_shop IN (".$share_stock_shops.")";
      dbquery($query);  /* we must do this update for all shops in the group */
	}
	else
    { $psupdates .= " advanced_stock_management='".mysqli_real_escape_string($conn, $advanced_stock_management)."',";
	  if ((version_compare(_PS_VERSION_ , "1.6.0.12", ">=")) && ($advanced_stock_management == "1"))
	    $psupdates .= " pack_stock_type='3',";
	}
  } 
  if(isset($GLOBALS['availorder'.$x]))
  { $availorder = $GLOBALS['availorder'.$x];
    if($availorder == 0) 
	  $psupdates .= "available_for_order=1,show_price=1,";
	else
	{ $psupdates .= "available_for_order=0,";
	  if($availorder == 1) 
		$psupdates .= "show_price=1,";
	  else
		$psupdates .= "show_price=0,";		  
    }
  } 
  if(isset($GLOBALS['available'.$x]))  
  { $available = $GLOBALS['available'.$x];
    if(($available != "0") && ($available != "1")) colordie("invalid available for ".$x);
    $psupdates .= " available_for_order='".mysqli_real_escape_string($conn, $available)."',";
  }
  if(isset($GLOBALS['available_date'.$x]))  
  { $available_date = $GLOBALS['available_date'.$x];
    $psupdates .= " available_date='".mysqli_real_escape_string($conn, $available_date)."',";
  }
  if(isset($GLOBALS['date_add'.$x]))  
  { $date_add = $GLOBALS['date_add'.$x];
    $psupdates .= " date_add='".mysqli_real_escape_string($conn, $date_add)."',";
  }
  if(isset($GLOBALS['minimal_quantity'.$x]))  
  { $minimalquantity = $GLOBALS['minimal_quantity'.$x];
    if(!is_numeric($minimalquantity)) colordie("invalid minimal_quantity for ".$x);
    $psupdates .= " minimal_quantity='".mysqli_real_escape_string($conn, $minimalquantity)."',";
  }
  if(isset($GLOBALS['price'.$x]))
  { $price = str_replace(",", ".", $GLOBALS['price'.$x]);
    if(!is_numeric($price)) colordie("invalid price");
    $psupdates .= " price='".mysqli_real_escape_string($conn, $price)."',";
  }
  if(isset($GLOBALS['ecotax'.$x]))
  { $ecotax = str_replace(",", ".", $GLOBALS['ecotax'.$x]);
    if(!is_numeric($ecotax)) colordie("invalid ecotax");
    $psupdates .= " ecotax='".mysqli_real_escape_string($conn, $ecotax)."',";
  }
  if(isset($GLOBALS['visibility'.$x]))
  { $psupdates .= " visibility='".mysqli_real_escape_string($conn, $GLOBALS['visibility'.$x])."',";
  }
  if(isset($GLOBALS['condition'.$x]))
  { $psupdates .= " `condition`='".mysqli_real_escape_string($conn, $GLOBALS['condition'.$x])."',";
  }
  if(isset($GLOBALS['show_condition'.$x]))
  { $psupdates .= " `show_condition`='".mysqli_real_escape_string($conn, $GLOBALS['show_condition'.$x])."',";
  }  
  if(isset($GLOBALS['pack_stock_type'.$x]))
  { $pack_stock_type = $GLOBALS['pack_stock_type'.$x];
    if(!is_numeric($pack_stock_type)) colordie("invalid pack_stock_type");
	if(($pack_stock_type <0) || ($pack_stock_type > 3)) colordie("invalid pack_stock_type");
    $psupdates .= " pack_stock_type='".mysqli_real_escape_string($conn, $GLOBALS['pack_stock_type'.$x])."',";
  }  
  if(isset($GLOBALS['unit'.$x]))
    $psupdates .= " unity='".mysqli_real_escape_string($conn, $GLOBALS['unit'.$x])."',";
  if((isset($GLOBALS['unitPrice'.$x])) && (isset($GLOBALS['price'.$x]))) 
  { if($GLOBALS['unitPrice'.$x] == 0)
	  $unit_price_ratio = "0.000000";
    else
	  $unit_price_ratio = round($GLOBALS['price'.$x] / $GLOBALS['unitPrice'.$x], 6);
    $psupdates .= " unit_price_ratio='".mysqli_real_escape_string($conn, $unit_price_ratio)."',";
  }
  if(isset($GLOBALS['wholesaleprice'.$x]))
  { $wholesaleprice = str_replace(",", ".", $GLOBALS['wholesaleprice'.$x]);
    if(!is_numeric($wholesaleprice)) colordie("invalid wholesale price");
    $psupdates .= " wholesale_price='".mysqli_real_escape_string($conn, $wholesaleprice)."',";
  }
  if(isset($GLOBALS['aShipCost'.$x]))
    $psupdates .= " additional_shipping_cost='".mysqli_real_escape_string($conn, $GLOBALS['aShipCost'.$x])."',";
  if(isset($GLOBALS['VAT'.$x]))
    $psupdates .= " id_tax_rules_group='".mysqli_real_escape_string($conn, $GLOBALS['VAT'.$x])."',";
  if(isset($GLOBALS['category_default'.$x]) && ($GLOBALS['category_default'.$x] != '0'))
    $psupdates .= " id_category_default='".mysqli_real_escape_string($conn, $GLOBALS['category_default'.$x])."',";
  if($psupdates != "")
  { if(!isset($id_shop)) die("<p><h2>Shop must be provided!</h2>");
    $query = "UPDATE ". _DB_PREFIX_."product_shop SET".substr($psupdates,0,strlen($psupdates)-1)." WHERE id_product='".$id_product."'".$shopmask;
    dbquery($query);
  }
  
  /* note that we don't change values that would necessitate re-application of catalogue rules here */
  if(isset($GLOBALS['combinations'.$x]))
  { /* pro memory */
  }
  
  if(isset($GLOBALS['shopz'.$x]))
  { if(file_exists("TE_plugin_shopz.php"))
	{ include "TE_plugin_shopz.php";
	  $shopzpack_loaded = true;
	}
	else 
	  $errstring .= "\\nAdding or removing shops of a product is handled by a plugin that you need to buy separately! Such changes will be ignored.";
  }

  /* set all shops up for re-indexing for this product */
  if((isset($GLOBALS['category_default'.$x])) || (isset($GLOBALS['manufacturer'.$x]))
	|| (isset($GLOBALS['ean'.$x])) || (isset($GLOBALS['upc'.$x])) || (isset($GLOBALS['reference'.$x]))
	|| $tagspack_loaded || $featurespack_loaded)
  { $query = "UPDATE ". _DB_PREFIX_."product_shop SET indexed=0 WHERE id_product='".$id_product."'";
    dbquery($query); 
  }
}

/* custom price rules: as we can't change attributes here we don't check for that */
if($changed_cr_fields["category"] || $changed_cr_fields["manufacturer"] || $changed_cr_fields["features"] || $changed_cr_fields["supplier"])
{ $query = "SELECT r.id_specific_price_rule, GROUP_CONCAT(',', c.type) AS typelist";
  $query .= " FROM `". _DB_PREFIX_."specific_price_rule` r";
  $query .= " LEFT JOIN `". _DB_PREFIX_."specific_price_rule_condition_group` g ON g.id_specific_price_rule=r.id_specific_price_rule";
  $query .= " LEFT JOIN `". _DB_PREFIX_."specific_price_rule_condition` c ON c.id_specific_price_rule_condition_group=g.id_specific_price_rule_condition_group";
  $query .= " GROUP BY r.id_specific_price_rule";
  $res=dbquery($query);
  $rules = array();
  while ($row=mysqli_fetch_array($res))
  { $typelist = "x".$row["typelist"]; /* prepare for the strpos check */
	foreach($changed_cr_fields AS $key => $field)
	{ if($field AND (strpos($typelist,$key)>0))
		$rules[] = $row["id_specific_price_rule"];
	}
/* if a rule has no conditions this routine will not select it. That is ok, as a rule with no condition applies to all products */
/* such a rule applies to product 0 in the database. There is no need to change it */
  }
  $rules = array_unique($rules);
  apply_catalogue_rules($rules, $modified_products_for_cpr);
}

$changed_categories = array_unique($changed_categories); // prevent that we sort the same category twice
foreach ($changed_categories AS $changedcat)
{ // the following is a copy of the function cleanPositions($id_category) in file product.php in the Classes directory
  // it readapts the positions after deletions
  echo "Now assign all products in category ".$changedcat." a new position to fill the space left by the deletion(s).<br/>";
  $xquery = 'SELECT `id_product` FROM `'._DB_PREFIX_.'category_product` WHERE `id_category` = '.(int)($changedcat).' ORDER BY `position`';
  $xres=dbquery($xquery);
  $i = 0;
  while ($xrow=mysqli_fetch_array($xres)) 
  { $yquery = 'UPDATE `'._DB_PREFIX_.'category_product` SET `position` = '.(int)($i++).' WHERE `id_category` = '.(int)($changedcat).' AND `id_product` = '.(int)($xrow['id_product']);
	$yres=dbquery($yquery);
  }
}

foreach($deleted_tags AS $deleted_tag)
{ $dquery = "SELECT id_product FROM "._DB_PREFIX_."product_tag WHERE id_tag='".$deleted_tag."' LIMIT 1";
  $dres=dbquery($dquery);
  if(mysqli_num_rows($dres) == 0)
  { $query ="DELETE FROM "._DB_PREFIX_."tag WHERE id_tag='".$deleted_tag."' AND id_lang='".$id_lang."'";
	$res = dbquery($query);
  }	
}

/* the code below was adapted from the Prestashop 1.6.1.4 function updateTagCount() in classes\tag.php */
if(($tagspack_loaded) && (_PS_VERSION_ >= "1.6.1") && (count($tags_changed)>0))
	update_tag_count($tags_changed);

echo "<p>";
if(($skipindexation != "on") && ($skipindexation != "ON") && ($skipindexation != "true") && ($skipindexation != "TRUE"))
  update_shop_index(10);  /* in ps_sourced_code.php. The number is the number of seconds that it is allowed to run. */
else
  update_unindexed_counter(-1); /* -1 means that we don't know the new number so we will need to do a query */

if($errstring != "")
{ echo "<script>alert('There were errors: ".$errstring."');</script>!";
  echo str_replace("\n","<br>",$errstring);
}

echo "<br>Finished successfully!<p>Go back to <a href='".$refscript."'>".$srcscript." page</a>";

/* the following section takes care of Ajax on the product-edit page when doing rowsubmit() */
if((isset($_POST['submittedrow'])) && ($_POST['submittedrow'] != '')) /* is empty for product-solo */
{ echo "\n<script>updateblock = [];";
  foreach($updateblock AS $field => $updateline)
  { echo "\nupdateblock['".$field."'] = [];";
    foreach($updateline AS $subrow => $subid)
    { echo "\nupdateblock['".$field."']['".$subrow."'] = '".$subid."'; ";
	}
  }
  echo "\nif(parent && parent.reg_unchange) parent.reg_unchange('".$_POST['submittedrow']."', updateblock);</script>";
}
else if($verbose!="true")
{ echo "<script>location.href = '".$refscript."';</script>";
}

echo "</body></html>";

function check_customer($customer)
{ $dquery = "SELECT id_customer FROM "._DB_PREFIX_."customer WHERE id_customer='".$customer."' LIMIT 1";
  $dres=dbquery($dquery);
  if(mysqli_num_rows($dres) == 0)
    colordie("Customer No ".$customer." is not a valid customer number");
}
function check_country($country)
{ global $countries;
  if(!isset($countries))
  { $cquery = "SELECT id_country FROM "._DB_PREFIX_."country";
    $cres=dbquery($cquery);
	$countries = array();
	while ($crow=mysqli_fetch_array($cres)) 
	  $countries[] = $crow["id_country"];
  }
  if(!in_array($country, $countries))
    colordie("Country No ".$country." is not a valid country number");
}
function check_group($group)
{ global $groups;
  if(!isset($groups))
  { $gquery = "SELECT id_group FROM "._DB_PREFIX_."group";
    $gres=dbquery($gquery);
	$groups = array();
	while ($grow=mysqli_fetch_array($gres)) 
	  $groups[] = $grow["id_group"];
  }
  if(!in_array($group, $groups))
    colordie("Group No ".$group." is not a valid group number");
}
function check_currency($currency)
{ global $currencies;
  if(!isset($currencies))
  { $cquery = "SELECT id_currency FROM "._DB_PREFIX_."currency";
    $cres=dbquery($cquery);
	$currencies = array();
	while ($crow=mysqli_fetch_array($cres)) 
	  $currencies[] = $crow["id_currency"];
  }
  if(!in_array($currency, $currencies))
    colordie("Currency No ".$currency." is not a valid currency number");
}

function check_shop($shop)
{ global $shops;
  if(!isset($shops))
  { $squery = "SELECT id_shop FROM "._DB_PREFIX_."shop";
    $sres=dbquery($squery);
	$shops = array();
	while ($srow=mysqli_fetch_array($sres)) 
	  $shops[] = $srow["id_shop"];
  }
  if(!in_array($shop, $shops))
    colordie("Shop No ".$shop." is not a valid shop number");
}

/* strip makes sure that the requirements of isValidName() - that is used by PS on many fields - are respected */
/* first to check which fields */
function strip($txt)
{ $txt = preg_replace('/[<>={}]+/', '', $txt);
  return $txt;
}


function get_warehouse_price($id_product, $id_product_attribute)
{ global $conn, $id_shop, $id_shop_group;
  $price = $endprice = 0;
  $query = "SELECT product_supplier_price_te FROM "._DB_PREFIX_."product_supplier WHERE id_product='".$id_product."' AND id_product_attribute='".$id_product_attribute."'";
  $res=dbquery($query);
  while (($row=mysqli_fetch_array($res)) && ($price==0))
  { $price = $row["product_supplier_price_te"];
  }
  if($price != 0) return $price;
  $query = "SELECT price, wholesale_price FROM "._DB_PREFIX_."product_shop WHERE id_product='".$id_product."' AND id_shop='".$id_shop."'";
  $res=dbquery($query);
  if ($row=mysqli_fetch_array($res))
  { $price = floatval($row["wholesale_price"]);
	if($price == 0)
	  $endprice = floatval($row["price"]);  
  }
  if($id_product_attribute == 0)
  { if($price != 0)
	  return $price;
    else
	  return $endprice;
  }
  $query = "SELECT price, wholesale_price FROM "._DB_PREFIX_."product_attribute_shop WHERE id_product='".$id_product."' AND id_product_attribute='".$id_product_attribute."' AND id_shop='".$id_shop."'";
  $res=dbquery($query);
  while (($row=mysqli_fetch_array($res)) && ($price==0))
  { $price = $price + floatval($row["wholesale_price"]);
	if($price == 0)
	  $price = $endprice + floatval($row["price"]);  
  }
  return $price;
}

function add_qty_to_warehouse($id_product, $id_product_attribute, $x)
{ global $conn, $share_stock, $id_shop, $id_shop_group, $share_stock_shops,$wherestock;
  $price = get_warehouse_price($id_product, $id_product_attribute);
  if($price == 0) return; /* we cannot add zero prices */
  /* now get quantity */
  $query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE ".$wherestock." AND id_product='".$id_product."' AND id_product_attribute='".$id_product_attribute."'";
  $res = dbquery($query);
  $row = mysqli_fetch_array($res);
  $quantity = $row["quantity"];
  if($quantity > 0)
  { $squery = "INSERT INTO ". _DB_PREFIX_."stock SET id_product = '".$id_product."', id_product_attribute='".$id_product_attribute."', id_warehouse='".(int)$GLOBALS['stockflags_warehouse'.$x]."',reference='',ean13='',upc=''";
	$squery .= ",physical_quantity=".$quantity.", usable_quantity=".$quantity.", price_te='".$price."'";
	$sres = dbquery($squery);
	$stock_id = mysqli_insert_id($conn);
	/* now we handle the stock_mvt table */
	$equery = "SELECT e.id_employee,firstname,lastname FROM ". _DB_PREFIX_."employee e";
	$equery .= " LEFT JOIN ". _DB_PREFIX_."employee_shop es ON e.id_employee=es.id_employee";		
	if($share_stock)
	  $equery .= " WHERE id_shop IN (".$share_stock_shops.")";
	else 
	  $equery .= " WHERE id_shop=".$id_shop;
	$equery .= " LIMIT 1";
	$eres = dbquery($equery);
    $erow = mysqli_fetch_array($eres);
	$squery = "INSERT INTO ". _DB_PREFIX_."stock_mvt SET id_stock=".$stock_id.",id_order=0,id_supply_order=0";
	$squery .= ", id_stock_mvt_reason=1, id_employee=".$erow["id_employee"].", employee_lastname='".$erow["lastname"]."'";
	$squery .= ", employee_firstname='".$erow["firstname"]."', physical_quantity=".$quantity.", date_add=NOW(), sign=1";
	$squery .= ",price_te='".$price."', last_wa=0, current_wa=".$price.",referer=0";
	$sres = dbquery($squery);
  }
}
?>

