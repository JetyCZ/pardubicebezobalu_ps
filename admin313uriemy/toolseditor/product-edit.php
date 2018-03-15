<?php 
/* For better understanding this file is divided in sections.
 * 1. include files 
 * 2. handling input variables
 * 3. make field declarations and build infofields array of displayed fields
 * 4. retrieve system settings and build php blocks for select fields 
 * 5. publish header block of html page, including javascript version of select blocks
 * 6. publish page header: menu and diagnostic information
 * 7. publish search block
 * 8. Publish the block where you can select your fields
 * 9. Mass edit
 * 10. CSV export
 * 11. CSV import
 * 12. Re-indexation
 * 13. switchform: hide/show/edit fields 
 * 14. generation of product lists
 * 15. Hidden form for copying data from one language to another
 * 16. build and execute the product query 
 * 17. Next and Prev
 * 18. warnings and explanations
 * 19. The main form: the multishop option
 * 20. The main table: the column headers 
 * 21. the main table: the products
*/
/* section 1: includes */
if(!@include 'approve.php') die( "approve.php was not found!");
if(!@include 'ps_sourced_code.php') die( "ps_sourced_code.php was not found!");
if(!isset($prestoolslanguage)) $prestoolslanguage = "en";
if(!include $prestoolslanguage.".php") colordie( "Language file ".$prestoolslanguage.".php was not found!");
foreach($productedit_fieldblock AS $line)
  if(in_array("sosh_title",$line)) colordie("Please update your settings1.php file");

/* section 2: handling input variables */
$input = $_GET; 
$verbose = "false";
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
$startrec = intval($input['startrec']);
/* $productedit_numrecs is set in Settings1.php */
if(!isset($input['numrecs'])) {if(isset($productedit_numrecs)) $input['numrecs'] = $productedit_numrecs; else $input['numrecs']="100";}
$numrecs = intval($input['numrecs']);
if(!isset($input['id_category'])) {$id_category=0;} else {$id_category = intval($input['id_category']);}
if(!isset($input['id_shop'])) $input['id_shop']="1";
$id_shop = intval($input["id_shop"]);
if(!isset($input['startdate'])) $input['startdate']="";
if(!isset($input['enddate'])) $input['enddate']="";
if((!isset($input['rising'])) || ($input['rising'] == "ASC")) {$rising = "ASC";} else {$rising = "DESC";}
if(!isset($fieldsorder)) $fieldsorder = array("name");
if(!isset($input['order'])) /* sorting order */
{ if($id_category == 0) {$input['order']="id_product";} else {$input['order']="position";}
}
if(!isset($input['id_lang'])) $input['id_lang']="";
if(!isset($input['active'])) {$input['active']="";}
if(!isset($input['imgformat'])) {$input['imgformat']="";}

if(empty($input['fields'])) // if not set, set default set of active fields
  $input['fields'] = $default_product_fields; /* this is set in settings1.php */

/* section 3: make field declarations and build infofields array of displayed fields */
  $infofields = array(); /* this will hold the displayed fields with a copy of the field_array entries */
  $if_index = 0;
   /* [0]always same as key, [1]language specific texts, [2]source, [3]display(0=not;1=yes;2=edit;), [4]fieldwidth(0=not set), 
      [5]align(0=default;1=right), [6]not used, [7]Editable, [8]tablefield (used in search block) */
  define("HIDE", 0); define("DISPLAY", 1); define("EDIT", 2);  // display
  define("LEFT", 0); define("RIGHT", 1); // align
  define("NOT_EDITABLE", 0); define("INPUT", 1); define("TEXTAREA", 2); define("DROPDOWN", 3); define("BINARY", 4); define("EDIT_BTN", 5);  /* title, keyover, source, display(0=not;1=yes;2=edit), fieldwidth(0=not set), align(0=default;1=right), sortfield */
   /* sortfield => 0=no escape removal; 1=escape removal; 2 and higher= escape removal and n lines textarea */
  $infofields[$if_index++] = array("",array("","",""), "", DISPLAY, 0, LEFT, 0,0);

  $field_array = array(
   "accessories" => array("accessories",null, "accessories", DISPLAY, 0, LEFT, null, INPUT, "accessories"),
   "active" => array("active",null, "active", DISPLAY, 0, LEFT, null, BINARY, "ps.active"),
   "aShipCost" => array("aShipCost",null, "additional_shipping_cost", DISPLAY, 0, LEFT, null, INPUT, "ps.additional_shipping_cost"),
   "attachmnts" => array("attachmnts",null, "attachmnts", DISPLAY, 0, LEFT, null, INPUT, ""), 
   "available_now" => array("available_now",null, "available_now", DISPLAY, 0, LEFT, null, INPUT, "pl.available_now"),
   "available_later" => array("available_later",null, "available_later", DISPLAY, 0, LEFT, null, INPUT, "pl.available_later"), 
   "available_date" => array("available_date",null, "available_date", DISPLAY, 0, LEFT, null, BINARY, "ps.available_date"),
   /* availorder combines two of Prestashop's datafields: available_for_order and show_price */
   "availorder" => array("availorder",null, "available_for_order", DISPLAY, 0, LEFT, null, BINARY, "ps.available_for_order"),
   "carrier" => array("carrier",null, "carrier", DISPLAY, 0, LEFT, null, DROPDOWN, "cr.name"),
   "category" => array("category",null, "id_category_default", DISPLAY, 0, LEFT, null, DROPDOWN, "ps.id_category_default"),
   "combinations" => array("combinations",null, "combinations", DISPLAY, 0, LEFT, 0, INPUT, "combinations"),
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
   "shopz" => array("shopz",null, "id_shop", DISPLAY, 0, LEFT, null, BINARY, "ps.id_shop"),
   "show_condition" => array("show_condition",null, "show_condition", DISPLAY, 0, LEFT, null, BINARY, "ps.show_condition"),
   "stockflags" => array("stockflags",null, "depends_on_stock", DISPLAY, 0, LEFT, null, BINARY, "s.depends_on_stock"),
   "state" => array("state",null, "state", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "p.state"),   /* STATE_TEMP=0 STATE_SAVED=1 */
   "supplier" => array("supplier",null, "supplier", DISPLAY, 0, LEFT, null, INPUT, "su.name"),
   "tags" => array("tags",null, "tags", DISPLAY, 0, LEFT, null, TEXTAREA, "tg.name"),
   "unit" => array("unit",null, "unity", DISPLAY, 0, LEFT, null, INPUT, "ps.unity"),
   "unitPrice" => array("unitPrice",null, "unit_price_ratio", DISPLAY, 0, LEFT, null, INPUT, "ps.unit_price_ratio"),   
   "upc" => array("upc",null, "upc", DISPLAY, 200, LEFT, null, INPUT, "p.upc"),   
   "VAT" => array("VAT",null, "rate", DISPLAY, 0, LEFT, null, DROPDOWN, "t.rate"),
   "virtualp" => array("virtualp",null, "virtualp", DISPLAY, 0, LEFT, null, INPUT, "virtualp"), /* virtual product */
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

    /* if PriceVAT or discount in array => make sure that VAT and price are there too */
  if(in_array("priceVAT", $input["fields"]) || in_array("discount", $input["fields"]))
  { if(!in_array("price", $input["fields"]))
	    array_push($input["fields"], "price");
    if(!in_array("VAT", $input["fields"]))
	    array_push($input["fields"], "VAT");
  }
  if(in_array("unitPrice", $input["fields"])) /* if PriceVAT in array => make sure that VAT and price are there too */
  { if(!in_array("price", $input["fields"]))
	    array_push($input["fields"], "price");
  }
  
/* remove fields from the field_array that are not present in the used Prestashop version */
$x = 0;
$deleters = array();
foreach($field_array AS $key => $farray)
{ if((($key=="pack_stock_type") && (version_compare(_PS_VERSION_ , "1.6.0.12", "<")))
		 || (in_array($key, array("isbn", "show_condition","state")) && (version_compare(_PS_VERSION_ , "1.7.0", "<"))))
     $deleters[] = $x;
  $x++;
}
rsort($deleters); /* we have to work from high to low as deleting the low numbers first we would need to adapt the higher ones */
foreach($deleters AS $deleter)
  array_splice($field_array,$deleter,1);
  
  /* put the language specific data at position 1 in the field array */
  /* $screentext_pe is defined in the language file */
  foreach($field_array as $key => $value)
  { if(isset($screentext_pe[$key]) && isset($screentext_pe[$key][1]))
	{ if($screentext_pe[$key][1] == "")
	    $screentext_pe[$key][1] = $screentext_pe[$key][0];
      $field_array[$key][1] = $screentext_pe[$key];
	}
	else /* when users use old translation files that don't support this entry */
	  $field_array[$key][1] = array($key,"",$key);
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

/* section 4: retrieve system settings and build php blocks for select fields */
$rewrite_settings = get_rewrite_settings();
$allow_accented_chars = get_configuration_value('PS_ALLOW_ACCENTED_CHARS_URL');
/* the following deals with the case of customized product urls (used for the links on the product name) */
$product_url_conf = get_configuration_value('PS_ROUTE_product_rule');
if($product_url_conf != null) /* null is the default */
{ $regexp = preg_quote($product_url_conf, '#');
  $keywordlist = array("id", "rewrite","ean13","category","categories","reference","meta_keywords","meta_title","manufacturer","supplier","price","tags");
  $keywords = array();
  foreach($keywordlist AS $key)
  { if(strpos($product_url_conf, $key) > 0)
	  $keywords[] = $key;
  }
  if (sizeof($keywords)>0) 
  { $transform_keywords = array();
    preg_match_all('#\\\{(([^{}]*)\\\:)?('.implode('|', $keywords).')(\\\:([^{}]*))?\\\}#', $regexp, $m);
    for ($i = 0, $total = count($m[0]); $i < $total; $i++) 
	{ $prepend = $m[2][$i];
      $keyword = $m[3][$i];
      $append = $m[5][$i];
      $transform_keywords[$keyword] = array(
                    'prepend' =>    stripslashes($prepend),
                    'append' =>     stripslashes($append),
                );
	}
  }
}

/* get default language: we use this for the categories, manufacturers */
$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$def_lang = $row['value'];
if($input['id_lang'] == "") 
  $id_lang = $def_lang;
else 
  $id_lang = $input['id_lang'];

$langselblock = ""; /* used in the searchblock */
$langcopyselblock = ""; /* used in "copy from other lang" mass edit option; Note: this becomes javascript */
$query = "SELECT id_lang, name, language_code, iso_code FROM ". _DB_PREFIX_."lang";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ if($def_lang == $row["id_lang"])
	$def_langname = $row['name'];
  if($id_lang == $row["id_lang"])
  { $iso_code = $row['iso_code'];
    $languagename = $row['name'];
  }
  $selected='';
  if ($row['id_lang']==$id_lang) 
    $selected=' selected="selected" ';
  else 
    $langcopyselblock .= '<option value='.$row['id_lang'].'>'.$row['language_code'].'</option>';
  $langselblock .= '<option  value="'.$row['id_lang'].'" '.$selected.'>'.$row['name'].'</option>';
}
if(!isset($iso_code))
{ mysqli_data_seek($res, 0);
  $row=mysqli_fetch_array($res);
  $iso_code = $row['iso_code']; 
  $languagename = $row['name'];
  $id_lang == $row["id_lang"];
  echo "<b>Illegal value for default or selected language!</b> Using ".$languagename;
}

/* now get multi-language status. If true you get product urls like www.myshop.com/en/mycat/123-prod.html */
$query='SELECT COUNT(DISTINCT l.id_lang) AS langcount FROM `'._DB_PREFIX_.'lang` l
				JOIN '._DB_PREFIX_.'lang_shop ls ON (ls.id_lang = l.id_lang AND ls.id_shop = '.(int)$id_shop.')
				WHERE l.`active` = 1';
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$langcount = (int)$row['langcount'];
$langinsert = "";
if($langcount>1)
	$langinsert = $iso_code."/";

$and_code = "and"; /* this is the word that will be used instead of the ampersand when you regenerate link_rewrites */
if($iso_code == "fr") $and_code = "et";
if($iso_code == "es") $and_code = "y";
if($iso_code == "de") $and_code = "und";
if($iso_code == "it") $and_code = "e";
if($iso_code == "nl") $and_code = "en";

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

/* making shop block */
    $shopblock = "";
	$shops = $shop_ids = array();
	$query=" select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	while ($row=mysqli_fetch_array($res)) 
	{ $selected='';
      if ($row['id_shop']==$id_shop) 
		$selected=' selected="selected" ';
      $shopblock .= '<option  value="'.$row['id_shop'].'" '.$selected.'>'.$row['id_shop']."-".$row['name'].'</option>';
	  $shops[] = $row['name'];
	  $shop_ids[] = $row['id_shop'];
	}


/* make tax block */
  $query = "SELECT rate,name,tr.id_tax_rule,g.id_tax_rules_group FROM "._DB_PREFIX_."tax_rule tr";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax t ON (t.id_tax = tr.id_tax)";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax_rules_group g ON (tr.id_tax_rules_group = g.id_tax_rules_group)";
  $query .= " WHERE tr.id_country = '".$id_country."' AND tr.id_state='0'";
  $res=dbquery($query);
  $taxblock = '<option value="0">Select VAT</option>';
  while($row = mysqli_fetch_array($res))
  { $taxblock .= '<option value="'.$row['id_tax_rules_group'].'" rate="'.$row['rate'].'">'.str_replace("'","\'",$row['name']).'</option>';
  }   
  $taxblock .= "</select>";
  
/* visibility block */
  $visibilityblock = '<option>both</option><option>catalog</option><option>search</option><option>none</option>';
  $visibilityblock .= "</select>";
  
/* condition block */
  $conditionblock = '<option>new</option><option>used</option><option>refurbished</option>';
  $conditionblock .= "</select>";
  
/* availorder block */
  $availordertypes = array(0=>"available", 1=>"show price only",2=>"not available");
  $availorderblock = "";
  foreach($availordertypes AS $key => $availordertype)
    $availorderblock .= '<option value="'.$key.'">'.$availordertype.'</option>';
  $availorderblock .= "</select>";

/* pack_stock_type block */
  $packstocktypes = array(0=>"Decrement pack only", 1=>"Decrement products in pack only",2=>"Decrement both",3=>"Default");
  $pack_stock_typeblock = "";
  foreach($packstocktypes AS $key => $packstocktype)
    $pack_stock_typeblock .= '<option value="'.$key.'">'.$packstocktype.'</option>';
  $pack_stock_typeblock .= "</select>";
  
/* look for double category names */
  $duplos = array();
  $query = "select name,count(*) AS duplocount from ". _DB_PREFIX_."category_lang WHERE id_lang='".$def_lang."' AND id_shop='".$id_shop."' GROUP BY name HAVING duplocount > 1";
  $res=dbquery($query);
  while ($row=mysqli_fetch_array($res)) 
  {  $duplos[] = $row["name"];
  }
  
/* make category block */
/* Note that we need the name of all categories. In a quirk Prestashop defines the name of a category for all shops 
 * - even if the category is not defined for that shop. So the filter here works only to get the name of the category for this shop. */
/* for the (id_shop='".$id_shop."' or id_shop is NOT NULL) construction see http://forums.mysql.com/read.php?10,652781,652802#msg-652802 */
  $query = "select id_category,name,link_rewrite from ". _DB_PREFIX_."category_lang WHERE id_lang='".$def_lang."'";
  $query .= " AND id_shop='".$id_shop."' ORDER BY name";
//  $query .= " AND (id_shop='".$id_shop."' or id_shop is NOT NULL) ORDER BY name"; 
  $res=dbquery($query);
  $category_names = $category_rewrites = array();
  $allcats = array();
  $x=0;
  $categoryblock0 = '<input type=hidden name="category_defaultCQX"><input type=hidden name="mycatsCQX">';
  $categoryblock0 .= '<table cellspacing=8><tr><td><select id="categorylistCQX" size=4 multiple onchange="cat_change(\\\'CQX\\\');">';
  $categoryblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { if(in_array($row['name'], $duplos))
	  $name = $row['name'].$row['id_category'];
	else
	  $name = $row['name'];
    $categoryblock1 .= '<option value="'.$row['id_category'].'">'.str_replace("'","\'",$name).'</option>';
    $category_names[$row['id_category']] = $name;
    $category_rewrites[$row['id_category']] = $row['link_rewrite'];	
  } 
  $categoryblock1 .= '</select>';
  $categoryblock2 = '</td><td><a href=# onClick=" Addcategory(\\\'CQX\\\'); reg_change(this); return false;"><img src=add.gif border=0></a><br><br>';
  $categoryblock2 .= '<input id="category_numberCQX" style="width:23px; height:13px; color:#888888" onkeyup="change_category_number(\\\'CQX\\\')"><br><br>';
  $categoryblock2 .= '<a href=# onClick="Removecategory(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></td>';
  $categoryblock2 .= '<td><select id=categoryselCQX size=3 onchange="catsel_change(\\\'CQX\\\');"></select></td>';
  $categoryblock2 .= '<td><a href=# onClick="MakeCategoryDefault(\\\'CQX\\\'); reg_change(this); return false;"><img src="starr.jpg" border=0></a></td></td></tr></table>';

$stockflagsblock = "";
if(in_array('stockflags', $input["fields"])) 
{ $stockflagsarray = array("Manual","Adv Stock Management","ASM with Warehousing");
  $stockflagsblock = '<option value="1">'.$stockflagsarray[0].'</option><option value="2">'.$stockflagsarray[1].'</option><option value="3">'.$stockflagsarray[2].'</option></select>';
}
  
/* make manufacturer block */
if(in_array('manufacturer', $input["fields"]))
{ $query = "SELECT id_manufacturer,name FROM "._DB_PREFIX_."manufacturer ORDER BY name";
  $res=dbquery($query);
  $manufacturerblock = '<option value="0">No manufacturer</option>';
  while($row = mysqli_fetch_array($res))
  { $manufacturerblock .= '<option value="'.$row['id_manufacturer'].'">'.str_replace("'","\'",$row['name']).'</option>';
  }   
  $manufacturerblock .= "</select>";
}
else 
  $manufacturerblock = "";

/* make warehouse block */
if(in_array('stockflags', $input["fields"]))
{ $query = "SELECT w.id_warehouse,name FROM "._DB_PREFIX_."warehouse w";
  $query .= " LEFT JOIN ". _DB_PREFIX_."warehouse_shop ws ON ws.id_warehouse=w.id_warehouse AND ws.id_shop='".$id_shop."'";
  $query .= " ORDER BY name";
  $res=dbquery($query);
  $warehouseblock = '<option value="0">Do not transfer</option>';
  while($row = mysqli_fetch_array($res))
  { $warehouseblock .= '<option value="'.$row['id_warehouse'].'">'.str_replace("'","\'",$row['name']).'</option>';
  }   
  $warehouseblock .= "</select>";
}
else 
  $warehouseblock = "";

$out_of_stockblock = '<option value="0">Deny orders</option><option value="1">Allow orders</option><option value="2">Default</option></select>';
  
/* make carrier block */
if(in_array('carrier', $input["fields"]))
{ $query = "select id_reference,name from ". _DB_PREFIX_."carrier WHERE deleted='0' ORDER BY name";
  $res=dbquery($query);
  $carrierblock0 = '<input type=hidden name="carrier_defaultCQX"><input type=hidden name="mycarsCQX">';
  $carrierblock0 .= '<table cellspacing=8><tr><td><select id="carrierlistCQX" size=4 multiple>';
  $carrierblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { $carrierblock1 .= '<option value="'.$row['id_reference'].'">'.str_replace("'","\'",$row['name']).'</option>';
  } 
  $carrierblock1 .= '</select>';
  $carrierblock2 = '</td><td><a href=# onClick=" Addcarrier(\\\'CQX\\\'); reg_change(this); return false;"><img src=add.gif border=0></a><br><br>';
  $carrierblock2 .= '<a href=# onClick="Removecarrier(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></td><td><select id=carrierselCQX size=3><option>none</option></select></td></tr></table>';
}  
else 
  $carrierblock0 = $carrierblock1 = $carrierblock2 = ""; 
  
  /* make supplier names list */
if(in_array('supplier', $input["fields"]))
{ $query = "select id_supplier,name from ". _DB_PREFIX_."supplier ORDER BY name";  $res=dbquery($query);
  $supplier_names = array();
  $supplierblock0 = '<input type=hidden name="supplier_defaultCQX"><input type=hidden name="mysupsCQX">';
  $supplierblock0 .= '<table><tr><td><select id="supplierlistCQX" size=2>';
  $supplierblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { $supplier_names[$row['id_supplier']] = $row['name'];
    $supplierblock1 .= '<option value="'.$row['id_supplier'].'">'.str_replace("'","\'",$row['name']).'</option>';
  }
  $supplierblock1 .= '</select>';
  $supplierblock2 = '</td><td><nobr><a href=# onClick=" Addsupplier(\\\'CQX\\\',1); reg_change(this); return false;"><img src=add.gif border=0></a> &nbsp; &nbsp; ';
  $supplierblock2 .= '<a href=# onClick="Removesupplier(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></nobr></td><td><select size=2 id="supplierselCQX"></select>';
  $supplierblock2 .= '</td><td style="vertical-align:middle"><a href="#" onclick="MakeSupplierDefault(\\\'CQX\\\'); reg_change(this); return false;"><img src="starr.jpg" border="0"></a></td></tr></table>';
}
else 
  $supplierblock0 = $supplierblock1 = $supplierblock2 = "";
  
/* make attachment attachmnts block */
if((in_array('attachmnts', $input["fields"])) || (in_array('virtualp', $input["fields"])))
{ $query = "SELECT a.file_name, l.name, a.id_attachment FROM ". _DB_PREFIX_."attachment a";
  $query .= " LEFT JOIN ". _DB_PREFIX_."attachment_lang l ON a.id_attachment=l.id_attachment AND l.id_lang='".$id_lang."'";
  $query .= " ORDER BY l.name";
  $res = dbquery($query);
  $attachmentblock0 = '<input type=hidden name="attachment_defaultCQX"><input type=hidden name="myattachmentsCQX">';
  $attachmentblock0 .= '<table cellspacing=8><tr><td><select id="attachmentlistCQX" size=4 multiple>';
  $attachmentblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { $attachmentblock1 .= '<option value="'.$row['id_attachment'].'">'.str_replace("'","\'",$row['name']).'</option>';
  } 
  $attachmentblock1 .= '</select>';
  $attachmentblock2 = '</td><td><a href=# onClick=" Addattachment(\\\'CQX\\\'); reg_change(this); return false;"><img src=add.gif border=0></a><br><br>';
  $attachmentblock2 .= '<a href=# onClick="Removeattachment(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></td><td><select id=attachmentselCQX size=3><option>none</option></select></td></tr></table>';
  $currentDir = dirname(__FILE__);
  $download_dir = $currentDir."/".$triplepath."download/";
}
else 
  $attachmentblock0 = $attachmentblock1 = $attachmentblock2 = "";
  
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
  if(in_array(str_replace('"','_',$row['name']), $input["fields"]))
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
	  { $value = preg_replace("/[\r\n]+/"," ",$value); /* some people have linefeeds in those values. That doesn't work in the javascript array */
	    $block .= '<option value="'.$key.'">'.str_replace("'","\'",$value).'</option>';
	  }
	  $featureblocks[$featurecount++] = $block."</select>";
	}
  }
}

