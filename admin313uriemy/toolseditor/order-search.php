<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['term'])) $input['term']="";
if(!isset($input['fields'])) $input['fields']=array();
if(!isset($input['search_txt1'])) $input['search_txt1']="";
$search_txt1 = mysqli_real_escape_string($conn, $input['search_txt1']);
if(!isset($input['search_txt2'])) $input['search_txt2']="";
$search_txt2 = mysqli_real_escape_string($conn, $input['search_txt2']);
if(!isset($input['search_txt3'])) $input['search_txt3']="";
$search_txt3 = mysqli_real_escape_string($conn, $input['search_txt3']);
if(!isset($input['search_fld1'])) $input['search_fld1']="";
$search_fld1 =  $input['search_fld1'];
if(!isset($input['search_fld2'])) $input['search_fld2']="";
$search_fld2 =  $input['search_fld2'];
if(!isset($input['search_fld3'])) $input['search_fld3']="";
$search_fld3 =  $input['search_fld3'];
if(!isset($input['startrec']) || (trim($input['startrec']) == '')) $input['startrec']="0";
if(!isset($input['numrecs'])) $input['numrecs']="100";
if(!isset($input['carrier'])) $carrier = "All carriers"; else $carrier = mysqli_real_escape_string($conn, $input['carrier']);
if(!isset($input['payoption'])) $payoption = "All payoptions"; else $payoption = mysqli_real_escape_string($conn, $input['payoption']);
if(!isset($input['startdate']) || (!check_mysql_date($input['startdate']))) $startdate=""; else $startdate = $input['startdate'];
if(!isset($input['enddate']) || (!check_mysql_date($input['enddate']))) $enddate=""; else $enddate = $input['enddate'];
if(!isset($input['newcust'])) $input['newcust']="all";
if(!isset($input['id_lang'])) $input['id_lang']="";
$id_lang = $input['id_lang'];
if(!isset($input['id_shop'])) $input['id_shop']="0";
$id_shop = $input['id_shop'];
if((!isset($input['paystatus'])) || ($input['paystatus'] == "all")) {$paystatus = "all";} else {$paystatus = $input['paystatus'];}
if(isset($input['sortorder']) && in_array($input['sortorder'], array("date","amount","customer","best customer"))) $sortorder = $input['sortorder']; else $sortorder="date";
if((!isset($input['rising'])) || ($input['rising'] == "DESC") || ($sortorder=="date") || ($sortorder=="amount")) {$rising = "DESC";} else {$rising = "ASC";}

echo 
'<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Order Search</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style type="text/css">
body {font-family:arial; font-size:13px}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script>
function show_products(row, id_order)
{ if(id_order == 0)
  { var myspan = document.getElementById("prodspan"+row);
    myspan.style.display = "inline";
    var mylink = document.getElementById("prodlink"+row);
    mylink.innerHTML = "<a href=\"#\" onclick=\"hide_products("+row+"); return false;\">Hide products</a>";
  }
  else
    LoadPage("order-search2.php?row="+row+"&id_order="+id_order,dynamo3);
}

function hide_products(row)
{ var myspan = document.getElementById("prodspan"+row);
  myspan.style.display = "none";
  
  var mylink = document.getElementById("prodlink"+row);
  mylink.innerHTML = "<a href=\"#\" onclick=\"show_products("+row+", 0); return false;\">Show products</a>";
}

function LoadPage(url, callback)
{ var request =  new XMLHttpRequest("");
  request.open("GET", url, true); /* delaypage must be a global var; changed from POST to GET */
  request.onreadystatechange = function() 
  { if (request.readyState == 4 && request.status == 404) /* done = 4, ok = 200 */
	alert("ERROR "+request.status+" "+request.responseText) 
    if (request.readyState == 4 && request.status == 200) /* done = 4, ok = 200 */
    { if (request.responseText) 
        callback(request.responseText);
    };
  }
  request.send(null);
}

function dynamo3(data)  /* get product name */
{ var lines = data.split("\n");
  var row = lines[0].trim();
  var row = parseInt(row);
 
  var myspan = document.getElementById("prodspan"+row);
  myspan.innerHTML = data.substring(lines[0].length+1);
  
  var mylink = document.getElementById("prodlink"+row);
  mylink.innerHTML = "<a href=\"#\" onclick=\"hide_products("+row+"); return false;\">Hide products</a>";
}

