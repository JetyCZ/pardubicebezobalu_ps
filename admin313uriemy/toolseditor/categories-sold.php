<?php 
/* This script - part of Prestools - lists the revenues for each category within a certain period */
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['id_shop'])) $input['id_shop']="0";
$id_shop = intval($input["id_shop"]);
if(!isset($input['startdate']) || (!check_mysql_date($input['startdate'])))
	$input['startdate']="";
if(!isset($input['enddate']) || (!check_mysql_date($input['enddate'])))
	$input['enddate']="";
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Ordered Products by category</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<?php echo '<script type="text/javascript">
	function salesdetails(product)
	{ window.open("product-sales.php?product="+product+"&startdate='.$input["startdate"].'&enddate='.$input["enddate"].'&id_shop='.$id_shop.'","", "resizable,scrollbars,location,menubar,status,toolbar");
      return false;
    }
</script>
<style>
  table#Maintable td {text-align: right;}
  table#Maintable td:nth-child(2) {text-align: left;}
</style>
</head><body>';
print_menubar();
echo '<h1>Prestashop Category revenue</h1>';

echo '<form name="search_form" method="get">
Period (yyyy-mm-dd): <input size=5 name=startdate value='.$input['startdate'].'> till <input size=5 name=enddate value='.$input['enddate'].'> &nbsp; ';

/* making shop block */
	$query= "select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	echo " &nbsp; Shop: <select name=id_shop><option value=0>All shops</option>";
	while ($shop=mysqli_fetch_array($res)) 
	{   $selected = "";
	    if($shop["id_shop"] == $id_shop) $selected = " selected";
        echo '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
	}	
    echo '</select><input type=submit></form>';

	$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];
	
	$order_states = array(2,3,4,5);
	echo "<p>Orders with the following states have been included: ";
	$comma = "";
	foreach($order_states AS $order_state)
	{ $osquery="select name from ". _DB_PREFIX_."order_state_lang WHERE id_order_state='".$order_state."'";
	  $osres=dbquery($osquery);
	  $osrow = mysqli_fetch_array($osres);
	  echo $comma.$osrow['name'];
	  $comma = ", ";
	}
	
	echo "<p>When a product is present in more than one category there is no way to determine from which one (if any) it was sold. To give some
	indication the table provides both values for only the products for who it is the default category and for all products present.";	
	
$query = "SELECT name,c.id_category,link_rewrite,active FROM ". _DB_PREFIX_."category c";
$query .= " LEFT JOIN ". _DB_PREFIX_."category_lang cl ON c.id_category=cl.id_category";
$query .= " WHERE id_lang=".$id_lang;
if($id_shop !=0)
	$query .= " AND o.id_shop=".$id_shop;
$query.= " ORDER BY id_category";
$res=dbquery($query);
$myresults = array();
while($datarow = mysqli_fetch_array($res))
{ $myresults[$datarow["id_category"]] = $datarow;
}
mysqli_free_result($res);
	
$query="SELECT id_category_default AS id_category, SUM(total_price_tax_incl) AS pricetotal, product_price";
$query .= ", SUM(total_price_tax_excl) AS pricetotalex, SUM(product_quantity) AS quantitytotal, count(o.id_order) AS ordercount ";
$query .= ", COUNT(DISTINCT d.product_id) AS producttotal";
$query .= " FROM ". _DB_PREFIX_."order_detail d";
$query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order";
$query .= " LEFT JOIN ". _DB_PREFIX_."product_shop ps ON ps.id_product = d.product_id AND ps.id_shop=o.id_shop";

$query .= " WHERE o.current_state IN (".implode(",",$order_states).")";
if($id_shop !=0)
	$query .= " AND o.id_shop=".$id_shop;
if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) > TO_DAYS('".mysqli_real_escape_string($conn, $input['startdate'])."')";
if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) < TO_DAYS('".mysqli_real_escape_string($conn, $input['enddate'])."')";
$query .= " GROUP BY id_category";
$query .= " ORDER BY id_category";
//$verbose=true;
$res=dbquery($query);
while($datarow = mysqli_fetch_array($res))
{ $myresults[$datarow["id_category"]]["pricetotal"] = $datarow["pricetotal"];
  $myresults[$datarow["id_category"]]["pricetotalex"] = $datarow["pricetotalex"];
  $myresults[$datarow["id_category"]]["quantitytotal"] = $datarow["quantitytotal"];
  $myresults[$datarow["id_category"]]["ordercount"] = $datarow["ordercount"];
  $myresults[$datarow["id_category"]]["producttotal"] = $datarow["producttotal"];
}
mysqli_free_result($res);

$query="SELECT id_category, SUM(total_price_tax_incl) AS pricetotal, product_price";
$query .= ", SUM(total_price_tax_excl) AS pricetotalex, SUM(product_quantity) AS quantitytotal, count(o.id_order) AS ordercount ";
$query .= ", COUNT(DISTINCT d.product_id) AS producttotal";
$query .= " FROM ". _DB_PREFIX_."order_detail d";
$query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order";
$query .= " LEFT JOIN ". _DB_PREFIX_."category_product cp ON cp.id_product = d.product_id";

$query .= " WHERE o.current_state IN (".implode(",",$order_states).")";
if($id_shop !=0)
	$query .= " AND o.id_shop=".$id_shop;
