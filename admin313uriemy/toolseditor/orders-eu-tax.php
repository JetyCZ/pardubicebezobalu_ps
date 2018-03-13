<?php 
/* This script - part of Prestools - gives a list of all the order completed within a certain period */
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['id_shop'])) $input['id_shop']="0";
$id_shop = intval($input["id_shop"]);
if(!isset($input['startdate']) || (!check_mysql_date($input['startdate'])))
	$input['startdate']="";
if(!isset($input['enddate']) || (!check_mysql_date($input['enddate'])))
	$input['enddate']="";
if(!isset($input['grouprates']))
	$input['grouprates']="";
if(!isset($input['startrec']) || (trim($input['startrec']) == '')) $input['startrec']="0"; else $input['startrec'] = intval(trim($input['startrec']));
if(!isset($input['numrecs']) || (intval(trim($input['numrecs']) == '0'))) $input['numrecs']="1000";
if(!isset($input['non-eu']) || (intval(trim($input['non-eu']) == '0'))) $input['non-eu']="0";
$eucountrynames = array("Belgium", "Bulgaria", "Croatia", "Cyprus (the Greek part)", "Denmark", "Germany", "Estonia", "Finland", "France", "Greece", "United Kingdom", "Hungary", "Ireland", "Italy", "Latvia", "Lithuania", "Luxembourg", "Malta", "The Netherlands", "Austria", "Poland", "Portugal", "Romania", "Slovenia", "Slovakia", "Spain", "Czech Republic", "Sweden");
/*							3			236			74			76						20			1			86			7		8			9			17				143			26		10		125			131				12			139				13			2		14			15			36			193			37			6		16				18		*/
$eucountries = array("3", "236", "74", "76", "", "20", "1", "86", "7", "8", "9", "17", "143", "26", "10", "125", "131", "	12", "139", "13", "2", "14", "15", "36", "193", "37", "6", "16", "18");

	$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];

$query="select c.value,l.name from ". _DB_PREFIX_."configuration c";
$query .= " LEFT JOIN "._DB_PREFIX_."country_lang l ON c.value=l.id_country AND l.id_lang='".$id_lang."'";
$query .= " WHERE c.name='PS_COUNTRY_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_country_default = $row["value"];
$owncountry = $row["name"];
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Order and tax list for EU tax</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
.datums { text-align: right; }
td+td+td+td { text-align: right; }
td+td+td+td+td+td+td { text-align: left; }
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script type="text/javascript">
var rowsremoved = 0;
function RemoveRow(row)
{ var tblEl = document.getElementById("offTblBdy");
  var trow = document.getElementById("trid"+row).parentNode;
  trow.innerHTML = "<td></td>";
  rowsremoved++;
}

/* check datums before submit */
function check_data()
{ var startdate = search_form.startdate.value;
  var enddate = search_form.enddate.value;
  if(startdate != "")
  { var sd = new Date(startdate);
	if((!isValidDate(sd)) || (!isValidDate2(startdate)))
	{ alert("invalid startdate! Format must be yyyy-mm-dd.");
	  return false;
	}
  }
  if(enddate != "")
  { var ed = new Date(enddate);
	if((!isValidDate(ed)) || (!isValidDate2(enddate)))
	{ alert("invalid enddate! Format must be yyyy-mm-dd.");
	  return false;
	}
  } 
  if(sd.getTime() >= ed.getTime())
  { alert("Enddate must be after startdate!");
	return false;
  }
  return true;
}

function isValidDate(d) 
{ if(!(d.getTime() === d.getTime()))
	return false; /* NaN === NaN returns false */
  return true;
}

function isValidDate2(datestring)
{ var parts = datestring.split("-");
  if((parseInt(parts[0])<2000)||(parseInt(parts[0])>2100)||(parseInt(parts[1])>12)|| (parseInt(parts[2])>31)) /* check for valid dates that don't respect the yyyy-mm-dd format */
	  return false;
  return true;
}
</script>
</head><body>
<?php
print_menubar();
echo '<h1>Prestashop Orders in a period for EU Tax</h1>';

echo '<form name="search_form" method="get" onsubmit="return check_data()">
Period (yyyy-mm-dd): <input size=7 name=startdate value="'.$input['startdate'].'" class="datums"> till <input size=7 name=enddate value="'.$input['enddate'].'" class="datums"> &nbsp;';

