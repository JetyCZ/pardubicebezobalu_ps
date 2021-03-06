<?php
if(!@include 'approve.php') die( "approve.php was not found!");
if ((!isset($_GET['id_order'])) && (!isset($_GET['id_shop'])) && (!isset($_GET['id_lang'])) && (!isset($_POST['id_order'])))
{ echo "<script>setTimeout('location.href=\'order-edit.php\';',4000);</script>";
  die("add-product.php is not a standalone script. It is a script that is called from <a href='order-edit.php'>order-edit.php</a>");
}

if(isset($_POST['id_order']))
	$input = $_POST;
else 
	$input = $_GET;
if (!isset($input['id_order'])) colordie("No order ID provided");
else $id_order = intval($input['id_order']);
if($id_order == 0) colordie("Invalid order ID");
if (!isset($input['id_shop'])) colordie("No shop ID provided");
else $id_shop = intval($input['id_shop']);
if($id_shop == 0) colordie("Invalid shop ID");
if (!isset($input['id_lang'])) colordie("No lang ID provided");
else $id_lang = intval($input['id_lang']);
if (!isset($input['search_lang'])) $search_lang = 2;
else $search_lang = intval($input['search_lang']);

if (!isset($input['search_txt'])) $search_txt="";
else $search_txt = $input['search_txt'];
if(!isset($input['offset'])) $input['offset'] = 0;
$offset = strval(intval($input['offset']));
if(!isset($input['id_category'])) $input['id_category'] = "0";
$id_category = intval($input['id_category']);
if(!isset($input['order']) || ($input['order'] != "name")) $order = "id_product"; else $order = "name";

/* check if VAT number is set and intra-EU trade without VAT is applicable */
$intra_EU_trade = false;
$eu_countries = array("AT","BE","BG","CY","CZ","DE","DK","EE","ES","FI","FR","GR","HR","HU","IE","IT","LT","LU","LV","MT","NL","PL","PT","RO","SE","SI","SK","UK");
$query="select a.id_country, a.vat_number, a2.vat_number AS vat_invoice from ". _DB_PREFIX_."orders o";
$query .= " left join ". _DB_PREFIX_."address a on o.id_address_delivery=a.id_address";
$query .= " left join ". _DB_PREFIX_."address a2 on o.id_address_invoice=a2.id_address";
$query.=" WHERE o.id_order ='".mysqli_real_escape_string($conn, intval($id_order))."'";
$res=dbquery($query);
$row=mysqli_fetch_array($res);
if($row["vat_invoice"] != "")
  $vat_number = $row["vat_invoice"];
else
  $vat_number = $row["vat_number"];	
$id_country = $row["id_country"];	
if($vat_number != "")
{ $vquery="select id_country from ". _DB_PREFIX_."country WHERE iso_code IN ('".implode("','",$eu_countries)."')";
  $vres=dbquery($vquery);
  $eu_country_ids = array();
  while($vrow=mysqli_fetch_array($vres))
	$eu_country_ids[] = $vrow["id_country"];
  $shop_country = get_configuration_value('PS_COUNTRY_DEFAULT');
  if((in_array($id_country,$eu_country_ids)) && (in_array($shop_country,$eu_country_ids)) && ($id_country != $shop_country))
	   $intra_EU_trade = true;
}

/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];
if($share_stock)
{ $gquery="select GROUP_CONCAT(id_shop) AS shared_shops FROM ". _DB_PREFIX_."shop WHERE id_shop_group='".$id_shop_group."' GROUP BY id_shop_group";
  $gres=dbquery($gquery);
  $grow = mysqli_fetch_array($gres);
  $share_stock_shops = $grow['shared_shops'];
  if ($verbose=="true") echo "Shared stock for shops ".$share_stock_shops."<br>";
}
if($share_stock)
  $wherestock = "s.id_shop_group = '".$id_shop_group."'";		
