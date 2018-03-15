<?php
if(!@include 'approve.php') die( "approve.php was not found!");

if (isset($_GET['id_order']))
	$id_order = (int)$_GET['id_order'];
else if (isset($_POST['id_order']))
{	$id_order = (int)$_POST['id_order'];
	$_GET = $_POST;
}
else
    colordie("No order ID provided!");

$stock_management_conf = get_configuration_value('PS_STOCK_MANAGEMENT');
$advanced_stock_management_conf = get_configuration_value('PS_ADVANCED_STOCK_MANAGEMENT');
if($advanced_stock_management_conf) $stock_mvt_reason = get_configuration_value('PS_STOCK_MVT_REASON_DEFAULT');

$eu_countries = array("AT","BE","BG","CY","CZ","DE","DK","EE","ES","FI","FR","GR","HR","HU","IE","IT","LT","LU","LV","MT","NL","PL","PT","RO","SE","SI","SK","UK");

$query=" select cu.name, cu.id_currency,cu.conversion_rate from ". _DB_PREFIX_."configuration cf, ". _DB_PREFIX_."currency cu";
$query.=" WHERE cf.name='PS_CURRENCY_DEFAULT' AND cf.value=cu.id_currency";
$res=dbquery($query);
$row=mysqli_fetch_array($res);
$cur_name = $row['name'];
$cur_rate = $row['conversion_rate'];
$id_currency = $row['id_currency'];

$query="select o.id_shop, o.id_lang, oi.id_order_invoice, a.id_country, a.vat_number, a2.vat_number AS vat_invoice,";
$query .= " a.id_state, cu.id_currency, cu.name AS currname, cu.conversion_rate AS currrate";
$query .= " from ". _DB_PREFIX_."orders o";
$query .= " left join ". _DB_PREFIX_."order_invoice oi on o.id_order=oi.id_order";
$query .= " left join ". _DB_PREFIX_."address a on o.id_address_delivery=a.id_address";
$query .= " left join ". _DB_PREFIX_."address a2 on o.id_address_invoice=a2.id_address";
$query .= " left join ". _DB_PREFIX_."currency cu on cu.id_currency=o.id_currency";
$query.=" WHERE o.id_order ='".mysqli_real_escape_string($conn, $id_order)."'";
$res=dbquery($query);
$row=mysqli_fetch_array($res);
$id_country = intval($row['id_country']);
$id_state = intval($row['id_state']);
$id_shop = intval($row['id_shop']);
$id_order_invoice = $row['id_order_invoice'];
if($row["vat_invoice"] != "")
  $vat_number = $row["vat_invoice"];
else
  $vat_number = $row["vat_number"];	
$order_currency = $row['id_currency'];
$order_currname = $row['currname'];
$id_lang = (int)$row['id_currency'];
$conversion_rate = $row['currrate'] / $cur_rate;

/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];

if(isset($demo_mode) && $demo_mode)
   echo '<script>alert("The script is in demo mode. Nothing is changed!");</script>';
