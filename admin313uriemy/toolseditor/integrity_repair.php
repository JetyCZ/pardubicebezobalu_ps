<?php
if(!@include 'approve.php') die( "approve.php was not found!");

if(!isset($_POST['task'])) colordie("No argument provided!");

$query = "SELECT id_shop FROM ". _DB_PREFIX_."shop ORDER BY active,id_shop";
$res = dbquery($query);
if(mysqli_num_rows($res)==0)
	colordie("No active shops available!");
$shops = array();
while($row = mysqli_fetch_array($res))
	$shops[] = $row['id_shop'];

if(($_POST['task']) == "productrepair")
{ if(!isset($_POST['products'])) colordie("No products provided!");
  else $products = mescape($_POST['products']);
  
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
    $iquery .= "            (id_product,id_shop,id_category_default,id_tax_rules_group, on_sale,online_only,ecotax,minimal_quantity,price,wholesale_price, unity,unit_price_ratio,additional_shipping_cost,customizable, uploadable_files,text_fields, active,redirect_type,id_product_redirected, available_for_order,available_date,`condition`,show_price,indexed, visibility,cache_default_attribute,advanced_stock_management,date_add, date_upd,pack_stock_type)";
    $iquery .= " select id_product,'".$shop."',id_category_default,id_tax_rules_group, on_sale,online_only,ecotax,minimal_quantity,price,wholesale_price, unity,unit_price_ratio,additional_shipping_cost,customizable, uploadable_files,text_fields, active,redirect_type,id_product_redirected, available_for_order,available_date,`condition`,show_price,indexed, visibility,cache_default_attribute,advanced_stock_management,date_add, date_upd,pack_stock_type";
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
		{ $dummyname = "dummy".$dummyctr++;
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
    $iquery .= "       (id_product,id_category_default,id_tax_rules_group, on_sale,online_only,ecotax,minimal_quantity,price,wholesale_price, unity,unit_price_ratio,additional_shipping_cost,customizable, uploadable_files,text_fields, active,redirect_type,id_product_redirected, available_for_order,available_date,`condition`,show_price,indexed, visibility,cache_default_attribute,advanced_stock_management,date_add, date_upd,pack_stock_type,id_shop_default,reference,supplier_reference,location,width,height,depth,weight,out_of_stock,quantity_discount,cache_is_pack,cache_has_attachments,is_virtual,id_supplier,id_manufacturer,ean13,upc)";
    $iquery .= " select id_product,id_category_default,id_tax_rules_group, on_sale,online_only,ecotax,minimal_quantity,price,wholesale_price, unity,unit_price_ratio,additional_shipping_cost,customizable, uploadable_files,text_fields, active,redirect_type,id_product_redirected, available_for_order,available_date,`condition`,show_price,indexed, visibility,cache_default_attribute,advanced_stock_management,date_add, date_upd,pack_stock_type,".$row["id_shop"].",'',		'',				'',		0,		0,		0,	0,		2,			0,					0,			0,						0,			0,			0,			0,		0";
    $iquery .= " FROM "._DB_PREFIX_."product_shop WHERE id_product=".$row["id_product"]." AND id_shop=".$row["id_shop"];
    $ires=dbquery($iquery);
  }
}

/* some product problems still need a fix */

if(($_POST['task']) == "categoryrepair")
{ if(!isset($_POST['categorys'])) colordie("No categorys provided!");
  else $categorys = mescape($_POST['categorys']);
  echo "Handling catgsi";
  
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
		{ $dummyname = "dummy".$dummyctr++;
		  $iquery = "Insert into ". _DB_PREFIX_."category_lang";
		  $iquery .= " 				(id_category,		id_shop,	id_lang,			name,description,link_rewrite,meta_title,meta_keywords,meta_description)";
		  $iquery .= " VALUES (".$row["id_category"].",".$id_shop.",".$id_lang.",'".$dummyname."','','".$dummyname."','','','')";
          $ires=dbquery($iquery);			
		}
      }
	}
  }
  
  // categorys in "._DB_PREFIX_."category_shop that are not in "._DB_PREFIX_."category
  // This cannot be handled as there are too much delicate fields like nleft,nright,is_root and id_parent
}
  
	
	echo "<br>repairing";
