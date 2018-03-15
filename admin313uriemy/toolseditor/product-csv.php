<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['search_txt1'])) $input['search_txt1'] = "";
$search_txt1 = mysqli_real_escape_string($conn,$input['search_txt1']);
if(!isset($input['search_txt2'])) $input['search_txt2'] = "";
$search_txt2 = mysqli_real_escape_string($conn,$input['search_txt2']);
if(!isset($input['search_cmp1'])) $input['search_cmp1'] = "in";
if(!isset($input['search_cmp2'])) $input['search_cmp2'] = "in";
if(!isset($input['search_fld1']) || ($input['search_fld1'] == "")) $input['search_fld1'] = "main fields";
$search_fld1 = $input['search_fld1'];
if(!isset($input['search_fld2']) || ($input['search_fld2'] == "")) $input['search_fld2'] = "main fields";
$search_fld2 = $input['search_fld2'];
if(!isset($input['startrec']) || (trim($input['startrec']) == '')) $input['startrec']="0";
if(!isset($input['numrecs'])) $input['numrecs']="100";
if(!isset($input['id_category'])) {$id_category=0;} else {$id_category = intval($input['id_category']);}
if(!isset($input['id_shop'])) $input['id_shop']="1";
$id_shop = intval($input["id_shop"]);
if(!isset($input['startdate'])) $input['startdate']="";
if(!isset($input['enddate'])) $input['enddate']="";
if((!isset($input['rising'])) || ($input['rising'] == "ASC")) {$rising = "ASC";} else {$rising = "DESC";}
if(!isset($fieldsorder)) $fieldsorder = array("name"); /* if not set, use alphabetical order, but with name in front */
if(!isset($input['order']))
{ if($id_category == 0) {$input['order']="id_product";} else {$input['order']="position";}
}
if(!isset($input['id_lang'])) $input['id_lang']="";
if(!isset($input['active'])) {$input['active']="";}
if(!isset($input['imgformat'])) {$input['imgformat']="";}

  if(empty($input['fields'])) // if not set, set default set of active fields
  { $input['fields'] = array("name","VAT","price", "active","category", "ean", "description", "shortdescription", "image");
  }
  $infofields = array();
  $if_index = 0;
   /* [0]title, [1]keyover, [2]source, [3]display(0=not;1=yes;2=edit;), [4]fieldwidth(0=not set), 
      [5]align(0=default;1=right), [6]sortfield, [7]Editable, [8]table */
  define("HIDE", 0); define("DISPLAY", 1); define("EDIT", 2);  // display
  define("LEFT", 0); define("RIGHT", 1); // align
  define("NO_SORTER", 0); define("SORTER", 1); /* sortfield => 0=no escape removal; 1=escape removal; */
  define("NOT_EDITABLE", 0); define("INPUT", 1); define("TEXTAREA", 2); define("DROPDOWN", 3); define("BINARY", 4); define("EDIT_BTN", 5);  /* title, keyover, source, display(0=not;1=yes;2=edit), fieldwidth(0=not set), align(0=default;1=right), sortfield */
   /* sortfield => 0=no escape removal; 1=escape removal; 2 and higher= escape removal and n lines textarea */
  $infofields[$if_index++] = array("","", "", DISPLAY, 0, LEFT, 0,0);
  $infofields[$if_index++] = array("id","", "id_product", DISPLAY, 0, RIGHT, NO_SORTER,NOT_EDITABLE);
  
  $field_array = array(
   "accessories" => array("accessories",null, "accessories", DISPLAY, 0, LEFT, null, INPUT, "accessories"),
   "active" => array("active",null, "active", DISPLAY, 0, LEFT, null, BINARY, "ps.active"),
   "aShipCost" => array("aShipCost",null, "additional_shipping_cost", DISPLAY, 0, LEFT, null, INPUT, "ps.additional_shipping_cost"),
   "attachmnts" => array("attachmnts",null, "attachmnts", DISPLAY, 0, LEFT, null, INPUT, ""), 
   "available_now" => array("available_now",null, "available_now", DISPLAY, 0, LEFT, null, INPUT, "pl.available_now"),
   "available_later" => array("available_later",null, "available_later", DISPLAY, 0, LEFT, null, INPUT, "pl.available_later"), 
   "available_date" => array("available_date",null, "available_date", DISPLAY, 0, LEFT, null, BINARY, "ps.available_date"),
   /* available combines two of Prestashop's datafields: available_for_order and show_price */
   "availorder" => array("availorder",null, "available_for_order", DISPLAY, 0, LEFT, null, BINARY, "ps.available_for_order"),
   "carrier" => array("carrier",null, "carrier", DISPLAY, 0, LEFT, null, DROPDOWN, "cr.name"),
   "category" => array("category",null, "id_category_default", DISPLAY, 0, LEFT, null, DROPDOWN, "ps.id_category_default"),
   "combinations" => array("combinations",null, "combinations", DISPLAY, 0, LEFT, 0, 0, ""),
   "condition" => array("condition",null, "condition", DISPLAY, 0, LEFT, null, DROPDOWN, "ps.condition"),
   "date_add" => array("date_add",null, "date_add", DISPLAY, 0, LEFT, null, BINARY, "ps.date_add"),  
   "date_upd" => array("date_upd",null, "date_upd", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "ps.date_upd"),
   "description" => array("description",null, "description", DISPLAY, 0, LEFT, null, TEXTAREA, "pl.description"),
   "description_short" => array("description_short",null, "description_short", DISPLAY, 0, LEFT, null, TEXTAREA, "pl.description_short"),
   "discount" => array("discount",null, "discount", DISPLAY, 0, LEFT, null, INPUT, "discount"),
   "ean" => array("ean",null, "ean13", DISPLAY, 200, LEFT, null, INPUT, "p.ean13"),
   "ecotax" => array("ecotax",null, "ecotax", DISPLAY, 200, LEFT, null, INPUT, "ps.ecotax"),
   "isbn" => array("isbn",null, "isbn", DISPLAY, 0, LEFT, null, INPUT, "p.isbn"),
   "id_product" => array("id_product",array("id","id","id"), "id_product", DISPLAY, 0, RIGHT, null,NOT_EDITABLE, "p.id_product"),
   "image" => array("image",null, "name", DISPLAY, 0, LEFT, 0, EDIT_BTN, ""), // name here is a dummy that is not used
   "link_rewrite" => array("link_rewrite",null, "link_rewrite", DISPLAY, 0, LEFT, null, INPUT, "pl.link_rewrite"),
   "manufacturer" => array("manufacturer",null, "manufacturer", DISPLAY, 0, LEFT, null, DROPDOWN, "m.name"),
   "meta_description" => array("meta_description",null, "meta_description", DISPLAY, 0, LEFT, null, TEXTAREA, "pl.meta_description"),
   "meta_keywords" => array("meta_keywords",null, "meta_keywords", DISPLAY, 0, RIGHT, null, TEXTAREA, "pl.meta_keywords"),
   "meta_title" => array("meta_title",null, "meta_title", DISPLAY, 0, LEFT, null, INPUT, "pl.meta_title"),
   "minimal_quantity" => array("minimal_quantity",null, "minimal_quantity", DISPLAY, 0, LEFT, null, INPUT, "p.sminimal_quantity"),
   "name" => array("name",null, "name", DISPLAY, 0, LEFT, null, INPUT, "pl.name"),
   "online_only" => array("online_only",null, "online_only", DISPLAY, 0, LEFT, null, BINARY, "ps.online_only"),
   "on_sale" => array("on_sale",null, "on_sale", DISPLAY, 0, LEFT, null, BINARY, "ps.on_sale"),
   "out_of_stock" => array("out_of_stock",null, "out_of_stock", DISPLAY, 0, LEFT, null, DROPDOWN, "s.out_of_stock"),
   "pack_stock_type" => array("pack_stock_type",null, "pack_stock_type", DISPLAY, 0, LEFT, null, DROPDOWN, "ps.pack_stock_type"),
   "position" => array("position",null, "position", DISPLAY, 0, RIGHT, null, NOT_EDITABLE, "cp.position"),  
   "price" => array("price",null, "price", DISPLAY, 200, LEFT, null, INPUT, "ps.price"),
   "priceVAT" => array("priceVAT",null, "priceVAT", DISPLAY, 0, LEFT, null, INPUT, "priceVAT"),
   "quantity" => array("quantity",null, "quantity", DISPLAY, 0, LEFT, null, TEXTAREA, "s.quantity"),
   "reference" => array("reference",null, "reference", DISPLAY, 200, LEFT, null, INPUT, "p.reference"),
   /* stockflags combines two of Prestashop's datafields: depends_on_stock and advanced_stock_management */
   "shipdepth" => array("shipdepth",null, "depth", DISPLAY, 0, LEFT, null, INPUT, "p.depth"), 
   "shipheight" => array("shipheight",null, "height", DISPLAY, 0, LEFT, null, INPUT, "p.height"),
   "shipweight" => array("shipweight",null, "weight", DISPLAY, 0, LEFT, null, INPUT, "p.weight"),
   "shipwidth" => array("shipwidth",null, "width", DISPLAY, 0, LEFT, null, INPUT, "p.width"),
   "shops" => array("shops",null, "id_shop", DISPLAY, 0, LEFT, null, BINARY, "ps.id_shop"),
   "show_condition" => array("show_condition",null, "show_condition", DISPLAY, 0, LEFT, null, BINARY, "ps.show_condition"),
   "show_price" => array("show_price",null, "show_price", DISPLAY, 0, LEFT, null, BINARY, "ps.show_price"),   
   "stockflags" => array("stockflags",null, "depends_on_stock", DISPLAY, 0, LEFT, null, BINARY, "sa.depends_on_stock"),   
   "supplier" => array("supplier",null, "supplier", DISPLAY, 0, LEFT, null, INPUT, "su.name"),
   "tags" => array("tags",null, "tags", DISPLAY, 0, LEFT, null, TEXTAREA, "tg.name"),
   "unit" => array("unit",null, "unity", DISPLAY, 0, LEFT, null, INPUT, "ps.unity"),
   "unitPrice" => array("unitPrice",null, "unit_price_ratio", DISPLAY, 0, LEFT, null, INPUT, "ps.unit_price_ratio"),   
   "upc" => array("upc",null, "upc", DISPLAY, 200, LEFT, null, INPUT, "p.upc"),   
   "VAT" => array("VAT",null, "rate", DISPLAY, 0, LEFT, null, DROPDOWN, "t.rate"),
   "virtualp" => array("virtualp",null, "filename", DISPLAY, 0, LEFT, null, NOT_EDITABLE, ""), /* virtual product */
   "visibility" => array("visibility",null, "visibility", DISPLAY, 0, LEFT, null, INPUT, "ps.visibility"), 
   "warehousing" => array("warehousing",null, "quantity", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "w.id_warehouse"),
   "wholesaleprice" => array("wholesaleprice",null, "wholesale_price", DISPLAY, 0, LEFT, null, INPUT, "ps.wholesale_price"),
  
	/* statistics */
   "visits" => array("visits",null, "visitcount", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "visitcount"),
   "visitz" => array("visitz",null, "visitedpages", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "visitedpages"),
   "salescnt" => array("salescnt",null, "salescount", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "salescount"),
   "revenue" => array("revenue",null, "revenue", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "revenue"),
   "orders" => array("orders",null, "ordercount", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "ordercount"),
   "buyers" => array("buyers",null, "buyercount", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "buyercount")
   ); 
  
  /* put the language specific data at position 1 in the field array */
  foreach($field_array as $key => $value)
  { if(isset($screentext_pe[$key]) && isset($screentext_pe[$key][1]))
	{ if($screentext_pe[$key][1] == "")
	    $screentext_pe[$key][1] = $screentext_pe[$key][0];
      $field_array[$key][1] = $screentext_pe[$key];
	}
	else /* when users use old translation files that don't support this entry */
	 $field_array[$key][1] = array($key,$key,"");
  }

  /* get the infofields array with the active fields. Put the fields pre-sorted in the $fieldsorder array in Settings1.php first */
  $infofields[$if_index++] = $field_array["id_product"];
  foreach($fieldsorder AS $ofield)
  { if (in_array($ofield, $input["fields"]))
    { 	$infofields[$if_index++] = $field_array[$ofield];
	}
  }
  
  foreach($field_array AS $key => $value)
  { if ((in_array($key, $input["fields"])) && (!in_array($key, $fieldsorder)))
    { $infofields[$if_index++] = $value;
	}
  }