else if ($_GET['action']=='add-product') 
{ $id_attribute = intval($_GET['attribute']);
  $id_product = intval($_GET['id_product']);
  if ((isset($_GET['action'])) &&($_GET['action']=='add-product') && ($conversion_rate != 1))
     echo "Currency converted: Product price: ".$price." ".$cur_name." was converted into ".($price*$conversion_rate)." ".$order_currname;
	
  $fields = " p.weight,p.ean13,p.upc,p.reference,p.supplier_reference,p.quantity, ps.*,pl.name,pl.id_lang,l.iso_code";
  $fields .= ",ps.advanced_stock_management,s.depends_on_stock,t.rate as tax_rate,t.id_tax, tl.name as tax_name,p.id_tax_rules_group";
  $query="select ".$fields." from ". _DB_PREFIX_."product_shop ps";
  $query.=" left join ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
  $query.=" left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".$id_lang."'";
  $query.=" left join ". _DB_PREFIX_."lang l on pl.id_lang=l.id_lang";
  $query.=" left join ". _DB_PREFIX_."stock_available s on s.id_product=ps.id_product";
  if($share_stock)
	$query.=" AND s.id_shop_group=".$id_shop_group; 
  else
	$query.=" AND s.id_shop=".$id_shop; 
  $query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."'  AND tr.id_state='".$id_state."'";
  $query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
  $query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".$id_lang."'";
  $query.=" WHERE ps.id_shop='".$id_shop."' AND p.id_product='".$id_product."' ";
  $res=dbquery($query);
  $products=mysqli_fetch_array($res);
 
  if(($id_state!=0) && (!isset($row['id_tax']) || ($row['id_tax'] == 0))) /* Italy clause: if there is no tax for the state there is a national tax */
  { $query="select ".$fields." from ". _DB_PREFIX_."product_shop ps";
    $query.=" left join ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
    $query.=" left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".$id_lang."'";
    $query.=" left join ". _DB_PREFIX_."lang l on pl.id_lang=l.id_lang";
    $query.=" left join ". _DB_PREFIX_."stock_available s on s.id_product=ps.id_product";
    if($share_stock)
	  $query.=" AND s.id_shop_group=".$id_shop_group; 
    else
	  $query.=" AND s.id_shop=".$id_shop;
    $query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."'  AND tr.id_state='0'";
    $query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
    $query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".$id_lang."'";
    $query.=" WHERE ps.id_shop='".$id_shop."' AND ps.id_product='".$id_product."' ";
    $res=dbquery($query);
    $products=mysqli_fetch_array($res);
  }
 
 /* handle intra-EU sales with VAT number => no tax */
  if($vat_number != "")
  { $vquery="select id_country from ". _DB_PREFIX_."country WHERE iso_code IN ('".implode("','",$eu_countries)."')";
    $vres=dbquery($vquery);
    $eu_country_ids = array();
    while($vrow=mysqli_fetch_array($vres))
 	  $eu_country_ids[] = $vrow["id_country"];
    $shop_country = get_configuration_value('PS_COUNTRY_DEFAULT');
    if((in_array($id_country,$eu_country_ids)) && (in_array($shop_country,$eu_country_ids)) && ($id_country != $shop_country))
	{ $products['tax_rate'] = 0;
	  $products['id_tax'] = 0;
    }
  }

  $name = $products['name'];
  $price = $products['price'];
  $weight = $products['weight'];
  $quantity = $products['quantity'];

  if (is_null($products['tax_rate'])) $products['tax_rate']=0;
 
  if($id_attribute!=0)
  { $price = $price+$_GET['attprice'];
    $weight = $weight+$_GET['attweight'];
    $gquery = "SELECT public_name,l.name,pa.quantity FROM ". _DB_PREFIX_."product_attribute_combination c LEFT JOIN "._DB_PREFIX_."attribute a on c.id_attribute=a.id_attribute";
    $gquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_group_lang g on a.id_attribute_group=g.id_attribute_group AND g.id_lang='".$id_lang."'";
    $gquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on a.id_attribute=l.id_attribute AND l.id_lang='".$id_lang."'";
    $gquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute pa on pa.id_product_attribute=c.id_product_attribute";
    $gquery .= " WHERE c.id_product_attribute='".$id_attribute."'";
    $gres = dbquery($gquery);
    $grow=mysqli_fetch_array($gres);
    $atquantity = $grow["quantity"];
    $name .= " - ".$grow['public_name'].": ".$grow['name'];
    while ($grow=mysqli_fetch_array($gres))  /* products with multiple attributes */
      $name .= ", ".$grow['public_name'].": ".$grow['name'];
  }
  
  /* Filling the id_warehouse field of an added product. This is provisional.  */
  /* We may allocate the product to a different warehouse as the rest of the order. But we will not split the order here */
  $wquery ="SELECT wpl.id_warehouse, usable_quantity, id_shop FROM ". _DB_PREFIX_."warehouse_product_location wpl";
  $wquery .= " LEFT JOIN ". _DB_PREFIX_."warehouse_carrier wc on wpl.id_warehouse=wc.id_warehouse";
  $wquery .= " LEFT JOIN ". _DB_PREFIX_."order_carrier oc on oc.id_carrier=wc.id_carrier AND oc.id_order=".$id_order;
  $wquery .= " LEFT JOIN ". _DB_PREFIX_."stock s on s.id_warehouse=wpl.id_warehouse AND wpl.id_product=s.id_product AND wpl.id_product_attribute=s.id_product_attribute";
  $wquery .= " LEFT JOIN ". _DB_PREFIX_."warehouse_shop ws on wpl.id_warehouse=ws.id_warehouse";
  $wquery .= " WHERE wpl.id_product='".$products['id_product']."' AND wpl.id_product_attribute='".$id_attribute."'";
  $wquery .= " ORDER BY usable_quantity DESC";
  $wres = dbquery($wquery);
  if(mysqli_num_rows($wres) == 0)
	  $id_warehouse = "0";
  else 
  { $wrow=mysqli_fetch_array($wres);
    $id_warehouse = $wrow["id_warehouse"];
  }
  if(mysqli_num_rows($wres) > 1) /* two more optional determinants: id_shop and the warehouse of the rest of the order */
  { /* note that we have already allocated a value so it is no problem if this section gives no results */
    $cquery ="SELECT id_warehouse FROM ". _DB_PREFIX_."order_detail WHERE id_order=".$id_order;
    $cres = dbquery($cquery);
	if(mysqli_num_rows($cres) == 0)
	{ $crow=mysqli_fetch_array($cres);
	  while($wrow=mysqli_fetch_array($wres))
	  { if(($crow["id_warehouse"] == $wrow["id_warehouse"]) && ($wrow["id_shop"] == $id_shop))
		{ $id_warehouse = $wrow["id_warehouse"];
		  break;
		} 
	  }
	}
  }
  
  $query ="insert into ". _DB_PREFIX_."order_detail ";
  $query.=" SET id_order = '".$id_order."'";
  $query.=" ,id_order_invoice = '".$id_order_invoice."'";
  $query.=" ,id_shop = '".$id_shop."'";
  $query.=" ,product_id = '".$products['id_product']."'";
  $query.=" ,product_attribute_id = '".$id_attribute."'";
  $query.=" ,product_name = '".mysqli_real_escape_string($conn, $name)."'";
  $query.=" ,tax_name = ''";
  $query.=" ,product_quantity = '1'";
  $query.=" ,id_warehouse = '".$id_warehouse."'";
  $query.=" ,product_quantity_in_stock = '1'"; //".$products['quantity']."'";
  $query.=" ,product_price = '".($price*$conversion_rate)."'";
  $query.=" ,product_ean13 = '".$products['ean13']."'";
  $query.=" ,product_upc = '".$products['upc']."'";
  $query.=" ,product_reference = '".$products['reference']."'";
  $query.=" ,product_supplier_reference = '".$products['supplier_reference']."'";
  $query.=" ,product_weight = '".$weight."'";
  if (version_compare(_PS_VERSION_ , "1.6.0.10", ">="))
    $query.=" ,id_tax_rules_group = '".$products['id_tax_rules_group']."'";	
  else
  { $query.=" ,tax_name = '".$products['tax_name']."'";
    $query.=" ,tax_rate = '".$products['tax_rate']."'";
  }
  $query.=" ,download_hash = ''"; /* it is not clear how this is calculated; it is not the filename */
  $query.=" ,download_deadline = null";
  $unit_price_excl = round($price,4);
  $unit_price_incl = round(($price*(floatval($products['tax_rate'])+100)/100),4);
  $query.=" ,total_price_tax_incl = '".$unit_price_incl."'";
  $query.=" ,total_price_tax_excl = '".$unit_price_excl."'";
  $query.=" ,unit_price_tax_incl = '".$unit_price_incl."'";
  $query.=" ,unit_price_tax_excl = '".$unit_price_excl."'";
  $query.=" ,original_product_price = '".$price."'"; 
  dbquery($query);
 
  $tquery ="insert into ". _DB_PREFIX_."order_detail_tax ";
  $tquery.=" SET id_order_detail = LAST_INSERT_ID()";
  $tquery.=" ,id_tax = '".$products['id_tax']."'";
  $net_tax = $price*(floatval($products['tax_rate']))/100;
  $tquery.=" ,unit_amount = '".$net_tax."'";
  $tquery.=" ,total_amount = '".$net_tax."'"; /* always one product here */
  dbquery($tquery);
  
  update_stock($id_order, $id_product,$id_attribute,-1);
  update_total($id_order);
} /* end if($_GET['action']=='add-product') */

