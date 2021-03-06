<?php 
if(isset($_POST['verbose'])) { $verbose = $_POST['verbose']; }
else if (isset($_GET['verbose'])) { $verbose = $_GET['verbose']; }
else $verbose = "false"; 
if($verbose == "on") $verbose = "true";

function dbquery($query) {
global $conn, $mode, $verbose;
if($verbose == "true") echo $query;
$res = @mysqli_query($conn, $query); 
if(!$res) { 
  $error = mysqli_error($conn);
  $full_error = "<p>MySQL error ".mysqli_errno($conn).": ".$error."<br>Generated by URL '".$_SERVER["PHP_SELF"]."'<br>with Query '".$query."' <p>";
  if((mysqli_errno($conn) == "1062") && (strpos($query, "specific_price") > 0))
  { echo "<br>Index 2 of the 'specific_price' table contains the following fields: id_product,id_shop,id_shop_group,id_currency,id_country,id_group,id_customer,id_product_attribute,from_quantity,from,to";
  }
  if($mode == "background") colordie($full_error);
  else colordie($full_error);
}
else if($verbose == "true")
{ if(!strcasecmp(substr($query, 0, 6),"UPDATE"))
    echo " - ".mysqli_affected_rows($conn)." affected";
  echo "<br>";
}
return $res;
}

$selected_img_extension = "";
function get_image_extension($id_product, $id_image, $type)
{ global $selected_img_extension, $img_extensions, $triplepath;
  $legacy_images = get_configuration_value('PS_LEGACY_IMAGES');
  if($selected_img_extension == "")
  { if($type == "category")
    { foreach($img_extensions AS $ipart)
      { $fff = $triplepath.'img/c/'.$id_image.$ipart;
	    if(file_exists($fff))
	      $selected_img_extension = $ipart;
      }	 
	}
	else // type=product
    { foreach($img_extensions AS $ipart)
      { if($legacy_images)
		{ $fff = $triplepath.'img/p/'.($id_product).'-'.$id_image.$ipart;
		  if(file_exists($fff))
			$selected_img_extension = $ipart;
 	    }		
	    else // Legacy_images=NO	 
	    { $fff = $triplepath.'img/p'.getpath($id_image).'/'.$id_image.$ipart;
	      if(file_exists($fff))
	        $selected_img_extension = $ipart;
		}
      }	  
	}
  }
  return $selected_img_extension;
}

function get_product_image($id_product, $id_image, $imagelist) /* returns link or "X" */
{ global $selected_img_extension, $prod_imgwidth, $prod_imgheight;
  if(($id_image == 0) || ($id_image == ""))
    return "X";
  get_image_extension($id_product, $id_image, "product");
  $legacy_images = get_configuration_value('PS_LEGACY_IMAGES');
  $base_uri = get_base_uri();
  $imgsizing = "";
  if((($prod_imgwidth != 0) ||($prod_imgwidth == "auto")) && (($prod_imgheight!= 0) ||($prod_imgheight == "auto")))
	  $imgsizing = 'style="width:'.$prod_imgwidth.'px; height:'.$prod_imgheight.'px;"';
  if($selected_img_extension != "")
  { if($legacy_images)
	  return '<a href="'.$base_uri.'img/p/'.$id_product.'-'.$id_image.'.jpg" target="_blank" title="'.$id_image.';'.str_replace('"','&quot;',$imagelist).'"><img src="'.$base_uri.'img/p/'.$id_product.'-'.$id_image.$selected_img_extension.'" '.$imgsizing.' /></a>';
    else
	  return '<a href="'.$base_uri.'img/p'.getpath($id_image).'/'.$id_image.'.jpg" target="_blank" title="'.$id_image.';'.str_replace('"','&quot;',$imagelist).'"><img src="'.$base_uri.'img/p'.getpath($id_image).'/'.$id_image.$selected_img_extension.'" '.$imgsizing.' /></a>';
  }
  return $id_image."<br>miss";
}

/* getpath() takes a string like "189" and returns something like "/1/8/9" */
function getpath($name)
{ $str = "";
  for ($i=0; $i<strlen($name); $i++)
  { $str .= "/".substr($name,$i,1);
  }
  return $str;
}