if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) > TO_DAYS('".mysqli_real_escape_string($conn, $input['startdate'])."')";
if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) < TO_DAYS('".mysqli_real_escape_string($conn, $input['enddate'])."')";
$query .= " GROUP BY id_category";
$query .= " ORDER BY id_category";
//$verbose=true;
$res=dbquery($query);
while($datarow = mysqli_fetch_array($res))
{ $myresults[$datarow["id_category"]]["allpricetotal"] = $datarow["pricetotal"];
  $myresults[$datarow["id_category"]]["allpricetotalex"] = $datarow["pricetotalex"];
  $myresults[$datarow["id_category"]]["allquantitytotal"] = $datarow["quantitytotal"];
  $myresults[$datarow["id_category"]]["allordercount"] = $datarow["ordercount"];
  $myresults[$datarow["id_category"]]["allproducttotal"] = $datarow["producttotal"];
}

echo "<p>".mysqli_num_rows($res).' categories with sales for period: '.$input['startdate'].' - '.$input['enddate']." for ";
if($id_shop == 0)
  echo "all shops";
else 
  echo "shop nr. ".$id_shop;


$infofields = array("id","Category Name","Sales","Quant","Av.price","Sales/tax","orders","nr.products","","Sales","Quant","Av.price","Sales/tax","orders","nr.products");
echo '<div id="testdiv"><table id="Maintable" border=1><colgroup id="mycolgroup">';
for($i=0; $i<sizeof($infofields); $i++)
  echo "<col id='col".$i."' ></col>";
echo '</colgroup><thead><tr>';






echo '<td colspan=2></td><td colspan=7 style="text-align:center">Default products</td><td colspan=6 style="text-align:center">All products</td>';
echo '</tr><tr>';
for($i=0; $i<sizeof($infofields); $i++)
{ $reverse = "false";
  if($i != 1) $reverse = "1";
  echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$i.', '.$reverse.');">'.$infofields[$i].'</a></th
>';
}
$total = 0;
$sumquantity = $sumtotal = $sumtotalex = 0;
echo "</tr></thead><tbody id='offTblBdy'>";
foreach ($myresults as $key => $datarow)
  { echo '<tr>';
	echo '<td>'.$key.'</td>';
	if((!isset($datarow["active"])) ||($datarow["active"]=="0"))
	  echo '<td style="background-color:#DDAAFF">';
    else
	  echo '<td>';
	if(isset($datarow["name"]))
	  echo '<a href="'.$triplepath.$key.'-'.$datarow["link_rewrite"].'" target=_blank>'.$datarow["name"].'</a></td>';
    else
	  echo 'Deleted products</td>';
	if(!isset($datarow["pricetotal"])) $datarow["pricetotal"] = "0";
	$sumtotal += $datarow["pricetotal"];
	echo "<td>".number_format($datarow["pricetotal"],2,".","")."</a></td>";
 	if(!isset($datarow["quantitytotal"])) $datarow["quantitytotal"] = "0";
    echo '<td>'.$datarow["quantitytotal"].'</td>';
	$sumquantity += intval($datarow["quantitytotal"]);
	if($datarow["quantitytotal"] != 0)
		echo '<td>'.number_format(($datarow["pricetotal"]/$datarow["quantitytotal"]),2,".","").'</td>';
	else 
		echo '<td>-</td>';
	if(!isset($datarow["pricetotalex"])) $datarow["pricetotalex"] = "0";
	$sumtotalex += $datarow["pricetotalex"];
    echo '<td>'.number_format($datarow["pricetotalex"],2,".","").'</td>';
	if(!isset($datarow["ordercount"])) $datarow["ordercount"] = "0";
    echo '<td>'.$datarow["ordercount"].'</td>';
	if(!isset($datarow["producttotal"])) $datarow["producttotal"] = "0";
    echo '<td>'.$datarow["producttotal"].'</td>';
	echo '<td></td>';
	if(!isset($datarow["allpricetotal"])) $datarow["allpricetotal"] = "0";
	echo "<td>".number_format($datarow["allpricetotal"],2,".","")."</a></td>";
 	if(!isset($datarow["allquantitytotal"])) $datarow["allquantitytotal"] = "0";
    echo '<td>'.$datarow["allquantitytotal"].'</td>';
	if($datarow["allquantitytotal"] != 0)
		echo '<td>'.number_format(($datarow["allpricetotal"]/$datarow["allquantitytotal"]),2,".","").'</td>';
	else 
		echo '<td>-</td>';	
	if(!isset($datarow["allpricetotalex"])) $datarow["allpricetotalex"] = "0";	
    echo '<td>'.number_format($datarow["allpricetotalex"],2,".","").'</td>';
	if(!isset($datarow["allordercount"])) $datarow["allordercount"] = "0";
    echo '<td>'.$datarow["allordercount"].'</td>';
	if(!isset($datarow["allproducttotal"])) $datarow["allproducttotal"] = "0";
    echo '<td>'.$datarow["allproducttotal"].'</td>';	
	echo "</tr
>";
  }
  echo "</tbody></table></div>";
  echo $sumquantity." copies sold in ".mysqli_num_rows($res)." categories for in total ".number_format($sumtotal,2)." (".number_format($sumtotalex,2)." without VAT)";

?>