/* Make the discount blocks */
/* 					0			1				2		3		4			5		6			7			8			9	 			10			11	  12	*/
/* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_tax, reduction_type, from, to */
  if(in_array("discount", $input["fields"]))
  { $countryblock = "";
	$query=" select id_country,name from ". _DB_PREFIX_."country_lang WHERE id_lang='".$id_lang."' ORDER BY name";
	$res=dbquery($query);
	while ($country=mysqli_fetch_array($res)) {
		$countryblock .= '<option value="'.$country['id_country'].'" >'.$country['id_country']."-".$country['name'].'</option>';
	}

	$groupblock = "";
	$query=" select id_group,name from ". _DB_PREFIX_."group_lang WHERE id_lang='".$id_lang."' ORDER BY id_group";
	$res=dbquery($query);
	while ($group=mysqli_fetch_array($res)) {
		$groupblock .= '<option value="'.$group['id_group'].'" >'.$group['id_group']."-".$group['name'].'</option>';
	}
  }
  
  /* currency block: for discounts and suppliers */
  if((in_array("discount", $input["fields"])) || (in_array("supplier", $input["fields"])))
  { $currencyblock = "";
	$def_currency = "0";
    $currencies = array();
	$query="SELECT c.id_currency,c.iso_code, cs.conversion_rate";
	$query .= " FROM ". _DB_PREFIX_."currency c";
	$query .= " LEFT JOIN ". _DB_PREFIX_."currency_shop cs ON c.id_currency=cs.id_currency AND cs.id_shop='".$id_shop."'";	
	$query .= " WHERE deleted='0' AND active='1' ORDER BY name";
	$res=dbquery($query);
	while ($currency=mysqli_fetch_array($res)) {
		$currencyblock .= '<option value="'.$currency['id_currency'].'" >'.$currency['iso_code'].'</option>';
		$currencies[] = $currency['iso_code'];
		if($currency['conversion_rate'] == 1) 
			$def_currency = $currency['iso_code'];
	}
	if($def_currency == "0")
		$def_currency = $currencies[0];
  }
    
/* Make image format block */
  $query = "SELECT name,width,height from ". _DB_PREFIX_."image_type WHERE products=1 ORDER BY width";
  $res=dbquery($query);
  $imgformatblock = '<select name="imgformat" style="width:100px">';
  $row = mysqli_fetch_array($res); /* take small as the default */
  $selected_img_extension = "-".$row["name"].".jpg";
  $prod_imgwidth = $row["width"];
  $prod_imgheight = $row["height"];
  mysqli_data_seek($res,0);
  while($row = mysqli_fetch_array($res))
  { if($row["name"] == $input["imgformat"]) 
    { $selected = "selected"; 
	  $selected_img_extension = "-".$row["name"].".jpg";
	  $prod_imgwidth = $row["width"];
	  $prod_imgheight = $row["height"];
	}
    else $selected = "";
    $imgformatblock .= '<option value="'.$row['name'].'" '.$selected.'>'.$row['name'].' ('.$row['width'].'x'.$row['height'].')</option>';
  }
  $imgformatblock .= '</select>';
  
  $carrier_notpaid = $tags_notpaid = $supplier_notpaid = $discount_notpaid = $features_notpaid = $virtual_notpaid = false;
  
/* section 5: publish header block of html page, including javascript version of select blocks */
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<?php  // for security reasons the location of Prestools should be secret. So we dont give referer when you click on Prestools.com 
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false  || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false))
  echo '<meta name="referrer" content="no-referrer">';
else
  echo '<meta name="referrer" content="none">';	
?>
<title>Prestashop Product Multiedit</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script type="text/javascript" src="product-edit3.js"></script>
<?php 
  if(in_array("discount", $input["fields"]))
    echo '<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="windowfiles/dhtmlwindow.js"></script>
';
if($initwarnings != "")
	echo "<script>alert('".$initwarnings."');</script>";
