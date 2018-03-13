<?php
if(!@include 'approve.php') die( "approve.php was not found!");

if(!isset($_POST['task'])) colordie("No argument provided!");

if(!isset($integrity_repair_allowed)) $integrity_repair_allowed = true;
if(!isset($integrity_delete_allowed)) $integrity_delete_allowed = false;

 if(isset($demo_mode) && $demo_mode)
 { echo '<script>alert("The script is in demo mode. Nothing is changed!");</script>';
   return;
 }

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
</script></head><body>
<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';

$query = "SELECT id_shop FROM ". _DB_PREFIX_."shop ORDER BY active,id_shop";
$res = dbquery($query);
if(mysqli_num_rows($res)==0)
	colordie("No active shops available!");
$shops = array();
while($row = mysqli_fetch_array($res))
	$shops[] = $row['id_shop'];

if((($_POST['task']) == "productrepair") && $integrity_repair_allowed)
{ if(!isset($_POST['products'])) colordie("No products provided!");
  else $products = mescape($_POST['products']);
  
  if (version_compare(_PS_VERSION_ , "1.7.1.0", ">="))
	  $redirect_value = "id_type_redirected";
  else
	  $redirect_value = "id_product_redirected";
  
  // Products in "._DB_PREFIX_."product that are not in "._DB_PREFIX_."product_shop
  $query = "SELECT p.id_product, p.id_shop_default FROM ". _DB_PREFIX_."product p";
  $query .= " LEFT OUTER JOIN ". _DB_PREFIX_."product_shop ps on p.id_product=ps.id_product";
  $query .= " WHERE ps.id_shop is null AND p.id_product IN (".$products.") ORDER BY p.id_product";
  $res=dbquery($query);
  while ($row=mysqli_fetch_array($res)) 
  { if(in_array($row["id_shop_default"],$shops))
		$shop = $row["id_shop_default"];
    else 
		$shop = $shops[0];
    $iquery = "Insert into ". _DB_PREFIX_."product_shop";
    $iquery .= "            (id_product,id_shop,id_category_default,id_tax_rules_group, on_sale,online_only,ecotax,minimal_quantity,price,wholesale_price, unity,unit_price_ratio,additional_shipping_cost,customizable, uploadable_files,text_fields, active,redirect_type,".$redirect_value.", available_for_order,available_date,`condition`,show_price,indexed, visibility,cache_default_attribute,advanced_stock_management,date_add, date_upd,pack_stock_type)";
    $iquery .= " select id_product,'".$shop."',id_category_default,id_tax_rules_group, on_sale,online_only,ecotax,minimal_quantity,price,wholesale_price, unity,unit_price_ratio,additional_shipping_cost,customizable, uploadable_files,text_fields, active,redirect_type,".$redirect_value.", available_for_order,available_date,`condition`,show_price,indexed, visibility,cache_default_attribute,advanced_stock_management,date_add, date_upd,pack_stock_type";
    $iquery .= " FROM "._DB_PREFIX_."product WHERE id_product=".$row["id_product"];
    $ires=dbquery($iquery);
  }
  
// Products in "._DB_PREFIX_."product that are not in "._DB_PREFIX_."product_lang
  $dummyctr = 0;
  $query = "SELECT id_lang FROM ". _DB_PREFIX_."lang ORDER BY id_lang";
  $res = dbquery($query);
  $languages = array();
  while($row = mysqli_fetch_array($res))
	$languages[] = $row['id_lang'];

  foreach($languages AS $id_lang)
  { foreach($shops AS $id_shop)
    { $query = "SELECT p.id_product FROM ". _DB_PREFIX_."product p";
      $query .= " LEFT OUTER JOIN ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_shop =".$id_shop." AND pl.id_lang=".$id_lang;
      $query .= " WHERE pl.id_shop is null AND p.id_product IN (".$products.") ORDER BY p.id_product";
      $res=dbquery($query);
      while ($row=mysqli_fetch_array($res)) 
      { /* first check if the same language is in a different shop, then any language. if not fill in dummy data */
		$squery = "SELECT * FROM ". _DB_PREFIX_."product_lang WHERE id_product=".$row["id_product"]." AND id_lang=".$id_lang." LIMIT 1";
        $sres=dbquery($squery);
		if(mysqli_num_rows($sres)==0) /* if there is another language that we can copy */
		{ $squery = "SELECT * FROM ". _DB_PREFIX_."product_lang WHERE id_product=".$row["id_product"]." LIMIT 1";
          $sres=dbquery($squery);
		}
		if(mysqli_num_rows($sres)>0) /* if there is another language that we can copy */
		{ $srow = mysqli_fetch_array($sres);
		  $iquery = "Insert into ". _DB_PREFIX_."product_lang";
		  $iquery .= " (id_product,id_shop,id_lang,description,description_short,link_rewrite,meta_description,meta_keywords,meta_title,name,available_now,available_later)";
		  $iquery .= " select id_product,'".$id_shop."','".$id_lang."',description,description_short,link_rewrite,meta_description,meta_keywords,meta_title,name,available_now,available_later";
		  $iquery .= " FROM "._DB_PREFIX_."product_lang WHERE id_product=".$row["id_product"]." AND id_lang=".$srow["id_lang"];
          $ires=dbquery($iquery);
		}
		else
		{ /* try first if there is a legend */
		  $squery = "SELECT legend AS name FROM ". _DB_PREFIX_."image i";
		  $squery .= " INNER JOIN ". _DB_PREFIX_."image_lang il on i.id_image=il.id_image";
		  $squery .= " WHERE TRIM(legend)!='' AND i.id_product=".$row["id_product"];
		  $sres=dbquery($squery);
		  if(mysqli_num_rows($sres)>0)
		  { $srow = mysqli_fetch_array($sres);
			$dummyname = $srow["name"];
		  }
		  else
		    $dummyname = "dummy".$dummyctr++;
		  $iquery = "Insert into ". _DB_PREFIX_."product_lang";
		  $iquery .= " (id_product,id_shop,id_lang,description,description_short,link_rewrite,meta_description,meta_keywords,meta_title,name,available_now,available_later)";
		  $iquery .= " VALUES (".$row["id_product"].",".$id_shop.",".$id_lang.",'','','".$dummyname."','','','','".$dummyname."','','')";
          $ires=dbquery($iquery);			
		}
      }
	}
  }
  
  // Products in "._DB_PREFIX_."product_shop that are not in "._DB_PREFIX_."product
  $query = "SELECT DISTINCT ps.id_product,ps.id_shop FROM ". _DB_PREFIX_."product_shop ps";
  $query .= " LEFT OUTER JOIN ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
  $query .= " WHERE p.id_shop_default is null AND ps.id_product IN (".$products.") ORDER BY ps.id_product";
  $res=dbquery($query);
  while ($row=mysqli_fetch_array($res)) 
  { $iquery = "Insert into ". _DB_PREFIX_."product";
    $iquery .= "       (id_product,id_category_default,id_tax_rules_group, on_sale,online_only,ecotax,minimal_quantity,price,wholesale_price, unity,unit_price_ratio,additional_shipping_cost,customizable, uploadable_files,text_fields, active,redirect_type,".$redirect_value.", available_for_order,available_date,`condition`,show_price,indexed, visibility,cache_default_attribute,advanced_stock_management,date_add, date_upd,pack_stock_type,id_shop_default,reference,supplier_reference,location,width,height,depth,weight,out_of_stock,quantity_discount,cache_is_pack,cache_has_attachments,is_virtual,id_supplier,id_manufacturer,ean13,upc)";
    $iquery .= " select id_product,id_category_default,id_tax_rules_group, on_sale,online_only,ecotax,minimal_quantity,price,wholesale_price, unity,unit_price_ratio,additional_shipping_cost,customizable, uploadable_files,text_fields, active,redirect_type,".$redirect_value.", available_for_order,available_date,`condition`,show_price,indexed, visibility,cache_default_attribute,advanced_stock_management,date_add, date_upd,pack_stock_type,".$row["id_shop"].",'',		'',				'',		0,		0,		0,	0,		2,			0,					0,			0,						0,			0,			0,			0,		0";
    $iquery .= " FROM "._DB_PREFIX_."product_shop WHERE id_product=".$row["id_product"]." AND id_shop=".$row["id_shop"];
    $ires=dbquery($iquery);
  }


  // echo "<p>Products with an empty name: ";
  $query = "SELECT id_product,id_lang,$id_shop FROM ". _DB_PREFIX_."product_lang";
  $query .= " WHERE TRIM(name)='' AND id_product IN (".$products.") ORDER BY id_product";
  $res=dbquery($query);
  while ($row=mysqli_fetch_array($res)) 
  { $squery = "SELECT name FROM ". _DB_PREFIX_."product_lang";
    $squery .= " WHERE TRIM(name)!='' AND id_lang=".$row["id_lang"]." AND id_product=".$row["id_product"];
    $sres=dbquery($squery);
    if(mysqli_num_rows($sres)==0) /* if there is another language that we can copy */
    { $squery = "SELECT name FROM ". _DB_PREFIX_."product_lang";
      $squery .= " WHERE TRIM(name)!='' AND id_product=".$row["id_product"];
      $sres=dbquery($squery);
    }
    if(mysqli_num_rows($sres)==0) /* we still can check if there is an image legend */
    { $squery = "SELeCT legend AS name FROM ". _DB_PREFIX_."image i";
      $squery .= " INNER JOIN ". _DB_PREFIX_."image_lang il on i.id_image=il.id_image";
      $squery .= " WHERE TRIM(legend)!='' AND i.id_product=".$row["id_product"];
      $sres=dbquery($squery);
    }
    if(mysqli_num_rows($sres)!=0)
    { $srow = mysqli_fetch_assoc($sres);
      $uquery = "UPDATE ". _DB_PREFIX_."product_lang SET name='".mescape($srow["name"])."'";
      $uquery .= " WHERE id_lang=".$row["id_lang"]." AND id_shop=".$row["id_shop"]." AND id_product=".$row["id_product"];
       $ures=dbquery($uquery);
    }
    else
    { $srow = mysqli_fetch_assoc($sres);
      $uquery = "UPDATE ". _DB_PREFIX_."product_lang SET name='Dummy".$dummyctr++."'";
      $uquery .= " WHERE id_lang=".$row["id_lang"]." AND id_shop=".$row["id_shop"]." AND id_product=".$row["id_product"];
      $ures=dbquery($uquery);
    } 
  }

  //echo "<p>Products in "._DB_PREFIX_."product that are not in "._DB_PREFIX_."category_product:";
  $query = "SELECT DISTINCT p.id_product, p.id_category_default FROM ". _DB_PREFIX_."product p";
  $query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
  $query .= " WHERE p.id_shop_default is null AND p.id_product IN (".$products.") ORDER BY p.id_product";
  $res=dbquery($query);
  if(mysqli_num_rows($res) != 0)
  { $squery = "SELECT s.id_category AS scat,  FROM ". _DB_PREFIX_."shop s";
    $squery .= " LEFT OUTER JOIN ". _DB_PREFIX_."category c on s.id_category=c.id_category";
    $squery .= " WHERE c.id_category is NOT null ORDER BY s.active DESC LIMIT 1";
    $sres=dbquery($squery);
    if(mysqli_num_rows($sres) == 0) colordie("No shop found with a valid home category");
    $srow=mysqli_fetch_assoc($sres);
    $homecat = $srow["id_category"];
  }
  while ($row=mysqli_fetch_array($res)) 
  { $squery = "SELECT * FROM ". _DB_PREFIX_."category";
    $squery .= " WHERE id_category = '".$row["id_category_default"]."'";
    $sres=dbquery($squery);
    if(mysqli_num_rows($sres) == 0) 
	  $newcat = $homecat;
    else
  	  $newcat = $row["id_category_default"];
    $position = rand(0,100);
    $iquery = "Insert into ". _DB_PREFIX_."category_product";
    $iquery .= " (id_category,id_product,position)";
    $iquery .= " VALUES ('".$newcat."','".$row["id_product"]."','".$position."')";
    $ires=dbquery($iquery);
  }
  
  /* some product problems still need a fix */
}

