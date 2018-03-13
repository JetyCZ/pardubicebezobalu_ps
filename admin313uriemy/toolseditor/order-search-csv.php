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
if(!isset($input['carrier'])) $carrier = "all carriers"; else $carrier = mysqli_real_escape_string($conn, $input['carrier']);
if(!isset($input['payoption'])) $payoption = "all payoptions"; else $payoption = mysqli_real_escape_string($conn, $input['payoption']);
if(!isset($input['startdate']) || (!check_mysql_date($input['startdate']))) $startdate=""; else $startdate = $input['startdate'];
if(!isset($input['enddate']) || (!check_mysql_date($input['enddate']))) $enddate=""; else $enddate = $input['enddate'];
if(!isset($input['newcust'])) $input['newcust']="all";
if(!isset($input['id_lang'])) $input['id_lang']="";
$id_lang = $input['id_lang'];
if(!isset($input['id_shop'])) $input['id_shop']="0";
$id_shop = $input['id_shop'];
if((!isset($input['rising'])) || ($input['rising'] == "DESC")) {$rising = "DESC";} else {$rising = "ASC";}
if((!isset($input['paystatus'])) || ($input['paystatus'] == "all")) {$paystatus = "all";} else {$paystatus = $input['paystatus'];}

  $query = "SELECT DISTINCT current_state FROM ". _DB_PREFIX_."orders ORDER BY current_state";
  $res = dbquery($query);
  $orderstates = array();
  while($row = mysqli_fetch_array($res))
  { $squery = "SELECT name, id_order_state FROM ". _DB_PREFIX_."order_state_lang WHERE id_order_state=".$row['current_state']." AND id_lang=".$id_lang;
	$sres = dbquery($squery);
	$srow = mysqli_fetch_array($sres);
	$currentstate = $row['current_state'];
	if(!isset($_GET["orderstate"]) || isset($_GET["orderstate"][$currentstate]))
	{	$orderstates[] = $currentstate;
	}
  }

  $default_country = get_configuration_value('PS_COUNTRY_DEFAULT');
  
  $qfields = "o.id_order,o.date_add,o.invoice_number,o.delivery_number,o.reference,o.id_customer,o.id_address_delivery";
  $qfields .= ",o.id_address_invoice,o.shipping_number,o.total_paid,o.total_paid_tax_excl,o.payment";
  $qfields .= ",total_products_wt,total_shipping,total_wrapping,s.name AS order_state,o.current_state";
  $qfields .= ",o.invoice_date,c.firstname,c.lastname,c.company";
  $qfields .= ",a1.firstname AS firstname1,a1.lastname AS lastname1,a1.company AS company1,a1.address1,a1.address2,a1.postcode,a1.city,a1.id_country,a1.phone,a1.phone_mobile";
  $qfields .= ",a2.firstname AS firstname2,a2.lastname AS lastname2,a2.company AS company2,a2.address1 AS address12,a2.address2 AS address22,a2.postcode AS postcode2,a2.city AS city2,a2.id_country AS id_country2,a2.phone AS phone2,a2.phone_mobile AS phone_mobile2";  
  $qfields .= ",cl1.name AS country1, cl2.name AS country2, c.email, ct.id_customer_thread";
  $qfields .= ', IF((SELECT so.id_order FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = c.id_customer AND so.id_order < o.id_order LIMIT 1) > 0, 0, 1) as newcust'; /* this line is copied from Prestashop */
  $qfields .= ", GROUP_CONCAT(ca.name) AS carriers";
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
  for($i=1; $i<=3; $i++)
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

	if((($sfield == "product id") || ($sfield == "product name")) && !$product_searched)
	{ $product_searched = true;
      $qbody .= " LEFT JOIN ". _DB_PREFIX_."order_detail od ON o.id_order=od.id_order";
	}	
	
	if($sfield == "product id")
	 	$subconditions[] = "od.product_id='".$stext."'";	
	if($sfield == "product name")
	 	$subconditions[] = "od.product_name LIKE '%".$stext."%'";	
	
	$qconditions .= " AND (".implode(" OR ",$subconditions).")";
  }
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
  $query = "SELECT SQL_CALC_FOUND_ROWS ".$qfields.$qbody.$qconditions." GROUP BY id_order ";
  $query .= $having." ORDER BY id_order ".$rising." LIMIT ".$input['startrec'].",".$input['numrecs'];
//  echo $query."<br>";
  $res = dbquery($query);
  $res2=dbquery("SELECT FOUND_ROWS() AS foundrows");
  $row2 = mysqli_fetch_array($res2);
  $numrecs2 = $row2['foundrows'];
  
  if($input['separator'] == "comma")
  { $separator = ",";
	$subseparator = ";";
  }
  else 
  { $separator = ";";
	$subseparator = ",";
  }
  
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=order-search-'.date('Y-m-d-Gis').'.csv');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
	
	$fields = array("id_order","reference","delivery_number","invoice_number","shipping_number"
	,"payment","carriers"
	,"id_customer","firstname","lastname","company","date_add","invoice_date","total_paid","total_paid_tax_excl"
	,"total_products_wt","total_shipping","total_wrapping","order_state","address1","address2"
	,"postcode","city","id_country","phone","phone_mobile","email","newcust",
	"address12","address22","postcode2","city2","id_country2","phone2","phone_mobile2");
	
	$out = fopen('php://output', 'w');
    publish_csv_line($out, $fields, $separator);
	
  while ($row=mysqli_fetch_array($res))
  { $csvline = array();
    for($i=0; $i< sizeof($fields); $i++)
	  $csvline[] = $row[$fields[$i]];
	publish_csv_line($out, $csvline, $separator);
  }
  fclose($out);

  
function publish_csv_line($out, $csvline, $separator)
{ fputcsv($out, $csvline, $separator);
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