?>
<script type="text/javascript">
var prestashop_version = '<?php echo _PS_VERSION_ ?>';
var product_fields = new Array();
var imgwidth = '<?php echo $prod_imgwidth ?>';
var imgheight = '<?php echo $prod_imgheight ?>';
var taxblock = '<?php echo $taxblock ?>';
var visibilityblock = '<?php echo $visibilityblock ?>';
var conditionblock = '<?php echo $conditionblock ?>';
var pack_stock_typeblock = '<?php echo $pack_stock_typeblock ?>';
var availorderblock = '<?php echo $availorderblock ?>';
var supplierblock0 = '<?php echo $supplierblock0 ?>';
var supplierblock1 = '<?php echo $supplierblock1 ?>';
var supplierblock2 = '<?php echo $supplierblock2 ?>';
var manufacturerblock = '<?php echo $manufacturerblock ?>';
var warehouseblock = '<?php echo $warehouseblock ?>';
var stockflagsblock = '<?php echo $stockflagsblock ?>';
var categoryblock0 = '<?php echo $categoryblock0 ?>';
var categoryblock1 = '<?php echo $categoryblock1 ?>';
var categoryblock2 = '<?php echo $categoryblock2 ?>';
var attachmentblock0 = '<?php echo $attachmentblock0 ?>';
var attachmentblock1 = '<?php echo $attachmentblock1 ?>';
var attachmentblock2 = '<?php echo $attachmentblock2 ?>';
var carrierblock0 = '<?php echo $carrierblock0 ?>';
var carrierblock1 = '<?php echo $carrierblock1 ?>';
var carrierblock2 = '<?php echo $carrierblock2 ?>';
var out_of_stockblock = '<?php echo $out_of_stockblock ?>';
var langcopyselblock = '<?php echo $langcopyselblock ?>';
var shop_ids = '<?php echo implode(",",$shop_ids); ?>';
var featurelist = [<?php 
$tmp = 0;
foreach($featurelist AS $featureitem)
{ if($tmp++ != 0) echo ",";
  echo "'".str_replace("'","\'",$featureitem)."'";
}
?>];
var featurekeys = ['<?php echo implode($featurekeys, "','"); ?>'];
var featureblocks = new Array();
<?php 
  for ($i=0; $i<$featurecount; $i++)
  { echo "featureblocks[".$i."]='".$featureblocks[$i]."';
";
  }
  if((in_array("discount", $input["fields"])) || (in_array("supplier", $input["fields"])))
  { echo "currencyblock='".$currencyblock."';
";	  
  }  
  if(in_array("discount", $input["fields"]))
  { echo "countryblock='".str_replace("'","\'",$countryblock)."';
	groupblock='".str_replace("'","\'",$groupblock)."';
	shopblock='".str_replace("'","\'",$shopblock)."';
";
    echo 'currencies=["'.implode('","', $currencies).'"]; 
'; 
  }
  
  echo 'var prestools_notbought = ["'.implode('","', $prestools_notbought).'"];
  numrecs='.$input["numrecs"].';
  startdate="'.$input["startdate"].'";
  enddate="'.$input["enddate"].'";
  id_shop='.$id_shop.';
  triplepath="'.$triplepath.'";
  fields = ["'.implode('","', $input["fields"]).'"];
';
?>

function init()
{ showtotals();
<?php if(isset($lean_tabindex) && ($lean_tabindex)) /* lean_tabindex is set in settings1.php. It removes links from the tabindex - making work faster for people using the tabkey on the keyboard to navigate between fields */
	echo '
var tab = document.getElementById("Maintable");
var links = tab.getElementsByTagName("a");
var linkcount = links.length;
for(var i=0; i<linkcount; i++)
  links[i].tabIndex = "-1";
var buttons = tab.querySelectorAll("input[type=button]");
var buttoncount = buttons.length;
for(var i=0; i<buttoncount; i++)
  buttons[i].tabIndex = "-1";
'; ?>
}
</script>
</head>

<body onload=init()>
<?php
/* section 6: publish page header: menu and diagnostic information */
print_menubar();
echo '<table class="triplehome" cellpadding=0 cellspacing=0><tr>';
echo '<td width="80%" ><center><a href="product-edit.php" style="text-decoration:none;"><b><font size="+1">Product Edit</font></b></a></center>';
echo "Config: ";
echo "Default lang=".$def_langname." (used for names)";
if($input['id_lang'] != "")
  echo " - Active lang=".$languagename;

echo ". Country=".$countryname." (used for VAT).";
/* The following was added to have diagnostics when problems happen */
$cquery="select count(*) from `". _DB_PREFIX_."shop`";
$cres=dbquery($cquery);
list($shop_count) = mysqli_fetch_row($cres);
$cquery="select count(*) from `". _DB_PREFIX_."shop_group`";
$cres=dbquery($cquery);
list($shop_group_count) = mysqli_fetch_row($cres);
$cquery="select count(*) from `". _DB_PREFIX_."group`";
$cres=dbquery($cquery);
list($group_count) = mysqli_fetch_row($cres);
$cquery="select count(*) from `". _DB_PREFIX_."lang`";
$cres=dbquery($cquery);
list($lang_count) = mysqli_fetch_row($cres);
$stock_management_conf = get_configuration_value('PS_STOCK_MANAGEMENT');
$advanced_stock_management_conf = get_configuration_value('PS_ADVANCED_STOCK_MANAGEMENT');
echo " ".$shop_count." shop(s); ".$shop_group_count." shopgroup(s); ".$group_count." group(s); ".$lang_count." language(s).";
echo ' Stock: ';
if($stock_management_conf == 0) 	echo 'No';
  else if($advanced_stock_management_conf == 0)	echo 'Yes';
  else echo 'ASM';
echo '; Shared: ';
if(!$share_stock) echo 'No';
  else echo $id_shop_group;
echo ";";
echo "</td>";

echo '<td style="text-align:right; width:30%" rowspan=3><iframe name=tank width="230" height="95"></iframe></td></tr>';
echo '</tr><tr><td id="notpaid"></td></tr></table>';

/* section 7: publish search block */
function fieldcmp($a, $b) 
{ if ($a[1][0] == $b[1][0]) 
  { return 0;
  }
  return (strtoupper($a[1][0]) < strtoupper($b[1][0])) ? -1 : 1;
}
/* Remove fields from the sorted_field_array that can not be searched on */
$x=0;
$sorted_field_array = $field_array; /* this array will provide the field names for the "sort by" select box */
$deleters = array();
foreach($sorted_field_array AS $key => $farray) /* now filter out the fields not supported for the search function */
{ if(in_array($key, array("pack_stock_type")))
     $deleters[] = $x;	 
  $x++;
}

rsort($deleters); /* we have to work from high to low as deleting the low numbers first we would need to adapt the higher ones */
foreach($deleters AS $deleter)
{ array_splice($sorted_field_array,$deleter,1);
}
  
uasort($sorted_field_array, "fieldcmp");
$x=0;

?>

<table class="triplesearch"><tr><td>
<form name="search_form" method="get" action="product-edit.php" onsubmit="newwin_check()"><div id="searchblock">
<table class="tripleminimal"><tr><td><nobr>Find <input name="search_txt1" type="text" value="<?php echo str_replace('"','&quot;',$input['search_txt1']) ?>" size="6"  />
<select name="search_cmp1" class="comparer">
<?php 
 $comparer_values = array("in"=>"in","not_in"=>"!in","gt"=>"&gt;","gte"=>"&gt;=","eq"=>"=","not_eq"=>"!=","lte"=>"&lt;=","lt"=>"&lt;");
 foreach($comparer_values AS $key => $value)
 { $selected = "";
   if($key == $input["search_cmp1"])
	 $selected = " selected";
   echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
 }
 echo "</select></nobr>";
 echo '<br><select name="search_fld1" style="width:10em" onchange="change_sfield(this);"><option>main fields</option>';
  $statz = array("salescount", "revenue","ordercount","buyercount","visitcount","visitedpages");
  $found = false;
  foreach($sorted_field_array AS $key => $value)
  { if(($value[8] != "") && (!in_array($value[8], $statz)))
	{ $selected = "";
	  if($search_fld1 == $value[8])
	  { $selected = "selected";
		$found = true;
	  }
	  if($key == "category")
	  {	echo "<option value='".$value[8]."' ".$selected.">category default id</option>"; 
        $selected = "";
	    if($search_fld1 == "cl.name")
	    { $selected = "selected";
		  $found = true;
	    }
		echo "<option value='cl.name' ".$selected.">category name</option>";			
        $selected = "";
	    if($search_fld1 == "cl.id_category")
	    { $selected = "selected";
		  $found = true;
	    }
		echo "<option value='cl.id_category' ".$selected.">category id</option>";
	  }
	  else if($key == "availorder")
	  {	echo "<option value='".$value[8]."' ".$selected.">available for Order</option>"; 
        $selected = "";
	    if($search_fld1 == "ps.show_price")
	    { $selected = "selected";
		  $found = true;
	    }
		echo "<option value='ps.show_price' ".$selected.">show price</option>"; 
	  }
	  else
	  { $searchtitle = $value[1][1];
	    echo "<option value='".$value[8]."' ".$selected.">".$searchtitle."</option>"; 
	  }
 	}
  }
/* the following fields are named sattrib and sfeatur. The "s" stands for search and at the end 
 * something has been removed from the words feature and attribute. Aim is to make it easy to
 * search for those fields without getting confused by other attribute or feature related code */
  $query = "SELECT id_attribute_group, name FROM `". _DB_PREFIX_."attribute_group_lang` WHERE id_lang=".$id_lang;
  $query .= " ORDER BY name";
  $res = dbquery($query);
  while($row=mysqli_fetch_assoc($res))
  { $selected = "";
	if($search_fld1 == "sattrib".$row["id_attribute_group"])
	{ $selected = "selected";
	  $found = true;
	}
    echo "<option value='sattrib".$row["id_attribute_group"]."' ".$selected.">a-".$row["name"]."</option>"; 
  }
  
  $query = "SELECT id_feature, name FROM `". _DB_PREFIX_."feature_lang` WHERE id_lang=".$id_lang;
  $query .= " ORDER BY name";
  $res = dbquery($query);
  while($row=mysqli_fetch_assoc($res))
  { $selected = "";
	if($search_fld1 == "sfeatur".$row["id_feature"])
	{ $selected = "selected";
	  $found = true;
	}
    echo "<option value='sfeatur".$row["id_feature"]."' ".$selected.">f-".$row["name"]."</option>"; 
  }
  
  if(!$found)
	  $search_fld1 = "main fields";
?>
</select></td><td><nobr>And <input name="search_txt2" type="text" value="<?php echo str_replace('"','&quot;',$input['search_txt2']) ?>" size="6"  />
<select name="search_cmp2" class="comparer">
<?php 
 foreach($comparer_values AS $key => $value)
 { $selected = "";
   if($key == $input["search_cmp2"])
	 $selected = " selected";
   echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
 }
 echo "</select></nobr>";
 echo '<br><select name="search_fld2" style="width:10em" onchange="change_sfield(this);"><option>main fields</option>';
  $found = false;
  foreach($sorted_field_array AS $key => $value)
  { if(($value[8] != "") && (!in_array($value[8], $statz)))
	{ $selected = "";
	  if($search_fld2 == $value[8])
	  { $selected = "selected";
		$found = true;
	  }
	  if($key == "category")
	  {	echo "<option value='".$value[8]."' ".$selected.">category default id</option>"; 
        $selected = "";
	    if($search_fld2 == "cl.name")
	    { $selected = "selected";
		  $found = true;
	    }
		echo "<option value='cl.name' ".$selected.">category name</option>";
        $selected = "";
	    if($search_fld2 == "cl.id_category")
	    { $selected = "selected";
		  $found = true;
	    }
		echo "<option value='cl.id_category' ".$selected.">category id</option>";		
	  }
	  else
	  { $searchtitle = $value[1][1];
	    echo "<option value='".$value[8]."' ".$selected.">".$searchtitle."</option>"; 
	  }
 	}
  }
/* the following fields are named sattrib and sfeatur. The "s" stands for search and at the end 
 * something has been removed from the words feature and attribute. Aim is to make it easy to
 * search for those fields without getting confused by other attribute or feature related code */
  $query = "SELECT id_attribute_group, name FROM `". _DB_PREFIX_."attribute_group_lang` WHERE id_lang=".$id_lang;
  $query .= " ORDER BY name";
  $res = dbquery($query);
  while($row=mysqli_fetch_assoc($res))
  { $selected = "";
	if($search_fld2 == "sattrib".$row["id_attribute_group"])
	{ $selected = "selected";
	  $found = true;
	}
    echo "<option value='sattrib".$row["id_attribute_group"]."' ".$selected.">a-".$row["name"]."</option>"; 
  }
  
  $query = "SELECT id_feature, name FROM `". _DB_PREFIX_."feature_lang` WHERE id_lang=".$id_lang;
  $query .= " ORDER BY name";
  $res = dbquery($query);
  while($row=mysqli_fetch_assoc($res))
  { $selected = "";
	if($search_fld2 == "sfeatur".$row["id_feature"])
	{ $selected = "selected";
	  $found = true;
	}
    echo "<option value='sfeatur".$row["id_feature"]."' ".$selected.">f-".$row["name"]."</option>"; 
  }

  if(!$found)
	  $search_fld2 = "main fields";
  
	$res=dbquery("SELECT COUNT(*) AS rcount from ". _DB_PREFIX_."product");
	$row = mysqli_fetch_array($res);
	$totcount = $row["rcount"];
    echo '</select></td><td>Total '.$totcount.' prods. Sort by <select name="order" onchange="change_order()">';

	  if ($input['order']=="position") {$selected=' selected="selected" ';} else $selected="";
	  if (($id_category == 0) ||(isset($input['subcats']))) {$catdisp=' style="display:none"';} else $catdisp="";
	  echo '<option '.$selected.$catdisp.' id="cat_order">position</option>';
	  if ($input['order']=="id_product") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>id_product</option>';
	  if ($input['order']=="name") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>name</option>';
	  if ($input['order']=="price") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>price</option>';
	  if ($input['order']=="VAT") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>VAT</option>';
	  if ($input['order']=="shipweight") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>shipweight</option>';
	  if ($input['order']=="image") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>image</option>';
	  if ($input['order']=="active") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>active</option>';
	  if ($input['order']=="availorder") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>availorder</option>';
	  if ($input['order']=="pack_stock_type") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>pack_stock_type</option>';	  
	  if ($input['order']=="visits") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>visits</option>';
	  if ($input['order']=="visitz") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>visitz</option>';	  
	  if ($input['order']=="revenue") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>revenue</option>';
	  if ($input['order']=="orders") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>orders</option>';
	  if ($input['order']=="buyers") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>buyers</option>';
	  if ($input['order']=="date_upd") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>date_upd</option>';
	  echo '</select>';

	$checked = "";
	if($rising == 'DESC')
	  $checked = "selected";
    echo ' <SELECT name=rising><option>ASC</option><option '.$checked.'>DESC</option></select>';
	
	echo '&nbsp; <nobr>category <select name="id_category" onchange="change_categories();" style="width:12em">
	<option value="0">All categories</option>';

	$query=" select DISTINCT id_category,name from ". _DB_PREFIX_."category_lang WHERE id_lang=".$def_lang." ORDER BY name";
	$res=dbquery($query);
	while ($category=mysqli_fetch_array($res)) {
		if ($category['id_category']==$id_category) {$selected=' selected="selected" ';} else $selected="";
	        echo '<option  value="'.$category['id_category'].'" '.$selected.'>'.$category['name'].'</option>';
	}
	echo '</select>';
	$catvalue = "";
	if($id_category!=0)
		$catvalue = $id_category;
	echo '<input id="category_number" style="width:26px; height:13px; color:#888888" onkeyup="change_category_number(\'x\')" value="'.$catvalue.'">';
	
	$checked = "";
	if(isset($_GET["subcats"]) && $_GET["subcats"] == "on") $checked = "checked";
    echo ' &nbsp;With subcats<input type="checkbox" name="subcats" '.$checked.' onchange="change_subcats()"></nobr>';
 	
	echo ' <nobr>Startrec: <input size=3 name=startrec value="'.$startrec.'">';
	echo ' &nbsp &nbsp; Number of recs: <input size=3 name=numrecs value="'.$numrecs.'"></nobr>';
	echo ' &nbsp &nbsp; Language: <select name="id_lang" style="margin-top:5px">';
	echo $langselblock;
	echo '</select>';

	echo ' &nbsp; shop <select name="id_shop">'.$shopblock.'</select>';
	echo '</td></tr></table>';
	
	if($input["active"] == "active") $selected = "selected"; else $selected = "";
	echo '<select name=active><option>all</option><option '.$selected.'>active</option>';
	if($input["active"] == "inactive") $selected = "selected"; else $selected = "";	
	echo '<option '.$selected.'>inactive</option></select>';
	
  echo ' &nbsp; img '.$imgformatblock;
  echo '<hr/>';
	