$rewrite_settings = get_rewrite_settings();

/* get default language: we use this for the categories, manufacturers */
$query="select value, l.name, l.iso_code from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$def_lang = $row['value'];
$def_langname = $row['name'];
$iso_code = $row['iso_code'];

/* Get default language if none provided */
if($input['id_lang'] == "") 
  $id_lang = $def_lang;
else
{ $query="select name, iso_code from ". _DB_PREFIX_."lang WHERE id_lang='".(int)$input['id_lang']."'";
  $res=dbquery($query);
  $row = mysqli_fetch_array($res);
  $languagename = $row['name'];
  $id_lang = $input['id_lang'];
  $iso_code = $row['iso_code'];
}

/* Get default country for the VAT tables and calculations */
$query="select l.name, id_country from ". _DB_PREFIX_."configuration f, "._DB_PREFIX_."country_lang l";
$query .= " WHERE f.name='PS_COUNTRY_DEFAULT' AND f.value=l.id_country ORDER BY id_lang IN('".$def_lang."','1') DESC"; /* the construction with the languages should select all languages with def_lang and '1' first */
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$countryname = $row['name'];
$id_country = $row["id_country"];

/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];
$shop_group_name = $row["name"];

/* look for double category names */
  $duplos = array();
  $query = "select name,count(*) AS duplocount from ". _DB_PREFIX_."category_lang WHERE id_lang='".$def_lang."' AND id_shop='".$id_shop."' GROUP BY name HAVING duplocount > 1";
  $res=dbquery($query);
  while ($row=mysqli_fetch_array($res)) 
  {  $duplos[] = $row["name"];
  }
  
/* make category block */
  $query = "select id_category,name from ". _DB_PREFIX_."category_lang WHERE id_lang='".$def_lang."' AND id_shop='".$id_shop."' ORDER BY name";
  $res=dbquery($query);
  $category_names = array();
  $allcats = array();
  $x=0;
  while ($row=mysqli_fetch_array($res)) 
  { if(in_array($row['name'], $duplos))
	  $name = $row['name'].$row['id_category'];
	else
	  $name = $row['name'];
    $category_names[$row['id_category']] = $name;
  } 
  
  /* make supplier names list */
  $query = "select id_supplier,name from ". _DB_PREFIX_."supplier ORDER BY id_supplier";
  $res=dbquery($query);
  $supplier_names = array();
  while ($row=mysqli_fetch_array($res)) 
  { $supplier_names[$row['id_supplier']] = $row['name'];
  } 
  