/* making shop block */
	$query= "select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	echo " &nbsp; Shop: <select name=id_shop><option value=0>All shops</option>";
	while ($shop=mysqli_fetch_array($res)) 
	{   $selected = "";
	    if($shop["id_shop"] == $id_shop) $selected = " selected";
        echo '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
	}
	if($input["grouprates"]=="on") $checked=" checked"; else $checked="";
    echo '</select> &nbsp; Group taxrates <input type="checkbox" name="grouprates" '.$checked.'> &nbsp; <input type="submit"><p>';
//	$checked = "";
//	 echo '<br/>Include non-eu countries: <input name="non-eu" value="1" '.$checked.' type="checkbox" />';
//	echo '<br/>Startrec: <input size=3 name=startrec value="'.$input['startrec'].'">';
//	echo ' &nbsp &nbsp; Number of recs: <input size=3 name=numrecs value="'.$input['numrecs'].'"> &nbsp; <input type=submit><p/>';

	echo "For EU countries orders with and without VAT number are mentioned seperately as those with VAT number don't have VAT.<br/>";
	$order_states = array(2,3,4,5);
	echo "Orders with the following states have been included: ";
	$comma = "";
	foreach($order_states AS $order_state)
	{ $osquery="select name from ". _DB_PREFIX_."order_state_lang WHERE id_order_state='".$order_state."'";
	  $osres=dbquery($osquery);
	  $osrow = mysqli_fetch_array($osres);
	  echo $comma.$osrow['name'];
	  $comma = ", ";
	}
	
	echo "<br/><br/>Orders with an invoice date within the follow period have been included: startdate=".$input["startdate"]." - enddate=".$input["enddate"]." for ";
	if($id_shop == 0)
		echo "all shops";
	else 
		echo "shop nr. ".$id_shop;
	
$query="SELECT a.id_country, name AS countryname, total_paid_tax_excl, total_paid_tax_incl, id_order, c.firstname, c.lastname, invoice_date";
$query .= ", b.vat_number, ROUND(((total_paid_tax_incl-total_paid_tax_excl)/total_paid_tax_excl)*100,1) AS taxrate";
$query .= ", (LENGTH(b.vat_number) != 0) AS isCompany FROM ". _DB_PREFIX_."orders o";
 $query .= " LEFT JOIN ". _DB_PREFIX_."customer c ON o.id_customer = c.id_customer";
$query .= " LEFT JOIN ". _DB_PREFIX_."address a ON o.id_address_delivery = a.id_address";
$query .= " LEFT JOIN ". _DB_PREFIX_."country_lang cl ON cl.id_country = a.id_country AND cl.id_lang='".$id_lang."'";
$query .= " LEFT JOIN ". _DB_PREFIX_."address b ON o.id_address_invoice = b.id_address";
$query .= " WHERE o.current_state IN (".implode(",",$order_states).")";
if($id_shop !=0)
	$query .= " AND o.id_shop=".$id_shop;
if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) >= TO_DAYS('".mysqli_real_escape_string($conn, $input['startdate'])."')";
if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) <= TO_DAYS('".mysqli_real_escape_string($conn, $input['enddate'])."')";
$query .= " ORDER BY countryname, taxrate, id_order";
$res=dbquery($query);