$configuration = array();
function get_configuration_value($name, $id_shop_group = NULL, $id_shop = NULL)
{ global $configuration, $conn;
  if(!isset($configuration[$name]))
  { $configuration[$name] = array();
    $res = dbquery("select value,id_shop_group,id_shop from ". _DB_PREFIX_."configuration WHERE name='".mysqli_real_escape_string($conn,$name)."'");
	while ($row = mysqli_fetch_array($res))
	{ if($row["id_shop"] != NULL)
	  { if(!isset($configuration[$name]["shop"]))
		  $configuration[$name]["shop"] = array();
	    $configuration[$name]["shop"][$row["id_shop"]] = $row["value"];
	  }
	  else if($row["id_shop_group"] != NULL)
	  { if(!isset($configuration[$name]["group"]))
		  $configuration[$name]["group"] = array();
	    $configuration[$name]["group"][$row["id_shop_group"]] = $row["value"];
	  }
	  else
	    $configuration[$name]["global"] = $row["value"];
	}
  }
  if(($id_shop != NULL) && isset($configuration[$name]["shop"]) && isset($configuration[$name]["shop"][$id_shop]))
	  return $configuration[$name]["shop"][$id_shop];
  if(($id_shop_group != NULL) && isset($configuration[$name]["group"]) && isset($configuration[$name]["group"][$id_shop_group]))
	  return $configuration[$name]["group"][$id_shop_group]; 
  if(isset($configuration[$name]["global"]))
	  return $configuration[$name]["global"];
  return false;
}

function get_configuration_lang_value($name, $id_lang, $id_shop_group = NULL, $id_shop = NULL)
{ global $configuration, $conn;
  if(!isset($configuration[$name]))
	 $configuration[$name] = array();
  if(!isset($configuration[$name][$id_lang]))
  { $configuration[$name][$id_lang] = array();
    $query = "select cl.value,id_shop_group,id_shop from ". _DB_PREFIX_."configuration c";
    $query .= " LEFT JOIN ". _DB_PREFIX_."configuration_lang cl ON c.id_configuration=cl.id_configuration";
    $query .= " WHERE c.name='".mysqli_real_escape_string($conn, $name)."' AND cl.id_lang=".$id_lang;
    $res = dbquery($query);
	while ($row = mysqli_fetch_array($res))
	{ if($row["id_shop"] != NULL)
	  { if(!isset($configuration[$name][$id_lang]["shop"]))
		  $configuration[$name][$id_lang]["shop"] = array();
	    $configuration[$name][$id_lang]["shop"][$row["id_shop"]] = $row["value"];
	  }
	  else if($row["id_shop_group"] != NULL)
	  { if(!isset($configuration[$name][$id_lang]["group"]))
		  $configuration[$name][$id_lang]["group"] = array();
	    $configuration[$name][$id_lang]["group"][$row["id_shop_group"]] = $row["value"];
	  }
	  else
	    $configuration[$name][$id_lang]["global"] = $row["value"];
	}
  }
  if(($id_shop != NULL) && isset($configuration[$name][$id_lang]["shop"]) && isset($configuration[$name][$id_lang]["shop"][$id_shop]))
	  return $configuration[$name][$id_lang]["shop"][$id_shop];
  if(($id_shop_group != NULL) && isset($configuration[$name][$id_lang]["group"]) && isset($configuration[$name][$id_lang]["group"][$id_shop_group]))
	  return $configuration[$name][$id_lang]["group"][$id_shop_group]; 
  if(isset($configuration[$name][$id_lang]["global"]))
	  return $configuration[$name][$id_lang]["global"];
  return false;
}

function get_rewrite_settings()
{ return get_configuration_value('PS_REWRITING_SETTINGS');
}

$physical_uris = array();
function get_base_uri()
{ global $id_shop, $physical_uris;
  if(!isset($id_shop) || ($id_shop == ""))
  { $res = dbquery("select id_shop from ". _DB_PREFIX_."shop WHERE active=1 AND deleted=0");
	$row = mysqli_fetch_array($res);
	$id_shop = $row["id_shop"];
  }
  if(isset($physical_uris[$id_shop]))
	return $physical_uris[$id_shop];
  $query="select physical_uri from ". _DB_PREFIX_."shop_url";
  $query .= " WHERE id_shop='".$id_shop."'";
  $res=dbquery($query);
  if(mysqli_num_rows($res) != 0)
  { $row = mysqli_fetch_array($res);
	$physical_uris[$id_shop] = $row["physical_uri"];
	return $row["physical_uri"];
  }
  /* we didn't find a base uri for our shop. So we take any other */
  $query="select physical_uri from ". _DB_PREFIX_."shop_url";
  $res=dbquery($query);
  if(mysqli_num_rows($res) != 0)
  { $row = mysqli_fetch_array($res);
	$physical_uris[$id_shop] = $row["physical_uri"];
	return $row["physical_uri"];
  }
  $query="select value from ". _DB_PREFIX_."configuration";
  $query .= " WHERE name='__PS_BASE_URI__'";
  $res=dbquery($query);
  if(mysqli_num_rows($res) != 0)
  { $row = mysqli_fetch_array($res);
    return $row['value'];
  }
  if(defined("_PS_DIRECTORY_"))
    return _PS_DIRECTORY_;
  return "SORRYNOTHINGFOUND";
}