/* Make blocks for features */
$query = "SELECT fl.id_feature, name FROM ". _DB_PREFIX_."feature_lang fl";
$query .= " LEFT JOIN ". _DB_PREFIX_."feature_shop fs ON fs.id_feature = fl.id_feature";
$query .= " WHERE id_lang='".$id_lang."' AND id_shop='".$id_shop."'";
$query .= " ORDER BY id_feature";
$res = dbquery($query);
$features = array();
$featureblocks = array();
$featurecount = 0;
$featurelist = array();
$featurekeys = array();
while($row = mysqli_fetch_array($res))
{ $features[$row['id_feature']] = $row['name'];
  if(in_array($row['name'], $input["fields"]))
  { $featurelist[$row['id_feature']] = $row['name'];
    $featurekeys[] = $row['id_feature'];
	$block = '<option value="">Select '.str_replace("'","\'",$row['name']).'</option>';
    $fquery = "SELECT v.id_feature_value, value FROM ". _DB_PREFIX_."feature_value v";
	$fquery .= " LEFT JOIN ". _DB_PREFIX_."feature_value_lang vl ON v.id_feature_value = vl.id_feature_value AND vl.id_lang='".$id_lang."'";
	$fquery .= " WHERE v.id_feature='".$row['id_feature']."' AND v.custom='0'";
	$fres = dbquery($fquery);
	if(mysqli_num_rows($fres) == 0)
		$featureblocks[$featurecount++] = "";
	else
	{ $fvalues = array();
	  while($frow = mysqli_fetch_array($fres))
	  {  $fvalues[$frow['id_feature_value']] = $frow['value'];
	  }
	  natsort($fvalues);
	  foreach($fvalues AS $key => $value)
	  { $block .= '<option value="'.$key.'">'.str_replace("'","\'",$value).'</option>';
	  }
	  $featureblocks[$featurecount++] = $block."</select>";
	}
  }
}

/* making shop block */
    $shopblock = "";
	$shops = array();
	$query=" select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	while ($shop=mysqli_fetch_array($res)) {
		if ($shop['id_shop']==$input['id_shop']) {$selected=' selected="selected" ';} else $selected="";
	        $shopblock .= '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
		$shops[] = $shop['name'];
	}	


/* Make the discount blocks */
/* 						0				1		2		3		  4			5			6		7				8			9	 		10	11*/
/* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_type, from, to */
  if(in_array("discount", $input["fields"]))
  { $currencyblock = "";
    $currencies = array();
	$query=" select id_currency,iso_code from ". _DB_PREFIX_."currency WHERE deleted='0' AND active='1' ORDER BY name";
	$res=dbquery($query);
	while ($currency=mysqli_fetch_array($res)) {
		$currencyblock .= '<option  value="'.$currency['id_currency'].'" >'.$currency['iso_code'].'</option>';
		$currencies[] = $currency['iso_code'];
	}
	
	$countryblock = "";
	$query=" select id_country,name from ". _DB_PREFIX_."country_lang WHERE id_lang='".$id_lang."' ORDER BY name";
	$res=dbquery($query);
	while ($country=mysqli_fetch_array($res)) {
		$countryblock .= '<option  value="'.$country['id_country'].'" >'.$country['id_country']."-".$country['name'].'</option>';
	}

	$groupblock = "";
	$query=" select id_group,name from ". _DB_PREFIX_."group_lang WHERE id_lang='".$id_lang."' ORDER BY id_group";
	$res=dbquery($query);
	while ($group=mysqli_fetch_array($res)) {
		$groupblock .= '<option  value="'.$group['id_group'].'" >'.$group['id_group']."-".$group['name'].'</option>';
	}
  }
  
$categories = array();
if(isset($input['subcats']))
  get_subcats($id_category, $categories);
else 
  $categories = array($id_category);
$cats = join(',',$categories);

$wheretext = $nottext1 = "";
if ($search_txt1 != "")
{  if($input['search_cmp1'] == "gt")
	 $inc = "< '".$search_txt1."'";
   else if($input['search_cmp1'] == "gte")
	 $inc = "<= '".$search_txt1."'";   
   else if(($input['search_cmp1'] == "eq") || ($input['search_cmp1'] == "not_eq"))
	 $inc = "= '".$search_txt1."'"; 
   else if($input['search_cmp1'] == "lte")
	 $inc = ">= '".$search_txt1."'";
   else if($input['search_cmp1'] == "lt")
	 $inc = "> '".$search_txt1."'";
   else   /* default = "in": also for "not_in" */
	 $inc = "like '%".$search_txt1."%'";
   if(($input['search_cmp1'] == "not_in") || ($input['search_cmp1'] == "not_eq"))
	   $nottext1 = "NOT ";
   if($search_fld1 == "main fields")
     $wheretext .= " AND ".$nottext1." (p.reference ".$inc." or pl.name ".$inc." or pl.description ".$inc."  or pl.description_short ".$inc." or m.name ".$inc." or p.id_product='".$search_txt1."') ";
   else if(($search_fld1 == "ps.id_category_default") || ($search_fld1 == "p.id_product"))
     $wheretext .= " AND ".$nottext1.$search_fld1." ".$inc." ";
   else if ($search_fld1 == "cr.name")
	 $wheretext .= " AND ".$nottext1." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_carrier pc LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0 WHERE pc.id_product = p.id_product AND cr.name ".$inc.")";
   else if ($search_fld1 == "tg.name")
   { $wheretext .= " AND ".$nottext1." EXISTS (SELECT NULL FROM ". _DB_PREFIX_."tag tg";
     $wheretext .= " LEFT JOIN ". _DB_PREFIX_."product_tag pt ON pt.id_tag=tg.id_tag WHERE tg.name ".$inc." AND p.id_product=pt.id_product AND tg.id_lang='".$id_lang."') ";
   }
   else if($search_fld1 == "su.name")
	 $wheretext .= " AND ".$nottext1." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_supplier psu LEFT JOIN ". _DB_PREFIX_."supplier su ON psu.id_supplier=su.id_supplier WHERE psu.id_product = p.id_product AND su.name ".$inc.")";
   else if($search_fld1 == "priceVAT")
	 $wheretext .= " AND ".$nottext1." (ROUND(((rate/100)+1)*ps.price,2) ".$inc.")";
   else if($search_fld1 == "w.id_warehouse")
   { $wheretext .= " AND (".$nottext1." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."warehouse_product_location wpl LEFT JOIN ". _DB_PREFIX_."warehouse w ON wpl.id_warehouse=w.id_warehouse WHERE wpl.id_product = p.id_product AND w.name ".$inc.")";
	 $wheretext .= " OR ".$nottext1." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."stock st LEFT JOIN ". _DB_PREFIX_."warehouse w ON st.id_warehouse=w.id_warehouse WHERE st.id_product = p.id_product AND w.name ".$inc."))";
   }
   else if($search_fld1 == "cl.id_category")
   { if($input['search_cmp1'] == "not_in")
	   $wheretext .= " AND NOT EXISTS(SELECT NULL FROM ". _DB_PREFIX_."category_product cpp WHERE cpp.id_product = p.id_product AND cpp.id_category='".str_replace("'","",$search_txt1)."')";
	 else
	   $wheretext .= " AND ".$nottext1." cp1.id_category ".$inc." ";
   }
   else if($search_fld1 == "combinations")  /* combinations */
   { $wheretext .= " AND ".$nottext1." EXISTS(SELECT NULL FROM 
   ". _DB_PREFIX_."product_attribute pa LEFT JOIN ". _DB_PREFIX_."product_attribute_combination pac 
   ON pa.id_product_attribute=pac.id_product_attribute LEFT JOIN ". _DB_PREFIX_."attribute_lang al
   ON pac.id_attribute = al.id_attribute WHERE pa.id_product=p.id_product AND al.name ".$inc.")";
   }
   else if($search_fld1 == "p.reference") /* check here also for supplier reference */
   { $wheretext .= " AND (".$nottext1.$search_fld1." ".$inc." ";
   	 $wheretext .= " OR ".$nottext1." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_supplier psu WHERE psu.id_product = p.id_product AND psu.product_supplier_reference ".$inc."))";
   }
   else if(substr($search_fld1,0,7) == "sattrib") /* attribute search */
   { $id_attribute_group = substr($search_fld1,7);
     $wheretext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_attribute pa 
   LEFT JOIN ". _DB_PREFIX_."product_attribute_combination pac ON pa.id_product_attribute=pac.id_product_attribute 
   LEFT JOIN ". _DB_PREFIX_."attribute_lang al ON pac.id_attribute = al.id_attribute 
   LEFT JOIN ". _DB_PREFIX_."attribute atr ON pac.id_attribute = atr.id_attribute 
   WHERE pa.id_product=p.id_product AND atr.id_attribute_group=".$id_attribute_group." AND ".$nottext1." (al.name ".$inc."))";
   }
   else if(substr($search_fld1,0,7) == "sfeatur") /* feature search */
   { $id_feature = substr($search_fld1,7);
     $wheretext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."feature_product fp 
   LEFT JOIN ". _DB_PREFIX_."feature_value fv ON fp.id_feature_value=fv.id_feature_value 
   LEFT JOIN ". _DB_PREFIX_."feature_value_lang fvl ON fv.id_feature_value=fvl.id_feature_value
   WHERE fp.id_product=p.id_product AND fp.id_feature=".$id_feature." AND ".$nottext1." (fvl.value ".$inc."))";
   }
   else if(($search_fld1 != "discount") && ($search_fld1 != "virtualp"))
     $wheretext .= " AND ".$nottext1.$search_fld1." ".$inc." ";
}
if($search_fld1 == "discount") /* this works also when search_txt field is empty */
	 $wheretext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."specific_price sp WHERE sp.id_product = p.id_product)";