$infofields = array("","id","Country","Sales/incl","Sales/excl","Tax","Pct", "Orders");
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
$sumquantity = $sumtotal = $sumtotalex = 0;
echo "</tr></thead><tbody id='offTblBdy'>";
$x=0;
$incl = $excl = $taxes = $euincl = $euexcl  = $eutaxes = $exincl = $exexcl  = $extaxes = $ownincl = $ownexcl  = $owntaxes = 0;
$oldcountry = 0;
$oldtaxrate = -1;
$total_incl = $total_excl = 0;
$myorders = "";
while($datarow = mysqli_fetch_array($res))
  { if(($input["grouprates"]=="on") && ($datarow['taxrate'] > 0)) /* if this switch is on we see all different taxrates separately; if it is off there are only two lines: all orders with taxes and all orders without */
	   $datarow["taxrate"] = 1;
	if(($datarow['id_country'] != $oldcountry) || ($datarow['taxrate'] != $oldtaxrate))
	{ if($oldcountry != 0) 
      { print_line($oldrow, $total_incl, $total_excl, $myorders);
	  }
	  $total_incl = $total_excl = 0;
	  $myorders = "";
	  $oldcountry = $datarow['id_country'];
	  $oldtaxrate = $datarow['taxrate'];	  
	}
	$incl += $datarow["total_paid_tax_incl"];
	$excl += $datarow["total_paid_tax_excl"];
	$tax = $datarow["total_paid_tax_incl"] - $datarow["total_paid_tax_excl"];
    $taxes += $tax;
	$total_incl += $datarow["total_paid_tax_incl"];
	$total_excl += $datarow["total_paid_tax_excl"];	
    if(in_array($datarow["id_country"], $eucountries) && ($datarow["id_country"] != $id_country_default))
	{ 	$euincl += $datarow["total_paid_tax_incl"];
		$euexcl += $datarow["total_paid_tax_excl"];
		$eutaxes += $tax;
	}
	else if($datarow["id_country"] == $id_country_default)
	{ 	$ownincl += $datarow["total_paid_tax_incl"];
		$ownexcl += $datarow["total_paid_tax_excl"];
		$owntaxes += $tax;
		$owncountry = $datarow["countryname"];
	}
	else
	{ 	$exincl += $datarow["total_paid_tax_incl"];
		$exexcl += $datarow["total_paid_tax_excl"];
		$extaxes += $tax;
	}
	if(strlen($myorders) > 0) $myorders .= ",";
	$myorders .= '<a title="'.$datarow['firstname'].' '.$datarow['lastname'].' - '.$datarow['countryname'].' - '.$datarow['vat_number'].' : '.number_format($datarow['total_paid_tax_incl'],2).' / '.number_format($datarow['total_paid_tax_excl'],2).' - '.substr($datarow['invoice_date'],0,10).' '.'" href="#" onclick="return false;">'.$datarow["id_order"].'</a>';
	$oldrow = $datarow;
  }
  if(isset($oldrow))
    print_line($oldrow, $total_incl, $total_excl, $myorders);
  else
	echo "<p><b>No sales in this period!</b><p>";
  echo "</tbody></table></div>";
  
  echo "<table border=1;><tr><th colspan=4>Totals</th></tr>";
  echo "<tr><th></th><th>Sales/incl</th><th>Sales/excl</th><th>Tax</th></tr>";
  echo "<tr><td>".$owncountry."</td><td>".number_format($ownincl,2)."</td><td>".number_format($ownexcl,2)."</td><td>".number_format($owntaxes,2)."</td></tr>";
  echo "<tr><td>Within EU</td><td>".number_format($euincl,2)."</td><td>".number_format($euexcl,2)."</td><td>".number_format($eutaxes,2)."</td></tr>";
  echo "<tr><td>Outside EU</td><td>".number_format($exincl,2)."</td><td>".number_format($exexcl,2)."</td><td>".number_format($extaxes,2)."</td></tr>";
  echo "<tr><td>Total</td><td>".number_format($incl,2)."</td><td>".number_format($excl,2)."</td><td>".number_format($taxes,2)."</td></tr>";
  echo "</table>";
  
  function print_line($datarow, $total_incl, $total_excl, $myorders)
  { global $eucountries, $id_country_default, $x;
    $bgcolor = "";
    if(!in_array($datarow["id_country"], $eucountries))
      $bgcolor = 'style="background-color: yellow"';
	if($datarow["id_country"] == $id_country_default)
       $bgcolor = 'style="background-color: #EFCCEF"';	
    echo '<tr '.$bgcolor.'>';
	echo '<td id="trid'.$x.'"><input type="button" value="X" style="width:4px" onclick="RemoveRow('.$x.')" title="Hide line from display" /></td>';
	echo '<td>'.$datarow["id_country"].'</td>';
	echo '<td>'.$datarow["countryname"].'</td>';
	echo '<td>'.number_format($total_incl,2).'</td>';
    echo '<td>'.number_format($total_excl,2).'</td>';
	$tax = $total_incl- $total_excl;
    echo '<td>'.number_format($tax,2).'</td>';
	if($total_excl != 0)
	  echo '<td>'.number_format(($tax*100)/$total_excl,2).'</td>';
    else 
	  echo '<td>0</td>';
	if($datarow["id_country"] == $id_country_default)
	  echo "<td></td>";
	else	
	  echo '<td>'.$myorders.'</td>';
	echo '<tr
	  >';
	$x++;
  }
 
?>
