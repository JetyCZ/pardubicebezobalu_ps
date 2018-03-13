<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['id_lang'])) $input['id_lang']="";
if(isset($_GET["colcount"])) $colcount = intval($_GET["colcount"]); else $colcount = 7;
if(!isset($input['startrec']) || (trim($input['startrec']) == '')) $input['startrec']="0";
if(!isset($input['startimg']) || (trim($input['startimg']) == '')) $input['startimg']="";
$startrec = intval($input['startrec']);
$startimg = intval($input['startimg']);
if(!isset($input['startmethod']) || (trim($input['startmethod']) != 'id')) $startmethod ="num"; /* default */
else $startmethod = "id";
if(!isset($input['imgorder']) || (trim($input['imgorder']) != 'id_product')) $imgorder ="id_image"; /* default */
else $imgorder = "id_product";
if(!isset($input['numrecs'])) $input['numrecs']=$colcount * 20;
$numrecs = intval($input['numrecs']);
if(isset($_GET["imgtype"])) $imgtype = $_GET["imgtype"]; else $imgtype = "home_default";

$rewrite_settings = get_rewrite_settings();

/* Get default language if none provided */
if($input['id_lang'] == "") {
	$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
	$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];
	$languagename = $row['name'];
}
else
  $id_lang = $input['id_lang'];
 
$query="select count(*) AS imgcount FROM ". _DB_PREFIX_."image";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$imgcount = $row['imgcount']; 
  
/* calculate prev and next */
$nonext = $noprev = false;
if($startmethod == "num")
{ if(($startrec-$numrecs) < 0)
	$prevstart = 0;
  else
	$prevstart = $startrec-$numrecs;
  if(($startrec+$numrecs) > $imgcount)
	$nonext = true;
  else
	$nextstart = $startrec+$numrecs;
} 
else if($imgorder == "id_image")
{ $query="select id_image FROM ". _DB_PREFIX_."image WHERE id_image < ".$startrec;
  $query .= " ORDER BY id_image DESC LIMIT ".$numrecs.",1";
  $res=dbquery($query);
  if(mysqli_num_rows($res) == 0)
	$prevstart = 0;
  else
  { $row = mysqli_fetch_array($res);
    $prevstart = $row['id_image'];
  }
  $query="select id_image FROM ". _DB_PREFIX_."image WHERE id_image > ".$startrec;
  $query .= " ORDER BY id_image LIMIT ".$numrecs.",1";
  $res=dbquery($query);
  if(mysqli_num_rows($res) == 0)
	  $nonext = true;
  else
  { $row = mysqli_fetch_array($res);
    $nextstart = $row['id_image'];
  }
}
else  /* if($imgorder == "id_product") */
{ $query="select id_product FROM ". _DB_PREFIX_."image WHERE id_product < ".$startrec;
  $query .= " ORDER BY id_product DESC LIMIT ".$numrecs.",1";
  $res=dbquery($query);
  if(mysqli_num_rows($res) == 0)
	$prevstart = 0;
  else
  { $row = mysqli_fetch_array($res);
    $prevstart = $row['id_product'];
  }
  $query="select id_product FROM ". _DB_PREFIX_."image WHERE id_product > ".$startrec;
  $query .= " ORDER BY id_product LIMIT ".$numrecs.",1";
  $res=dbquery($query);
  if(mysqli_num_rows($res) == 0)
	  $nonext = true;
  else
  { $row = mysqli_fetch_array($res);
    $nextstart = $row['id_product'];
  }
}

if($startrec == 0)
	$prevlink = "PREV";
else 
	$prevlink = "<a href=image-overview.php?startrec=".$prevstart."&numrecs=".$numrecs."&startmethod=".$startmethod."&imgorder=".$imgorder.">PREV</a>";
if($nonext)
	$nextlink = "NEXT";
else
	$nextlink = "<a href=image-overview.php?startrec=".$nextstart."&numrecs=".$numrecs."&startmethod=".$startmethod."&imgorder=".$imgorder.">NEXT</a>";
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Product Image Overview</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
table.image_overview {border:0; padding: 0;}
table.image_overview td {vertical-align:top}
</style>
</head>

<body>
<?php
print_menubar();
echo '<h3 style="margin-bottom:0;">Product Image Overview</h3>';
echo 'This script gives an overview of the '.$imgcount.' product images on your website - sorted by image id. It is meant for quality control. ';
echo 'Missing images are not counted.';
?>

<form name="selform" method="get">