function check_date()
{ var flds = [];
  flds[0] = searchform.startdate.value;
  flds[1] = searchform.enddate.value;
  for(var i=0; i<=1; i++)
  { if(flds[i] == "") continue;
    var parts = flds[i].split("-");
	if((parts.length != 3) || (parts[0]<1990) || (parts[0]>2030) || (parts[1]<=0) || (parts[1]>12) || (parts[2]<=0) || (parts[2]>31))
	{ alert("Invalid date");
	  return false;
	}	
  }
  if(searchform.csvexport.checked)
  { searchform.action="order-search-csv.php";
    searchform.target="_blank";
    searchform.submit();
    searchform.target = "";
	searchform.action = "order-search.php";
	return false;
  }
  return true;
}

function sortorder_change()
{ var num = searchform.sortorder.selectedIndex;
  var val = searchform.sortorder.options[num].value;
  if(val=="customer") searchform.rising.selectedIndex = 0;
  else searchform.rising.selectedIndex = 1;
}
</script>
</head>
<body>';
print_menubar();

echo '<table><tr><td style="width:80%"><center><a href="order-search.php" style="text-decoration:none;"><b><font size="+2">Order Search</font></b></a></center>';
echo '<p>Find orders by searching in customer data, product data and date (<i>dates as yyyy-mm-dd</i>)';
echo '<br>"main fields" covers everything except date before, date after, country, product id and product name"';
echo '<br>When you select Sort By Customer and you have a Nr of recs limit the most recent orders of the period will be processed.';
echo '<br>With Sort By Customer the communication with that customer is shown - but only within the selected period.';
echo '</td><td><iframe name=tank width="230" height="88"></iframe></td></tr></table>';

echo '<form name="searchform" onsubmit="return check_date();">';
echo '<table class="ordersearch" style="display:inline-block">';

if($id_lang == "")
	$id_lang = get_configuration_value('PS_LANG_DEFAULT');
echo '<td>Language: <select name="id_lang" value="'.$id_lang.'">';
$query = "SELECT id_lang, name FROM ". _DB_PREFIX_."lang";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ $selected = '';
  if ($row['id_lang']==$id_lang) 
    $selected=' selected="selected" ';
  echo '<option  value="'.$row['id_lang'].'" '.$selected.'>'.$row['name'].'</option>';
}
echo '</select></td>';

echo '<td>Shop: <select name="id_shop" value="'.$id_shop.'"><option value=0>all shops</option>';
$query = "SELECT id_shop, name FROM ". _DB_PREFIX_."shop";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ $selected = "";
  if ($row['id_shop'] == $id_shop) 
    $selected=' selected="selected" ';
  echo '<option  value="'.$row['id_shop'].'" '.$selected.'>'.$row['name'].'</option>';
}
echo '</select></td>';

$default_country = get_configuration_value('PS_COUNTRY_DEFAULT');

echo '<td><input type=submit></td></tr>';

echo '<tr><td colspan=2>Period (yyyy-mm-dd): <input size=5 name=startdate value='.$startdate.'> till <input size=5 name=enddate value='.$enddate.'>'; 
$checked = "";
if($rising == 'DESC')
  $checked = "selected";
echo ' &nbsp; &nbsp; <SELECT name=rising><option>ASC</option><option '.$checked.'>DESC</option></select></td>';
echo '<td><input type=checkbox name=csvexport> Export as CSV</td></tr>';

echo '<tr><td colspan=2><input name="newcust" type=radio value="all"';
if($input['newcust'] == "all") echo "checked";
echo '> all customers <input name="newcust" type=radio value="new"';
if($input['newcust'] == "new") echo "checked";
echo '> new only <input name="newcust" type=radio value="old"';
if($input['newcust'] == "old") echo "checked";
echo '> old only</td><td>Separator &nbsp;<input type="radio" name="separator" value="semicolon" 
checked>; <input type="radio" name="separator" value="comma"></td></tr>';
$sfields = array("main fields", "order id","order reference", "order date", "invoice number","delivery number"
, "customer name", "customer id","customer address","customer country","customer phone","customer email","product id", "product name");
echo '<tr><td colspan=2>Startrec: <input size=3 name=startrec value="'.$input['startrec'].'"> &nbsp; ';
echo ' &nbsp; Nr of recs: <input size=3 name=numrecs value="'.$input['numrecs'].'"> &nbsp; &nbsp; ';
if($sortorder == "amount") $selected = " selected"; else $selected = "";
echo 'Sort by <select name=sortorder onchange="sortorder_change()"><option>date</option><option '.$selected.'>amount</option>';
if($sortorder == "customer") $selected = " selected"; else $selected = "";
echo '<option '.$selected.'>customer</option>';
if($sortorder == "best customer") $selected = " selected"; else $selected = "";
echo '<option '.$selected.'>best customer</option></select></td>';