if($search_fld1 == "virtualp")
	 $wheretext .= " AND (p.is_virtual = 1)";
if (($search_txt1 == "") && (($search_fld1 == "combinations") || ($search_fld1 == "cr.name") || (substr($search_fld1,0,7) == "sattrib") || (substr($search_fld1,0,7) == "sfeatur")))
{ if(($input['search_cmp1'] == "not_eq") || ($input['search_cmp1'] == "not_in"))
	 $nottext1 = "NOT";
}

if(($search_fld1 == "combinations") && ($search_txt1 == "")) /* combinations: this works when search_txt field is empty */
	 $wheretext .= " AND ".$nottext1." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_attribute pax WHERE pax.id_product = p.id_product)";
if(($search_fld1 == "cr.name") && ($search_txt1 == "")) /* carriers: this works when search_txt field is empty */
	 $wheretext .= " AND ".$nottext1." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_carrier pcx WHERE pcx.id_product = p.id_product)";
else if((substr($search_fld1,0,7) == "sattrib") && ($search_txt1 == ""))  /* attribute search */
   { $id_attribute_group = substr($search_fld1,7);
     $wheretext .= " AND ".$nottext1." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_attribute pa 
   LEFT JOIN ". _DB_PREFIX_."product_attribute_combination pac ON pa.id_product_attribute=pac.id_product_attribute 
   LEFT JOIN ". _DB_PREFIX_."attribute_lang al ON pac.id_attribute = al.id_attribute 
   LEFT JOIN ". _DB_PREFIX_."attribute atr ON pac.id_attribute = atr.id_attribute 
   WHERE pa.id_product=p.id_product AND atr.id_attribute_group=".$id_attribute_group.")";
   }
else if((substr($search_fld1,0,7) == "sfeatur") && ($search_txt1 == "")) /* feature search */
   { $id_feature = substr($search_fld1,7);
     $wheretext .= " AND ".$nottext1." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."feature_product fp 
   LEFT JOIN ". _DB_PREFIX_."feature_value fv ON fp.id_feature_value=fv.id_feature_value 
   LEFT JOIN ". _DB_PREFIX_."feature_value_lang fvl ON fv.id_feature_value=fvl.id_feature_value
   WHERE fp.id_product=p.id_product AND fp.id_feature=".$id_feature.")";
   }

$catfrags = array(); 
if (($input['search_txt2'] != "") && ($search_fld2 != "discount"))
{  $nottext2 = "";
   if(($input['search_cmp2'] == "not_in") || ($input['search_cmp2'] == "not_eq"))
	   $nottext2 = "NOT ";
   if(in_array($input['search_cmp2'], array("eq","not_eq","in","not_in")))
   { $frags = explode(" ",$search_txt2); /* changed from comma in earlier versions */
     if(($search_fld2 == "cl.id_category") || ($search_fld2 == "ps.id_category_default"))
     { 
	   foreach($frags AS $clp)
	   { if(stripos($clp,'s')) /* "6s" means category 6 with subcategories */
		   get_subcats(str_replace('s','',$clp), $catfrags); /* this function will place the results in the categories array */
	     else if($clp != 0)
		   $catfrags[] = $clp;
	   }
	   $frags = $catfrags;
	 }
   }
   else
	   $frags = array($search_txt2);
   
   $wheretext .= " AND ".$nottext2." (";
   $first = true;
   foreach($frags AS $frag)
   { if($input['search_cmp2'] == "gt")
	   $inc = "< '".$frag."'";
     else if($input['search_cmp2'] == "gte")
	   $inc = "<= '".$frag."'";
     else if(($input['search_cmp2'] == "eq") || ($input['search_cmp2'] == "not_eq"))
	   $inc = "= '".$frag."'"; 
     else if($input['search_cmp2'] == "lte")
	   $inc = ">= '".$frag."'";
     else if($input['search_cmp2'] == "lt")
	   $inc = "> '".$frag."'";
     else   /* default = "in": also for "not_in" */
	   $inc = "like '%".$frag."%'";

     if($first) $first = false; else $wheretext .= " OR ";

     if($search_fld2 == "main fields") 
       $wheretext .= "p.reference ".$inc." or pl.name ".$inc." or pl.description ".$inc."  or pl.description_short ".$inc." or m.name ".$inc." or p.id_product='".$search_txt2."' ";
     else if(($search_fld2 == "ps.id_category_default") || ($search_fld2 == "p.id_product"))
       $wheretext .= $search_fld2." ".$inc." ";
     else if ($search_fld2 == "cr.name")
	   $wheretext .= " EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_carrier pc LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0 WHERE pc.id_product = p.id_product AND cr.name ".$inc.")";
     else if ($search_fld2 == "tg.name")
     { $wheretext .= " EXISTS (SELECT NULL FROM ". _DB_PREFIX_."tag tg";
       $wheretext .= " LEFT JOIN ". _DB_PREFIX_."product_tag pt ON pt.id_tag=tg.id_tag WHERE tg.name ".$inc." AND p.id_product=pt.id_product AND tg.id_lang='".$id_lang."') ";
     }
     else if($search_fld2 == "su.name")
	   $wheretext .= " EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_supplier psu LEFT JOIN ". _DB_PREFIX_."supplier su ON psu.id_supplier=su.id_supplier WHERE psu.id_product = p.id_product AND su.name ".$inc.")";
     else if($search_fld2 == "priceVAT")
	   $wheretext .= " (ROUND(((rate/100)+1)*ps.price,2) ".$inc.")";
     else if($search_fld2 == "w.id_warehouse")
	 { $wheretext .= " (EXISTS(SELECT NULL FROM ". _DB_PREFIX_."warehouse_product_location wpl LEFT JOIN ". _DB_PREFIX_."warehouse w ON wpl.id_warehouse=w.id_warehouse WHERE wpl.id_product = p.id_product AND w.name ".$inc.")";
	   $wheretext .= " OR EXISTS(SELECT NULL FROM ". _DB_PREFIX_."stock st LEFT JOIN ". _DB_PREFIX_."warehouse w ON st.id_warehouse=w.id_warehouse WHERE st.id_product = p.id_product AND w.name ".$inc."))";
	 }
	 else if($search_fld2 == "cl.id_category")
     { if($input['search_cmp2'] == "not_in")
	     $wheretext .= " EXISTS(SELECT NULL FROM ". _DB_PREFIX_."category_product cpq WHERE cpq.id_product = p.id_product AND cpq.id_category='".str_replace("'","",$frag)."')";
	   else
	     $wheretext .= "cp2.id_category ".$inc." ";
     } 
     else if($search_fld2 == "combinations")  /* combinations */
     { $wheretext .= " EXISTS(SELECT NULL FROM 
     ". _DB_PREFIX_."product_attribute pa LEFT JOIN ". _DB_PREFIX_."product_attribute_combination pac 
     ON pa.id_product_attribute=pac.id_product_attribute LEFT JOIN ". _DB_PREFIX_."attribute_lang al
     ON pac.id_attribute = al.id_attribute WHERE pa.id_product=p.id_product AND al.name ".$inc.")";
     }
     else if($search_fld2 == "p.reference") /* check here also for supplier reference */
     { $wheretext .= " (".$search_fld2." ".$inc." ";
   	   $wheretext .= " OR EXISTS (SELECT NULL FROM ". _DB_PREFIX_."product_supplier psu WHERE psu.id_product = p.id_product AND psu.product_supplier_reference ".$inc."))";
     }
     else if(substr($search_fld2,0,7) == "sattrib") /* attribute search */
     { $id_attribute_group = substr($search_fld2,7);
       $wheretext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_attribute pa 
   LEFT JOIN ". _DB_PREFIX_."product_attribute_combination pac ON pa.id_product_attribute=pac.id_product_attribute 
   LEFT JOIN ". _DB_PREFIX_."attribute_lang al ON pac.id_attribute = al.id_attribute 
   LEFT JOIN ". _DB_PREFIX_."attribute atr ON pac.id_attribute = atr.id_attribute 
   WHERE pa.id_product=p.id_product AND atr.id_attribute_group=".$id_attribute_group." AND ".$nottext2." (al.name ".$inc."))";
     }
     else if(substr($search_fld2,0,7) == "sfeatur") /* feature search */
     { $id_feature = substr($search_fld2,7);
       $wheretext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."feature_product fp 
   LEFT JOIN ". _DB_PREFIX_."feature_value fv ON fp.id_feature_value=fv.id_feature_value 
   LEFT JOIN ". _DB_PREFIX_."feature_value_lang fvl ON fv.id_feature_value=fvl.id_feature_value
   WHERE fp.id_product=p.id_product AND fp.id_feature=".$id_feature." AND ".$nottext2." (fvl.value ".$inc."))";
     }
     else if(($search_fld2 != "discount") && ($search_fld2 != "virtualp"))
       $wheretext .= $search_fld2." ".$inc." ";
   }
   $wheretext .= ") ";
}
if($search_fld2 == "discount")
	 $wheretext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."specific_price sp WHERE sp.id_product = p.id_product)";