/* translate function */
function t($mytxt,$context = "")
{ global $prestoolslanguage, $t;
  if(($context != "") && isset($t[$mytxt][$context]))
	  $txt = $t[$mytxt][$context];
  else if(isset($t[$mytxt]))
	  $txt = $t[$mytxt];
  else $txt = $mytxt;
  $argv = func_get_args();
  array_shift( $argv );
  array_shift( $argv );
  return vsprintf( $txt, $argv );
}
  
function colordie($text)
{ echo "<script>setTimeout('document.body.bgColor=\'#44eecc\';',200);

function inIframe () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}
 if(inIframe ())
{alert('".str_replace("'","\\'",$text)."');}
 </script>"; /* page must be complete before this works */
  die($text);
}

/* addspaces adds spaces to the begin of numbers  */
function addspaces($source) { 
  if($source == "") return "";
  $source = ltrim($source); /* remove leading spaces */
  for($i=0; $i<strlen($source); $i++)
    if(!is_numeric($source[$i]))
      break;
  $spaces = "";
  for(;$i<4;$i++) $spaces=$spaces." ";
  return $spaces.$source;
}

function check_mysql_date($mydate)
{ $parts = explode("-", $mydate);
  if(sizeof($parts) != 3) return false;
  return(checkdate($parts[1],$parts[2],$parts[0]));
}