$checked = "";
if(isset($input["verbose"])) $checked = "checked";
echo '<td><input type=checkbox name=verbose '.$checked.'> verbose</td></tr>';
echo '<tr><td colspan=3><select name=carrier><option>All carriers</option>';
$cquery = "SELECT DISTINCT c.name FROM ". _DB_PREFIX_."order_carrier oc";
$cquery .= " LEFT JOIN ". _DB_PREFIX_."carrier c on oc.id_carrier=c.id_carrier";
$cquery .= " ORDER BY name";
$cres = dbquery($cquery);
while($crow = mysqli_fetch_array($cres))
{ if($crow['name'] == $carrier) $selected = "selected"; else $selected = "";
  echo '<option '.$selected.'>'.$crow['name'].'</option>';
}
echo '</select> &nbsp; &nbsp; &nbsp; ';
echo '<select name=payoption><option>All payoptions</option>';
$cquery = "SELECT DISTINCT payment FROM ". _DB_PREFIX_."orders ORDER BY payment";
$cres = dbquery($cquery);
while($crow = mysqli_fetch_array($cres))
{ if($crow['payment'] == $payoption) $selected = "selected"; else $selected = "";
  echo '<option '.$selected.'>'.$crow['payment'].'</option>';
}
echo '</select>';
if($paystatus == 'valid') $checked = "selected"; else $checked = "";
echo ' &nbsp; &nbsp; <SELECT name=paystatus><option>all</option><option '.$checked.'>valid</option>';
if($paystatus == 'not paid') $checked = "selected"; else $checked = "";
echo '<option '.$checked.'>not paid</option>';
echo '</select></td></tr>';
  echo '<tr><td>Find <input name="search_txt1" type="text" value="'.$search_txt1.'" size="20"  /><br>
in <select name="search_fld1" style="width:10em">';
  foreach($sfields as $sfield)
  { $selected = '';
    if ($input["search_fld1"] == $sfield) 
      $selected=' selected="selected" ';
    echo '<option '.$selected.'>'.$sfield.'</option>';
  }
  echo '</select></td>';

  echo '<td>and <input name="search_txt2" type="text" value="'.$search_txt2.'" size="20"  /><br>
in <select name="search_fld2" style="width:10em">';
  foreach($sfields as $sfield)
  { $selected = '';
    if ($input["search_fld2"] == $sfield) 
      $selected=' selected="selected" ';
    echo '<option '.$selected.'>'.$sfield.'</option>';
  }
  echo '</select>';
  echo '</select></td>';

  echo '<td>and <input name="search_txt3" type="text" value="'.$search_txt3.'" size="20"  /><br>
in <select name="search_fld3" style="width:10em">';
  foreach($sfields as $sfield)
  { $selected = '';
    if ($input["search_fld3"] == $sfield) 
      $selected=' selected="selected" ';
    echo '<option '.$selected.'>'.$sfield.'</option>';
  }
  echo '</select></td>';
  echo '</tr></table> &nbsp; ';
  echo '<table class="ordersearch" style="display:inline-block;"><tr><td style="text-align:left"><b>Order statuses</b><br>';
  $query = "SELECT DISTINCT current_state FROM ". _DB_PREFIX_."orders ORDER BY current_state";
  $res = dbquery($query);
  $orderstates = array();
  while($row = mysqli_fetch_array($res))
  { $squery = "SELECT name, id_order_state FROM ". _DB_PREFIX_."order_state_lang WHERE id_order_state=".$row['current_state']." AND id_lang=".$id_lang;
	$sres = dbquery($squery);
	$srow = mysqli_fetch_array($sres);
	$currentstate = $row['current_state'];
	echo "<input type=checkbox name=orderstate[".$row['current_state']."] ";
	if(!isset($_GET["orderstate"]) || isset($_GET["orderstate"][$currentstate]))
	{	echo "checked";
		$orderstates[] = $currentstate;
	}
	echo "> ";
	if($srow)
		echo $srow["name"]."<br>";
	else
		echo "orderstate ".$row['current_state']."<br>";
  }
  echo '</td></tr></table>';
  echo '</form>';
  