if(($search_fld2 == "combinations") && ($search_txt2 == "")) /* combinations: this works when search_txt field is empty */
	 $wheretext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_attribute pa WHERE pa.id_product = p.id_product)";
if($search_fld2 == "virtualp")
	 $wheretext .= " AND (p.is_virtual = 1)";
if(($search_fld2 == "cr.name") && ($search_txt2 == "")) /* carriers: this works when search_txt field is empty */
	 $wheretext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_carrier pcx WHERE pcx.id_product = p.id_product)";
if (($search_txt2 == "") && (($search_fld2 == "combinations") || ($search_fld2 == "cr.name") || (substr($search_fld2,0,7) == "sattrib") || (substr($search_fld2,0,7) == "sfeatur")))
{ if(($input['search_cmp2'] == "not_eq") || ($input['search_cmp2'] == "not_in"))
	 $nottext2 = "NOT";
}
if(($search_fld2 == "combinations") && ($search_txt2 == "")) /* combinations: this works when search_txt field is empty */
	 $wheretext .= " AND ".$nottext2." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_attribute pax WHERE pax.id_product = p.id_product)";
if(($search_fld2 == "cr.name") && ($search_txt2 == "")) /* carriers: this works when search_txt field is empty */
	 $wheretext .= " AND ".$nottext2." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_carrier pcx WHERE pcx.id_product = p.id_product)";
if((substr($search_fld2,0,7) == "sattrib") && ($search_txt2 == ""))  /* attribute search */
   { $id_attribute_group = substr($search_fld2,7);
     $wheretext .= " AND ".$nottext2." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_attribute pa 
   LEFT JOIN ". _DB_PREFIX_."product_attribute_combination pac ON pa.id_product_attribute=pac.id_product_attribute 
   LEFT JOIN ". _DB_PREFIX_."attribute_lang al ON pac.id_attribute = al.id_attribute and al.id_lang=".$id_lang."
   LEFT JOIN ". _DB_PREFIX_."attribute atr ON pac.id_attribute = atr.id_attribute 
   WHERE pa.id_product=p.id_product AND atr.id_attribute_group=".$id_attribute_group.")";
   }
if((substr($search_fld2,0,7) == "sfeatur") && ($search_txt2 == "")) /* feature search */
   { $id_feature = substr($search_fld2,7);
     $wheretext .= " AND ".$nottext2." EXISTS(SELECT NULL FROM ". _DB_PREFIX_."feature_product fp 
   LEFT JOIN ". _DB_PREFIX_."feature_value fv ON fp.id_feature_value=fv.id_feature_value 
   LEFT JOIN ". _DB_PREFIX_."feature_value_lang fvl ON fv.id_feature_value=fvl.id_feature_value and fvl.id_lang=".$id_lang."
   WHERE fp.id_product=p.id_product AND fp.id_feature=".$id_feature.")";
   }
	
 /* Note: we start with the query part after "from". First we count the total and then we take 100 from it */
$queryterms = "p.*,ps.*,pl.*,t.id_tax,t.rate,m.name AS manufacturer, cl.name AS catname";
$queryterms .= ", cl.link_rewrite AS catrewrite, pld.name AS originalname, s.quantity,s.depends_on_stock";

$query = " from ". _DB_PREFIX_."product_shop ps left join ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
$query.=" left join ". _DB_PREFIX_."product_lang pl on pl.id_product=p.id_product and pl.id_lang='".$id_lang."' AND pl.id_shop='".$id_shop."'";
$query.=" left join ". _DB_PREFIX_."product_lang pld on pld.id_product=p.id_product and pld.id_lang='".$def_lang."' AND pld.id_shop='".$id_shop."'"; /* This gives the name in the shop language instead of the selected language */
$query.=" left join ". _DB_PREFIX_."manufacturer m on m.id_manufacturer=p.id_manufacturer";
$query.=" left join ". _DB_PREFIX_."category_lang cl on cl.id_category=ps.id_category_default AND cl.id_lang='".$id_lang."' AND cl.id_shop = '".$id_shop."'";
if($share_stock == 0)
  $query.=" left join ". _DB_PREFIX_."stock_available s on s.id_product=p.id_product AND s.id_shop = '".$id_shop."' AND id_product_attribute='0'";
