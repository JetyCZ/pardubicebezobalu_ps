<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['product'])) die("No product mentioned!");
if(!isset($input['startdate'])) $input['startdate']="";
if(!isset($input['enddate'])) $input['enddate']="";
//$verbose="true";
	$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
	$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];

$query="SELECT name from ". _DB_PREFIX_."product_lang WHERE id_product='".mysqli_real_escape_string($conn, $input['product'])."' AND id_lang='".mysqli_real_escape_string($conn, $id_lang)."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$product_name = $row["name"];

$query="SELECT o.id_order, o.id_shop, o.id_customer, product_id, product_attribute_id, product_name, firstname, lastname, product_quantity";
$query .= ", product_quantity_return,product_price,reduction_percent, reduction_amount,group_reduction, product_quantity_discount,email";
$query .= ",tax_rate,o.valid,DATE(o.date_add) AS odate,DATE(o.delivery_date) AS ddate, s.name AS sname, unit_price_tax_incl, total_price_tax_incl"; 
$query .= " FROM ". _DB_PREFIX_."order_detail d";
$query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order";

$query .= " LEFT JOIN ". _DB_PREFIX_."customer c ON c.id_customer = o.id_customer";
$query .= " LEFT JOIN ". _DB_PREFIX_."order_history h ON h.id_order=o.id_order AND h.date_add=o.date_upd";
$query .= " LEFT JOIN ". _DB_PREFIX_."order_state_lang s ON h.id_order_state = s.id_order_state AND s.id_lang='".$id_lang."'";
$query .= " WHERE d.product_id='".mysqli_real_escape_string($conn, $input['product'])."'";
if(isset($input['id_shop']) AND ($input['id_shop'] != ""))
  $query.= " AND o.id_shop='".intval($input['id_shop'])."'";
if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) > TO_DAYS('".mysqli_real_escape_string($conn, $input['startdate'])."')";
if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) < TO_DAYS('".mysqli_real_escape_string($conn, $input['enddate'])."')";
$res=dbquery($query);

echo '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
function switchDisplay(id, elt, fieldno, val)  // collapse(field)
{ var tmp, tmp2, val, checked;
  var advanced_stock = has_combinations = false;
  if(val == "0") /* hide */
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++)
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display="none";
  }
  if(val == "1")
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++) 
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display="table-cell";
  }
}

function export_csv()
{ window.open("product-sales-csv.php?'.$_SERVER['QUERY_STRING'].'", "_blank");
	
}
</script>
<title>Prestashop Product Sales Overview</title></head><body>';

echo 'Order overview for product nr. '.$input['product'].' ('.$product_name.'): Period: '.$input['startdate'].' - '.$input['enddate'];
if(isset($input['id_shop']) AND ($input['id_shop'] != ""))
   echo " for shop nr. ".$input['id_shop'];
else 
   echo " for all shops";
$infofields = array("order","shop","customer","cu.nr","email","name","attr","quant","returns","price","reduct%","reduct","tax","date","delivery","Last status","Total");

  echo '<form name=ListForm><table class="tripleswitch" style="empty-cells: show; display:inline" border=1><tr><td><br>Hide<br>Show</td>';
for($i=0; $i<sizeof($infofields); $i++)
{   echo '<td>'.$infofields[$i].'<br>';
    echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_off" value="0" onClick="switchDisplay(\'offTblBdy\', this,'.$i.',0)" /><br>';
    echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="1" checked onClick="switchDisplay(\'offTblBdy\', this,'.$i.',1)" /><br>';
    echo "</td>";
}
echo '</tr></table>';
echo ' &nbsp; &nbsp; <button onclick="export_csv(); return false;">Export csv for mailing</button>';

echo '</form>';

echo '<div id="testdiv"><table id="Maintable" border=1><colgroup id="mycolgroup">';
for($i=0; $i<sizeof($infofields); $i++)
  echo "<col id='col".$i."'></col>";
echo '</colgroup><thead><tr>';
for($i=0; $i<sizeof($infofields); $i++)
{ $reverse = "false";
  echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$i.', '.$reverse.');">'.$infofields[$i].'</a></th
>';
}
$total = 0;
$incompleted = 0;
echo "</tr></thead><tbody id='offTblBdy'>";
while($datarow = mysqli_fetch_array($res))
  { /* NOTE: as far as I can see the quantity discount and the groups discount are not used to calculate the price. The quantity discount field is changed but the meaning is unclear */
    if($datarow["valid"]==0)
	{ echo '<tr style="background-color:00FFFF">';
	  $incompleted += $datarow["total_price_tax_incl"];	
	}
	else
	{ echo '<tr>';
	  $total += $datarow["total_price_tax_incl"];
	}
    echo '<td><a href="order-search.php?search_txt1='.$datarow["id_order"].'&search_fld1=order+id" target="_blank">'.$datarow["id_order"].'</a></td>';
	echo '<td>'.$datarow["id_shop"].'</td>';
    echo '<td>'.$datarow["firstname"].' '.$datarow["lastname"].'</td>';	
	echo '<td>'.$datarow["id_customer"].'</td>';
	echo '<td>'.$datarow["email"].'</td>';	
	echo '<td>'.$datarow["product_name"].'</td>';
	echo '<td>'.$datarow["product_attribute_id"].'</td>';
    echo '<td>'.$datarow["product_quantity"].'</td>';
    echo '<td>'.$datarow["product_quantity_return"].'</td>';	
	echo '<td>'.$datarow["product_price"].'</td>';
	echo '<td>'.$datarow["reduction_percent"].'</td>';
    echo '<td>'.$datarow["reduction_amount"].'</td>';
	echo '<td>'.$datarow["tax_rate"].'</td>';
    echo '<td>'.$datarow["odate"].'</td>';
    echo '<td>'.$datarow["ddate"].'</td>';	
	echo '<td>'.$datarow["sname"].'</td>';
	$quant = ($datarow["product_quantity"]-$datarow["product_quantity_return"]);
	$netprice = ($datarow["product_price"]-$datarow["reduction_amount"])*(100+$datarow["tax_rate"])*(100-$datarow["reduction_percent"])/10000;
/* netprice*quant won't work as in not finished orders the tax rate field is zero */
	echo '<td>'.number_format($datarow["total_price_tax_incl"],2, '.', '').'</td>';
	echo "</tr
>";

  }
  echo '<tr><td colspan="16" style="text-align:right;">Total completed='.number_format($total,2, '.', '').' + incomplete='.number_format($incompleted,2, '.', '').' makes '.number_format(($total+$incompleted),2, '.', '').'</td></tr>';
  echo "</tbody></table></div>";

?>