/*  total_discounts,total_discounts_tax_incl,total_discounts_tax_excl,total_paid,total_paid_tax_incl,total_paid_tax_excl,
 * total_paid_real,total_products,total_products_wt,total_shipping,total_shipping_tax_incl,total_shipping_tax_excl,
 * carrier_tax_rate,total_wrapping,total_wrapping_tax_incl,total_wrapping_tax_excl
 */
  
  $qfields = "o.id_order,o.date_add,o.invoice_number,o.delivery_number,o.reference,o.id_customer,o.id_address_delivery";
  $qfields .= ",o.id_address_invoice,o.shipping_number,o.total_paid,o.total_paid_tax_excl,o.payment";
  $qfields .= ",total_products_wt,total_shipping,total_wrapping,s.name AS order_state,o.current_state";
  $qfields .= ",total_products,total_discounts_tax_excl,total_discounts_tax_incl, o.invoice_date,c.firstname,c.lastname,c.company";
  $qfields .= ",a1.firstname AS firstname1,a1.lastname AS lastname1,a1.company AS company1,a1.address1,a1.address2,a1.postcode,a1.city,a1.id_country,a1.phone,a1.phone_mobile";
  $qfields .= ",a2.firstname AS firstname2,a2.lastname AS lastname2,a2.company AS company2,a2.address1 AS address12,a2.address2 AS address22,a2.postcode AS postcode2,a2.city AS city2,a2.id_country AS id_country2,a2.phone AS phone2,a2.phone_mobile AS phone_mobile2";  
  $qfields .= ",cl1.name AS country1, cl2.name AS country2, c.email, GROUP_CONCAT(DISTINCT(ct.id_customer_thread)) AS customer_threads";
  $qfields .= ', IF((SELECT so.id_order FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = c.id_customer AND so.id_order < o.id_order LIMIT 1) > 0, 0, 1) as newcust'; /* this line is copied from Prestashop */
  $qfields .= ", GROUP_CONCAT(DISTINCT(ca.name)) AS carriers";
  $qbody = " FROM ". _DB_PREFIX_."orders o";
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."customer c ON c.id_customer=o.id_customer";
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."customer_thread ct ON ct.id_order=o.id_order"; 
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."address a1 ON o.id_address_invoice=a1.id_address";
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."country_lang cl1 ON cl1.id_country=a1.id_country AND cl1.id_lang=".$id_lang; 
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."address a2 ON o.id_address_delivery=a2.id_address"; 
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."country_lang cl2 ON cl2.id_country=a2.id_country AND cl2.id_lang=".$id_lang; 
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."order_state_lang s ON o.current_state=s.id_order_state AND s.id_lang=".$id_lang; 
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."order_carrier oc ON oc.id_order=o.id_order";
  $qbody .= " LEFT JOIN ". _DB_PREFIX_."carrier ca ON oc.id_carrier=ca.id_carrier";

  $qconditions = " WHERE 1";
  $product_searched = false;
  for($i=1; $i<=3; $i++)  /* search_txt1, search_txt2 and search_txt3 */
  { $stext = $GLOBALS["search_txt".$i];	
    if($stext == "") continue;
    $sfield = $GLOBALS["search_fld".$i];
	$subconditions = array();
	if(($sfield == "order id") || ($sfield == "main fields"))
	  $subconditions[] = "o.id_order='".$stext."'";
    if(($sfield == "main fields") && (strlen($stext)==10)) /* now check if it is a valid date */
    { $parts = explode("-",$stext);
      if(checkdate($parts[1],$parts[2],$parts[0]))
		  $subconditions[] = "DATE(o.date_add) = '".$stext."'";	  
	}
	if($sfield == "order date")
	  $subconditions[] = "DATE(o.date_add) = '".$stext."'";
	if(($sfield == "invoice number") || ($sfield == "main fields"))
	 	$subconditions[] = "CAST(o.invoice_number AS char)='".$stext."'"; /* without CAST 0 matches with any string */
	if(($sfield == "order reference") || ($sfield == "main fields"))
	 	$subconditions[] = "o.reference='".$stext."'";
	if(($sfield == "delivery number") || ($sfield == "main fields"))
	 	$subconditions[] = "CAST(o.delivery_number AS char)='".$stext."'"; /* without CAST 0 matches with any string */
 	if(($sfield == "customer email") || ($sfield == "main fields"))
	 	$subconditions[] = "c.email='".$stext."'";
	if(($sfield == "customer name") || ($sfield == "main fields"))
	 	$subconditions[] = "a1.firstname LIKE '%".$stext."%' OR a1.lastname LIKE '%".$stext."%' OR a2.firstname LIKE '%".$stext."%' OR a2.lastname LIKE '%".$stext."%' OR a1.company LIKE '%".$stext."%' OR a2.company LIKE '%".$stext."%'";
	if(($sfield == "customer id") || ($sfield == "main fields"))
	 	$subconditions[] = "c.id_customer='".$stext."'";
	if($sfield == "customer country")
	 	$subconditions[] = "cl1.name LIKE '%".$stext."%' OR cl2.name LIKE '%".$stext."%'";
 	if(($sfield == "customer address") || ($sfield == "main fields"))
	{ $tmp = "a1.address1 LIKE '%".$stext."%' OR a1.address2 LIKE '%".$stext."%' OR a1.postcode LIKE '%".$stext."%' OR a1.city LIKE '%".$stext."%'";
      $tmp .= " OR a2.address1 LIKE '%".$stext."%' OR a2.address2 LIKE '%".$stext."%' OR a2.postcode LIKE '%".$stext."%' OR a2.city LIKE '%".$stext."%'";
	  $subconditions[] = $tmp;
    }
 	if(($sfield == "customer phone") || ($sfield == "main fields"))
	 	$subconditions[] = "a1.phone LIKE '%".$stext."%' OR a1.phone_mobile LIKE '%".$stext."%' OR a2.phone LIKE '%".$stext."%' OR a2.phone_mobile LIKE '%".$stext."%'";
	if($sfield == "product id")
		$subconditions[] = "EXISTS (SELECT NULL FROM ". _DB_PREFIX_."order_detail od".$i." WHERE o.id_order=od".$i.".id_order AND od".$i.".product_id='".$stext."')";
	if($sfield == "product name")
		$subconditions[] = "EXISTS (SELECT NULL FROM ". _DB_PREFIX_."order_detail od".$i." WHERE o.id_order=od".$i.".id_order AND od".$i.".product_name LIKE '%".$stext."%')";
	
	$qconditions .= " AND (".implode(" OR ",$subconditions).")";
  }
