<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(isset($input['startrec'])) $startrec = intval($input['startrec']);
else $startrec = 0;
if(isset($input['numrecs'])) $numrecs = intval($input['numrecs']);
else $numrecs = 1000;
if($numrecs == 0) $numrecs = 1000;
if(!isset($input['id_lang'])) $input['id_lang']="";
if(!isset($input['id_shop'])) $input['id_shop']="0";
$id_shop = intval($input["id_shop"]);
$fields = array("id_product", "name", "category", "active", "VAT", "price", "from-price", "change","newprice","Min.Qu" ,"country","from", "to","group","shop","customer");
if(empty($input['fields'])) 
	$input['fields'] = $fields;

$rewrite_settings = get_rewrite_settings();

/* get default language: we use this for the categories, manufacturers */
$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];
$def_langname = $row['name'];

/* Get default country for the VAT tables and calculations */
$query="select l.name, id_country from ". _DB_PREFIX_."configuration f, "._DB_PREFIX_."country_lang l";
$query .= " WHERE f.name='PS_COUNTRY_DEFAULT' AND f.value=l.id_country AND l.id_lang='1'";

$res=dbquery($query);
$row = mysqli_fetch_array($res);
$countryname = $row['name'];
$id_country = $row["id_country"];

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Product Discount Overview for Prestashop</title>
<style>
option.defcat {background-color: #ff2222;}
</style>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
</head>

<body>
<?php
print_menubar();
echo '<center><b><font size="+1">Discount Overview</font></b></center>';
echo '<table style="width:100%" ><tr><td>';
echo "The following settings were used: ";
echo " Country=".$countryname." (used for VAT grouping and calculations)";
echo "<br>In this overview of the special prices in your shop last inserted discounts are shown first. The colored discounts are either expired or not yet active. Prices have been rounded at two digits.";
echo "</td><td>";
echo "</td></tr></table>";

  echo '<table class="triplesearch"><tr><td>
	<form name="search_form" method="get" action="discount-list.php" onsubmit="newwin_check()">
<table class="tripleminimal"><tr><td>Language: <select name="id_lang">';
	  $query=" select * from ". _DB_PREFIX_."lang ";
	  $res=dbquery($query);
	  while ($language=mysqli_fetch_array($res)) {
		$selected='';
	  	if ($language['id_lang']==$id_lang) $selected=' selected="selected" ';
	        echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }
  echo '</select>';
	echo ' &nbsp; &nbsp; &nbsp; shop <select name="id_shop"><option value="0">All shops</option>';
	$query=" select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	while ($shop=mysqli_fetch_array($res)) {
		if ($shop['id_shop']==$id_shop) {$selected=' selected="selected" ';} else $selected="";
	        echo '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
	}	
	echo '</select> &nbsp; &nbsp;';
	echo 'Startrec: <input size=3 name=startrec value="'.$startrec.'">';
	echo ' &nbsp &nbsp; Number of recs: <input size=3 name=numrecs value="'.$numrecs.'">';
	echo '</td></tr></table>';
	echo '<hr/>';
	echo '<table ><tr>';
	foreach($fields AS $field)
	{ $checked = in_array($field, $input["fields"]) ? "checked" : "";
	  echo '<td><input type="checkbox" name="fields[]" value="'.$field.'" '.$checked.' />'.$field.'</td>';
	}
	echo '</tr></table></td><td><input type=checkbox name=newwin>new<br/>window<p/><input type="submit" value="search" /></td>';
	echo '</tr></table></form>';
  // "*********************************************************************";

$query = "SELECT s.*,s.price AS fromprice,c.name AS country, g.name AS groupname,cu.firstname,cu.lastname";
$query .= " FROM ". _DB_PREFIX_."specific_price s";
$query.=" left join ". _DB_PREFIX_."image i on i.id_product=s.id_product and i.cover=1";
$query.=" left join ". _DB_PREFIX_."country_lang c on s.id_country=c.id_country AND c.id_lang='".$id_lang."'";
$query.=" left join ". _DB_PREFIX_."group_lang g on g.id_group=s.id_group AND g.id_lang='".$id_lang."'";
$query.=" left join ". _DB_PREFIX_."customer cu on cu.id_customer=s.id_customer";
$query.=" ORDER BY s.id_specific_price DESC";
$query .= " LIMIT ".$startrec.",".$numrecs;

  $res=dbquery($query);
  $numrecs2 = mysqli_num_rows($res);
  echo $numrecs2." displayed.";
//echo $query;

  echo '<div id="testdiv"><table id="Maintable" border=1><colgroup id=mycolgroup>';
  foreach($fields AS $field)
     echo '<col></col>'; /* needed for sort */
  echo '</colgroup><thead><tr>';
  $x=0;
  foreach($input['fields'] AS $field)
    echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$x++.', 0);" title="'.$field.'">'.$field.'</a></th