/* all orderline fields are arrays with as index the line's id_order_detail */
else if ($_GET['action']=='change-products')
{ 
  //delete product
  if (isset($_GET['product_delete']))
  { 
	foreach ($_GET['product_delete'] as $id_order_detail=>$value)
	{ $res = dbquery("SELECT product_id,product_attribute_id,product_quantity from ". _DB_PREFIX_."order_detail where id_order_detail=".$id_order_detail);
	  $drow=mysqli_fetch_array($res);
	  if(mysqli_num_rows($res) == 0) continue;
	  
      update_stock($id_order, $drow['product_id'],$drow['product_attribute_id'],$drow['product_quantity']);

      dbquery("delete from ". _DB_PREFIX_."order_detail where id_order_detail=".$id_order_detail);
	  dbquery("delete from ". _DB_PREFIX_."order_detail_tax where id_order_detail=".$id_order_detail);
	  unset($_GET['product_price'][$id_order_detail]); /* take care that this row doesn't count in the next loop */
    }
  }

  $total_products = 0;
  if ($_GET['product_price']) 
  { foreach ($_GET['product_price'] as $id_order_detail=>$price_product)
    { $price_product = (float)$price_product;
	  $qty_difference=$_GET['product_quantity_old'][$id_order_detail]-$_GET['product_quantity'][$id_order_detail];
      $name=$_GET['product_name'][$id_order_detail];
      $attribute = (int)$_GET['product_attribute'][$id_order_detail];
	  $id_product = (int)$_GET['product_id'][$id_order_detail];
  
      $tquery  = "SELECT rate from ". _DB_PREFIX_."order_detail_tax ot";
      $tquery .= " LEFT JOIN ". _DB_PREFIX_."tax t ON t.id_tax=ot.id_tax";
      $tquery .= " WHERE id_order_detail = '".$id_order_detail."'";
      $tres = dbquery($tquery);
      $trow=mysqli_fetch_array($tres);
  
      $unit_price_excl = round($price_product,4);
      $unit_price_incl = round(($price_product*(floatval($trow['rate'])+100)/100),4);
      $quantity = intval($_GET['product_quantity'][$id_order_detail]);
  
      $query = "update ". _DB_PREFIX_."order_detail set product_price='".$price_product."'";
      $query .= ", product_quantity='".$quantity."'";
      $query .= ", product_quantity_in_stock='".intval($quantity)."'";
      $query .= ", product_name='".mysqli_real_escape_string($conn, $name)."'";
      $query .= ", total_price_tax_incl = '".($quantity*$unit_price_incl)."'";
      $query .= ", total_price_tax_excl = '".($quantity*$unit_price_excl)."'";
      $query .= ", unit_price_tax_incl = '".$unit_price_incl."'";
      $query .= ", unit_price_tax_excl = '".$unit_price_excl."'";
      $query .= "  where id_order_detail='".$id_order_detail."'";
      dbquery($query);
  
      $net_tax = $price_product*(floatval($trow['rate']))/100; 
      $tquery ="UPDATE ". _DB_PREFIX_."order_detail_tax ";
      $tquery.="SET unit_amount = '".$net_tax."'";
      $tquery.=" ,total_amount = '".$net_tax*$quantity."'"; /* always one product here */
      $tquery .= "  where id_order_detail='".$id_order_detail."'"; 
      dbquery($tquery);
	  
      update_stock($id_order, $id_product,$attribute,$qty_difference);
	  
      $total_products+=$_GET['product_quantity'][$id_order_detail]*price($price_product);
    }
    update_total($id_order);
  }
} // end if ($_GET['action']=='change-products')