else
  $query.=" left join ". _DB_PREFIX_."stock_available s on s.id_product=p.id_product AND s.id_shop_group = '".$id_shop_group."' AND id_product_attribute='0'";
$query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."' AND tr.id_state='0'";
$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
$query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".$def_lang."'";

if ($input['order']=="id_product") $order="p.id_product";
else if ($input['order']=="name") $order="pl.name";
else if ($input['order']=="position") $order="cp.position"; /* later to be refined */
else if ($input['order']=="VAT") $order="t.rate";
else if ($input['order']=="price") $order="ps.price";
else if ($input['order']=="active") $order="ps.active";
else if ($input['order']=="shipweight") $order="p.weight";
else if ($input['order']=="date_upd") $order="p.date_upd";
else if ($input['order']=="image")  /* sorting on image makes only sense to get the products without an image */
{  $order="i.cover";
   $queryterms .= ",i.id_image, i.cover";
   $query.=" LEFT JOIN ". _DB_PREFIX_."image i ON i.id_product=p.id_product and i.cover=1";
}
else $order = $input['order'];

if ($input['active']=="active")
	$wheretext = " AND ps.active=1";
else if ($input['active']=="inactive")
	$wheretext = " AND ps.active=0";

if(($search_txt1 != "")&&($search_fld1 == "cl.id_category"))
{	$query .= " LEFT JOIN ". _DB_PREFIX_."category_product cp1 on p.id_product=cp1.id_product";
}
if(($search_txt2 != "")&&($search_fld2 == "cl.id_category"))
{	$query .= " LEFT JOIN ". _DB_PREFIX_."category_product cp2 on p.id_product=cp2.id_product";
}
if ($id_category != 0)
{	$query .= " LEFT JOIN ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
	$wheretext .= " AND cp.id_category IN ($cats)";
}
else
{	$query .= " LEFT JOIN ". _DB_PREFIX_."category_product cp on ps.id_category_default=cp.id_category AND p.id_product=cp.id_product";
}

if (($order=="cp.position") && ((sizeof($categories)>1) || ($id_category == 0)))
{ $query .= " LEFT JOIN ". _DB_PREFIX_."category c on c.id_category=cp.id_category";
  $order = "c.nleft,cp.position";
}

if(in_array("virtualp", $input["fields"]))
{ $query.=" LEFT JOIN ". _DB_PREFIX_."product_download pd ON pd.id_product=p.id_product";
  $queryterms .= ", filename, display_filename";
}

if(in_array("out_of_stock", $input["fields"]))
{ $queryterms .= ", s.out_of_stock AS out_of_stock";
}

if(in_array("accessories", $input["fields"]))
{ $query.=" LEFT JOIN ( SELECT GROUP_CONCAT(id_product_2) AS accessories, id_product_1 FROM "._DB_PREFIX_."accessory GROUP BY id_product_1 ) a ON a.id_product_1=p.id_product";
  $queryterms .= ", accessories";
}

if(in_array("position", $input["fields"]))
{ $queryterms .= ", position";
}

foreach($features AS $key => $feature)
{ if (in_array($feature, $input["fields"]))
  { $query.=" left join ". _DB_PREFIX_."feature_product fp".$key." on fp".$key.".id_product=p.id_product AND fp".$key.".id_feature='".$key."'";
	$query.=" left join ". _DB_PREFIX_."feature_value fv".$key." on fp".$key.".id_feature_value=fv".$key.".id_feature_value";
	$query.=" left join ". _DB_PREFIX_."feature_value_lang fl".$key." on fp".$key.".id_feature_value=fl".$key.".id_feature_value AND fl".$key.".id_lang='".$id_lang."'";
	$queryterms .= ",fv".$key.".custom AS custom".$key.",fl".$key.".value AS value".$key;
  }
}

if(($input["search_fld1"]=="cr.name") || ($input["search_fld2"]=="cr.name"))
{ $queryterms .= ",cr.name";
  $query .= " LEFT JOIN ". _DB_PREFIX_."product_carrier pc ON pc.id_product = p.id_product";
  $query .= " LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0";
}

if(($input["search_fld1"]=="tg.name") || ($input["search_fld2"]=="tg.name"))
{ $queryterms .= ",tg.name";
  $query .= " LEFT JOIN ". _DB_PREFIX_."product_tag pt ON pt.id_product = p.id_product";
  $query .= " LEFT JOIN ". _DB_PREFIX_."tag tg ON pt.id_tag=tg.id_tag AND tg.id_lang='".$id_lang."'";
}

if(in_array("visits", $input["fields"]) OR ($order=="visits") OR ($input["search_fld1"]=="visitcount") OR ($input["search_fld2"]=="visitcount"))
{ $query .= " LEFT JOIN ( SELECT pg.id_object, count(*) AS visitcount FROM ". _DB_PREFIX_."connections c LEFT JOIN ". _DB_PREFIX_."page pg ON pg.id_page_type='1' AND pg.id_page = c.id_page AND c.id_shop='".$id_shop."'";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(c.date_add) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(c.date_add) <= TO_DAYS('".$input['enddate']."')";
  $queryterms .= ", visitcount ";
  $query .= " GROUP BY pg.id_object ) v ON p.id_product=v.id_object";
}
if(in_array("visitz", $input["fields"]) OR ($order=="visitz") OR ($input["search_fld1"]=="visitedpages") OR ($input["search_fld2"]=="visitedpages"))
{ /* for mysql 5.7.5 compatibility "SELECT pg.id_object" was replaced by "SELECT MAX(pg.id_object) AS id_object" */
  $query .= " LEFT JOIN ( SELECT MAX(pg.id_object) AS id_object, sum(counter) AS visitedpages FROM ". _DB_PREFIX_."page_viewed v LEFT JOIN ". _DB_PREFIX_."page pg ON pg.id_page_type='1' AND pg.id_page = v.id_page AND v.id_shop='".$id_shop."'";
  $query .= " LEFT JOIN ". _DB_PREFIX_."date_range d ON d.id_date_range = v.id_date_range";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(d.time_start) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(d.time_end) <= TO_DAYS('".$input['enddate']."')";
  $queryterms .= ", visitedpages ";
  $query .= " GROUP BY v.id_page ) w ON p.id_product=w.id_object";
}
if(in_array("revenue", $input["fields"]) OR in_array("salescnt", $input["fields"]) OR in_array("orders", $input["fields"]) OR ($order=="revenue")OR ($order=="orders")OR ($order=="buyers"))
{ $query .= " LEFT JOIN ( SELECT product_id, SUM(product_quantity)-SUM(product_quantity_return) AS quantity, ";
  $query .= " ROUND(SUM(total_price_tax_incl),2) AS revenue, ";
  $query .= " COUNT(DISTINCT d.id_order) AS ordercount, count(DISTINCT o.id_customer) AS buyercount FROM ". _DB_PREFIX_."order_detail d";
  $query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order AND o.id_shop=d.id_shop";
  $query .= " WHERE d.id_shop='".$id_shop."'";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) <= TO_DAYS('".$input['enddate']."')";
  $query .= " AND o.valid=1";
  $query .= " GROUP BY d.product_id ) r ON p.id_product=r.product_id";
  $queryterms .= ", revenue, r.quantity AS salescount, ordercount, buyercount ";
}

$res=dbquery("SELECT COUNT(*) AS rcount ".$query." WHERE ps.active='1' AND ps.id_shop='".$id_shop."' ".$wheretext);
$row = mysqli_fetch_array($res);
$numrecs = $row['rcount'];

$query.=" WHERE ps.id_shop='".$id_shop."' ".$wheretext;

 $statfields = array("salescnt", "revenue","orders","buyers","visits","visitz");
  $stattotals = array("salescnt" => 0, "revenue"=>0,"orders"=>0,"buyers"=>0,"visits"=>0,"visitz"=>0); /* store here totals for stats */
