<?php
/* 1. handling $_GET/$_POST variables and initialization
 * 2. page header
 * 3. find order
 * 4. retrieve order data: print data above line
 * 5. produce order form
 * 6. the productsform: orderlines
 */
/* section 1: handling $_GET/$_POST variables and initialization */

require_once '../../classes/custom/CustomUtils.php';

if(!@include 'approve.php') die( "approve.php was not found!");
/* flags for addon fields */
$showdate = 0;
$showcustomer = 0;
$showreference = 0;

if (isset($_POST['id_order'])) $id_order = intval($_POST['id_order']);
else if (isset($_GET['id_order'])) $id_order = intval($_GET['id_order']);
else $id_order = "";
if($id_order=="0") $id_order = "";
if (isset($_GET['order_reference'])) $order_reference = preg_replace("/[^A-Za-z0-9]/","",$_GET['order_reference']);
else if (isset($_POST['order_reference'])) $order_reference = preg_replace("/[^A-Za-z0-9]/","",$_POST['order_reference']);
else $order_reference = "";
if (isset($_GET['id_lang'])) $id_lang = $_GET['id_lang'];
else if (isset($_POST['id_lang'])) $id_lang = $_POST['id_lang'];
else {
	$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
	$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];
}




$id_lang = strval(intval($id_lang));
if (!isset($_GET['attribute'])) $_GET['attribute'] = "";
else
$_GET['attribute'] = strval(intval($_GET['attribute']));

$query=" select cu.name, cu.id_currency,cu.conversion_rate from ". _DB_PREFIX_."configuration cf, ". _DB_PREFIX_."currency cu";
$query.=" WHERE cf.name='PS_CURRENCY_DEFAULT' AND cf.value=cu.id_currency";
$res=dbquery($query);
$row=mysqli_fetch_array($res);
$cur_name = $row['name'];
$cur_rate = $row['conversion_rate'];
$id_currency = $row['id_currency'];