else if ($_GET['action']=='change-order')
{ 
  $query="update  ". _DB_PREFIX_."order_carrier set ";
  $query.=" id_carrier=".(int)$_GET['id_carrier'];
  $query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
  dbquery($query); 
	
  $query="select t.rate AS carriertax FROM "._DB_PREFIX_."carrier_tax_rules_group_shop ct ";
  $query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ct.id_tax_rules_group AND tr.id_country='".$id_country."' AND tr.id_state='".$id_state."'";
  $query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
  $query .= " where ct.id_carrier=".mysqli_real_escape_string($conn, $_GET['id_carrier'])." AND ct.id_shop=".$id_shop;
  $res = dbquery($query);
  $trow=mysqli_fetch_array($res);
  $carrier_tax_rate = (float)$trow["carriertax"];

  $total=price($_GET['total_products_wt'])+price($_GET['total_shipping'])+price($_GET['total_wrapping'])-price($_GET['total_discounts']);
  $total_shipping_tax_excl = ($_GET['total_shipping']/($carrier_tax_rate+100))*100;
  $tax = price($_GET['total_products_wt']) - price($_GET['total_products']) + price($_GET['total_shipping']) - price($total_shipping_tax_excl);

  $query="update ". _DB_PREFIX_."orders set ";
  $query.=" total_discounts=".(float)price($_GET['total_discounts']);
  $query.=" ,total_wrapping=".(float)price($_GET['total_wrapping']);
  $query.=" ,total_shipping=".(float)price($_GET['total_shipping']);
  $query.=" ,total_shipping_tax_excl=".(float)price($total_shipping_tax_excl);
  $query.=" ,total_shipping_tax_incl=".(float)price($_GET['total_shipping']);
  $query.=" ,delivery_number=".(int)price($_GET['delivery_number']);
  $query.=" ,id_carrier=".(int)$_GET['id_carrier'];
  $query.=" ,carrier_tax_rate=".$carrier_tax_rate;
  $query.=" ,total_paid_tax_incl=".$total;
  $query.=" ,total_paid_tax_excl='".($total - $tax)."'";
  $query.=" ,total_paid=".$total;
  $query.=" ,total_paid_real=".$total;
  $query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
  $query.=" limit 1";
  dbquery($query);

  if($_GET['id_carrier']!=0) /* when we don't have downloads ... */
  { $query="update ". _DB_PREFIX_."order_carrier set ";
    $query.=" shipping_cost_tax_excl=".price($total_shipping_tax_excl);
    $query.=" ,shipping_cost_tax_incl=".price($_GET['total_shipping']);
    $query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
    $query.=" limit 1";
    dbquery($query);
  }

  $query="update ". _DB_PREFIX_."order_invoice set ";
  $query.=" total_discount_tax_incl=".price($_GET['total_discounts']);
  $query.=" ,total_discount_tax_excl=".price($_GET['total_discounts']);
  $query.=" ,total_wrapping_tax_incl=".price($_GET['total_wrapping']);
  $query.=" ,total_wrapping_tax_excl=".price($_GET['total_wrapping']);
  $query.=" ,total_shipping_tax_excl='".price($total_shipping_tax_excl)."'";
  $query.=" ,total_shipping_tax_incl='".price($_GET['total_shipping'])."'";
  $query.=" ,total_paid_tax_excl='".($total - $tax)."'";
  $query.=" ,total_paid_tax_incl='".$total."'";
  $query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
  $query.=" limit 1";
  dbquery($query);
}  // end if ($_GET['action']=='change-order')