//  $statz = array("salescount", "revenue","ordercount","buyercount","visitcount","visitedpages"); /* here pro memori: moved up to search_fld definition */
  if(in_array($order, $statfields))
  { $ordertxt = $statz[array_search($order, $statfields)];
  }
  else
    $ordertxt = str_replace(" ","",$order);
  /* GROUP BY p.id_product is for "With subcats" when the products is in more than one of the involved categories */
  $query .= " GROUP BY p.id_product ORDER BY ".$ordertxt." ".$rising." LIMIT ".$input['startrec'].",".$input['numrecs'];
 
  $query= "select SQL_CALC_FOUND_ROWS ".$queryterms.$query; /* note: you cannot write here t.* as t.active will overwrite p.active without warning */
  $res=dbquery($query);
  $numrecs3 = mysqli_num_rows($res);
  $res2=dbquery("SELECT FOUND_ROWS() AS foundrows");
  $row2 = mysqli_fetch_array($res2);
  $numrecs2 = $row2['foundrows'];
  
//  echo $query;
  
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=product-'.date('Y-m-d-Gis').'.csv');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

// According to a comment on php.net the following can be added here to solve Chinese language problems
// fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

  // "*********************************************************************";
  if($input['separator'] == "comma")
  { $separator = ",";
	$subseparator = ";";
  }
  else 
  { $separator = ";";
	$subseparator = ",";
  }
  $csvline = array();  // array for the fputcsv function
  for($i=2; $i<sizeof($infofields); $i++)
  { if($infofields[$i][0] == "supplier")
    { $csvline[] = "supplier";
	  $csvline[] = "supplier reference";
	}
	else if($infofields[$i][0] == "discount")
    { $csvline[] = "discount amount";
	  $csvline[] = "discount pct";
	  $csvline[] = "discount from";
	  $csvline[] = "discount to";
	}
	else if($infofields[$i][0] == "stockflags")	
    { $csvline[] = "depends_on_stock";
	  $csvline[] = "advanced_stock_management";
	}
	else if($infofields[$i][0] == "VAT")
    { $csvline[] = "VAT group";
	  $csvline[] = "VAT perc";
	}
	else if($infofields[$i][0] == "warehousing")
    {// $csvline[] = "id_warehouse";
	  $csvline[] = "warehouse";
	//  $csvline[] = "whlocation";	  
	}
	else
      $csvline[] = $infofields[$i][0];
  }
  foreach($features AS $key => $feature)
  { if (in_array($feature, $input["fields"]))
      $csvline[] = $feature;
  }	
  $out = fopen('php://output', 'w');
  publish_csv_line($out, $csvline, $separator);

  $x=0;
  $ress = dbquery("SELECT domain,physical_uri FROM ". _DB_PREFIX_."shop_url WHERE id_shop='".$id_shop."'");
  $rows = mysqli_fetch_array($ress);
  $imagebase = "http://".$rows["domain"].$rows["physical_uri"];
  while ($datarow=mysqli_fetch_array($res))
  { $csvline = array();
    for($i=2; $i< sizeof($infofields); $i++)
    { $sorttxt = "";
      $color = "";
	  $csvspecials = array("carrier","combinations","depends_on_stock","discount","supplier","tags");
      if($infofields[$i][2] == "priceVAT")
		$myvalue =  number_format(((($datarow['rate']/100) +1) * $datarow['price']),4, '.', '');
      else if (!in_array($infofields[$i][2],$csvspecials))
        $myvalue = $datarow[$infofields[$i][2]];
      if($i == 1) /* id */
	  { $csvline[] = $myvalue;
	  }
	  else if($infofields[$i][6] == 1) /* happens never?? */
      { $csvline[] = $myvalue;
      }
	  else if ($infofields[$i][0] == "accessories")
	  { $csvline[] =  $accs;
	  }
	  else if ($infofields[$i][0] == "carrier")		/* niet beschikbaar in CSV */
      { $tmp = ""; 
	    $cquery = "SELECT id_carrier_reference FROM ". _DB_PREFIX_."product_carrier WHERE id_product=".$datarow['id_product']." AND id_shop='".$id_shop."' LIMIT 1";
		$cres=dbquery($cquery);
		if(mysqli_num_rows($cres) != 0)
		{ $cquery = "SELECT id_reference, cr.name FROM ". _DB_PREFIX_."product_carrier pc";
		  $cquery .= " LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0";
		  $cquery .= " WHERE id_product='".$datarow['id_product']."' AND id_shop='".$id_shop."' ORDER BY cr.name";
		  $cres=dbquery($cquery);
		  $idx = 0;
		  while ($crow=mysqli_fetch_array($cres)) 
		  { if($idx++ > 0) $tmp .= $subseparator;
		    $tmp .= $crow["name"];
		    $idx++;
		  }
		}
		$csvline[] = $tmp;
		mysqli_free_result($cres);
	  }
      else if ($infofields[$i][0] == "category")
	  { $cquery = "select cp.id_category from ". _DB_PREFIX_."category_product cp";
		$cquery .= " LEFT JOIN ". _DB_PREFIX_."category_lang cl on cp.id_category=cl.id_category AND id_lang='".$id_lang."'";
		$cquery .= " WHERE cp.id_product='".$datarow['id_product']."' ORDER BY id_category";
		$cres=dbquery($cquery);
		$z=0;
		$tmp = $category_names[$myvalue];
		while ($crow=mysqli_fetch_array($cres)) 
		{	if ($crow['id_category'] == $myvalue)
				continue;
			$tmp .= $subseparator.$category_names[$crow['id_category']]; // without the space this won't work: the categories become different fields
		}
	    $csvline[] = $tmp;
		mysqli_free_result($cres);
	  }
	  else if ($infofields[$i][0] == "combinations")
      { $csvline[] = "";
	    continue;
	    $cquery = "SELECT count(*) AS counter FROM ". _DB_PREFIX_."product_attribute";
	    $cquery .= " WHERE id_product='".$datarow['id_product']."'";
		$cres=dbquery($cquery);
		$crow=mysqli_fetch_array($cres);
		echo "<td>";
		if($crow["counter"] != 0)
			echo '<a href="combi-edit.php?id_product='.$datarow['id_product'].'&id_shop='.$id_shop.'" title="Click here to edit combinations in separate window" target="_blank" style="background-color:#99aaee; text-decoration:none">&nbsp; '.$crow["counter"].' &nbsp;</a>';
		echo "</td>";
		mysqli_free_result($cres);
      }
	  else if ($infofields[$i][0] == "discount")
      { $dquery = "SELECT sp.reduction,sp.reduction_type,sp.from,sp.to";
		$dquery .= " FROM ". _DB_PREFIX_."specific_price sp";
	    $dquery .= " WHERE sp.id_product='".$datarow['id_product']."' AND (sp.id_shop='".$id_shop."' OR sp.id_shop='0') AND (sp.to >= NOW() OR sp.to = '0000-00-00 00:00:00' ) AND sp.id_product_attribute='0'";
		$dquery .= " ORDER BY sp.id_country, sp.id_group, sp.id_customer,sp.id_currency LIMIT 1"; /* order by should put zero's (=all) first */
		$dres=dbquery($dquery);
		if(mysqli_num_rows($dres) > 0)
		{ $drow=mysqli_fetch_array($dres);
		  if($drow["reduction_type"] == "pct")
		  { $csvline[] = "0";
		    $csvline[] = $drow['reduction'];
		  }
		  else
		  { $csvline[] = $drow['reduction'];
			$csvline[] = "0";
		  }
		  $csvline[] = $drow['from'];
		  $csvline[] = $drow['to'];
		}
		else
		{ $csvline[] = "ddd";
		  $csvline[] = "";
		  $csvline[] = "";
		  $csvline[] = "";
		}
		mysqli_free_result($dres);
      }
      else if ($infofields[$i][0] == "image")
      { $iquery = "SELECT id_image,cover FROM ". _DB_PREFIX_."image WHERE id_product='".$datarow['id_product']."' ORDER BY cover DESC, position";
		$ires=dbquery($iquery);
		$id_image = 0;
		$xx = 0;
		$imsize = mysqli_num_rows($ires);
		$tmp = "";
		while ($irow=mysqli_fetch_array($ires)) 
		{ $tmp .= $imagebase.'img/p'.getpath($irow['id_image']).'/'.$irow['id_image'].'.jpg';
		  $xx++;
		  if($xx < $imsize)
		    $tmp .= $subseparator; // the space is necessary
		}
		$csvline[] = $tmp;
		mysqli_free_result($ires);
      }
	  else if ($infofields[$i][0] == "revenue")
      { $csvline[] = $datarow['revenue'].";";
      }
	  else if ($infofields[$i][0] == "stockflags")
	  { $csvline[] = $datarow['depends_on_stock'];
		$csvline[] = $datarow['advanced_stock_management'];
	  }
	  else if ($infofields[$i][0] == "supplier")
      { $squery = "SELECT id_supplier,product_supplier_reference AS reference FROM ". _DB_PREFIX_."product_supplier WHERE id_product=".$datarow['id_product']." AND id_product_attribute='0' LIMIT 1";
		$sres=dbquery($squery);
		if(mysqli_num_rows($sres) > 0)
		{ $srow=mysqli_fetch_array($sres); 
		  $csvline[] = $supplier_names[$srow['id_supplier']];
		  $csvline[] = $srow['reference'];
		}
		else
		{ $csvline[] = "";
		  $csvline[] = "";
		}
		mysqli_free_result($sres);
      }
	  else if ($infofields[$i][0] == "tags")
      { $tquery = "SELECT pt.id_tag,name FROM ". _DB_PREFIX_."product_tag pt";
		$tquery .= " LEFT JOIN ". _DB_PREFIX_."tag t ON pt.id_tag=t.id_tag AND t.id_lang='".$id_lang."'";
	    $tquery .= " WHERE pt.id_product='".$datarow['id_product']."'";
		$tres=dbquery($tquery);
		$idx = 0;
		$tmp = "";
		while ($trow=mysqli_fetch_array($tres)) 
		{ if($idx++ > 0) $tmp .= $subseparator;
		  $tmp .= $trow["name"];
		  $idx++;
		}
		$csvline[] = $tmp;
		mysqli_free_result($tres);
	  }
      else if ($infofields[$i][0] == "VAT")
      { $csvline[] = $datarow["id_tax_rules_group"];
		$csvline[] = (float)$myvalue;
      }
      else if ($infofields[$i][0] == "warehousing")
      { $wquery = "SELECT DISTINCT w.id_warehouse, w.name AS whname";
		$wquery .= " FROM ". _DB_PREFIX_."warehouse w";
	    $wquery .= " LEFT JOIN ". _DB_PREFIX_."stock st on w.id_warehouse=st.id_warehouse AND st.id_product='".$datarow['id_product']."'";
	    $wquery .= " LEFT JOIN ". _DB_PREFIX_."warehouse_product_location wpl on w.id_warehouse=wpl.id_warehouse AND wpl.id_product='".$datarow['id_product']."'";		  
		$wquery .= " WHERE st.id_warehouse IS NOT NULL OR wpl.id_warehouse IS NOT NULL";
		$wquery .= " ORDER BY w.name";
 		$wres=dbquery($wquery);
		$wids = $wnames = array();
		while ($wrow=mysqli_fetch_array($wres)) 
		{ $wnames[] = $wrow["whname"];
		  $wids[] = $wrow["id_warehouse"];
		}
//		$csvline[] = implode(",",$wids);
		$csvline[] = implode(",",$wnames);
		mysqli_free_result($wres);
	  }
      else
         $csvline[] = $myvalue;
	  if(in_array($infofields[$i][0], $statfields))
	    $stattotals[$infofields[$i][0]] += $myvalue;
    }

	foreach($features AS $key => $feature)
	{ if (in_array($feature, $input["fields"]))
	  { if($datarow['value'.$key] == "")
			$csvline[] = "";
		else if($datarow['custom'.$key] == "0")
	  	    $csvline[] = $datarow['value'.$key];
		else // custom = 1
	  	    $csvline[] = $datarow['value'.$key];
	  }
	}
    $x++;
    publish_csv_line($out, $csvline, $separator);
  }
  fclose($out);
  