else
  $wherestock = "s.id_shop = '".$id_shop."'";

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Product Order n.'.$id_order.'</title>
</head>
<body>
<form name="search_form" action="add-product.php">
  <input name=id_lang type=hidden value='.$id_lang.'>
  <input name=id_order type=hidden value='.$id_order.'>  
  <label>Search
  <input name="search_txt" type="text" value="'.htmlspecialchars($search_txt).'" size="60"  />
  </label>
    <input type="submit" value="search" /> &nbsp; &nbsp; &nbsp; &nbsp; 
	Shop nr: <select name=id_shop>';
	/* making shop block */
	$query=" select id_shop,name from ". _DB_PREFIX_."shop WHERE active=1 ORDER BY id_shop";
	$res=dbquery($query);
	while ($row=mysqli_fetch_array($res)) 
	{ $selected='';
      if ($row['id_shop']==$id_shop) 
		$selected=' selected="selected" ';
      echo '<option  value="'.$row['id_shop'].'" '.$selected.'>'.$row['id_shop']."-".$row['name'].'</option>';
	}	
	echo '</select> &nbsp; ';
	if(isset($input["verbose"])) {$checked = "checked";} else $checked = "";
	echo '<input type=checkbox name=verbose '.$checked.'> verbose 
  <p>
    <label>Filter Language
    <select name="search_lang">      
	  <option selected="selected" value="">all</option>';
	  $query=" select * from ". _DB_PREFIX_."lang ";
	  $res=dbquery($query);
	  while ($language=mysqli_fetch_array($res)) {
		$selected='';
	  	if ($language['id_lang']==$search_lang) $selected=' selected="selected" ';
	    echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }

	echo '</select>
    </label> &nbsp; &nbsp; &nbsp; &nbsp; order <select name="order">
	<option>id_product</option>';
	if ($order=="name") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>name</option>';
    echo '</select>';

	echo ' &nbsp; &nbsp; &nbsp; &nbsp; category <select name="id_category">';
	echo '<option value="">All categories</option>';
	$query=" select * from ". _DB_PREFIX_."category_lang WHERE id_lang=".$id_lang." ORDER BY name";
	$res=dbquery($query);
	while ($category=mysqli_fetch_array($res)) {
		if ($category['id_category']==$id_category) {$selected=' selected="selected" ';} else $selected="";
	        echo '<option  value="'.$category['id_category'].'" '.$selected.'>'.$category['name'].'</option>';
	}
	echo '</select></p><p><br /></p>';

$query="select id_country,id_state from ". _DB_PREFIX_."orders o left join ". _DB_PREFIX_."address a on o.id_address_delivery=a.id_address";
$query.=" WHERE o.id_order ='".$id_order."'";
$res=dbquery($query);
$row=mysqli_fetch_array($res);
$id_country = $row['id_country'];
$id_state = $row['id_state'];

$searchtext = "";
if ($search_txt != "") 
  $searchtext = " AND (p.reference like '%".mysqli_real_escape_string($conn, $search_txt)."%' or p.supplier_reference like '%".mysqli_real_escape_string($conn, $search_txt)."%' or pl.name like '%".mysqli_real_escape_string($conn, $search_txt)."%' or p.id_product='".mysqli_real_escape_string($conn, $search_txt)."') ";
if ($search_lang != 0)
{ $lang1text=" and pl.id_lang='".$id_lang."'";
  $lang2text=" and tl.id_lang='".$id_lang."'";
  $langtext = "";
}
else
{ $lang1text=$lang2text = "";
  $langtext=' and pl.id_lang=tl.id_lang';
}
if ($order=="name") $ordertext="pl.name"; else $ordertext="p.id_product";
$catseg1=$catseg2="";
if ($id_category!=0) {
	$catseg1=" LEFT JOIN ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
	$catseg2=" AND cp.id_category=".$id_category;
}
/* Note: we start with the query part after "from". First we count the total and then we take 100 from it */
$query = " from ". _DB_PREFIX_."product_shop ps left join ". _DB_PREFIX_."product_lang pl on ps.id_product=pl.id_product".$lang1text." AND pl.id_shop='".$id_shop."'";
$query.=" left join ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
$query.=" left join ". _DB_PREFIX_."lang l on pl.id_lang=l.id_lang";
$query.=" left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
$query.=" left join ". _DB_PREFIX_."stock_available s on s.id_product=p.id_product and s.id_product_attribute=0";
$query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."' AND tr.id_state='".$id_state."'";
$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
$query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax".$lang2text;

$query.=$catseg1;
$query.=" WHERE ".$wherestock.$searchtext.$langtext.$catseg2." GROUP BY ps.id_product"; // the "true" serves to catch optional " AND " follow-up texts
// echo $query;

$res=dbquery("SELECT COUNT(*) AS rcount ".$query);
$row = mysqli_fetch_array($res);
$numrecs = $numshown = $row['rcount'];
echo "<table width='100%'><tr><td>Your search delivered ".$numrecs." records";
$numlimit = 100;
if($numrecs > $numlimit) { 
  $numshown = $numrecs - $offset;
  if($numshown > $numlimit) $numshown = $numlimit;
  echo ": $numshown are shown<br>";
  echo 'Select the record number offset: <select name="offset">';
  if ($offset==0) {$selected=' selected="selected" ';} else $selected=""; 
  echo '<option '.$selected.'>0</option>';
  $i=$numlimit;
  while ($i < $numrecs) { 
    if ($i==$offset) {$selected=' selected="selected" ';} else $selected="";
    echo '<option '.$selected.'>'.$i.'</option>';
    $i=$i+$numlimit;
  }
  echo "</select> and click 'Search'";
}
  echo "<input type=hidden name=numshown value=".$numshown.">";
  echo "</td><td align=right>Show images <input type=checkbox checked name=imagehider onchange='hide_images(this)'>";
  echo '</form></td></tr></table>';
  echo '<form name=attriForm>
  <table width="100%" border="1" id="maintable">
  <tr>
    <td width="5%">ID</td>
    <td width="10%">Reference</td>
    <td width="5%">Lang</td>
    <td width="50%">Name</td>
    <td width="2%">Quant</td>
    <td width="15%">attributes</td>
    <td width="10%">Price</td>
    <td width="5%">Tax Value</td>
	<td width="5%">PriceInc</td>
    <td width="10%">Action</td>
    <td width="5%">Image</td>
  </tr>';

  $query .= " ORDER BY ".$ordertext." LIMIT ".$offset.",$numlimit";
  $res=dbquery("select ps.*,p.*, pl.name,pl.id_lang,l.iso_code,t.*,i.id_image,s.quantity ".$query);