else if ($_GET['action']=='update-addons')
{ if (isset($_GET['order_date']) && strtotime($_GET['order_date']) && 1 === preg_match('~[0-9]~', $_GET['order_date']))
  { $query = "UPDATE ". _DB_PREFIX_."orders SET date_add='".mysqli_real_escape_string($conn, $_GET['order_date'])."',date_upd='".mysqli_real_escape_string($conn, $_GET['order_date'])."' ";
	$query.=" WHERE id_order ='".mysqli_real_escape_string($conn, $id_order)."'";
	$res=dbquery($query);
	echo "<br>Order date was updated.<br>";
  }
  else
	 echo "<p/><b>Invalid order date: ".$_GET['order_date']."</b><p/>";

  if (isset($_GET['id_customer']))
  { $query = "SELECT * FROM ". _DB_PREFIX_."customer WHERE id_customer ='".mysqli_real_escape_string($conn, $_GET['id_customer'])."'";
    $res=dbquery($query);
    if(mysqli_num_rows($res) == 0)
    { echo "<p><u><b>You provided an invalid customer number. The order was not updated.</u></b><p>";
    }
    else
    { $query = "select id_address from "._DB_PREFIX_."address WHERE id_customer='".mysqli_real_escape_string($conn, $_GET['id_customer'])."'";
      $res=dbquery($query);
	  $row=mysqli_fetch_array($res);
	  $query = "UPDATE "._DB_PREFIX_."orders SET id_customer='".mysqli_real_escape_string($conn, $_GET['id_customer'])."', ";
	  $query .= " id_address_delivery='".$row['id_address']."', id_address_invoice='".$row['id_address']."'";
	  $query.=" WHERE id_order ='".mysqli_real_escape_string($conn, $id_order)."'";
	  $res=dbquery($query);	   
	  echo "<br>Customer id was updated.<br>";
    }
  }

  if (isset($_GET['reference']))
  { $query = "UPDATE ". _DB_PREFIX_."orders SET reference='".mysqli_real_escape_string($conn, $_GET['reference'])."' ";
    $query.=" WHERE id_order ='".mysqli_real_escape_string($conn, $id_order)."'";
    $res=dbquery($query);
	echo "<br>Reference was updated.<br>";
  }
}