//  $qconditions .= " AND o.valid=0";
  if(isset($_GET["orderstate"]))
	  $qconditions .= " AND (o.current_state IN (".implode(",",$orderstates)."))";
  if($startdate != "")
	  $qconditions .= " AND o.date_add>='".$startdate."'"; 
  if($enddate != "")
	  $qconditions .= " AND o.date_add<='".$enddate."'";
  if($payoption != "All payoptions")
	  $qconditions .= " AND o.payment='".mysqli_real_escape_string($conn, $payoption)."'";
  if($paystatus == "valid")
	  $qconditions .= " AND o.valid=1";
  else if($paystatus == "not paid")
	  $qconditions .= " AND o.valid=0";  
  if($carrier != "All carriers")
  { $cquery = "SELECT GROUP_CONCAT(id_carrier SEPARATOR '\',\'') AS ids FROM ". _DB_PREFIX_."carrier";
	$cquery .= " WHERE name='".$carrier."' GROUP BY name";
	$cres = dbquery($cquery);
	$crow = mysqli_fetch_array($cres);
	$qconditions .= " AND oc.id_carrier IN ('".$crow['ids']."')";  
  }
  $having = "";
  if($input["newcust"] == "new")
	  $having .= " HAVING newcust=1";
  else if($input["newcust"] == "old")
	  $having .= " HAVING newcust=0";
  if(($qconditions == " WHERE 1") && !isset($_GET["orderstate"])) return; /* we don't want to list all orders */
  /* we group by id_order because an order can have more than one carrier */
  $query = "SELECT SQL_CALC_FOUND_ROWS ".$qfields.$qbody.$qconditions." GROUP BY id_order ";

  $srtorder = "id_order";
  if($sortorder == "amount") $srtorder = "total_products";
  if($sortorder == "customer") $srtorder = "c.lastname,c.firstname,c.company,c.id_customer"; 
  $query .= $having." ORDER BY ".$srtorder." ".$rising." LIMIT ".$input['startrec'].",".$input['numrecs'];