/* section 2: page header */
?><!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Order Modify</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style type="text/css">
body {font-family:arial; font-size:13px}
form {width:260px;}
label,span {height:20px; padding:5px 0; line-height:20px;}
label {width:130px; display:block; float:left; clear:left}
label[for="costumer_id"] {float:left; clear:left}
span {float:left; clear:right}
input {border:1px solid #CCC}
input[type="text"] {width:120px; height:24px; margin:3px 0; float:left; clear:right; padding:0 0 0 2px; border-radius:3px; background:#F9F9F9}
	input[type="text"]:focus {background:#FFF}
select {width:120px; border:1px solid #CCC}
input[type="submit"] {clear:both; display:block; color:#FFF; background:#000; border:none; height:24px; padding:2px 4px; cursor:pointer; border-radius:3px}
input[type="submit"]:hover {background:#333}
table img {display:block; }
</style>
<script type="text/javascript">
function check_products()
{ if(!checkPrices()) return false;
  productsform.verbose.value = orderform.verbose.checked;
}

function checkPrices()
{ rv = document.getElementsByClassName("price"); // also possible with document.querySelectorAll("price")
  len = rv.length;
  for(var i=0; i<len; i++)
  { if(rv[i].value.indexOf(',') != -1)
    { alert("Please use dots instead of comma's for the prices!");
      rv[i].focus();
      return false;
    }
  }
  return true;
}
</script>
<script type="text/javascript" src="utils8.js"></script>
</head>
<body>
<?php print_menubar();

/* section 3: find order */
if ($id_order != "") {
	$res = dbquery("SELECT * FROM ". _DB_PREFIX_."orders WHERE id_order='".$id_order."'");
	if(mysqli_num_rows($res) == 0)
	{   echo "<b>".$id_order." is not a valid order id.</b>";
		$id_order = "";
	}
	else
	{   $row = mysqli_fetch_array($res);
		$order_reference = $row["reference"];
	}
}
else if ($order_reference != "")
{ 	$res = dbquery("SELECT * FROM ". _DB_PREFIX_."orders WHERE reference='".$order_reference."'");
	if(mysqli_num_rows($res) == 0)
	{   echo "<b>".$order_reference." is not a valid order reference.</b>";
		$order_reference = "";
	}
	else
	{   $row = mysqli_fetch_array($res);
		$id_order = $row["id_order"];
	}
}
?>
<table style="border-bottom: 2px dotted #CCCCCC;"><tr><td width="250px">
<form name="searchform" method="post" action="order-edit.php">
	<label for="order_number">Order number:</label><input name="id_order" type="text" value="<?php echo $id_order ?>" size="10" maxlength="10" />
    <label for="order_number">Order reference:</label><input name="order_reference" type="text" value="<?php echo $order_reference ?>" size="10" maxlength="10" />
    <?php echo CustomUtils::orderLink($id_order, "<h3>Administrace objednávky</h3>")?>
</td><td width="100px">
	<input name="send" type="submit" value="Find order" />
</form>
</td><td>

<?php
if($id_order == "")
{	echo "</td></tr></table>";
    print_footer();
	return;
}

/* section 4: retrieve order data: print data above line */
$query="select o.id_shop, oi.id_order_invoice, a.id_country, a.vat_number, a2.vat_number AS vat_invoice,";
$query .= " a.id_state, s.name AS sname, c.name AS cname, cu.id_currency, cu.name AS currname,";
$query .= " cu.conversion_rate AS currrate";
$query .= " from ". _DB_PREFIX_."orders o";
$query .= " left join ". _DB_PREFIX_."order_invoice oi on o.id_order=oi.id_order";
$query .= " left join ". _DB_PREFIX_."address a on o.id_address_delivery=a.id_address";
$query .= " left join ". _DB_PREFIX_."address a2 on o.id_address_invoice=a2.id_address";
$query .= " left join ". _DB_PREFIX_."country_lang c on a.id_country=c.id_country AND c.id_lang='".$id_lang."'";
$query .= " left join ". _DB_PREFIX_."state s on a.id_country=s.id_country  AND a.id_state=s.id_state";
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
$conversion_rate = $row['currrate'] / $cur_rate;

/* get shop group and its shared_stock status */
$gquery="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$gquery .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$gres=dbquery($gquery);
$grow = mysqli_fetch_array($gres);
$id_shop_group = $grow['id_shop_group'];
$share_stock = $grow["share_stock"];

$query="select distinct o.*,a.*,o.date_add AS order_date, osl.name AS order_status,SUM(c.weight) AS weight";
$query .= " from ". _DB_PREFIX_."orders o";
$query .=" LEFT JOIN "._DB_PREFIX_."order_state_lang osl ON o.current_state=osl.id_order_state AND osl.id_lang=".$id_lang;
$query .=" LEFT JOIN "._DB_PREFIX_."address a ON a.id_address=o.id_address_delivery";
$query .=" LEFT JOIN "._DB_PREFIX_."order_carrier c ON c.id_order=o.id_order";
$query .= " where o.id_order=".mysqli_real_escape_string($conn, $id_order);
$query .= " GROUP BY id_order"; /* split orders? */
$res=dbquery($query);
if (mysqli_num_rows($res)==0) colordie("Error retrieving order data!");

$order=mysqli_fetch_array($res);
$id_customer=$order['id_customer'];
$reference = $order['reference'];
$id_lang=$order['id_lang'];
$id_cart=$order['id_cart'];
$payment=$order['payment'];
$module=$order['module'];
$invoice_number=$order['invoice_number'];
$delivery_number=$order['delivery_number'];
$total_paid_tax_excl=$order['total_paid_tax_excl'];
$total_paid_tax_incl=$order['total_paid_tax_incl'];
$total_products=$order['total_products'];
$total_products_wt=$order['total_products_wt'];
$total_discounts=$order['total_discounts'];
$total_shipping=$order['total_shipping'];
$total_wrapping=$order['total_wrapping'];
$firstname=$order['firstname'];
$lastname=$order['lastname'];
$company=$order['company'];
$carrier = $order['id_carrier'];
$order_date = $order['order_date'];
$order_weight = $order['weight'];

echo 'Customer: <a href="http://localhost/_ps16017/triple/order-search.php?search_fld1=customer+id&search_txt1='.$id_customer.'">'.$firstname.' '.$lastname.' '.$company."</a><br>
Customer ID: ".$id_customer."<br>
VAT number: ".$vat_number."<br>
Order status: ".$order['order_status']."
</td><td style='padding:6pt' valign='top'>
Tax country=".$row['cname'];
if ($id_state != 0)
  echo " AND state=".$row['sname'];
echo "<br>Shop id=".$id_shop;
echo "<br>Date=".$order_date;

/* section 5: produce order form */
?>
</td></tr></table>
<table><tr><td>
<form name="orderform" method="post" action="order-proc.php" style="padding-top: 20px;width: 580px;">
<!-- hidden value --> <input type=hidden name=id_lang value="<?php echo $id_lang ?>">
  <input type=hidden name=action value="change-order">
	<label for="carrier">Carrier:</label>
	<select name="id_carrier">
	<?php	$carrierfound = false;
			$query=" select * from ". _DB_PREFIX_."carrier WHERE deleted='0' OR id_carrier='".$carrier."'";
			$res=dbquery($query);
			while ($carrierrow=mysqli_fetch_array($res))
			{ $selected = $deleted = '';
			  if ($carrierrow['id_carrier']==$carrier)
			  { $selected=' selected="selected" ';
				$carrierfound = true;
			  }
			  if ($carrierrow['deleted'] != '0')
			    $deleted = ' style="background-color:grey" ';
			  echo '<option  value="'.$carrierrow['id_carrier'].'" '.$deleted.' '.$selected.'>'.$carrierrow['name'].'</option>';
			}
			if(!$carrierfound)
			{ if($carrier == 0)
			    echo '<option value=0  style="background-color:grey" selected>None</option>';
			  else
				echo '<option value="'.$carrier.'"  style="background-color:grey" selected>Unknown-'.$carrier.'</option>';
			}
		?>
	</select>

	<label for="total_shipping">Shipping:</label><input name="total_shipping" type="text" value="<?php echo $total_shipping ?>" />
	<label for="total_discounts">Discounts:</label><input name="total_discounts" type="text"  value="<?php echo $total_discounts ?>" />
	<label for="total_wrapping">Wrapping:</label><input name="total_wrapping" type="text" value="<?php echo $total_wrapping ?>" />
	<label for="delivery_number">Delivery no.:</label><input name="delivery_number" type="text"  value="<?php echo $delivery_number ?>" />
	<label for="subtotal">Subtotal (tax excl.):</label><span><?php echo $total_paid_tax_excl ?></span>
	<label for="total">Total (tax incl.):</label><span><?php echo $total_paid_tax_incl." &nbsp; ".$order_currname ?></span>

	<!-- hidden value -->  <input name="total_products" type="hidden"  value="<?php echo $total_products ?>" />
	<!-- hidden value -->  <input name="total_products_wt" type="hidden"  id="total_products_wt" value="<?php echo $total_products_wt ?>" />
	<!-- hidden value -->  <input name="id_order" type="hidden" value="<?php echo $id_order ?>" />

	<input type="submit" name="orderform"  value="Modify Order" />

</td><td>&nbsp; &nbsp;</td><td style="vertical-align:top">
<?php
  $qfields = "a1.firstname AS firstname1,a1.lastname AS lastname1,a1.company AS company1,a1.address1,a1.address2,a1.postcode,a1.city,a1.id_country,a1.phone,a1.phone_mobile";
  $qfields .= ",a2.firstname AS firstname2,a2.lastname AS lastname2,a2.company AS company2,a2.address1 AS address12,a2.address2 AS address22,a2.postcode AS postcode2,a2.city AS city2,a2.id_country AS id_country2,a2.phone AS phone2,a2.phone_mobile AS phone_mobile2";
  $qfields .= ",cl1.iso_code AS country1, cl2.iso_code AS country2, c.email, o.id_address_delivery, o.id_address_invoice";
  $qbody = " FROM ". _DB_PREFIX_."orders o";
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."customer c ON c.id_customer=o.id_customer";
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."address a1 ON o.id_address_invoice=a1.id_address";
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."country cl1 ON cl1.id_country=a1.id_country";
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."address a2 ON o.id_address_delivery=a2.id_address";
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."country cl2 ON cl2.id_country=a2.id_country";
  $qbody .= " WHERE id_order=".$id_order;
  $qres = dbquery("SELECT ".$qfields.$qbody);
  $qrow=mysqli_fetch_assoc($qres);
  echo $qrow["email"]."<br>";
  if($qrow["id_address_delivery"] != $qrow["id_address_invoice"]) echo "INV: ";
  echo $qrow["firstname1"]." ".$qrow["lastname1"]."<br>";
  if($qrow["company1"]!="")echo $qrow["company1"]."<br>";
  echo $qrow["address1"]."<br>";
  if($qrow["address2"]!="")echo $qrow["address2"]."<br>";
  echo $qrow["postcode"]." ".$qrow["city"]." ".$qrow["country1"]."<br>";
  echo $qrow["phone"]." / ".$qrow["phone_mobile"]."<p>";

  if($qrow["id_address_delivery"] != $qrow["id_address_invoice"])
  {	echo "SHIP: ".$qrow["firstname2"]." ".$qrow["lastname2"]."<br>";
	if($qrow["company2"]!="")echo $qrow["company2"]."<br>";
	echo $qrow["address12"]."<br>";
	if($qrow["address22"]!="")echo $qrow["address22"]."<br>";
	echo $qrow["postcode2"]." ".$qrow["city2"]." ".$qrow["country2"]."<br>";
	echo $qrow["phone2"]." / ".$qrow["phone_mobile2"]."<p>";
  }
?>
<input type=checkbox name=verbose> verbose<br></form>
Total products excl. VAT: &nbsp; <?php echo $total_products ?><br>
Total products incl. VAT: &nbsp; <?php echo $total_products_wt ?><br>
Total weight: &nbsp; &nbsp; <?php echo $order_weight ?>
</td></tr></table>
<br style="clear:both; height:40px;display:block;" />

<!-- section 6: the productsform: orderlines -->
<form name="productsform" method="post" action="order-proc.php" onSubmit="return check_products();">
  <input type=hidden name=action value="change-products"><input type=hidden name=verbose>
<table width="100%" ><tr><td width="100%" align=right>
<a style="height:20px; background:#000; color:#FFF; border-radius:3px; padding:5px 10px; text-decoration:none; margin:20px 0"href="add-product.php?id_order=<?php echo $id_order ?>&id_lang=<?php echo $id_lang ?>&id_shop=<?php echo $id_shop ?>" target="_self">Add new product</a>
</td></tr>
<tr><td>
<table width="100%" border="1" bgcolor="#FFCCCC" style="margin-top:10px;">
  <tr>
    <td >product id</td>
    <td>attrib</td>
    <td>Product Reference</td>
    <td>Product Name</td>
    <td>Price tax ex</td>
    <td>Tax</td>
    <td>Price with tax</td>
    <td>Qty</td>
    <td>Total no tax</td>
    <td>Total  tax inc.</td>
    <td>Weight</td>
    <td>Image</td>
    <td>Delete</td>
  </tr>

  <?php
$itemcount = 0;
$query="select o.*,p.quantity as stock, i.id_image, o.product_attribute_id,t.rate from ". _DB_PREFIX_."order_detail o";
$query .= " left join ". _DB_PREFIX_."product p  on  o.product_id=p.id_product";
$query .= " left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
$query .= " LEFT JOIN ". _DB_PREFIX_."order_detail_tax ot ON o.id_order_detail=ot.id_order_detail";
$query .= " LEFT JOIN ". _DB_PREFIX_."tax t ON t.id_tax=ot.id_tax";
$query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
$query.=" order by id_order_detail asc";
  $res1=dbquery($query);

/* process order lines */
while ($products=mysqli_fetch_array($res1))
{ echo '<tr>';
  if($products["rate"] == NULL)
	  $products["rate"] = 0;
/*{ $rquery = " SELECT rate from ". _DB_PREFIX_."product_shop ps ";
    $rquery.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."' AND tr.id_state='".$id_state."'";
    $rquery.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
	$rquery.=" WHERE ps.id_product='".$products["product_id"]."' AND ps.id_shop='".$id_shop."'";
	$rres=dbquery($rquery);
	$rrow=mysqli_fetch_array($rres);
	$products["rate"] = $rrow["rate"];
  }
*/
//  $products["rate"] = (100*((float)$products['unit_price_tax_incl'] - (float)$products['unit_price_tax_excl']))/(float)$products['unit_price_tax_excl'];

  echo '<td>'.$products['product_id'].'</td>';
  echo '<td>'.$products['product_attribute_id'].'</td>';
  echo '<td>'.$products['product_reference'].'</td>';
  echo '<td><input name="product_name['.$products['id_order_detail'].']" style="width:21em" value="'.htmlspecialchars($products['product_name']).'"/></td>';
  echo '<td><input name="product_price['.$products['id_order_detail'].']" class="price" value="'.number_format($products['product_price'], 4, '.', '').'" size="9" /></td>';
  echo '<td>'.number_format($products['rate'], 4, '.', '').'%</td>';
  echo '<td>'.number_format($products['product_price']*(1+$products['rate']/100),4, '.', '').'</td>';
  echo '<td style="position:relative"><input name="product_quantity['.$products['id_order_detail'].']" value="'.$products['product_quantity'].'" size="5" />';
    if($share_stock)
	  $shoplimiter = "id_shop_group=".$id_shop_group;
    else
	  $shoplimiter = "id_shop=".$id_shop;
	$stquery = "SELECT quantity from ". _DB_PREFIX_."stock_available WHERE id_product='".$products["product_id"]."' AND id_product_attribute='".$products['product_attribute_id']."' AND ".$shoplimiter;
	$stres=dbquery($stquery);
	if(mysqli_num_rows($stres)> 0)
	{ $strow = mysqli_fetch_array($stres);
	  echo '<div style="position: absolute; bottom: -8px; right:4px;"><span style="color:#9999FA; align:right">['.$strow['quantity'].']</span></div>';
	}
	echo '</td>';

  echo '<td>'.number_format($products['product_price']*$products['product_quantity'],4, '.', '').'</td>';
  echo '<td>'.number_format($products['product_price']*$products['product_quantity']*(1+$products['rate']/100),4, '.', '').'</td>';
  echo '<td>'.number_format($products['product_weight'],4, '.', '').'</td>';
  if($products['product_attribute_id']!=0) /* show attribute image when available */
  { $attriquery = "SELECT id_image from "._DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$products['product_attribute_id']."';";
    $attrires=dbquery($attriquery);
	$attrirow=mysqli_fetch_array($attrires);
	if($attrirow['id_image'] != 0)
	  echo "<td>".get_product_image($products['product_id'],$attrirow['id_image'],'')."</td>";
	else
	  echo "<td>".get_product_image($products['product_id'],$products['id_image'],'')."</td>";
  }
  else
    echo "<td>".get_product_image($products['product_id'],$products['id_image'],'')."</td>";
  echo '<td><input name="product_delete['.$products['id_order_detail'].']" type="checkbox" />';
  echo '<input name="product_quantity_old['.$products['id_order_detail'].']" type="hidden" value="'.$products['product_quantity'].'" />';
  echo '<input name="product_id['.$products['id_order_detail'].']" type="hidden" value="'.$products['product_id'].'" />';
  echo '<input name="product_attribute['.$products['id_order_detail'].']" type="hidden" value="'.$products['product_attribute_id'].'" />';
  echo '<input name="product_stock['.$products['id_order_detail'].']" type="hidden" value="'.$products['stock'].'" />';
  echo '</td></tr>';
  $itemcount += $products['product_quantity'];
}

  ?>
</table>
</td></tr>
<tr><td width="100%" align=center>
  <input name="Apply" type="submit" value="Modify order lines" />
  <input name="id_order" type="hidden" value="<?php echo $id_order ?>" />
  <!--input name="tax_rate" type="hidden" value="<-?php echo $tax_rate ?->" /-->
  <input name="id_lang" type="hidden" value="<?php echo $id_lang ?>" />
</td></tr>
</table>
</form>
<?php echo mysqli_num_rows($res1)." lines - ".$itemcount." items"; ?>
<p/>
<?php
/* the following code will produce an editable order_date field. It should be normally disabled. */

if($showdate || $showcustomer || $showreference)
{ echo '<script>
function check_addons()
{ if(addonsform.order_date)
	if(!check_date()) return false;
  addonsform.verbose.value = orderform.verbose.checked;
}
</script>';
  echo '<form name="addonsform" method="get" onsubmit="return check_addons();" action="order-proc.php">';
  echo '<input type=hidden name=action value="update-addons"><input type=hidden name=verbose>';
  echo '<table><tr><td>';
  if($showdate)
  { $oyear = substr($order_date, 0,4);
    $omonth = substr($order_date, 5,2);
    $oday = substr($order_date, 8,2);
    echo '
Order date (yyyy-mm-dd):</td><td><nobr>
<input id=oyear size=4 value='.$oyear.'>-<input id=omonth size=2 value='.$omonth.'>-<input id=oday size=2 value='.$oday.'>
</nobr></td><td>
<input name="id_order" type="hidden" value="'.$id_order.'" />
<input type=hidden name=order_date>
<script>
function check_date()
{ error = 0;
  day = document.addonsform["oday"].value;
  month = document.addonsform["omonth"].value;
  year = document.addonsform["oyear"].value;
  if((year < 500) || (year > 2100)) {field="oyear"; error=1;}
  if((month < 1 ) || (month > 12)) {field="omonth"; error=1;}
  if((day < 1) || (day > 31)) {field="oday"; error=1;}
  if((month==4 || month==6 || month==9 || month==11) && (day==31)) {field="oday"; error=1;}
  if((month==2) && (day > 29)) {field="day"; error=1;}
  if((month==2) && (day==29) && (!LeapYear(year))) {field="oday"; error=1;}
  if(error == 1)
  { alert("Invalid date!");
	document.addonsform[field].focus();
	document.addonsform[field].select();
	return false;
  }
  document.addonsform["order_date"].value = year+"-"+month+"-"+day;
  return true;
}

function LeapYear(intYear) 
{ if (intYear % 100 == 0) 
  { if (intYear % 400 == 0) { return true; }
  }
  else
  { if ((intYear % 4) == 0) { return true; }
  }
  return false;
}
</script></td></tr>';
  }

  if(isset($showcustomer))
  { echo '<tr><td><nobr>Customer id: </td><td><input name="id_customer" value="'.$id_customer.'"></nobr></td><td>';
    echo '<input name="id_order" type="hidden" value="'.$id_order.'" /></td></tr>
';
}
  if(isset($showreference))
  { echo '<tr><td><nobr>Order Ref: </td><td><input name="reference" value="'.$reference.'"></nobr></td><td>';
    echo '<input name="id_order" type="hidden" value="'.$id_order.'" /></td></tr>
';
  }
  echo '<tr><td></td><td><input type=submit value="Submit addon fields"></td></tr></table>';
  echo '</form>';
}

print_footer();

function print_footer()
{ global $prestools_notbought;
  echo '<p>Limitations:<br/>
 - this program will not recalculate your shipping costs if you change carrier or add or remove products. You should do that manually.<br/>
 - ecotax is ignored.<br/>
 - discounts on added products are not processed<br/>
 - stock management is supported. When a product is out of stock its quantity becomes negative but you get no warning.<br/>
 - warehousing is not supported. Prestools will choose the best fitting warehouse when available. But it will not change the quantities.
 You must do that manually.<br/>
 - Under in the quantity field you find in vague letters the stock of the product.
 - split orders (more than one shipment) are not supported.<br/>
 - total weight is not updated<br/>';

  include "footer1.php";
  echo '</body></html>';
}