echo "<br>Finished successfully!<p>Go back to <a href='order-edit.php?id_order=".$id_order."'>Order-edit page page</a>";
if($verbose!="true")
{ echo "<script>location.href = 'order-edit.php?id_order=".$id_order."';</script>";
}

function update_stock($id_order, $id_product, $id_product_attribute, $quantchange)
{ global $conn, $stock_management_conf, $advanced_stock_management_conf, $id_shop, $id_shop_group,$share_stock;
  global $stock_mvt_reason;
  /* first check whether there is a need to update */
  /* only update when there is stock keeping but no warehousing */
//  if(!$stock_management_conf) 
//	return;
  $query = "SELECT depends_on_stock,advanced_stock_management FROM ". _DB_PREFIX_."product_shop ps";
  if($share_stock)
	$query.=" left join ". _DB_PREFIX_."stock_available s on s.id_product=ps.id_product AND s.id_shop_group=".$id_shop_group; 
  else
	$query.=" left join ". _DB_PREFIX_."stock_available s on s.id_product=ps.id_product AND s.id_shop=".$id_shop; 		
  $query .= " where ps.id_product=".$id_product;
  $res=dbquery($query);
  $row=mysqli_fetch_array($res);
  if($row["depends_on_stock"])
	return;
  
/* supporting ASM here would be hard. 
 * You cannot have negative stock in warehouses and Order-edit doesn't check whether there is enough stock.
 * There is also the multi-warehouse problem: what to do when someone orders 10 pieces and you have 5 in one warehouse and 5 in another?
 */
 
  /* sometimes stock_available is not set */
  if($share_stock)
  { $res = dbquery("SELECT * FROM ". _DB_PREFIX_."stock_available where id_product=".$id_product." AND id_product_attribute=0 AND id_shop_group=".$id_shop_group);
    if(mysqli_num_rows($res)==0) dbquery("INSERT INTO ". _DB_PREFIX_."stock_available SET quantity=0, id_product='".$id_product."', id_product_attribute='0',id_shop_group=".$id_shop_group.",id_shop=0,depends_on_stock='0',out_of_stock='2'");
	if($id_product_attribute != 0)
	{ $res = dbquery("SELECT * FROM ". _DB_PREFIX_."stock_available where id_product=".$id_product." AND id_product_attribute='".$id_product_attribute."' AND id_shop_group=".$id_shop_group);
      if(mysqli_num_rows($res)==0) dbquery("INSERT INTO ". _DB_PREFIX_."stock_available SET quantity=0, id_product='".$id_product."', id_product_attribute='".$id_product_attribute."',id_shop_group=".$id_shop_group.",id_shop=0,depends_on_stock='0',out_of_stock='2'");
	}
  }
  else
  { $res = dbquery("SELECT * FROM ". _DB_PREFIX_."stock_available where id_product=".$id_product." AND id_product_attribute=0 AND id_shop=".$id_shop);
    if(mysqli_num_rows($res)==0) dbquery("INSERT INTO ". _DB_PREFIX_."stock_available SET quantity=0, id_product='".$id_product."', id_product_attribute='0',id_shop=".$id_shop.",id_shop_group=0,depends_on_stock='0',out_of_stock='2'");
	if($id_product_attribute != 0)
	{ $res = dbquery("SELECT * FROM ". _DB_PREFIX_."stock_available where id_product=".$id_product." AND id_product_attribute='".$id_product_attribute."' AND id_shop=".$id_shop);
      if(mysqli_num_rows($res)==0) dbquery("INSERT INTO ". _DB_PREFIX_."stock_available SET quantity=0, id_product='".$id_product."', id_product_attribute='".$id_product_attribute."',id_shop=".$id_shop.",id_shop_group=0,depends_on_stock='0',out_of_stock='2'");
	}
  }
  
  dbquery("update ". _DB_PREFIX_."product set quantity=quantity+".$quantchange." where id_product=".$id_product);
  if($share_stock)
	dbquery("UPDATE ". _DB_PREFIX_."stock_available SET quantity=quantity+".$quantchange." where id_product=".$id_product." AND id_product_attribute=0 AND id_shop_group=".$id_shop_group);
  else
	dbquery("UPdate ". _DB_PREFIX_."stock_available SET quantity=quantity+".$quantchange." where id_product=".$id_product." AND id_product_attribute=0 AND id_shop=".$id_shop);
  if(($id_product_attribute!=0))
  { dbquery("upDATE ". _DB_PREFIX_."product_attribute set quantity=quantity+".$quantchange." where id_product_attribute=".$id_product_attribute);
    if($share_stock)
	  dbquery("UPdaTE ". _DB_PREFIX_."stock_available SET quantity=quantity+".$quantchange." where id_product=".$id_product." AND id_product_attribute=".$id_product_attribute." AND id_shop_group=".$id_shop_group);
    else
	  dbquery("UpdaTE ". _DB_PREFIX_."stock_available SET quantity=quantity+".$quantchange." where id_product=".$id_product." AND id_product_attribute=".$id_product_attribute." AND id_shop=".$id_shop);
  }
}