<?php 
	echo '<br/><select name=startmethod><option value="num">start num</option>';
	$selected = '';
	if($startmethod == "id") $selected = "selected";
	echo '<option '.$selected.' value="id">start id</option></select>';
	echo ' <input size=3 name=startrec value="'.$startrec.'">';
	echo ' &nbsp &nbsp; Number of images: <input size=3 name=numrecs value="'.$numrecs.'">';
	echo ' &nbsp &nbsp; Images on one row <select name=colcount>';
    for($i=2; $i<=10; $i++)
	{ $selected = "";
	  if ($i == $colcount)
	    $selected = " selected";
	  echo "<option".$selected.">".$i."</option>";
	}
	echo "</select>";
	
	/* Get image type (=extension */
	$query = "SELECT name,width,height from ". _DB_PREFIX_."image_type WHERE products=1";
	$res=dbquery($query);
	echo ' &nbsp; &nbsp; image type: <select name="imgtype">';
	while($row = mysqli_fetch_array($res))
	{ $selected='';
	  if ($row['name']==$imgtype) $selected=' selected="selected" ';
	    echo '<option '.$selected.' value="'.$row['name'].'">'.$row['name']." (".$row["height"]."x".$row["width"].")</option>";
	}
	echo '</select>';
	
	echo ' &nbsp; order: <select name=imgorder><option>id_image</option>';
	$selected = '';
	if($imgorder == "id_product") $selected = "selected";
	echo '<option '.$selected.'>id_product</option></select>';
	echo ' &nbsp; <input type=submit> &nbsp; &nbsp; </form>';

	$query = "select DISTINCT i.id_image, i.id_product, i.cover, p.name, p.link_rewrite, cl.link_rewrite AS catrewrite FROM ". _DB_PREFIX_."image i";
	$query .= " left join ". _DB_PREFIX_."product_lang p ON i.id_product=p.id_product";	
    $query .= " inner join ". _DB_PREFIX_."product_shop ps on ps.id_product=p.id_product";
    $query .= " left join ". _DB_PREFIX_."category_lang cl on cl.id_category=ps.id_category_default AND cl.id_lang='".(int)$id_lang."'";
    if(($startmethod == "num") && ($imgorder == "id_image"))
		$query .= " ORDER BY id_image LIMIT ".$startrec.",".$numrecs."";
    else if(($startmethod == "num") && ($imgorder == "id_product"))	
		$query .= " ORDER BY id_product LIMIT ".$startrec.",".$numrecs."";	
	else if($imgorder == "id_product") 
		$query .= " WHERE i.id_product >= ".$startrec." ORDER BY i.id_product LIMIT ".$numrecs."";
	else
		$query .= " WHERE id_image >= ".$startrec." ORDER BY id_image LIMIT ".$numrecs."";
    $res = dbquery($query);
//	echo $query;

  $base_uri = get_base_uri();
  $x=0;
  $rownumber = 0;
  echo '<table style="width:100%"><tr><td style="width:50%">'.$prevlink.'</td><td style="width:50%; text-align:right">'.$nextlink.'</td></tr></table>';
  echo '<table class="image_overview">';
  while ($datarow=mysqli_fetch_array($res)) 
  { if(($x % $colcount) == 0)
	  echo '<tr>';
    $id_image = $datarow["id_image"];
	$numstyle = ""; 
	if($datarow["cover"] == 1)
		$numstyle = 'background-color: yellow';
	if(1==1)
	{ if ($rewrite_settings == '1')
        $link = $base_uri.$datarow['catrewrite'].'/'.$datarow["id_product"].'-'.$datarow["link_rewrite"].'.html';
	  else
        $link = $base_uri."index.php?id_product=".$datarow['id_product']."&controller=product&id_lang=".$id_lang;
	}
	echo '<td><a href="'.$base_uri.'img/p'.getpath($id_image).'/'.$id_image.'.jpg" target="_blank" title="'.$datarow['name'].'"><img src="'.$base_uri.'img/p'.getpath($id_image).'/'.$id_image.'-'.$imgtype.'.jpg"  /></a
		><br><span style="'.$numstyle.'">'.$id_image.'</span> 
		<a href="'.$link.'" target=_blank>'.$datarow["name"].'</a></td>';
    $x++;
	if(($x % $colcount) == 0)
	  echo '</tr>';
  }
  echo '</table>';
  echo '<table style="width:100%"><tr><td style="width:50%">'.$prevlink.'</td><td style="width:50%; text-align:right">'.$nextlink.'</td></tr></table>';
  include "footer1.php";
  echo '</body></html>';

?>