>';

  echo "</tr></thead><tbody id='offTblBdy'>"; /* end of header */
 
  $x=0;
  while ($datarow=mysqli_fetch_array($res)) {
    /* Note that trid (<tr> id) cannot be an attribute of the tr as it would get lost with sorting */
	$prodquery = "SELECT p.id_product,p.price, p.id_category_default, p.active, cl.name AS catname, pl.name, t.rate";
	$prodquery.=" FROM ". _DB_PREFIX_."product_shop p";
    $prodquery.=" left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".$id_lang."' AND pl.id_shop=p.id_shop";
    $prodquery.=" left join ". _DB_PREFIX_."category_lang cl on cl.id_category=p.id_category_default AND cl.id_lang='".$id_lang."'";
    $prodquery.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=p.id_tax_rules_group AND tr.id_country='".$id_country."'";
	$prodquery.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
	$prodquery.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".$id_lang."'";
    $prodquery.=" WHERE p.id_product='".$datarow["id_product"]."'";
	if($datarow["id_shop"]=="0")
        $prodquery.=" LIMIT 1";
	else
	    $prodquery.=" AND p.id_shop='".$datarow["id_shop"]."'";
    $prodres=dbquery($prodquery);
	$prodrow=mysqli_fetch_array($prodres);
	$background = "";
	if(($datarow["from"] != "0000-00-00 00:00:00") && ($datarow["from"] > date("Y-m-d H:i:s")))
	  $background = " style='background-color:#cccc00;'";
	if(($datarow["to"] != "0000-00-00 00:00:00") && ($datarow["to"] < date("Y-m-d H:i:s")))
	  $background = " style='background-color:#00cccc;'";

    $priceVAT = (($prodrow['rate']/100) +1) * $prodrow['price'];
    if($datarow['fromprice'] > 0)
	{ $frompriceVAT = (($prodrow['rate']/100) +1) * $datarow['fromprice'];
	  $fpv_text = number_format($datarow['fromprice'],4, '.', '').' / '.number_format($frompriceVAT,2, '.', '');
	}
	else 
	{ $fpv_text = "";
	  $frompriceVAT = $priceVAT;
	  $fromprice = $prodrow['price'];
	}
  	if ($datarow['reduction_type'] == "amount")
	{ if ((version_compare(_PS_VERSION_ , "1.6.0.11", ">=")) && ($datarow["reduction_tax"]==0))
		$reduction = $datarow['reduction']*(1+($prodrow["rate"]/100));
	  else
		$reduction = $datarow['reduction'];
	  $newprice = $frompriceVAT - $reduction;
	  $change = number_format($reduction,4,".","");
    }
	else 
	{ $newprice = $frompriceVAT*(1-$datarow['reduction']);
	  $change = ($datarow['reduction']*100).'%';
	}
	$newpriceEX = (1/(($prodrow['rate']/100) +1)) * $newprice;
    $newprice = number_format($newprice,4, '.', '');
    $newpriceEX = number_format($newpriceEX,4, '.', '');
  
    echo '<tr'.$background.'>';
	
    foreach ($input['fields'] AS $field)
	{ if($field == "id_product")
		echo '<td>'.$datarow['id_product'].'</td>';
	  if($field == "name")
		echo '<td>'.$prodrow['name'].'</td>';
	  if($field == "category")
		echo '<td><a title="'.$prodrow['catname'].'" href="#" onclick="return false;" style="text-decoration: none;">'.$prodrow['id_category_default'].'</a></td>';	
	  if($field == "active")
		echo '<td>'.$prodrow['active'].'</td>';
	  if($field == "VAT")
		echo '<td>'.($prodrow['rate']+0).'</td>';
	  if($field == "price")
	  {	echo '<td>'.number_format($prodrow['price'],2, '.', '').' / '.number_format($priceVAT,2, '.', '').'</td>';
	  }
	  if($field == "from-price")	  
		echo '<td>'.$fpv_text.'</td>';
	  if($field == "change")
		echo '<td>'.$change.'</td>';
	  if($field == "newprice")
	    echo '<td>'.$newpriceEX.' / '.$newprice.'</td>';
	  if($field == "Min.Qu")
		echo '<td>'.$datarow['from_quantity'].'</td>';
	  if($field == "country")
		echo '<td>'.$datarow['country'].'</td>';
	  if($field == "from")
	  { if($datarow['from'] == "0000-00-00 00:00:00")
		  echo '<td></td>';
		else if(substr($datarow['from'], 11) == "00:00:00")
		  echo '<td>'.substr($datarow['from'],0,10).'</td>';
		else
		  echo '<td>'.$datarow['from'].'</td>';
	  }
	  if($field == "to")	  
	  { if($datarow['to'] == "0000-00-00 00:00:00")
		  echo '<td></td>';
		else if(substr($datarow['to'], 11) == "00:00:00")
		  echo '<td>'.substr($datarow['to'],0,10).'</td>';
		else
		  echo '<td>'.$datarow['to'].'</td>';
	  }
	  if($field == "group")
		echo '<td>'.$datarow['groupname'].'</td>';
	  if($field == "shop")
	  {	if($datarow['id_shop'] == '0')
		  echo '<td>All</td>';
		else
		  echo '<td>'.$datarow['id_shop'].'</td>';
	  }
	  if($field == "customer")
		echo '<td>'.$datarow['firstname'].' '.$datarow['lastname'].'</td>';
	}
//	echo '<td>'.$datarow['id_specific_price'].'</td>';
    $x++;
    echo '</tr>';
  }
  
  if(mysqli_num_rows($res) == 0)
	echo "<strong>products not found</strong>";
  echo '</table></form></div>';
  
  echo '<div style="display:block;"><form name=rowform action="product_proc.php" method=post target=tank><table id=subtable></table><input type=hidden name=id_lang value="'.$id_lang.'"></form></div>';

  include "footer1.php";
  echo '</body></html>';

?>