/* This function re-implements catalogue rules after the characteristics (category, manufacturer, supplier, feature 
   and attribute fields) of products have changed
   Catalogue rules are implemented in the specific price table as a specific price for each of the affected products
   There are rule conditions and rule condition groups. Groups are ORed, Conditions are ANDed. So if you have group G1
   with conditions C1 and C2 and group G2 with conditions C3, C4 and C5, then the rule is applied if ((C1 AND C2) OR (C3 AND C4 AND C5))
   In a multishop setting the shop-specific conditions are set in the rules, not in the conditions. 
   To a considerable extent the same applies to attributes. If you set a catalogue price rule for a specific attribute
   the price change will apply to all combinations - unless you specify differently in the rule.
   In Prestashop, see the function getAffectedProducts() in classes\SpecificPriceRule.php.
   At the time of development the Prestashop code for Catalogue Price Rules on attributes didn't work as should be expected.
*/
function apply_catalogue_rules($rules, $modified_products)
{ global $verbose;
  sort($modified_products); /* these are the products for which the catalogue rules need to be re-implemented */
  foreach($rules AS $rule)
  { /* first delete the old stuff */
    if($verbose == "true") echo "-<br>"; 
    $dquery = "DELETE FROM `". _DB_PREFIX_."specific_price`";
    $dquery .= " WHERE id_specific_price_rule='".$rule."' AND id_product IN (".implode(",",$modified_products).")";
    dbquery($dquery);
	
	$myproducts = $mycombinations = array();
    $gquery = "SELECT id_specific_price_rule_condition_group FROM `". _DB_PREFIX_."specific_price_rule_condition_group`";
    $gquery .= " WHERE id_specific_price_rule='".$rule."';";
    $gres=dbquery($gquery);
	
    while ($grow=mysqli_fetch_array($gres))   /* for each group: get the distinct conditions */
	{ if($verbose == "true") echo "-=-<br>"; 
	  $rquery = "SELECT id_specific_price_rule_condition, type,value FROM `". _DB_PREFIX_."specific_price_rule_condition`";
      $rquery .= " WHERE id_specific_price_rule_condition_group='".$grow["id_specific_price_rule_condition_group"]."';";
      $rres=dbquery($rquery);
	  if(mysqli_num_rows($rres)==0)
	  { echo "empty condition.<br>"; /* should not happen */
		continue;
	  }

	  $qselector = "SELECT p.id_product";
	  $qfrom = " FROM `". _DB_PREFIX_."product` p";
	  $qjoins = "";
	  $qwhere = " WHERE 1";
	  $ctr = array("category"=>0, "supplier"=>0, "feature"=>0, "attribute"=>0); /* initialize counters for conditions */

	  $attributes_join_added = false;
	  while ($rrow=mysqli_fetch_array($rres))
	  { if($rrow["type"] == "category")
		{ $qjoins .= " LEFT JOIN `". _DB_PREFIX_."category_product` cp".$ctr["category"]." ON cp".$ctr["category"].".id_product=p.id_product";
		  $qwhere .= " AND (cp".$ctr["category"]++.".id_category=".$rrow["value"].")";
		}
		else if ($rrow["type"] == "supplier")
		{ $qwhere .= " AND (EXISTS(SELECT `ps".$ctr["supplier"]."`.`id_product` FROM `". _DB_PREFIX_."product_supplier` `ps".$ctr["supplier"]."`";
		  $qwhere .= " WHERE `p`.`id_product` = `ps".$ctr["supplier"]."`.`id_product` AND `ps".$ctr["supplier"]++."`.`id_supplier`=".$rrow["value"]."))";
		}
		else if ($rrow["type"] == "manufacturer")
		{ $qwhere .= " AND (p.id_manufacturer='".$rrow["value"]."')";
		}
		else if ($rrow["type"] == "feature")
		{ $qjoins .= " LEFT JOIN `". _DB_PREFIX_."feature_product` fp".$ctr["feature"]." ON fp".$ctr["feature"].".id_product=p.id_product";
		  $qwhere .= " AND (fp".$ctr["feature"]++.".id_feature_value=".$rrow["value"].")";
		}
		/* with attributes I am still looking for the best way to avoid duplicates. PS uses GROUP BY, I use DISTINCT */
		else if ($rrow["type"] == "attribute") 
		{ if (!$attributes_join_added) 
		  { $qselector .= ", pa.id_product_attribute";
			$qjoins .= " LEFT JOIN `". _DB_PREFIX_."product_attribute` pa ON p.`id_product` = pa.`id_product`";
			$attributes_join_added = true;
		  }
		  $qjoins .= " LEFT JOIN `". _DB_PREFIX_."product_attribute_combination` pac".$ctr["attribute"]." ON pac".$ctr["attribute"].".id_product_attribute=pa.id_product_attribute";
		  $qwhere .= " AND (pac".$ctr["attribute"]++.".id_attribute=".$rrow["value"].")";
		}
	  }
	  $qwhere .= " AND (p.id_product IN (".implode(",",$modified_products)."));";
	  if(!$attributes_join_added)
		  $qselector .= ", 0 AS id_product_attribute";
	  $qquery = $qselector.$qfrom.$qjoins.$qwhere;
      $qres=dbquery($qquery);
	  
	  /* note that as condition groups are ORed and we run those group queries separately a product can be collected more than once */
	  while ($qrow=mysqli_fetch_array($qres))
	  { if($qrow["id_product_attribute"] == 0)
		  $myproducts[] = $qrow["id_product"];	/* add the different groups into one array of products */
		else
		  $mycombinations[$qrow["id_product"]][] = $qrow["id_product_attribute"];	/* add the different groups into one array of products */
      }
	}
	$myproducts = array_unique($myproducts);
	
	/* now get the conditions that we will assign */
    $xquery = "SELECT * FROM `". _DB_PREFIX_."specific_price_rule`";
    $xquery .= " WHERE id_specific_price_rule='".$rule."';";
    $xres = dbquery($xquery);
	$xrow = mysqli_fetch_array($xres);
	
	/* compose the fixed part of the query to set for each product */
	$uquery = "INSERT INTO `". _DB_PREFIX_."specific_price` SET id_customer='0', id_shop='".$xrow["id_shop"]."', id_country='".$xrow["id_country"]."'";
	$uquery .= ", id_currency='".$xrow["id_currency"]."', id_group='".$xrow["id_group"]."', from_quantity='".$xrow["from_quantity"]."'";
	if (version_compare(_PS_VERSION_ , "1.6.0.11", ">="))
		$uquery .= ", reduction_tax='".$xrow["reduction_tax"]."'";
	if($xrow['reduction_type'] == 'percentage')
	    $uquery .= ", reduction='".($xrow["reduction"]/100)."'";
    else
	$uquery .= ", reduction='".$xrow["reduction"]."'";
	$uquery .= ", price='".$xrow["price"]."', reduction_type='".$xrow["reduction_type"]."', `from`='".$xrow["from"]."', `to`='".$xrow["to"]."'";
    $uquery .= ", id_specific_price_rule='".$xrow["id_specific_price_rule"]."'";
	
	/* now assign them to each product */
	foreach($modified_products AS $product)
	{ if(in_array($product, $myproducts))
	  {	$vquery = $uquery.", id_product='".$product."', id_product_attribute='0'";
		dbquery($vquery);
	  }
	  else if(isset($mycombinations[$product])) /* if product is not entered as a whole maybe there are combinations */
	  { $mycombis = array_unique($mycombinations[$product]);
	    foreach($mycombis AS $combi)
		{ $vquery = $uquery.", id_product='".$product."', id_product_attribute='".$combi."'";
		  dbquery($vquery);  
		}  
	  }
	}
  }	
}