/* Section 8: Publish the block where you can select your fields */
	echo '<table ><tr>';
	foreach($productedit_fieldblock AS $fieldrow)
	{ echo '<tr>';
	  foreach($fieldrow AS $fieldcel)
	  { $checked = in_array($fieldcel, $input["fields"]) ? "checked" : "";
	    if(($fieldcel == "") || (($fieldcel=="pack_stock_type") && (version_compare(_PS_VERSION_ , "1.6.0.12", "<")))
		 || (in_array($fieldcel, array("isbn", "show_condition","state")) && (version_compare(_PS_VERSION_ , "1.7.0", "<"))))
			echo "<td></td>";
		else if($fieldcel == "features")
			echo '<td><input type="checkbox" name="fields[]" value="features" '.$checked.' onchange="swapFeatures(this)" />Features</td>';
		else if ($fieldcel == "statistics")
			echo '<td><input type="checkbox" name="fields[]" value="statistics" '.$checked.' onchange="swapStats(this)" />Statistics</td>';
		else
			echo '<td><input type="checkbox" name="fields[]" value="'.$fieldcel.'" '.$checked.' />'.$field_array[$fieldcel][1][2].'</td>';
	  }
	  echo '</tr>';
	}

	$disped = in_array("features", $input["fields"]) ? "" : "style='display:none'";
	$colorclass = "";
	if($features_notpaid)
	  $colorclass = "notpaid";
	echo '<tr id=featureblock0 '.$disped.'" class="'.$colorclass.'">';
	$fcount = 0;
	$fblock = 0;
	foreach($features AS $key => $feature)
	{	if(!($fcount++ % 10)){ echo '</tr><tr id=featureblock'.++$fblock.' '.$disped.'>'; }
		$cleanfeature = str_replace('"','_',$feature);
		$checked = in_array($cleanfeature, $input["fields"]) ? "checked" : "";
		echo '<td><input type="checkbox" name="fields[]" value="'.$cleanfeature.'" '.$checked.' /> '.$feature.'</td>';
	}	
	
	$disped = in_array("statistics", $input["fields"]) ? "" : "style='display:none'";
	echo '</tr><tr id=statsblock '.$disped.'">';
	echo '<td colspan=4>Stats: Period (yyyy-mm-dd): <input size=5 name=startdate value='.$input['startdate'].'> till <input size=5 name=enddate value='.$input['enddate'].'><img src="ea.gif" title="Statistics here are per shop. For statistics for a product over all shops use product-sort."></td>';
	$checked = in_array("visits", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="visits" '.$checked.' />'.$screentext_pe["visits"][2].'</td>';
	$checked = in_array("visitz", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="visitz" '.$checked.' />'.$screentext_pe["visitz"][2].'</td>';
	$checked = in_array("salescnt", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="salescnt" '.$checked.' />'.$screentext_pe["salescnt"][2].'</td>';
	$checked = in_array("revenue", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="revenue" '.$checked.' />'.$screentext_pe["revenue"][2].'</td>';
	$checked = in_array("orders", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="orders" '.$checked.' />'.$screentext_pe["orders"][2].'</td>';	
	$checked = in_array("buyers", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="buyers" '.$checked.' />'.$screentext_pe["buyers"][2].'</td>';
	
	echo '</tr></table></div></td><td>';
	$checked = "";
	if(isset($input["imgonly"])) $checked = "checked";
	echo '<input type=checkbox name=imgonly '.$checked.'>img<br/>only<p/>
	<input type=checkbox name=newwin>new<br/>window<p/><input type="submit" value="search" /></td>';
	echo '</tr></table></form>';

/* section 9: Mass Edit */
/* first build the array that defines which mass edit functions are available for which fields */
	echo '<script type="text/javascript">
	  
	  var myarray = [];'; /* define which actions to show for which fields */
	  /* indices: 0=Set; 1=insert before 2=insert after 3=replace 4=increase% 5=regenerate 6=add 7=remove 8=copy from other lang 
	  9=copy from field 10=set as default 11=TinyMCE 12=TinyMCE-deluxe 13=replace from field 14=increase amount 
	  15=add fixed target discount */
  
    if($langcopyselblock != "")	/* if there are other languages: add "copy from other lang" */
	{ 							/*   0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 */
	  echo '
	  myarray["name"] = 			[1,1,1,1,0,0,0,0,1,0,0,0,0,0,0,0];
	  myarray["description_short"]= [1,1,1,1,0,0,0,0,1,1,0,1,1,1,0,0];
	  myarray["description"] = 		[1,1,1,1,0,0,0,0,1,1,0,1,1,1,0,0];	  
	  myarray["meta_title"] = 		[1,1,1,1,0,0,0,0,1,0,0,0,0,0,0,0];
	  myarray["metakeys"] = 		[1,1,1,1,0,0,0,0,1,0,0,0,0,0,0,0];
	  myarray["available_now"] = 	[1,1,1,1,0,0,0,0,1,0,0,0,0,0,0,0];
	  myarray["available_later"] = 	[1,1,1,1,0,0,0,0,1,0,0,0,0,0,0,0];
	  myarray["link_rewrite"] = 	[1,1,1,1,0,1,0,0,1,0,0,0,0,0,0,0];
	  myarray["meta_description"] = [1,1,1,1,0,0,0,0,1,1,0,0,0,1,0,0];';
	}
	else	/* no other languages    0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 */
	{ echo '
	  myarray["name"] = 			[1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["description_short"]= [1,1,1,1,0,0,0,0,0,1,0,1,1,1,0,0];
	  myarray["description"] = 		[1,1,1,1,0,0,0,0,0,1,0,1,1,1,0,0];	  
	  myarray["meta_title"] = 		[1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["metakeys"] = 		[1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["available_now"] = 	[1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["available_later"] = 	[1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["link_rewrite"] = 	[1,1,1,1,0,1,0,0,0,0,0,0,0,0,0,0];
	  myarray["meta_description"] = [1,1,1,1,0,0,0,0,0,1,0,0,0,1,0,0];';
	}							/*   0 1 2 3 4 5 6 7 8 9 0 1 2 3 */
	echo '
	  myarray["ean"] = 				[1,1,1,1,0,0,0,0,0,1,0,0,0,0,0,0];
	  myarray["upc"] = 				[1,1,1,1,0,0,0,0,0,1,0,0,0,0,0,0];	  
	  myarray["price"] = 			[1,0,0,1,1,0,0,0,0,0,0,0,0,0,1,0];
	  myarray["unitPrice"] = 		[1,0,0,1,1,0,0,0,0,0,0,0,0,0,1,0];	  
	  myarray["VAT"] = 				[1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["priceVAT"] = 		[1,0,0,1,1,0,0,0,0,0,0,0,0,0,1,0];
	  myarray["reference"] = 		[1,1,1,1,0,0,0,0,0,1,0,0,0,0,0,0];
	  myarray["manufacturer"] = 	[1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["stockflags"] = 		[1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];	  
	  myarray["link_rewrite"] = 	[1,1,1,1,0,1,0,0,0,0,0,0,0,0,0,0];
	  myarray["shipweight"] = 		[1,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["accessories"] = 		[1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["active"] = 			[1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["on_sale"] = 			[1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];';
	  							/*   0 1 2 3 4 5 6 7 8 9 0 1 2 3 */
	echo '
	  myarray["online_only"] = 		[1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["available"] = 		[1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];	
	  myarray["available_date"]=	[1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];		  
	  myarray["discount"] = 		[0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,1];
	  myarray["tags"] = 			[1,0,0,1,0,0,1,1,0,1,0,0,0,1,0,0];
	  myarray["shopz"] = 			[0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,0];	  
	  myarray["carrier"] = 			[0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,0];
	  myarray["quantity"] = 		[1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0];	  
	  myarray["default"] = 			[1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["supplier"] = 		[0,0,0,0,0,0,1,1,0,0,1,0,0,0,0,0];	
	  myarray["attachmnts"] = 		[0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,0];
	  myarray["wholesaleprice"] = 	[1,0,0,1,1,0,0,0,0,0,0,0,0,0,1,0];	  
	  myarray["image"] = 			[0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0];	
	  myarray["Select a field"] = 	[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["category"] = 		[0,0,0,0,0,0,1,1,0,0,1,0,0,0,0,0];';
   	foreach($featurelist AS $key => $feature)
	  echo 'myarray["feature'.$key.'field"]=[1,0,0,1,0,0,0,0,0,1,0,0,0,0,0,0];';
								/*   0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 */

echo '  
	</script>';
	echo '<hr/><table style="background-color:#CCCCCC; width:100%"><tr><td style="width:90%">'.t('Mass update').'<form name="massform" onsubmit="massUpdate(); return false;">
	<select name="field" onchange="changeMfield()"><option value="Select a field">'.t('Select a field').'</option>';
	foreach($field_array AS $key => $field)
	{	if((in_array($key, $input['fields'])) && ($field[7] != NOT_EDITABLE))
			echo '<option value="'.$field[0].'">'.$field[1][0].'</option>';
	}
	foreach($features AS $key => $feature)
    { $cleanfeature = str_replace('"','_',$feature);
	  if (in_array($cleanfeature, $input["fields"]))
	  {	echo '<option value="feature'.$key.'field">'.$feature.'</option>';
	  }
	}
	echo '</select>';
	echo '<select name="action" onchange="changeMAfield()" style="width:135px"><option>Select an action</option>';
	echo '<option>set</option>';
	echo '<option>insert before</option>';
	echo '<option>insert after</option>';
	echo '<option>replace</option>';
	echo '<option>increase%</option>';
	echo '<option>regenerate</option>';
	echo '<option>add</option>';
	echo '<option>remove</option>';
	echo '<option>copy from other lang</option>';
	echo '<option>copy from field</option>';
	echo '<option>set as default</option>';	
	echo '<option>TinyMCE</option>';
	echo '<option>TinyMCE-deluxe</option>';
	echo '<option>replace from field</option>';	
	echo '<option>increase amount</option>';
	echo '<option>add fixed target discount</option>';	
	echo '</select>';
	echo '&nbsp; <span id="muval">value: <textarea name="myvalue" class="masstarea"></textarea></span>';
	echo ' &nbsp; &nbsp; <input type="submit" value="'.t('update all editable records').'"></form>';
	echo t('NB: Prior to mass update you need to make the field editable. Afterwards you need to submit the records');
	echo '</td></tr></table>';
	
/* section 10: csv generation */
/* the searchblock will be copied to csvsearchdiv before submission */
	echo '<table class="triplecsv" ><tr><td width="50%"><form name=csvform target=_blank action="product-csv.php">Separator <input type="radio" name="separator" value="semicolon" checked>; <input type="radio" name="separator" value="comma">, ';
	echo '<input type=hidden name=verbose><div style="display:none" id="csvsearchdiv"></div>';
	echo ' &nbsp; <button onclick="submitCSV(); return false;">Export CSV</button>';
	echo ' &nbsp; &nbsp; select the fields and records in the search block</form></td>';
	
/* section 11: csv import */	
	echo '<td width="50%" style="display:none"><form method="post" name=csvimportform enctype="multipart/form-data" target=tank action="product-csv-import.php">';
	echo '<input type="file" name="fileToUpload" id="fileToUpload">';
	echo ' &nbsp; Separator <input type="radio" name="separator" value="semicolon" checked>; <input type="radio" name="separator" value="comma">, ';
	echo '<input type=hidden name=verbose><input type=hidden name=myprods><input type=hidden name=myfields>';
	echo '<input type=hidden name=myprodidxs> &nbsp; <button onclick="importCSV(); return false;">Import CSV</button>';
	echo ' &nbsp; <img src="ea.gif" title="The first field of the CSV must be the product id. Editable fields will be updated from the CSV. Use as column headings in the csv the same names as in Settings1.php">';
	echo '</form></td></tr></table>';
	
/* section 12: re-indexation */
  echo '<form name=IndexForm action="reindex.php" target="tank" method="post"><table class="tripleminimal"><tr><td style="width:80%">
  <input type=checkbox name=skipindexation>'.t('Skip indexation (much faster, but you need to re-index later)').'
  <input type=hidden name=verbose></td>';
  $iquery = "SELECT COUNT(DISTINCT id_product) AS prodcount FROM ". _DB_PREFIX_."product_shop WHERE indexed=0 AND visibility IN ('both', 'search') AND `active` = 1";
  $ires=dbquery($iquery);
  list($unindexedcount) = mysqli_fetch_row($ires); 
  echo '<td style="text-align:right; width:12%"><button onclick="reindex(); return false;"><nobr>'.t('Re-index the <span id=reindexspan>%s</span> unindexed products','',$unindexedcount).'</nobr></button></td>';

  echo '</tr></table></form><hr>';

/* section 13: the switchform: hide/show fields and submit button */
  echo '<form name=SwitchForm><table class="tripleswitch" style="empty-cells: show;"><tr><td><br>Hide<br>Show<br>Edit</td>';
  
  for($i=2; $i< sizeof($infofields); $i++)
  { /* standard the start mode of fields is "DISPLAY"(=1). But you could specify in $infofields[$i][3] that the field is initially hidden or in edit mode */
	$checked0 = $checked1 = $checked2 = "";
    if($infofields[$i][3] == 0) $checked0 = "checked"; 
    if($infofields[$i][3] == 1) $checked1 = "checked"; 
    if($infofields[$i][3] == 2) $checked2 = "checked"; 

	$colorclass = "";
    if(($infofields[$i][0] == "carrier") && $carrier_notpaid)
	  $colorclass = "notpaid";
    if(($infofields[$i][0] == "discount") && $discount_notpaid)
	  $colorclass = "notpaid";
    if(($infofields[$i][0] == "features") && $features_notpaid)
	  $colorclass = "notpaid";
    if(($infofields[$i][0] == "supplier") && $supplier_notpaid)
	  $colorclass = "notpaid";	
    if(($infofields[$i][0] == "tags") && $tags_notpaid)
	  $colorclass = "notpaid";
    if(($infofields[$i][0] == "virtualp") && $virtual_notpaid)
	  $colorclass = "notpaid";
    echo '<td class="'.$colorclass.'">'.$infofields[$i][1][0].'<br>';
    echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_off" value="0" '.$checked0.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',0)" /><br>';
    echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="1" '.$checked1.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',1)" /><br>';
    if ($infofields[$i][0] == "stockflags")
	{ /* first check the global flags. if they don't allow stockkeeping everything stops */
	  $query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_STOCK_MANAGEMENT'";
	  $res = dbquery($query);
	  $row = mysqli_fetch_array($res);
	  $query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_ADVANCED_STOCK_MANAGEMENT'";
	  $res2 = dbquery($query);
	  $row2 = mysqli_fetch_array($res2);
	  $stock_allowed = false;
	  if(($row["value"] == "1") && ($row2["value"] == "1")) $stock_allowed = true;
	}
	if(($infofields[$i][0]=="image"))
	{ echo '<nobr><input type="radio" name="disp'.$i.'" id="disp'.$i.'_off" value="2" onClick="switchDisplay(\'offTblBdy\', this,'.$i.',2)" />';
	  echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="3" onClick="switchDisplay(\'offTblBdy\', this,'.$i.',3)" />';
//	  echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="4" onClick="switchDisplay(\'offTblBdy\', this,'.$i.',4)" />';
	  echo '</nobr>';
    }
	else if(($infofields[$i][0]!="parent") && ($infofields[$i][0]!="depth") && ($infofields[$i][7]!=NOT_EDITABLE) && (($infofields[$i][0]!="stockflags") || $stock_allowed))
      echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_edit" value="2" '.$checked2.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',2)" />';
    else
      echo "&nbsp;";
    echo "</td>";
  }

  foreach($features AS $key => $feature)
  {	$cleanfeature = str_replace('"','_',$feature);
    if (in_array($cleanfeature, $input["fields"]))
	{    echo '<td><nobr>'.$feature.'</nobr><br>';
		echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_off" value="0" '.$checked0.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',0)" /><br>';
		echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="1" '.$checked1.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',1)" /><br>';
		echo '<nobr>';
		$pos = array_search($cleanfeature, $featurelist);
		$pos2 = array_search($pos, $featurekeys);
		if($featureblocks[$pos2] != "")
			echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_edit" value="4" onClick="switchDisplay(\'offTblBdy\', this,'.$i.',4)" />';
		echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_edit" value="2" onClick="switchDisplay(\'offTblBdy\', this,'.$i.',2)" />';
		echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_edit" value="3" onClick="switchDisplay(\'offTblBdy\', this,'.$i.',3)" /></nobr>';
		echo '</td>';
		$i++;
	}
  }
  echo "<td width='35%' align=center><input type=checkbox name=verbose>verbose &nbsp; &nbsp; ";
  echo "<input type=button value='Submit all' onClick='return SubmitForm();'></form></td>";
  if (in_array("image", $input["fields"]))
    $listsize = 20;
  else 
    $listsize = 40;
/* section 14: list form */
  echo '<td width="20%">
    <form name="listerform" target=_blank action="product-list.php"><input type=hidden name=verbose>
    <input type=checkbox name=listdefault checked> default fields<br/>
    Lines/page: <input name="listlines" value="'.$listsize.'" size="2"><br/>
	<nobr>Separationlines: <input name="listseps" value="1" size="1"></nobr><br/>
    Cols: <select name="listcols"><option>1</option><option>2</option><option selected>3</option></select> &nbsp;
	<div style="display:none" id="listsearchdiv"></div>
	<input type="submit" value="List products" onclick="ListProducts(); return false;" title="Make a printable productlist of the selected products and fields." /></td></tr></table></form>
	';
	
/* section 15: Hidden form for copying data from one language to another */
  echo '<form name="copyForm" action="copy_product_language.php" target="tank" method="post"><input type="hidden" name="products"><input type="hidden" name="id_shop" value="'.$id_shop.'"><input type="hidden" name="id_lang" value="'.$def_lang.'"><input type=hidden name=fields></form>';
 // "*********************************************************************";

/* section 16: build and execute the product query */
$categories = array();
$products_done = array();
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
/* DISTINCT is for when "with subcats" results in more than one occurence */
$queryterms = "DISTINCT p.*,pl.*,ps.*,t.id_tax,t.rate,m.name AS manufacturer, cl.name AS catname,p.id_product AS ptest,pl.id_product AS pltest";
$queryterms .=  ", cl.link_rewrite AS catrewrite, pld.name AS originalname, s.quantity, s.depends_on_stock";

$query = " FROM ". _DB_PREFIX_."product_shop ps LEFT JOIN ". _DB_PREFIX_."product p ON p.id_product=ps.id_product";
$query.=" LEFT JOIN ". _DB_PREFIX_."product_lang pl ON pl.id_product=p.id_product and pl.id_lang='".$id_lang."' AND pl.id_shop='".$id_shop."'";
$query.=" LEFT JOIN ". _DB_PREFIX_."product_lang pld ON pld.id_product=p.id_product and pld.id_lang='".$def_lang."' AND pld.id_shop='".$id_shop."'"; /* This gives the name in the shop language instead of the selected language */
$query.=" LEFT JOIN ". _DB_PREFIX_."manufacturer m ON m.id_manufacturer=p.id_manufacturer";
$query.=" LEFT JOIN ". _DB_PREFIX_."category_lang cl ON cl.id_category=ps.id_category_default AND cl.id_lang='".$id_lang."' AND cl.id_shop = '".$id_shop."'";
if($share_stock == 0)
  $query.=" LEFT JOIN ". _DB_PREFIX_."stock_available s ON s.id_product=p.id_product AND s.id_shop = '".$id_shop."' AND id_product_attribute='0'";
else
  $query.=" LEFT JOIN ". _DB_PREFIX_."stock_available s ON s.id_product=p.id_product AND s.id_shop_group = '".$id_shop_group."' AND id_product_attribute='0'";
$query.=" LEFT JOIN ". _DB_PREFIX_."tax_rule tr ON tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."' AND tr.id_state='0'";
$query.=" LEFT JOIN ". _DB_PREFIX_."tax t ON t.id_tax=tr.id_tax";
$query.=" LEFT JOIN ". _DB_PREFIX_."tax_lang tl ON t.id_tax=tl.id_tax AND tl.id_lang='".$def_lang."'";

if ($input['order']=="id_product") $order="p.id_product";
else if ($input['order']=="name") $order="pl.name";
else if ($input['order']=="position") $order="cp.position"; /* later to be refined */
else if ($input['order']=="VAT") $order="t.rate";
else if ($input['order']=="price") $order="ps.price";
else if ($input['order']=="active") $order="ps.active";
else if ($input['order']=="availorder") $order="ps.available_for_order,ps.show_price";
else if ($input['order']=="pack_stock_type") $order="ps.pack_stock_type";
else if ($input['order']=="shipweight") $order="p.weight";
else if ($input['order']=="date_upd") $order="p.date_upd";
else if ($input['order']=="image")  /* sorting on image makes only sense to get the products without an image */
{  $order="i.cover";
   $queryterms .= ",i.id_image, i.cover";
   $query.=" LEFT JOIN ". _DB_PREFIX_."image i ON i.id_product=p.id_product and i.cover=1";
}
else $order = $input['order'];

if ($input['active']=="active")
	$wheretext .= " AND ps.active=1";
else if ($input['active']=="inactive")
	$wheretext .= " AND ps.active=0";

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
  $queryterms .= ", filename, display_filename, date_expiration, nb_days_accessible, nb_downloadable";
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
{ $cleanfeature = str_replace('"','_',$feature);
  if (in_array($cleanfeature, $input["fields"]))
  { $query.=" LEFT JOIN ". _DB_PREFIX_."feature_product fp".$key." ON fp".$key.".id_product=p.id_product AND fp".$key.".id_feature='".$key."'";
	$query.=" LEFT JOIN ". _DB_PREFIX_."feature_value fv".$key." ON fp".$key.".id_feature_value=fv".$key.".id_feature_value";
	$query.=" LEFT JOIN ". _DB_PREFIX_."feature_value_lang fl".$key." ON fp".$key.".id_feature_value=fl".$key.".id_feature_value AND fl".$key.".id_lang='".$id_lang."'";
	$queryterms .= ",fv".$key.".custom AS custom".$key.",fl".$key.".value AS value".$key;
  }
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

$activequery = "SELECT COUNT(DISTINCT p.id_product) AS rcount ".$query." WHERE ps.active='1' AND ps.id_shop='".$id_shop."' ".$wheretext;
$res=dbquery($activequery);
$row = mysqli_fetch_array($res);
$activerecs = $row['rcount'];

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
  $query .= " GROUP BY p.id_product ORDER BY ".$ordertxt." ".$rising." LIMIT ".$startrec.",".$numrecs;
  
  $query= "select SQL_CALC_FOUND_ROWS ".$queryterms.$query; /* note: you cannot write here t.* as t.active will overwrite p.active without warning */
  $res=dbquery($query);
  $numrecs3 = mysqli_num_rows($res);
  $res2=dbquery("SELECT FOUND_ROWS() AS foundrows");
  $row2 = mysqli_fetch_array($res2);
  $numrecs2 = $row2['foundrows'];
//  echo $query;
  
/* section 17: Next and Prev */
  echo '<table class="tripleminimal"><tr><td style="width:5%">';
  $nextlink = preg_replace("/&?startrec=[0-9]*/","",$_SERVER['REQUEST_URI']);
  if(!strpos($nextlink, "?")) $nextlink .= "?1=1";
  $startrec = $startrec;
  $numrecs4 = $numrecs;  
  if($startrec == 0) echo "PREV ";
  else
  { $prevrec = $startrec-$numrecs4; 
	if($prevrec > $numrecs2) $prevrec = $numrecs2-1;
    if($prevrec < 0) $prevrec = 0;
	echo '<a href="'.$nextlink.'&startrec='.$prevrec.'">PREV</a>';
  }
  echo '</td><td style="width:30%; text-align:center"><b>'.$startrec.' to '.($startrec+$numrecs3-1).'</b> of '.$numrecs2." records (".$activerecs." active) in shop ".$id_shop;
  if($share_stock == 1) echo " - stock group ".$shop_group_name;
  echo '</td><td style="text-align:right; width:5%">';
  if($startrec + $numrecs4 >= $numrecs2) echo " NEXT";
  else
  { $nextrec = $startrec + $numrecs4; 
	echo '<a href="'.$nextlink.'&startrec='.$nextrec.'">NEXT</a>';
  }
  echo "</td></tr></table>";
  
/* section 18: warnings and explanations */
  $warning = "";
  if(in_array("accessories", $input["fields"]))
    $warning .= "For accessories fill in comma separated article numbers like '233,467'. Non-existent articles numbers will be ignored!<br/>";
  if(in_array("carrier", $input["fields"]))
    $warning .= "Standard all carriers are assigned to all products. You need to assign carriers here only in special cases.<br/>";
  if(in_array("shopz", $input["fields"]))
    $warning .= "No stock will be assigned if you add a product to more shops.<br/>";
  echo '<span id="warning" style="background-color: #FFAAAA">'.$warning.'</span>';
echo "<script>
var numrecs3 = ".$numrecs3.";
</script>";
  // "*********************************************************************";
  echo '<div id="dhwindow" style="display:none"></div>';
  
/* section 19: The main form: the multishop option */
  if(isset($avoid_iframes) && $avoid_iframes)
	 echo '<form name="Mainform" method=get>';
  else
	echo '<form name="Mainform" method=post>';
  echo '<input type=hidden name=reccount value="'.$numrecs3.'"><input type=hidden name=id_lang value="'.$id_lang.'">';
  if(sizeof($shops)>1)
  { if(!isset($updateallshops)) $updateallshops = 0;
    echo '<table class="triplemain"><tr><td>You have more than one shop. Do you want to apply your changes to other shops too?<br>
	<input type="radio" name="allshops" value="0" '.($updateallshops==0 ? 'checked': '').' onchange="change_allshops(\'0\')"> No ';
	if($share_stock == 1)
	{ echo ' &nbsp; <input type="radio" name="allshops" value="2" '.($updateallshops==1 ? 'checked': '').' onchange="change_allshops(\'2\')"> Yes, to the shop group';
	}
	else if($updateallshops==1) echo '<script>alert("You set an invalid value for $updateallshops!!!");</script>';
	echo ' &nbsp; <input type="radio" name="allshops" value="1" '.($updateallshops==2 ? 'checked': '').' onchange="change_allshops(\'1\')"> Yes, to all shops<br>
		(some stock related fields cannot be shared this way)
	</td></tr></table> ';
  }
  else
	echo '<input type=hidden name=allshops value=0>';
  echo '<input type=hidden name=id_shop value='.$id_shop.'><input type=hidden name=skipindexation>';
  echo '<input type=hidden name=verbose><input type=hidden name=featuresset><input type=hidden name=urlsrc>';
  
/* section 20: The main table: the column headers */
  if(!isset($input["imgonly"]))
  { echo '<div id="testdiv"><table id="Maintable" class="triplemain"><colgroup id="mycolgroup">';
    for($i=0; $i<sizeof($infofields); $i++)
    { $align = $namecol = "";
      if($infofields[$i][5] == 1)
        $align = ' style="text-align:right"';
	  if($infofields[$i][0] == "name")
        $namecol = ' class="namecol"';
      echo "<col id='col".$i."'".$align.$namecol."></col>";
    }
    for(;$i<sizeof($infofields)+$featurecount; $i++)
	  echo "<col id='col".$i."'></col>";
    echo "</colgroup><thead><tr>";

    $statsfound = false; /* flag whether we should create an extra stats totals line */
    $stattotals = array();
    for($i=0; $i<sizeof($infofields); $i++)
    { $reverse = "false";
      $id="";
      if (in_array($infofields[$i][0], $statfields))
	  { $reverse = 1;
	    $id = 'id="stat_'.$infofields[$i][0].'"'; /* assign id for filling in totals */
        $statsfound = true;
	    $stattotals[$infofields[$i][0]] = floatval(0);
	  }
      echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$i.', '.$reverse.');" '.$id.' fieldname="'.$infofields[$i][0].'" title="'.$infofields[$i][1][1].'">'.$infofields[$i][1][0].'</a></th
>';
    }
    foreach($features AS $key => $feature)
    { $cleanfeature = str_replace('"','_',$feature);
      if (in_array($cleanfeature, $input["fields"]))
	 	echo '<th><a href="" onclick="this.blur(); return sortTheTable(\'offTblBdy\', '.$i++.', false);" fieldname="'.str_replace('"','\"',$feature).'" title="'.str_replace('"','\"',$feature).'">'.$feature.'</a></th>';
    } 	

    echo '<th><a href="" onclick="this.blur(); return upsideDown(\'offTblBdy\');" title="Upside down: reverse table order"><img src="upsidedown.jpg"></a></th>';
    echo "</tr></thead
  ><tbody id='offTblBdy'>"; /* end of header */
  }
  
/* section 21: the main table: the products */
  $x=0;
  $legacy_images = get_configuration_value('PS_LEGACY_IMAGES');
  $haserrors = false;
  while ($datarow=mysqli_fetch_array($res)) { 
    if(($datarow['name'] == "") || ($datarow['pltest'] === NULL) || ($datarow['ptest'] === NULL))
	{ $haserrors = true;
	  continue;
	}
    if(isset($input["imgonly"]))
	{ $iquery = "SELECT i.id_image,legend FROM ". _DB_PREFIX_."image i";
	  $iquery .= " LEFT JOIN ". _DB_PREFIX_."image_lang il ON i.id_image=il.id_image AND il.id_lang=".$id_lang;
	  $iquery .= " WHERE id_product='".$datarow['id_product']."' AND cover=1";
	  $ires=dbquery($iquery);
	  echo "<a href='product-solo.php?id_product='".$datarow['id_product']."'&id_lang='".$id_lang."'&id_shop=".$id_shop."' title='".$datarow['originalname']."' target='_blank'>";
	  if(mysqli_num_rows($ires)==0)
	  { echo "<div class='noimg'>NO<br>IMG</div></a>";
		continue;		  
	  }
	  $irow=mysqli_fetch_array($ires);
	  $id_image = $irow["id_image"];
	  if($x==0)
	  { $selected_img_extension = get_image_extension($datarow['id_product'], $id_image, "product");
		$base_uri = get_base_uri();
	  }
	  if($legacy_images)
	    echo '<img src="'.$base_uri.'img/p/'.$id_product.'-'.$id_image.$selected_img_extension.'" /></a>';
      else
	    echo '<img src="'.$base_uri.'img/p'.getpath($id_image).'/'.$id_image.$selected_img_extension.'" /></a>';
	  mysqli_free_result($ires);
	  $x++;
      continue;
	}

    /* Note that trid (<tr> id) cannot be an attribute of the tr as it would get lost with sorting */
    echo '<tr><td id="trid'.$x.'" changed="0"><input type="button" value="X" style="width:4px" onclick="RemoveRow('.$x.')" title="Hide row '.$x.' from display" /><input type=hidden name="id_product'.$x.'" value="'.$datarow['id_product'].'"></td>';
	for($i=1; $i< sizeof($infofields); $i++)
    { $sorttxt = "";
      $color = "";
	  if (($infofields[$i][0] == "quantity") || ($infofields[$i][0] == "stockflags") || ($infofields[$i][0] == "warehousing") || ($infofields[$i][0] == "supplier") || ($infofields[$i][0] == "combinations"))
	  { $aquery = "SELECT count(*) FROM `". _DB_PREFIX_."product_attribute` WHERE id_product='".$datarow['id_product']."'";
		$ares=dbquery($aquery);
		list($combination_count) = mysqli_fetch_row($ares); 
	  }
	  
      if($infofields[$i][0] == "priceVAT")
		$myvalue =  number_format(((($datarow['rate']/100) +1) * $datarow['price']),4, '.', '');
      else if (($infofields[$i][0] != "carrier") && ($infofields[$i][0] != "tags") && ($infofields[$i][0] != "virtualp")
		  && ($infofields[$i][0] != "discount") && ($infofields[$i][0] != "combinations") 
	      && ($infofields[$i][0] != "supplier") && ($infofields[$i][0] != "attachmnts"))
        $myvalue = $datarow[$infofields[$i][2]];
	
	  /**************************************************************************************************/
	  /* Below the fields are listed alphabetically. Those missing get the default treatment at the end */
      if($i == 1) /* id */
	  { echo "<td><a href='product-solo.php?id_product=".$myvalue."&id_lang=".$id_lang."&id_shop=".$id_shop."' title='".$datarow['originalname']."' target='_blank'>".addspaces($myvalue)."</a></td>";
	  }
	  else if ($infofields[$i][0] == "accessories")
	  { echo "<td srt='".$myvalue."'>";
	    $accs = explode(",",$myvalue);
		$z=0;
	    foreach($accs AS $acc)
		{ if($z++ > 0) echo ",";
		  echo "<a title='".get_product_name($acc)."' href='#' onclick='return false;' style='text-decoration: none;'>".$acc."</a>";
		}
	    echo "</td>";
	  }  /* end of accessories */
	  
	  else if ($infofields[$i][0] == "attachmnts")
      { $cquery = "SELECT a.file_name, a.file, a.mime, l.name, p.id_attachment FROM ". _DB_PREFIX_."product_attachment p";
		$cquery .= " LEFT JOIN ". _DB_PREFIX_."attachment a ON a.id_attachment=p.id_attachment";
	    $cquery .= " LEFT JOIN ". _DB_PREFIX_."attachment_lang l ON a.id_attachment=l.id_attachment AND l.id_lang='".$id_lang."'";
	    $cquery .= " WHERE id_product='".$datarow['id_product']."'";
		echo "<td>";
		$cres=dbquery($cquery);
		$z=0;
		while ($crow=mysqli_fetch_array($cres)) 
		{	if($z++ > 0) echo "<br>";
			echo "<a class='attachlink' title='".$crow['id_attachment']."' href='downfile.php?filename=".$crow["file_name"]."&filecode=".$crow["file"]."&download_dir=".$download_dir."&mime=".$crow["mime"]."' target=_blank'>".$crow['name']."</a>";
	        if(!file_exists($download_dir."/".$crow["file"])) echo " <b>missing</b>";
			}
	    echo "</td>";
		mysqli_free_result($cres);
      }	 /* end of attachmnts */
	  
	  else if ($infofields[$i][0] == "availorder")
	  { echo "<td srt='".$myvalue."'>";
	    $available_for_order = $datarow['available_for_order'];
		$show_price = $datarow['show_price'];
		if($available_for_order == 1)
		  echo $availordertypes[0];
		else if($show_price == 1)
		  echo $availordertypes[1];
		else
		  echo $availordertypes[2];
	    echo "</td>";
	  }		/* end of availorder */
	  
	  else if ($infofields[$i][0] == "carrier")
      { $cquery = "SELECT id_carrier_reference FROM ". _DB_PREFIX_."product_carrier WHERE id_product='".$datarow['id_product']."' AND id_shop='".$id_shop."' LIMIT 1";
		$cres=dbquery($cquery);
		if(mysqli_num_rows($cres) != 0)
		{ $cquery = "SELECT id_reference, cr.name FROM ". _DB_PREFIX_."product_carrier pc";
		  $cquery .= " LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0";
		  $cquery .= " WHERE id_product='".$datarow['id_product']."' AND id_shop='".$id_shop."' ORDER BY cr.name";
		  $cres=dbquery($cquery);
		  echo "<td><table border=1 id='carriers".$x."'>";
		  while ($crow=mysqli_fetch_array($cres)) 
		  { echo "<tr><td id='".$crow['id_reference']."'>".$crow['name']."</td></tr>";
		  }
		  echo "</table></td>";
		}
		else
		  echo "<td></td>";
		mysqli_free_result($cres);
	  }	  /* end of carrier */
	  
      else if ($infofields[$i][0] == "category")
	  { echo "<td ".$sorttxt.">";
	    $cquery = "select id_category from ". _DB_PREFIX_."category_product WHERE id_product='".$datarow['id_product']."' ORDER BY id_category";
		$cres=dbquery($cquery);
		$z=0;
		$default_found = false;
		while ($crow=mysqli_fetch_array($cres)) 
		{	if($z++ > 0)
			{ echo ",";
			  if(!(($z-1)%3)) echo " "; /* put in a space so that the browser can break the line */
			}
			$catlink = get_base_uri().$langinsert.$crow['id_category']."-".$category_rewrites[$crow['id_category']];
			if ($crow['id_category'] == $myvalue)
			{	echo "<a title='".$category_names[$myvalue]."' href='".$catlink."' target='_blank'>".$myvalue."</a>";
				$default_found = true;
			}
			else 
				echo "<a title='".$category_names[$crow['id_category']]."' href='".$catlink."' target='_blank' style='text-decoration: none;'>".$crow['id_category']."</a>";
		}
	    echo "</td>";
		mysqli_free_result($cres);
	  }  /* end of category */
	  
	  else if ($infofields[$i][0] == "combinations")
      { echo '<td>';
		if($combination_count != 0)
		{ // if($datarow["depends_on_stock"] == "1")
		  echo '<table class="pattribute" style="display:none;" data-wh="'.$datarow["depends_on_stock"].'">';
		  $aquery = "SELECT ps.*, s.quantity, s.depends_on_stock";
		  $aquery .= " ,GROUP_CONCAT(CONCAT(LPAD(at.position,4,'0'))) AS positions FROM ". _DB_PREFIX_."product_attribute pa";
		  $aquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_shop ps on pa.id_product_attribute=ps.id_product_attribute AND ps.id_shop='".$id_shop."'";
		  $aquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_combination pc on pa.id_product_attribute=pc.id_product_attribute";
		  $aquery .= " LEFT JOIN ". _DB_PREFIX_."attribute at on pc.id_attribute=at.id_attribute";
		  if($share_stock == 0)
			$aquery .=" left join ". _DB_PREFIX_."stock_available s on s.id_product_attribute=pa.id_product_attribute AND s.id_shop = '".$id_shop."'";
		  else
			$aquery .=" left join ". _DB_PREFIX_."stock_available s on s.id_product_attribute=pa.id_product_attribute AND s.id_shop_group = '".$id_shop_group."'";
		  $aquery .= " WHERE pa.id_product='".$datarow['id_product']."' GROUP BY ps.id_product_attribute ORDER BY positions";
		  $ares=dbquery($aquery);
		  while ($arow=mysqli_fetch_array($ares))
		  { echo '<tr id="pa'.$arow["id_product_attribute"].'"><td>';
			$paquery = "SELECT GROUP_CONCAT(CONCAT(gl.name,': ',l.name,' ')) AS nameblock from ". _DB_PREFIX_."product_attribute pa";
			$paquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_combination c on pa.id_product_attribute=c.id_product_attribute";
			$paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute a on a.id_attribute=c.id_attribute";
			$paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on l.id_attribute=c.id_attribute AND l.id_lang='".$id_lang."'";
			$paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_group_lang gl on gl.id_attribute_group=a.id_attribute_group AND gl.id_lang='".$id_lang."'";
			$paquery .= " WHERE pa.id_product_attribute='".$arow['id_product_attribute']."' GROUP BY pa.id_product_attribute";
			$pares=dbquery($paquery);
			$parow = mysqli_fetch_array($pares);
			$labels = explode(",", $parow['nameblock']);
			sort($labels);
			foreach($labels AS $label)
				echo $label.", ";
			echo "</td><td>".$arow['quantity']."</td></tr>";
		  }
		  echo '</table>';
		  echo '<a href="combi-edit.php?id_product='.$datarow['id_product'].'&id_shop='.$id_shop.'" title="Click here to edit combinations in separate window" target="_blank" style="background-color:#99aaee; text-decoration:none">&nbsp; '.$combination_count.' &nbsp;</a>';
		}	
		echo "</td>";
      }  /* end of compbinations */
	  
	  else if ($infofields[$i][0] == "discount")
      { $dquery = "SELECT sp.*, cu.iso_code AS currency";
		$dquery .= " FROM ". _DB_PREFIX_."specific_price sp";
		$dquery.=" left join ". _DB_PREFIX_."currency cu on sp.id_currency=cu.id_currency";		
	    $dquery .= " WHERE sp.id_product='".$datarow['id_product']."'";
		$dres=dbquery($dquery);
		echo "<td><table border=1 id='discount".$x."'>";
		while ($drow=mysqli_fetch_array($dres)) 
		{ $bgcolor = "";
		  if($drow["id_specific_price_rule"] != 0)
			$bgcolor = ' style="background-color:#dddddd"';
		  echo '<tr specid='.$drow["id_specific_price"].' rule='.$drow["id_specific_price_rule"].$bgcolor.'>';
/* 					0			1				2		3		4			5		6			7			8			9	 			10			11	  12	*/
/* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_tax, reduction_type, from, to */
		  if($drow["id_shop"] == "0") $drow["id_shop"] = "";
		  echo "<td>".$drow["id_shop"]."</td>";
		  if($drow["id_product_attribute"] == "0") $drow["id_product_attribute"] = "";
		  echo "<td>".$drow["id_product_attribute"]."</td>";
		  echo "<td>".$drow["currency"]."</td>";
		  echo "<td>".$drow["id_country"]."</td>";
		  echo "<td>".$drow["id_group"]."</td>";

		  if($drow["id_customer"] == "0") $drow["id_customer"] = "";
		  echo "<td>".$drow["id_customer"]."</td>";
		  if($drow["price"] == -1)
		  {	$frompriceVAT = number_format(((($datarow['rate']/100) +1) * $datarow['price']),4, '.', '');
		    $fromprice = $datarow['price'];
			$drow["price"] = "";
		  }
		  else /* the prices mentioned here are excl VAT */
		  { $frompriceVAT = (($datarow['rate']/100) +1) * $drow['price'];
		    $drow["price"] = $drow["price"] * 1; /* remove trailing zeroes */
		  }
		  echo "<td>".$drow["price"]."</td>";
		  echo "<td style='background-color:#FFFF77'>".$drow["from_quantity"]."</td>";
		  if($drow["reduction_type"] == "percentage")
			$drow["reduction"] = $drow["reduction"] * 100;
		  else 
		    $drow["reduction"] = $drow["reduction"] * 1;
		  echo "<td>".$drow["reduction"]."</td>";
		  $reduction_tax = "1";
		  if (version_compare(_PS_VERSION_ , "1.6.0.11", ">="))
		  { echo "<td>".$drow["reduction_tax"]."</td>"; /* 0=excl; 1=incl before 1.6.0.11 there was only incl */
			$reduction_tax = $drow["reduction_tax"];
		  }
		  else 
		    echo "<td></td>";
		  if($drow["reduction_type"] == "amount") $drow["reduction_type"] = "amt"; else $drow["reduction_type"] = "pct";
		  echo "<td>".$drow["reduction_type"]."</td>"; 
		  if($drow["from"] == "0000-00-00 00:00:00") $drow["from"] = "";
		  else if(substr($drow["from"],11) == "00:00:00") $drow["from"] = substr($drow["from"],0,10);
		  echo "<td>".$drow["from"]."</td>";
		  if($drow["to"] == "0000-00-00 00:00:00") $drow["to"] = ""; 
		  else if(substr($drow["to"],11) == "00:00:00") $drow["to"] = substr($drow["to"],0,10);
		  echo "<td>".$drow["to"]."</td>";
		  if ($drow['reduction_type'] == "amt")
		  { if($reduction_tax == 1)
			  $newprice = $frompriceVAT - $drow['reduction'];
			else
			  $newprice = $frompriceVAT - ($drow['reduction']*(1+($datarow['rate']/100)));
		  }
		  else 
		    $newprice = $frompriceVAT*(1-($drow['reduction']/100));
		  $newpriceEX = (1/(($datarow['rate']/100) +1)) * $newprice;
	      $newprice = number_format($newprice,4, '.', '');
          $newpriceEX = number_format($newpriceEX,4, '.', '');
		  
		  echo '<td>'.$newpriceEX.'/ '.$newprice.'</td>';
		  echo "</tr>";
		}
		echo "</table></td>";
		mysqli_free_result($dres);
      }  /* end of discount */
      else if ($infofields[$i][0] == "image")
      { $iquery = "SELECT i.id_image,cover,legend FROM ". _DB_PREFIX_."image i";
		$iquery .= " LEFT JOIN ". _DB_PREFIX_."image_lang il ON i.id_image=il.id_image AND il.id_lang='".$id_lang."'";
		$iquery .= " WHERE id_product='".$datarow['id_product']."' ORDER BY position";
		$ires=dbquery($iquery);
		$id_image = 0;
		$imagelist = "";
		$first=0;
		while ($irow=mysqli_fetch_array($ires)) 
		{	if($irow['cover'] == 1)
			{ $id_image=$irow['id_image'];
			}
			$imagelist .= "|".$irow['id_image']."|".$irow['legend'];
		}
		echo "<td>".get_product_image($datarow["id_product"], $id_image,$imagelist)."</td>";
		mysqli_free_result($ires);
      }  /* end of image */	
	  
	  else if ($infofields[$i][0] == "name")
	  { if ($rewrite_settings == '1')
		{ if($product_url_conf == NULL) /* retrieved previously with get_configuration_value('PS_ROUTE_product_rule'); */
		  { $eanpostfix = ""; 
		  	if(($datarow['ean13'] != "") && ($datarow['ean13'] != null) && ($datarow['ean13'] != "0"))
				$eanpostfix = "-".$datarow['ean13'];
	        echo "<td><a href='".get_base_uri().$langinsert.$datarow['catrewrite']."/".$datarow['id_product']."-".$datarow['link_rewrite'].$eanpostfix.".html' title='".$datarow['originalname']."' target='_blank' class='redname'>".$myvalue."</a></td>";
		  }
		  else /* customized link. Prestashop code in getProductLink() in classes\Link.php */
		  { $produrl = $product_url_conf;
			foreach ($keywords as $key) 
	        { if($key == "id") 	$keyvalue = $datarow["id_product"];
			  else if($key == "rewrite") 	$keyvalue = $datarow["link_rewrite"];				  
			  else if($key == "category") 	$keyvalue = $datarow["catrewrite"];
			  else if($key == "ean13") 		$keyvalue = $datarow["ean13"];
			  else if($key == "manufacturer")	$keyvalue = $datarow["manufacturer"];
			  else if($key == "meta_keywords") 	$keyvalue = $datarow["meta_keywords"];
			  else if($key == "meta_title")	$keyvalue = $datarow["meta_title"];
			  else if($key == "price")	$keyvalue = $datarow["price"];			  
			  else if($key == "reference") 	$keyvalue = $datarow["reference"];				  
			  else if($key == "rewrite") 	$keyvalue = $datarow["link_rewrite"];				  
			  else $keyvalue = "xxxx"; /* Not (yet) implemented: "categories","supplier","tags" */

			/* next we implement a shortened version of Tools:str2url() */
			  $keyvalue = trim($keyvalue);
			  if(($keyvalue != "") && ($keyvalue != null) && ($keyvalue != "0"))
			  { $keyvalue = strtolower($keyvalue);
				if (!$allow_accented_chars)
					$keyvalue = replaceAccentedChars($keyvalue);
				// Remove all non-whitelist chars.
			    if ($allow_accented_chars)
				  $keyvalue = preg_replace('/[^a-zA-Z0-9\s\'\:\/\[\]\-\p{L}]/u', '', $keyvalue);
			    else
				  $keyvalue = preg_replace('/[^a-zA-Z0-9\s\'\:\/\[\]\-]/', '', $keyvalue);
			    $keyvalue = preg_replace('/[\s\'\:\/\[\]\-]+/', ' ', $keyvalue);
			    $keyvalue = str_replace(array(' ', '/'), '-', $keyvalue);
			  }
			  if ($keyvalue != "") 
			  { $replace = $transform_keywords[$key]['prepend'].$keyvalue.$transform_keywords[$key]['append'];
			  }
			  else 
			  { $replace = '';
			  }
			  $produrl = preg_replace('#\{([^{}]*:)?'.$key.'(:[^{}]*)?\}#', $replace, $produrl);
			}
            $produrl = preg_replace('#\{([^{}]*:)?[a-z0-9_]+?(:[^{}]*)?\}#', '', $produrl);
	        echo "<td><a href='".get_base_uri().$langinsert.$produrl."' title='".$datarow['originalname']."' target='_blank' class='redname'>".$myvalue."</a></td>";
		  }
		}
		else
          echo "<td><a href='".get_base_uri()."index.php?id_product=".$datarow['id_product']."&controller=product&id_lang=".$id_lang."' title='".$datarow['originalname']."' target='_blank' class='redname'>".$myvalue."</a></td>";
	  }  /* end of name */
	  
	  else if ($infofields[$i][0] == "out_of_stock")
	  { echo '<td>';
		$myvalue = $datarow["out_of_stock"];
		if($myvalue == "0") echo "Deny orders";
		else if($myvalue == "1") echo "Allow orders";
		else echo "Default";		
		echo '</td>'; 
	  }		/* end of out_of_stock */
	  
      else if ($infofields[$i][0] == "pack_stock_type")
      { echo "<td>".$packstocktypes[(int)$myvalue]."</td>";
      }  	/* end of pack_stock_type */
	  
	  else if ($infofields[$i][0] == "position")
	  { echo '<td>'.$datarow["position"].'</td>'; 
	  }		/* end of position */
	  
	  else if ($infofields[$i][0] == "quantity")
	  { if($myvalue == "") $myvalue = "0"; /* handle cases when there are no entries in stock_available table */
		if($combination_count != 0)
            echo '<td style="background-color:#FF8888"><a href="combi-edit.php?id_product='.$datarow['id_product'].'&id_shop='.$id_shop.'" target=_blank>'.$myvalue.'</a></td>';	 
		else if($datarow["depends_on_stock"] == "1")
          echo '<td style="background-color:yellow">'.$myvalue.'</td>';	  
		else
            echo "<td>".$myvalue."</td>";
	  }		/* end of quantity */
	  
	  else if ($infofields[$i][0] == "revenue")
      { echo "<td><a href onclick='return salesdetails(".$datarow['id_product'].")' title='show salesdetails'>".$datarow['revenue']."</a></td>";
      }	  	/* end of revenue */
	  
	  else if ($infofields[$i][0] == "shopz")
      { echo "<td>";
		$squery = "SELECT GROUP_CONCAT(id_shop) AS shops FROM ". _DB_PREFIX_."product_shop";
	    $squery .= " WHERE id_product = '".$datarow['id_product']."' GROUP BY id_product";
		$sres=dbquery($squery);
		$srow=mysqli_fetch_array($sres);
		echo $srow["shops"]."</td>";
      }	  	/* end of shops */
	  
	  else if ($infofields[$i][0] == "stockflags")
	  { echo "<td srt='".$myvalue."' haswarehouses=";
	    $depends_on_stock = $datarow['depends_on_stock'];
		$advanced_stock_management = $datarow['advanced_stock_management'];
		if(($advanced_stock_management == 0) || ($depends_on_stock == 0))
		{ $squery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."stock s";
	      $squery .= " LEFT JOIN ". _DB_PREFIX_."warehouse_shop ws on ws.id_warehouse=s.id_warehouse";
	      $squery .= " LEFT JOIN ". _DB_PREFIX_."warehouse w on ws.id_warehouse=w.id_warehouse";	  
	      $squery .= " WHERE s.id_product = '".$datarow['id_product']."' AND ws.id_shop ='".$id_shop."' AND w.deleted=0";
		  $sres=dbquery($squery);
		  if(mysqli_num_rows($sres) > 0)
			 echo '1';
		  else echo '0';
		}
		else 
		  echo '0';
		echo " >";
		$squery = "SELECT * FROM ". _DB_PREFIX_."pack WHERE id_product_pack='".$datarow['id_product']."'";
		$sres=dbquery($squery);
		if(mysqli_num_rows($sres) != 0)
		  echo "For packs this field cannot be edited.<br>";
		if($advanced_stock_management == 0)
		  echo $stockflagsarray[0];  // "Manual"
		else if($depends_on_stock == 0)
		  echo $stockflagsarray[1];  // "Adv Stock Management"
		else
		  echo $stockflagsarray[2];  // "ASM with Warehousing"
	    /* now we need to set a flag whether the product already has stock in warehouses set */
	    echo "</td>";
	  }		/* end of stockflags */
	  
	  else if ($infofields[$i][0] == "supplier")
      { $dquery = "SELECT id_supplier FROM ". _DB_PREFIX_."product WHERE id_product='".$datarow['id_product']."'";
		$dres=dbquery($dquery);
		$drow=mysqli_fetch_array($dres);
		$default_supplier = $drow["id_supplier"];
  
        $squery = "SELECT id_product_supplier,ps.id_supplier,id_product_attribute FROM ". _DB_PREFIX_."product_supplier ps";
	    $squery .= " LEFT JOIN ". _DB_PREFIX_."supplier s on s.id_supplier=ps.id_supplier";
		$squery .= " WHERE id_product='".$datarow['id_product']."' AND id_product_attribute=0";
		$squery .= " ORDER BY s.name";
		$sres=dbquery($squery);
	    $sups = array();
		while ($srow=mysqli_fetch_array($sres))
		    $sups[] = $srow["id_supplier"];

		$attrs = array();	
		if($combination_count == 0)
		{ echo '<td sups="'.implode(",",$sups).'" attrs="0" def_supplier='.$default_supplier.'>';
		  echo '<table border=1 class="supplier" id="suppliers0s'.$x.'" title="">';
		  $squery = "SELECT ps.id_product_supplier,s.id_supplier,ps.id_product_attribute,product_supplier_reference AS reference,product_supplier_price_te AS supprice,c.id_currency,c.iso_code";
		  $squery .= " FROM ". _DB_PREFIX_."product_supplier ps";
		  $squery .= " LEFT JOIN ". _DB_PREFIX_."supplier s on s.id_supplier=ps.id_supplier";
		  $squery .= " LEFT JOIN ". _DB_PREFIX_."currency c on c.id_currency=ps.id_currency";		  
		  $squery .= " WHERE id_product='".$datarow['id_product']."' AND (ps.id_supplier != 0) ORDER BY s.name";
		  $sres=dbquery($squery);
		  $rowcount = mysqli_num_rows($sres);
		  $xx=0;
		  while ($srow=mysqli_fetch_array($sres)) 
		  { echo "<tr title='".$srow["id_supplier"]."'>";
			if($supplier_names[$srow['id_supplier']] == $default_supplier)
				echo "<td ><b>".$supplier_names[$srow['id_supplier']]."</b></td>";
			else
				echo "<td >".$supplier_names[$srow['id_supplier']]."</td>";		
			echo "<td>".$srow['reference']."</td><td>".$srow['supprice']."</td>";
			if($srow['iso_code'] != "")
			  echo "<td >".$srow['iso_code']."</td>";
			else
			  echo "<td >".$def_currency."</td>";
			if($xx++ == 0) echo '<td rowspan="'.$rowcount.'"></td>';
			echo "</tr>";
		  }
		  echo "</table>";
		  mysqli_free_result($sres);
		}		
		else
		{ $aquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."product_attribute WHERE id_product='".$datarow['id_product']."'";
		  $ares=dbquery($aquery);
		  while ($arow=mysqli_fetch_array($ares))
		    $attrs[] = $arow["id_product_attribute"];
		  echo '<td sups="'.implode(",",$sups).'" attrs="'.implode(",",$attrs).'" def_supplier='.$default_supplier.'>';
		  
		  $paquery = "SELECT pa.id_product_attribute, GROUP_CONCAT(CONCAT(gl.name,': ',l.name)) AS nameblock";
		  $paquery .= " FROM ". _DB_PREFIX_."product_attribute pa";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_combination c on pa.id_product_attribute=c.id_product_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute a on a.id_attribute=c.id_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on l.id_attribute=c.id_attribute AND l.id_lang='".$id_lang."'";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_group_lang gl on gl.id_attribute_group=a.id_attribute_group AND gl.id_lang='".$id_lang."'";
		  $paquery .= " WHERE pa.id_product='".$datarow['id_product']."' GROUP BY pa.id_product_attribute ORDER BY pa.id_product_attribute";
		  $pares=dbquery($paquery);
		  
		  while ($parow=mysqli_fetch_array($pares))
		  { echo '<table border=1 class="supplier" id="suppliers'.$parow['id_product_attribute'].'s'.$x.'" title="'.$parow["nameblock"].'">';
			$suppls = array();
			$squery = "SELECT ps.id_product_supplier,ps.id_supplier,s.name as suppliername, ps.id_product_attribute,product_supplier_reference AS reference,product_supplier_price_te AS supprice,c.id_currency,c.iso_code";
			$squery .= " FROM ". _DB_PREFIX_."product_supplier ps";
			$squery .= " LEFT JOIN ". _DB_PREFIX_."supplier s on s.id_supplier=ps.id_supplier";
			$squery .= " LEFT JOIN ". _DB_PREFIX_."currency c on c.id_currency=ps.id_currency";		  
			$squery .= " WHERE ps.id_product_attribute='".$parow['id_product_attribute']."' AND (ps.id_supplier != 0) ORDER BY suppliername";
		    $sres=dbquery($squery);
			while ($srow=mysqli_fetch_array($sres))
			{ $suppls[$srow["id_supplier"]] = array($srow["id_product_supplier"],$srow['reference'], $srow['supprice'], $srow['iso_code']);
			}
			$xx = 0;
			foreach($sups AS $sup)
			{ if(isset($suppls[$sup]))
			  { echo "<tr title='".$sup."'>";
				if($sup == $default_supplier)
					echo '<td class="defcat">'.$supplier_names[$sup].'</td>';
				else
					echo "<td >".$supplier_names[$sup]."</td>";
				echo  "<td>".$suppls[$sup][1]."</td><td>".$suppls[$sup][2]."</td>";
				if($suppls[$sup][3] != "")
				  echo "<td >".$suppls[$sup][3]."</td>";
			    else
				  echo "<td >".$def_currency."</td>";
			  }
			  else 		/* this is the situation initially: when the supplier has just been added for the product */
			  { echo "<tr title='".$sup."'>"; 
				if($sup == $default_supplier)
					echo '<td class="defcat">'.$supplier_names[$sup].'</td>';
				else
					echo "<td >".$supplier_names[$sup]."</td>";
				echo "<td></td><td>0.000000</td><td>".$def_currency."</td>";
			  }
			  if($xx++ == 0)
			    echo '<td rowspan="'.sizeof($sups).'">'.$parow["nameblock"].'</td>';
			  echo "</tr>";
			}
		    echo "</table>";
		  }
		  mysqli_free_result($sres);
		  mysqli_free_result($pares);
		}
		echo "</td>";
      }		/* end of supplier */
	  
	  else if ($infofields[$i][0] == "tags")
      { $tquery = "SELECT pt.id_tag,name FROM ". _DB_PREFIX_."product_tag pt";
		$tquery .= " LEFT JOIN ". _DB_PREFIX_."tag t ON pt.id_tag=t.id_tag AND t.id_lang='".$id_lang."'";
	    $tquery .= " WHERE pt.id_product='".$datarow['id_product']."'";
		$tres=dbquery($tquery);
		$idx = 0;
		echo "<td>";
		while ($trow=mysqli_fetch_array($tres)) 
		{ if($idx++ > 0) echo "<br/>";
		  echo "<nobr>".$trow["name"]."</nobr>";
		}
		echo "</td>";
		mysqli_free_result($tres);
      }	  /* end of tags */
	  
	  else if ($infofields[$i][0] == "unitPrice")
	  { if($datarow["unit_price_ratio"] == 0)
		  echo "<td>0.000000</td>";
	    else
		  echo "<td>".round($datarow["price"]/$datarow["unit_price_ratio"],6)."</td>";
	  }		/* end of unitPrice */
	  
      else if ($infofields[$i][0] == "VAT")
      { $sorttxt = "idx='".$datarow['id_tax_rules_group']."'";
		echo "<td ".$sorttxt.">".(float)$myvalue."</td>";
      }		/* end of VAT */
	  
	  else if ($infofields[$i][0] == "virtualp")	  
	  {	$bgcolor = $visvirtual= $visfile = '';
		if($combination_count != 0)
		  $bgcolor = 'style="background-color:#FF8888"';
	    if($datarow['is_virtual'] == 0)
		  $visvirtual = ' style="display:none;"';
	    if($datarow['filename'] == "")
		  $visfile = ' style="display:none;"';
		echo "<td ".$bgcolor."><table><tr><td>On</td><td".$visvirtual.">File</td><td style='display:none'>Name</td><td"
		.$visfile.">exp_date</td><td".$visfile.">nb_days</td><td".$visfile.">nb_downloads</td></tr>";
		echo "<tr><td>".$datarow['is_virtual']."</td><td".$visvirtual.">";
		if($datarow['filename'] != "") 
	    { if(!file_exists($download_dir."/".$datarow["filename"])) echo " <b>file missing</b>";
		  echo "<a class='attachlink' title='".$datarow['display_filename']."' href='downfile.php?filename=".$datarow["display_filename"]."&filecode=".$datarow["filename"]."&download_dir=".$download_dir."&mime=application/zip' target=_blank'>".$datarow['display_filename']."</a>";
		}
		echo "</td><td style='display:none'>".$datarow["display_filename"];
		echo "</td><td".$visfile.">".$datarow["date_expiration"]."</td><td".$visfile.">".$datarow["nb_days_accessible"]."</td>";
		echo "</td><td".$visfile.">".$datarow["nb_downloadable"]."</td></tr></table></td>";	  
	  }		/* end of virtualp */
	  
	  else if ($infofields[$i][0] == "warehousing")
	  { echo "<td>";
	    if($combination_count == 0)
		{ echo "<table>";
		  $wquery = "SELECT w.id_warehouse, w.name AS whname, physical_quantity, usable_quantity, price_te, location";
		  $wquery .= " FROM ". _DB_PREFIX_."warehouse w";
	      $wquery .= " LEFT JOIN ". _DB_PREFIX_."stock st on w.id_warehouse=st.id_warehouse AND st.id_product='".$datarow['id_product']."'";
	      $wquery .= " LEFT JOIN ". _DB_PREFIX_."warehouse_product_location wpl on w.id_warehouse=wpl.id_warehouse AND wpl.id_product='".$datarow['id_product']."'";		  
		  $wquery .= " WHERE st.id_warehouse IS NOT NULL OR wpl.id_warehouse IS NOT NULL";
		  $wquery .= " ORDER BY w.name";
		  $wres=dbquery($wquery);
		  while ($wrow=mysqli_fetch_array($wres))
		  { echo "<tr><td>".$wrow["whname"]."</td><td>".$wrow["location"]."</td>";
			echo "<td>".$wrow["physical_quantity"]."</td><td>".$wrow["usable_quantity"]."</td>";
			if($wrow["price_te"] == "")
			  echo "<td></td>";
			else
			  echo "<td>".number_format($wrow["price_te"],2)."</td>";
			echo "</tr>";
		  }
		  echo "</table>";
		}
		else
		{ $aquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."product_attribute WHERE id_product='".$datarow['id_product']."'";
		  $ares=dbquery($aquery);
		  while ($arow=mysqli_fetch_array($ares))
		    $attrs[] = $arow["id_product_attribute"];
		  
		  $paquery = "SELECT pa.id_product_attribute, GROUP_CONCAT(CONCAT(gl.name,': ',l.name)) AS nameblock from ". _DB_PREFIX_."product_attribute pa";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_combination c on pa.id_product_attribute=c.id_product_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute a on a.id_attribute=c.id_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on l.id_attribute=c.id_attribute AND l.id_lang='".$id_lang."'";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_group_lang gl on gl.id_attribute_group=a.id_attribute_group AND gl.id_lang='".$id_lang."'";
		  $paquery .= " WHERE pa.id_product='".$datarow['id_product']."' GROUP BY pa.id_product_attribute ORDER BY pa.id_product_attribute";
		  $pares=dbquery($paquery);
		  
		  while ($parow=mysqli_fetch_array($pares))
		  { echo '<table border=1  title="'.$parow["nameblock"].'">';
		    $wquery = "SELECT w.id_warehouse, w.name AS whname, physical_quantity, usable_quantity, price_te, location";
		    $wquery .= " FROM ". _DB_PREFIX_."warehouse w";
	        $wquery .= " LEFT JOIN ". _DB_PREFIX_."stock st on w.id_warehouse=st.id_warehouse AND st.id_product='".$datarow['id_product']."' AND st.id_product_attribute='".$parow['id_product_attribute']."'";
	        $wquery .= " LEFT JOIN ". _DB_PREFIX_."warehouse_product_location wpl on w.id_warehouse=wpl.id_warehouse AND wpl.id_product='".$datarow['id_product']."' AND wpl.id_product_attribute='".$parow['id_product_attribute']."'";		  
		    $wquery .= " WHERE st.id_warehouse IS NOT NULL OR wpl.id_warehouse IS NOT NULL";
		    $wquery .= " ORDER BY w.name";
			$wres=dbquery($wquery);
			$size = mysqli_num_rows($wres);
			
			$xx = 0;
			while ($wrow=mysqli_fetch_array($wres))
			{ echo "<tr><td>".$wrow["whname"]."</td><td>".$wrow["location"]."</td>";
			  echo "<td>".$wrow["physical_quantity"]."</td><td>".$wrow["usable_quantity"]."</td>";
			  if($wrow["price_te"] == "")
				echo "<td></td>";
			  else
				echo "<td>".number_format($wrow["price_te"],2)."</td>";
			  if($xx++ == 0)
			    echo '<td rowspan="'.$size.'">'.$parow["nameblock"].'</td>';
			  echo "</tr>";
			}
		    echo "</table>";
		  }
		  mysqli_free_result($wres);
		  mysqli_free_result($pares);
		}
		echo "</td>";
	  }		/* end of warehousing */

	  else if($infofields[$i][6] == 1)
      { $sorttxt = "srt='".str_replace("'", "\'",$myvalue)."'";
        echo "<td ".$sorttxt.">".$myvalue."</td>";
      }
      else
         echo "<td>".$myvalue."</td>";
	 
	  if(in_array($infofields[$i][0], $statfields))
	    $stattotals[$infofields[$i][0]] += $myvalue;
    }

	foreach($features AS $key => $feature)
	{ $cleanfeature = str_replace('"','_',$feature);
	  if (in_array($cleanfeature, $input["fields"]))
	  { if($datarow['value'.$key] == "")
			echo "<td></td>";
		else if($datarow['custom'.$key] == "0")
	  	    echo "<td><b>".$datarow['value'.$key]."</b></td>";
		else // custom = 1
	  	    echo "<td>".$datarow['value'.$key]."</td>";
	  
/*	  if($datarow['value'.$key] != "")
	  	    echo "<td idx='".$datarow['custom'.$key]."'>".$datarow['value'.$key]."</td>";
		else
	  	    echo "<td idx='-1'></td>";
*/	  }
	}

	echo '<td><img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)"></td>';
	$x++;
    echo '</tr
>'; 
  }
  
  if(mysqli_num_rows($res) == 0)
	echo "<strong>products not found</strong>";
  echo '</table></form></div>';
  
  echo "<script>function showtotals() {";
  foreach($statfields AS $statfield)
	{ if(in_array($statfield, $input["fields"]))
	   echo "var id = document.getElementById('stat_".$statfield."');
	   id.title = 'Page total=".$stattotals[$statfield]."'; ";
	}
  echo "}";
//  if($haserrors)
//	  echo ' alert("Your shop database has errors. Run Integrity Checks (under Tools&Stats) or try to repair them in another way!");';
  echo "</script>";
  
  if(!isset($input["imgonly"]) && $statsfound)
  { echo '<table class=triplemain><td colspan=2 style="text-align:center">Totals</td>';
    for($i=0; $i< sizeof($infofields); $i++)
	{ if (in_array($infofields[$i][0], $statfields))
	    echo '<tr><td>'.$infofields[$i][0].'</td><td>'.$stattotals[$infofields[$i][0]].'</td></tr>';
	}
	echo '</table>';
  }
  
  echo '<div style="display:block;">';
  if(isset($avoid_iframes) && $avoid_iframes)
    echo '<form name=rowform action="product-proc.php" method=post target=_blank>';
  else
    echo '<form name=rowform action="product-proc.php" method=post target=tank>';
  echo '<table id=subtable></table>';
  echo '<input type=hidden name=submittedrow><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<input type=hidden name=allshops><input type=hidden name=reccount value="1">';
  echo '<input type=hidden name=id_shop value="'.$id_shop.'"><input type=hidden name=verbose><input type=hidden name=skipindexation>';
  echo '<input type=hidden name=featuresset></form></div>';

  include "footer1.php";
  echo '</body></html>';


$product_list = array();
function get_product_name($id)
{ global $product_list,$id_lang;
  if(isset($product_list[$id]))
    return $product_list[$id];
  $query = "select name from ". _DB_PREFIX_."product_lang WHERE id_product='".$id."' AND id_lang='".$id_lang."'";
  $res = dbquery($query);
  $row=mysqli_fetch_array($res);
  $product_list[$id] = $row["name"];
  return $row["name"];
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

?>