// Begin loop
  if (mysqli_num_rows($res)>0) {
		while ($products=mysqli_fetch_array($res)) {
?>
  <tr>
    <td width="5%"><?php echo $products['id_product'] ?></td>
    <td width="10%"><?php echo $products['reference'] ?></td>
    <td width="10%"><?php echo $products['iso_code'] ?></td>
    <td width="50%"><?php echo $products['name'] ?></td>
<?php	

  echo '<td width="2%">'.$products['quantity'].'</td>'; 
  echo '<td>';
  $aquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."product_attribute WHERE id_product=".$products['id_product']." LIMIT 1";
  $ares=dbquery($aquery);
  if(mysqli_num_rows($ares) != 0) {
	$aquery = "SELECT pa.id_product_attribute,pa.price,pa.weight, l.name,a.id_attribute_group,s.quantity";
	$aquery .= " FROM ". _DB_PREFIX_."product_attribute pa";
	$aquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_combination c on pa.id_product_attribute=c.id_product_attribute";
	$aquery .= " LEFT JOIN ". _DB_PREFIX_."attribute a on a.id_attribute=c.id_attribute";
	$aquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on l.id_attribute=c.id_attribute AND l.id_lang='".$id_lang."'";
	$aquery .= " LEFT JOIN ". _DB_PREFIX_."stock_available s on s.id_product_attribute=c.id_product_attribute AND pa.id_product=s.id_product";

	$aquery .= " WHERE pa.id_product='".$products['id_product']."' AND ".$wherestock." ORDER BY pa.id_product_attribute,a.id_attribute_group";
	$ares=dbquery($aquery);
	echo "<select name='attribs".$products['id_product']."'>";
	$lastgroup = "";
	while ($row=mysqli_fetch_array($ares)) {
		if($lastgroup != $row['id_product_attribute']) {
			if($lastgroup != "")
				echo "</option>";
			echo "<option value='".$row['id_product_attribute']."' price='".$row['price']."' weight='".$row['weight']."'>".$row['name'];
			echo " (q:".$row['quantity'].")";
			$lastgroup = $row['id_product_attribute'];
		}
		else
			echo " - ".$row['name'];
	}
	echo "</option></select>";	
  }
  if($intra_EU_trade)
	  $products['rate'] = 0;

  echo '</td><td>'.round($products['price'],3).'</td>';
  echo '<td>'.(float)$products['rate'].'%</td>';  
  echo '<td>'.number_format($products['price']*(1+($products['rate']/100)),2,'.','').'</td>';  
  echo '<td><div align="center"><a href="order-proc.php?action=add-product&id_lang='.$products['id_lang'];
    echo '&id_order='.$id_order.'&id_product='.$products['id_product'].'" onclick="return add_attributes(this);">';
    echo '<nobr>add product</nobr></a></div></td>';
  echo '<td>'.get_product_image($products['id_product'],$products['id_image'],'').'</td></tr>';

	}
	} else {
	echo "<strong>products not found</strong>";
	}

?>
</form>
</table>
  <script>
   function add_attributes(obj) 
   { var id_product = obj.parentNode.parentNode.parentNode.cells[0].innerHTML;
	 var sel = eval("document.attriForm.attribs"+id_product);
	 if(sel)
	 { var attrib = sel.options[sel.selectedIndex].value;
	   var name = sel.options[sel.selectedIndex].text;
	   var price = sel.options[sel.selectedIndex].getAttribute('price');
	   var weight = sel.options[sel.selectedIndex].getAttribute('weight');
	   obj.href = obj.href+"&attribute="+attrib;
	   obj.href = obj.href+"&attname="+name;
	   obj.href = obj.href+"&attprice="+price;
	   obj.href = obj.href+"&attweight="+weight;
	 }
	 else
	   obj.href = obj.href+"&attribute=0";	
	 if(search_form.verbose.checked)
	   obj.href = obj.href+"&verbose=on";
   }
   
   function hide_images(elt)
   { var num = document.search_form.numshown.value;
     var tabl = document.getElementById("maintable");
	 for(i=0; i<=num; i++)
	 {	if(elt.checked)
			tabl.tBodies[0].rows[i].cells[10].style.display="table-cell";
		else
			tabl.tBodies[0].rows[i].cells[10].style.display="none";
	 }
   }
  </script>
</body>
</html>