function mescape($arg)
{ global $conn;
  return mysqli_real_escape_string($conn,$arg);
}

function print_menubar()
{  global $demo_mode, $headermsgs;
	echo '
	<a name="top"></a>'.
	$headermsgs.'
	<div style="position:absolute"><img src=logo1.png></div>
	<ul class="navi">
	<li class="menuimg"></li>
	<li><a href="product-edit.php">✏ Products</a>
		<ul>
			<li><a href="product-edit.php">Product Edit</a></li>
			<li><a href="product-sort.php">Product Sort</a></li>
			<li><a href="product-vissort.php">Product Visual Sort</a></li>
		</ul>
	</li>
	<li><a href="combi-edit.php">⚄ Combinations</a>
		<ul>
			<li><a href="combi-edit.php">Combination Edit</a></li>
			<li><a href="combi-copy.php">Combination Copy</a></li>
			<li><a href="combi-delete.php">Combination Delete</a></li>
			<li><a href="attribute-sort.php">Attribute Sort</a></li>				
			<li><a href="attribute-exim.php">Attribute Ex- and Import</a></li>
		</ul>
	</li>
    <li><a href="image-edit.php">↕ Images</a>
		<ul>
			<li><a href="image-edit.php">Image Edit</a></li>
			<li><a href="image-regenerate.php">Image Regenerate</a></li>
			<li><a href="image-overview.php">Image Overview</a></li>
			<li><a href="cleanup.php">Image Cleanup</a></li>			
		</ul>
	</li>
  <li><a href="cat-edit.php">☰ Category Edit</a>
  </li>
  <li><a href="order-edit.php">☑ Orders</a>
		<ul>
			<li><a href="order-edit.php">Order Edit</a></li>
			<li><a href="orders-eu-tax.php">Order List for EU Tax</a></li>
			<li><a href="order-search.php">Order Search</a></li>
			<li><a href="categories-sold.php">Category revenue</a></li>
			<li><a href="products-sold.php">Sold Products</a></li>
			<li><a href="shippingcosts.php">Shipping Costs</a></li>
			<li><a href="sales-graph.php">Sales Graph</a></li>
		</ul>
	</li>
	<li><a href="shopsearch.php">⚒ Tools &amp; Stats</a>
		<ul>
			<li><a href="shopsearch.php">Search Statistics</a></li>
			<li><a href="discount-list.php">Discount Overview</a></li>
			<li><a href="ipaddresses.php">IP Adresses</a></li>
			<li><a href="urlseo-edit.php">SEO & URL\'s Edit</a></li>
			<li><a href="module-info.php">Module Info</a></li>
			<li><a href="override-list.php">Override List</a></li>			
			<li><a href="shop-rescue.php">Shop Rescue</a></li>
			<li><a href="requirements.php">Server Settings</a></li>			
			<li><a href="integrity-checks.php">Integrity Checks</a></li>
		</ul>
	</li>
	<li><a href="logout1.php">&#10006; Logout</a></li>
	<li style="background:#41a85f; float:right" ><a href=".." >To Back Office</a></li>
</ul>
	';
  if($demo_mode) echo '<div style="background-color:#333; color:#F7F; margin-top:-12px; font-size: 18px;">This software runs in demo mode. You can access everything but changes are not saved.</div>';
  $parts = explode('/', $_SERVER["SCRIPT_NAME"]);
  if(end($parts) != "product-edit.php") 
  { $query = "SELECT count(*) AS unindexedcount FROM "._DB_PREFIX_."product_shop WHERE indexed='0' AND visibility IN ('both', 'search') AND `active` = 1";
    $res = dbquery($query);
    list($unindexedcount) = mysqli_fetch_row($res); 
    if($unindexedcount > 10)
    { echo '<div style="background-color:#ff0; color:#f00; width:100%; margin-top:-10px">';
	  echo '<script>function update_index(ctr) {document.getElementById("reindexspan").innerHTML=ctr;}</script>';
      echo '<form name=IndexerForm action="reindex.php" target="tank" method="post">';
	  echo 'You have '.$unindexedcount.' unindexed products. ';
	  echo '<button onclick="IndexerForm.submit(); return false;" style="margin:7px">';
	  echo '<nobr>Re-index the <span id=reindexspan>'.$unindexedcount.'</span> unindexed products</nobr></button>';
	  echo '</form></div>';
	}
  }
}