if((($_POST['task']) == "productremove") && $integrity_delete_allowed)
{ if(!isset($_POST['delproducts'])) colordie("No products provided for deletion!");
  else $delproducts = explode(",",$_POST['delproducts']);
  
  $mytables = array();
  $query = "SHOW TABLES";
  $res = dbquery($query); 
  while($row = mysqli_fetch_row($res))
  { $mytables[] = $row[0];
  }
  
  foreach($delproducts AS $delproduct)
  { if(!ctype_digit($delproduct)) colordie("Invalid product number ".htmlspecialchars($delproduct));
    $res = dbquery("DELETE FROM "._DB_PREFIX_."accessory WHERE id_product_1='".mescape($delproduct)."' OR id_product_2='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."attribute_impact WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."category_product WHERE id_product='".mescape($delproduct)."'");
//    $res = dbquery("DELETE FROM "._DB_PREFIX_."compare_product WHERE id_product='".mescape($delproduct)."'");
	
	$query = "SELECT id_customization_field FROM "._DB_PREFIX_."customization_field";
	$query .= " WHERE id_product='".mescape($delproduct)."'";
	$res = dbquery($query);
    while ($row=mysqli_fetch_array($res)) 
	{ dbquery("DELETE FROM "._DB_PREFIX_."customization_field_lang WHERE customization_field='".mescape($row["id_customization_field"])."'");
	}
	$res = dbquery("DELETE FROM "._DB_PREFIX_."customization_field WHERE id_product='".mescape($delproduct)."'");

	$query = "SELECT fp.id_feature_value FROM "._DB_PREFIX_."feature_product fp";
	$query .= " LEFT JOIN "._DB_PREFIX_."feature_value fv ON fp.id_feature_value=fv.id_feature_value AND fv.custom=1";
	$query .= " WHERE id_product='".mescape($delproduct)."'";
	$res = dbquery($query);
    while ($row=mysqli_fetch_array($res)) 
	{ dbquery("DELETE FROM "._DB_PREFIX_."feature_value WHERE id_feature_value='".mescape($row["id_feature_value"])."'");
      dbquery("DELETE FROM "._DB_PREFIX_."feature_value_lang WHERE id_feature_value='".mescape($row["id_feature_value"])."'");
/*?*/ dbquery("DELETE FROM "._DB_PREFIX_."layered_indexable_feature_value_lang_value WHERE id_feature_value='".mescape($row["id_feature_value"])."'");
	}
    $res = dbquery("DELETE FROM "._DB_PREFIX_."feature_product WHERE id_product='".mescape($delproduct)."'");
 
	$backupdir = $triplepath.'img/archive';
	if(!is_dir($backupdir) && !mkdir($backupdir))
	  $backupdir = $triplepath.'img/tmp';
	$query = "SELECT id_image FROM "._DB_PREFIX_."image WHERE id_product='".mescape($delproduct)."'";
	$res = dbquery($query);
    while ($row=mysqli_fetch_array($res)) 
	{ $id_image = intval($row["id_image"]);
	  dbquery("DELETE FROM "._DB_PREFIX_."image_lang WHERE id_image='".$id_image."'");
	  dbquery("DELETE FROM "._DB_PREFIX_."image_shop WHERE id_image='".$id_image."'");

	  $hasdirs = false; /* when a directory has subdirectories we cannot delete it */
      $ipath =  $triplepath.'img/p'.getpath($id_image)."/";
	  if(!is_dir($ipath))
 	    continue; /* there is no image */
	  $files = scandir($ipath);
	  foreach ($files as $file)
	  { if (($file == ".") || ($file == "..")) continue;
		if (is_dir($ipath.$file)) 
		{ $hasdirs = true;
		  continue;		
		}
		if (preg_match('/^[0-9]+\.[a-zA-Z]+$/', $file)) /* the main image: move to \img\tmp */
		{ rename($ipath.$file, $backupdir.'/'.$file);
		  continue;
		}
		unlink($ipath.$file); /* delete all other files - including index.php */
	  }
	  rmdir($ipath);
	}
	dbquery("DELETE FROM "._DB_PREFIX_."image WHERE id_product='".mescape($delproduct)."'");
	
    $res = dbquery("DELETE FROM "._DB_PREFIX_."layered_price_index WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."layered_product_attribute WHERE id_product='".mescape($delproduct)."'");
//    $res = dbquery("DELETE FROM "._DB_PREFIX_."pack WHERE id_product='".mescape($delproduct)."'");
	$res = dbquery("DELETE FROM "._DB_PREFIX_."product_attachment WHERE id_product='".mescape($delproduct)."'");

	$query = "SELECT id_product_attribute FROM "._DB_PREFIX_."product_attribute";
	$query .= " WHERE id_product='".mescape($delproduct)."'";
	$res = dbquery($query);
    while ($row=mysqli_fetch_array($res)) 
	{ dbquery("DELETE FROM "._DB_PREFIX_."product_attribute_combination WHERE id_product_attribute='".mescape($row["id_product_attribute"])."'");
      dbquery("DELETE FROM "._DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".mescape($row["id_product_attribute"])."'");
      dbquery("DELETE FROM "._DB_PREFIX_."product_attribute_shop WHERE id_product_attribute='".mescape($row["id_product_attribute"])."'");
	}
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_attribute WHERE id_product='".mescape($delproduct)."'");

    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_carrier WHERE id_product='".mescape($delproduct)."'");

/*	$query = "SELECT id_product_comment FROM "._DB_PREFIX_."product_comment";
	$query .= " WHERE id_product='".mescape($delproduct)."'";
	$res = dbquery($query);
    while ($row=mysqli_fetch_array($res)) 
	{ dbquery("DELETE FROM "._DB_PREFIX_."product_comment_grade WHERE id_product_comment='".mescape($row["id_product_comment"])."'");
      dbquery("DELETE FROM "._DB_PREFIX_."product_comment_report WHERE id_product_comment='".mescape($row["id_product_comment"])."'");
      dbquery("DELETE FROM "._DB_PREFIX_."product_comment_usefulness WHERE id_product_comment='".mescape($row["id_product_comment"])."'");
	}
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_comment WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_comment_criterion_product WHERE id_product='".mescape($delproduct)."'");
*/
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_country_tax WHERE id_product='".mescape($delproduct)."'");

	$query = "SELECT * FROM ". _DB_PREFIX_."product_download WHERE id_product='".mescape($delproduct)."'";
    $res = dbquery($query);
	if(mysqli_num_rows($res) > 0)
	{ $row = mysqli_fetch_assoc($res);
	  unlink($triplepath.'download/'.$row["filename"]);
	}
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_download WHERE id_product='".mescape($delproduct)."'");

    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_group_reduction_cache WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_lang WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_sale WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_shop WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_supplier WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product_tag WHERE id_product='".mescape($delproduct)."'");
	if(in_array(_DB_PREFIX_."scene_products",$mytables))
      $res = dbquery("DELETE FROM "._DB_PREFIX_."scene_products WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."search_index WHERE id_product='".mescape($delproduct)."'");
	
	$query = "SELECT id_specific_price_rule FROM "._DB_PREFIX_."specific_price";
	$query .= " WHERE id_product='".mescape($delproduct)."'";
	$res = dbquery($query);
    while (($row=mysqli_fetch_array($res)) && ($row["id_specific_price_rule"]!="0"))
	{ $res = dbquery("DELETE FROM "._DB_PREFIX_."specific_price_rule WHERE id_specific_price_rule='".mescape($row["id_specific_price_rule"])."'");
//      $res = dbquery("DELETE FROM "._DB_PREFIX_."specific_price_rule_condition_group WHERE id_specific_price_rule='".mescape($row["id_specific_price_rule"])."'");
//		Note that ps_specific_price_rule_condition_group links to ps_specific_price_rule_condition
	}
    $res = dbquery("DELETE FROM "._DB_PREFIX_."specific_price_priority WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."specific_price WHERE id_product='".mescape($delproduct)."'");

	$query = "SELECT id_stock FROM "._DB_PREFIX_."stock";
	$query .= " WHERE id_product='".mescape($delproduct)."'";
	$res = dbquery($query);
    while ($row=mysqli_fetch_array($res)) 
	{ $squery = "SELECT id_stock_mvt FROM "._DB_PREFIX_."stock_mvt";
	  $squery .= " WHERE id_product='".mescape($delproduct)."'";
	  $sres = dbquery($squery);
      while ($srow=mysqli_fetch_array($sres)) 
	  { dbquery("DELETE FROM "._DB_PREFIX_."stock_mvt_reason WHERE id_stock_mvt='".mescape($row["id_stock_mvt"])."'");
	    dbquery("DELETE FROM "._DB_PREFIX_."stock_mvt_reason_lang WHERE id_stock_mvt='".mescape($row["id_stock_mvt"])."'");
	  }
	  dbquery("DELETE FROM "._DB_PREFIX_."stock_mvt WHERE id_stock_mvt='".mescape($row["id_stock_mvt"])."'");
	}  
    $res = dbquery("DELETE FROM "._DB_PREFIX_."stock WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."stock_available WHERE id_product='".mescape($delproduct)."'");
	
    $res = dbquery("DELETE FROM "._DB_PREFIX_."warehouse_product_location WHERE id_product='".mescape($delproduct)."'");
    $res = dbquery("DELETE FROM "._DB_PREFIX_."product WHERE id_product='".mescape($delproduct)."'");
  }
}

if((($_POST['task']) == "categoryrepair") && $integrity_repair_allowed)
{ if(!isset($_POST['categorys'])) colordie("No categorys provided!");
  else $categorys = mescape($_POST['categorys']);
  
  // categorys in "._DB_PREFIX_."category that are not in "._DB_PREFIX_."category_shop
  $query = "SELECT c.id_category, c.id_shop_default FROM ". _DB_PREFIX_."category c";
  $query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category_shop cs on c.id_category=cs.id_category";
  $query .= " WHERE cs.id_shop is null AND c.id_category IN (".$categorys.") ORDER BY c.id_category";
  $res=dbquery($query);
  while ($row=mysqli_fetch_array($res)) 
  { 
    $iquery = "Insert into ". _DB_PREFIX_."category_shop";
    $iquery .= " (id_category,id_shop, position)";
    $iquery .= " VALUES (".$row["id_category"].",".$shops[0].",".RAND(0,100).")";
    $ires=dbquery($iquery);
  }
  
// categorys in "._DB_PREFIX_."category that are not in "._DB_PREFIX_."category_lang
  $dummyctr = 0;
  $query = "SELECT id_lang FROM ". _DB_PREFIX_."lang ORDER BY id_lang";
  $res = dbquery($query);
  $languages = array();
  while($row = mysqli_fetch_array($res))
	$languages[] = $row['id_lang'];

  foreach($languages AS $id_lang)
  { foreach($shops AS $id_shop)
    { $query = "SELECT p.id_category FROM ". _DB_PREFIX_."category p";
      $query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category_lang cl on p.id_category=cl.id_category AND cl.id_shop =".$id_shop." AND cl.id_lang=".$id_lang;
      $query .= " WHERE cl.id_shop is null AND p.id_category IN (".$categorys.") ORDER BY p.id_category";
      $res=dbquery($query);
      while ($row=mysqli_fetch_array($res))
      { /* first check if the same language is in a different shop, then any language. if not fill in dummy data */
		$squery = "SELECT * FROM ". _DB_PREFIX_."category_lang WHERE id_category=".$row["id_category"]." AND id_lang=".$id_lang." LIMIT 1";
        $sres=dbquery($squery);
		if(mysqli_num_rows($sres)==0) /* if there is another language that we can copy */
		{ $squery = "SELECT * FROM ". _DB_PREFIX_."category_lang WHERE id_category=".$row["id_category"]." LIMIT 1";
          $sres=dbquery($squery);
		}
		if(mysqli_num_rows($sres)>0) /* if there is another language that we can copy */
		{ $srow = mysqli_fetch_array($sres);
		  $iquery = "Insert into ". _DB_PREFIX_."category_lang";
		  $iquery .= " (id_category,id_shop,id_lang,name,description,link_rewrite,meta_title,meta_keywords,meta_description)";
		  $iquery .= " select id_category,'".$id_shop."','".$id_lang."',name,description,link_rewrite,meta_title,meta_keywords,meta_description";
		  $iquery .= " FROM "._DB_PREFIX_."category_lang WHERE id_category=".$row["id_category"]." AND id_lang=".$srow["id_lang"];
          $ires=dbquery($iquery);
		}
		else
		{ $dummyname = "dummycat".$dummyctr++;
		  $iquery = "Insert into ". _DB_PREFIX_."category_lang";
		  $iquery .= " 				(id_category,		id_shop,	id_lang,			name,description,link_rewrite,meta_title,meta_keywords,meta_description)";
		  $iquery .= " VALUES (".$row["id_category"].",".$id_shop.",".$id_lang.",'".$dummyname."','','".$dummyname."','','','')";
          $ires=dbquery($iquery);			
		}
      }
	}
  }
  
/*
// "<p>Categories without valid parent: "; - put them under the homecat
// Disabled because it is not clear how this wil work on the tree
$query = "SELECT DISTINCT c.id_category FROM ". _DB_PREFIX_."category c";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category c2 on c.id_parent=c2.id_category";
$query .= " WHERE c2.id_category is null AND c.id_parent!='0' AND c.id_category IN (".$categorys.") ORDER BY c.id_category";
$res=dbquery($query);
if(mysqli_num_rows($res) != 0)
{ $squery = "SELECT s.id_category AS scat,  FROM ". _DB_PREFIX_."shop s";
  $squery .= " LEFT OUTER JOIN ". _DB_PREFIX_."category c on s.id_category=c.id_category";
  $squery .= " WHERE c.id_category is NOT null ORDER BY s.active DESC LIMIT 1";
  $sres=dbquery($squery);
  if(mysqli_num_rows($sres) == 0) colordie("No shop found with a valid home category");
  $srow=mysqli_fetch_assoc($sres);
  $homecat = $srow["id_category"];
}
while ($row=mysqli_fetch_array($res)) 
{ $uquery = "UPDATE ". _DB_PREFIX_."category SET id_parent='".$homecat."'";
  $uquery .= " WHERE id_category=".$row["id_category"];
  $ures=dbquery($uquery);
}
*/
  
  // categorys in "._DB_PREFIX_."category_shop that are not in "._DB_PREFIX_."category
  // This cannot be handled as there are too many delicate fields like nleft,nright,is_root and id_parent
}
  
	
	echo "<br>repairing";