/* fputcsv doesn't work here correctly. It will not put in quotes strings with a comma but without a space. */
/* As a result spreadsheets will take this as a second separator and fill several cells instead of just one */
/* The code of fputcsv3 comes from a forum where it was claimed to be the source for the PHP function. */
/* I have added the functionality that it will always escape strings with a semicolon or comma */
function publish_csv_line($out, $csvline, $separator)
{ fputcsv3($out, $csvline, $separator);
}
  
/* get subcategories: this function is recursively called */
function get_subcats($cat_id, &$realm) 
{ global $conn;
  $realm[] = $cat_id;
  if($cat_id == 0) die("You cannot have category with value zero; to see all categories use Home with subcategories");
  $query="select id_category from ". _DB_PREFIX_."category WHERE id_parent='".mysqli_real_escape_string($conn, $cat_id)."'";
  $res = dbquery($query);
  while($row = mysqli_fetch_array($res))
    get_subcats($row['id_category'], $realm);
}

/* if fputcsv doesn't work this can be used as alternative. It is one of the options mentioned in the comment section of php.net for fputcsv() */
function fputcsv2 ($fh, array $fields, $delimiter = ',', $enclosure = '"', $mysql_null = false) { 
    $delimiter_esc = preg_quote($delimiter, '/'); 
    $enclosure_esc = preg_quote($enclosure, '/'); 

    $output = array(); 
    foreach ($fields as $field) { 
        if ($field === null && $mysql_null) { 
            $output[] = 'NULL'; 
            continue; 
        } 

        $output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field) ? ( 
            $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure 
        ) : $field; 
    } 

    fwrite($fh, join($delimiter, $output) . "\n"); 
} 


  function fputcsv3(&$handle, $fields = array(), $delimiter = ',', $enclosure = '"') {
    $str = '';
    $escape_char = '\\';
    foreach ($fields as $value) {
      if (strpos($value, $delimiter) !== false ||
          strpos($value, $enclosure) !== false ||
          strpos($value, "\n") !== false ||
          strpos($value, "\r") !== false ||
          strpos($value, "\t") !== false ||
          strpos($value, ";") !== false ||
          strpos($value, ",") !== false ||          
		  strpos($value, ' ') !== false) {
        $str2 = $enclosure;
        $escaped = 0;
        $len = strlen($value);
        for ($i=0;$i<$len;$i++) {
          if ($value[$i] == $escape_char) {
            $escaped = 1;
          } else if (!$escaped && $value[$i] == $enclosure) {
            $str2 .= $enclosure;
          } else {
            $escaped = 0;
          }
          $str2 .= $value[$i];
        }
        $str2 .= $enclosure;
        $str .= $str2.$delimiter;
      } else {
        $str .= $value.$delimiter;
      }
    }
    $str = substr($str,0,-1);
    $str .= "\n";
    return fwrite($handle, $str);
  }

?>