//  echo $query."<br>";
  dbquery("SET sql_mode = ''"); /* disable Mysql strict mode (version 5.7.4 and higher) so that '0000-00-00' dates don't cause an error; using IGNORE (like INSERT IGNORE) would be an alternative */
  if(($sortorder == "date") || ($sortorder == "amount"))
	  $res = dbquery($query);
  else 
	  $res = dbquery("CREATE TEMPORARY TABLE "._DB_PREFIX_."ordertemp (".$query.")");
  $res2=dbquery("SELECT FOUND_ROWS() AS foundrows");
  $row2 = mysqli_fetch_array($res2);
  $numrecs2 = $row2['foundrows'];
  $total_excl = $total_incl = $total_shipping = 0;
  $total_excl_new = $total_incl_new = $total_shipping_new = $newrecs = 0;
  if(($sortorder == "customer") || ($sortorder == "best customer"))
  { $xquery = "SELECT NULL FROM "._DB_PREFIX_."ordertemp";
	$res = dbquery($xquery); 
  }
  $numrecs = mysqli_num_rows($res);
  
  echo "<br>Your search delivered ".$numrecs2." records. ".$numrecs." displayed. ";
  
  if(($sortorder == "date") || ($sortorder == "amount"))
  { $x=0;
    while ($row=mysqli_fetch_array($res))
    { $total_incl += floatval($row["total_paid"]);
      $total_excl += floatval($row["total_paid_tax_excl"]);
	  $total_shipping += floatval($row["total_shipping"]);
	  if($row["newcust"] == "1")
	  { $newrecs++;
		$total_incl_new += floatval($row["total_paid"]);
		$total_excl_new += floatval($row["total_paid_tax_excl"]);
		$total_shipping_new += floatval($row["total_shipping"]);
	  }

	  echo '<table class="orderlister"><tr><td>Order<br><a href=order-edit.php?id_order='.$row["id_order"].' target=_blank>'.$row["id_order"].'</a></td><td>Reference<br>'.$row["reference"].'</td>';
	  echo '<td>Delivery<br>'.$row["delivery_number"]."</td><td>Invoice<br>".$row["invoice_number"]."</td>";
	  echo '<td>Shipping<br>'.$row["shipping_number"].'</td><td>Cust id<br>'.$row["id_customer"].'</td>';	
	  echo '<td>'.$row["firstname"]." ".$row["lastname"]."<br>".$row["company"]."</td>";
	  $dateparts = explode(" ",$row["date_add"]);
	  echo "<td>".$dateparts[0]."<br/>".date('D', strtotime($dateparts[0]))." ".$dateparts[1]."</td>";
	  echo '<td>Tot '.number_format($row["total_paid"],2)."<br>Ex ".number_format($row["total_paid_tax_excl"],2)."</td>";
	  echo '<td>ProdsWT<br>'.number_format($row["total_products_wt"],2).'</td><td>Shipping<br>'.number_format($row["total_shipping"],2).'</td>';
	  echo '<td>Wrapping<br>'.number_format($row["total_wrapping"],2).'</td>';
	  echo '<td>'.$row["order_state"].'<br>'.$row["payment"].'</td>';	
	  echo '<td>';
	  if($row["newcust"]=="1") echo "new"; else echo "0";
	  echo '</td><td>'.$row["carriers"].'</td></tr><tr>';
	  echo '<td colspan=15 style="text-align:center">';
	  if($row["id_address_delivery"] != $row["id_address_invoice"])
		echo 'INV: ';
	  if(($row['firstname'] != $row['firstname1']) || ($row['lastname'] != $row['lastname1']) || ($row['company'] != $row['company1']))
		echo $row["firstname1"]." ".$row["lastname1"]." ".$row["company1"]." ";
	  echo $row["address1"]." ".$row["address2"]." ".$row["postcode"]." ".$row["city"];
	  if($row["id_country"]!=$default_country)
		echo " ".get_country($row["id_country"]);
	  echo " ".$row["phone"]." ".$row["phone_mobile"].' '.$row['email'].'</td></tr>';
	  if($row["id_address_delivery"] != $row["id_address_invoice"])
	  { echo '<tr><td colspan=15 style="text-align:center">SHIP: ';
	    if(($row['firstname'] != $row['firstname2']) || ($row['lastname'] != $row['lastname2']) || ($row['company'] != $row['company2']))
		  echo $row["firstname2"]." ".$row["lastname2"]." ".$row["company2"]." ";
	    echo $row["address12"]." ".$row["address22"]." ";
	    echo $row["postcode2"]." ".$row["city2"];

	    if($row["id_country2"]!=$default_country)
		  echo " ".get_country($row["id_country2"]);
	    echo " ".$row["phone2"]." ".$row["phone_mobile2"].'</td></tr>';
	  }
	  /* now check for other addresses that weren't allocated for this order to invoice or delivery */
	  $adquery = 'SELECT * FROM `'._DB_PREFIX_.'address`';
	  $adquery .= " WHERE id_customer=".$row['id_customer']." AND id_address !=".$row["id_address_invoice"];
	  $adquery .= " AND id_address !=".$row["id_address_delivery"];
      $adres=dbquery($adquery);
      while ($adrow=mysqli_fetch_array($adres))
	  { echo '<tr><td colspan=15 style="text-align:center">OTHER-'.$adrow["alias"].': ';
	    if(($row['firstname'] != $adrow['firstname']) || ($row['lastname'] != $adrow['lastname']) || ($row['company'] != $adrow['company']))
		  echo $adrow["firstname"]." ".$adrow["lastname"]." ".$adrow["company"]." ";
	    echo $adrow["address1"]." ".$adrow["address2"]." ";
	    echo $adrow["postcode"]." ".$adrow["city"];
	    if($adrow["id_country"]!=$default_country)
		  echo " ".get_country($adrow["id_country"]);
	    echo " ".$adrow["phone"]." ".$adrow["phone_mobile"].'</td></tr>';
	  }
	  if($row["customer_threads"] != NULL) /* if there are messages */
	  { echo '<tr><td colspan=15><b>Message: </b>';
	    $tquery = "SELECT cm.*,ct.id_order FROM ". _DB_PREFIX_."customer_message cm";
		$tquery .= " LEFT JOIN ". _DB_PREFIX_."customer_thread ct ON cm.id_customer_thread=ct.id_customer_thread";
	    $tquery .= " WHERE cm.id_customer_thread IN (".$row["customer_threads"].")";
		if($startdate != "")
			$tquery .= " AND (date_add>='".$startdate."' OR date_upd>='".$startdate."')"; 
		if($enddate != "")
			$tquery .= " AND (date_add<='".$enddate."' OR date_upd<='".$enddate."')"; 
	    $tres=dbquery($tquery);
	    $messages = "";
	    while ($trow=mysqli_fetch_array($tres))
		{ if($trow["id_order"] != 0)
			$messages .= "[".$trow["id_order"]."] ";
		  $messages .= substr($trow["date_add"],0,10)."-".$trow["message"]."<br/>";
		}
	    echo $messages.'</td></tr>';
      }
	  echo '<tr><td colspan=15 style="text-align:center"><span id="prodspan'.$x.'"></span>';
	  echo '<span id="prodlink'.$x.'"><a href="#" title="'.$x.'" onclick="show_products(\''.$x.'\',\''.$row["id_order"].'\'); return false;">Show products</a></span></td></table>';
	  echo '</table>';
	  $x++;
    } 
  }		/* end if sortorder is date or amount */
  else 	/* if sortorder is customer or best customer */
  { $x = 0;
    $aquery = "SELECT firstname, lastname, company, id_customer, email, SUM(total_products-total_discounts_tax_excl) AS customer_products, SUM(total_paid_tax_excl) AS customer_excl,";
    $aquery .= " SUM(total_shipping) AS customer_shipping, SUM(total_paid) AS customer_incl,";
    $aquery .= " COUNT(*) AS num_orders, GROUP_CONCAT(',',id_order) AS orders, customer_threads";
	$aquery .= " FROM "._DB_PREFIX_."ordertemp";
	$aquery .= " GROUP BY id_customer";
	if($sortorder == "customer")
	  $aquery .= " ORDER BY lastname ".$rising.",firstname ".$rising.",company ".$rising.",id_customer ".$rising;
    else /* best customer */
	  $aquery .= " ORDER BY customer_products ".$rising;
	$ares=dbquery($aquery);
	
	while ($arow=mysqli_fetch_array($ares))
	{ echo '<table class="orderlister"><tr><td>'.$arow["id_customer"].'</td>'; 
	  echo '<td colspan="2"><b>'.$arow["firstname"].' '.$arow["lastname"].'</b> '.$arow["company"].'</td>';
	  echo '<td colspan="2">'.$arow["email"].'</td>';
	  echo '<td colspan=10>orders='.$arow["num_orders"].' prods='.number_format($arow["customer_products"],2)." excl=".number_format($arow["customer_excl"],2)." incl=".number_format($arow["customer_incl"],2)." ship=".number_format($arow["customer_shipping"],2)."</td>";	  
	  echo '</tr>';
	  
	  /* now check for other addresses that weren't allocated for this order to invoice or delivery */
	  $adquery = 'SELECT * FROM `'._DB_PREFIX_.'address`';
	  $adquery .= " WHERE id_customer =".$arow["id_customer"];
      $adres=dbquery($adquery);
	  echo '<tr><td colspan=15>';
      while ($adrow=mysqli_fetch_array($adres))
	  { echo ' &nbsp; &nbsp; &nbsp; '.$adrow["alias"].' ['.$adrow["id_address"].']: ';
	    echo $adrow["address1"]." ".$adrow["address2"]." ";
	    echo $adrow["postcode"]." ".$adrow["city"];
	    if($adrow["id_country"]!=$default_country)
		  echo " ".get_country($adrow["id_country"]);
	    echo " ".$adrow["phone"]." ".$adrow["phone_mobile"];
		if($adrow["vat_number"] != "")
			echo " - VAT=".$adrow["vat_number"];
		echo '<br/>';
	  }
	  echo '</td></tr>';
	  
	  if($arow["customer_threads"] != NULL) /* if there are messages */
	  { echo '<tr><td colspan=15> &nbsp; <b>Message: </b>';
	    $tquery = "SELECT cm.*,ct.id_order FROM ". _DB_PREFIX_."customer_message cm";
		$tquery .= " LEFT JOIN ". _DB_PREFIX_."customer_thread ct ON cm.id_customer_thread=ct.id_customer_thread";
	    $tquery .= " WHERE cm.id_customer_thread IN (".$arow["customer_threads"].")";
		if($startdate != "")
			$tquery .= " AND (date_add>='".$startdate."' OR date_upd>='".$startdate."')"; 
		if($enddate != "")
			$tquery .= " AND (date_add<='".$enddate."' OR date_upd<='".$enddate."')"; 
		$tres=dbquery($tquery);
	    $messages = "";
	    while ($trow=mysqli_fetch_array($tres))
		{ if($trow["id_order"] != 0)
			$messages .= "[".$trow["id_order"]."] ";
		  $messages .= substr($trow["date_add"],0,10)."-".$trow["message"]."<br/>";
		}
	    echo $messages.'</td></tr>';
      }
	  
	  $query = "SELECT * FROM ". _DB_PREFIX_."ordertemp";
	  $query .= " WHERE id_customer=".$arow["id_customer"];
	  $query .= " ORDER BY id_order DESC";
	  $res=dbquery($query);
      while ($row=mysqli_fetch_array($res))
      { $total_incl += floatval($row["total_paid"]);
        $total_excl += floatval($row["total_paid_tax_excl"]);
	    $total_shipping += floatval($row["total_shipping"]);
	    if($row["newcust"] == "1")
	    { $newrecs++;
		  $total_incl_new += floatval($row["total_paid"]);
		  $total_excl_new += floatval($row["total_paid_tax_excl"]);
		  $total_shipping_new += floatval($row["total_shipping"]);
	    }
		echo '<tr><td>';
  	    echo 'Order<br><a href=order-edit.php?id_order='.$row["id_order"].' target=_blank>'.$row["id_order"].'</a></td><td>Reference<br>'.$row["reference"].'</td>';
	    echo '<td>Delivery<br>'.$row["delivery_number"]."</td><td>Invoice<br>".$row["invoice_number"]."</td>";
	    echo '<td>Shipping<br>'.$row["shipping_number"].'</td>';	
		echo '<td><span id="prodlink'.$x.'"><a href="#" title="'.$x.'" onclick="show_products(\''.$x.'\',\''.$row["id_order"].'\'); return false;">Show<br>products</a></span></td>';
	    $dateparts = explode(" ",$row["date_add"]);
	    echo "<td>".$dateparts[0]."<br/>".date('D', strtotime($dateparts[0]))." ".$dateparts[1]."</td>";
	    echo '<td>Tot '.number_format($row["total_paid"],2)."<br>Ex ".number_format($row["total_paid_tax_excl"],2)."</td>";
	    echo '<td>ProdsWT<br>'.number_format($row["total_products_wt"],2).'</td><td>Shipping<br>'.number_format($row["total_shipping"],2).'</td>';
	    echo '<td>Wrapping<br>'.number_format($row["total_wrapping"],2).'</td>';
	    echo '<td>'.$row["order_state"].'<br>'.$row["payment"].'</td>';	
	    echo '<td>';
	    if($row["newcust"]=="1") echo "new"; else echo "0";
	    echo '</td><td>'.$row["carriers"].'</td>';
	    echo '<td>SHIP:'.$row["id_address_delivery"].'<br/>INV:'.$row["id_address_invoice"].'</td>';
  	    echo '</tr>';
		echo '<tr><td colspan=15 style="text-align:center"><span id="prodspan'.$x.'"></span></td></tr>';
		$x++;
	  }
	  
	    echo "</tr>";
	  }
	  echo '</table>';
  }
  
  if($numrecs != 0)
  { echo '<table border="1"><tr><td></td><td>Count</td><td>Total paid excl VAT</td><td>Total paid incl VAT</td><td>Shipping</td><td>Average</td></tr>';
	echo '<tr><td>All</td><td>'.$numrecs.'</td><td>'.number_format($total_excl,2).'</td>';
	echo '<td>'.number_format($total_incl,2).'</td><td>'.number_format($total_shipping,2).'</td><td>'.number_format(($total_excl/$numrecs),2).'</td></tr>';
	echo '<tr><td>New</td><td>'.$newrecs.'</td><td>'.number_format($total_excl_new,2).'</td>';
	echo '<td>'.number_format($total_incl_new,2).'</td><td>'.number_format($total_shipping_new,2).'</td>';
	if($newrecs == 0) echo '<td></td>';
	else echo '<td>'.number_format(($total_excl_new/$newrecs),2).'</td>';
    echo '</tr></table>';
  }
$countries = array();
function get_country($id_country)
{ global $countries, $id_lang;
  if(!isset($countries[$id_country]))
  { $query = "select name from ". _DB_PREFIX_."country_lang";
    $query .= " WHERE id_country='".$id_country."' AND id_lang=".$id_lang;
	$res = dbquery($query);
	$row = mysqli_fetch_array($res);
	$countries[$id_country] = $row["name"];
  }
  return $countries[$id_country];
}

?>

</body>
</html>