function update_total($id_order) 
{ global $conn;
  $query="select sum(total_price_tax_incl) as total_products,sum(total_price_tax_excl) as total_products_notax  from  ". _DB_PREFIX_."order_detail where id_order=".$id_order;
  $res2=dbquery($query);
  $products=mysqli_fetch_array($res2);
  if($products['total_products']=="")
    $products['total_products'] = $products['total_products_notax'] = 0; /* no products present */
  $query="select * from  ". _DB_PREFIX_."orders where id_order=".mysqli_real_escape_string($conn, $id_order);
  $res3=dbquery($query);
  $order=mysqli_fetch_array($res3);
  $total=price($products['total_products'])+price($order['total_shipping'])+price($order['total_wrapping'])-price($order['total_discounts']);
  $total_ex=price($products['total_products_notax'])+price($order['total_shipping_tax_excl'])+price($order['total_wrapping'])-price($order['total_discounts']);
  $query="update ". _DB_PREFIX_."orders set ";
  $query.=" total_products=".$products['total_products_notax'];
  $query.=" ,total_products_wt=".$products['total_products'];
  $query.=" ,total_paid_tax_excl=".$total_ex;
  $query.=" ,total_paid_tax_incl=".$total;
  $query.=" ,total_paid_real=".$total;
  $query.=" ,total_paid=".$total;
  $query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
  $query.=" limit 1";
  dbquery($query);

  $query="update ". _DB_PREFIX_."order_invoice set ";
  $query.=" total_paid_tax_excl='".($order['total_shipping_tax_excl'] + $products['total_products_notax'])."'";
  $query.=" ,total_paid_tax_incl=".$total;
  $query.=" ,total_products=".$products['total_products_notax'];
  $query.=" ,total_products_wt=".$products['total_products'];
  $query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
  $query.=" limit 1";
  dbquery($query);
}

function price($price) {
$price=str_replace(",",".",$price);
return $price;
}

