<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($integrity_repair_allowed)) $integrity_repair_allowed = true;
if(!isset($integrity_delete_allowed)) $integrity_delete_allowed = false;

/* get default language: we use this for the categories, manufacturers */
$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];

$languages = array();
$query = "SELECT id_lang FROM ". _DB_PREFIX_."lang";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ $languages[] = $row["id_lang"];
}

$shops = array();
$query = "SELECT id_shop FROM ". _DB_PREFIX_."shop WHERE active=1";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ $shops[] = $row["id_shop"];
}
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Integrity Checks</title>
<style>
.comment {background-color:#aabbcc}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
function repair_products()
{ if(!repairform.pagree.checked) 
  { alert("You need to agree before you can repair!");
	return false;
  }
  repairform.submit();
}

function delete_products()
{ if(!removeform.pagree.checked) 
  { alert("You need to agree before you can delete!");
	return false;
  }
  var delprods = removeform.delproducts.value.split(",");
  for(var i=0; i< delprods.length; i++)
  { if(problems.indexOf(delprods[i]) === -1)
    { alert("You can only erase problematic products that were flagged. "+delprods[i]+" is not problematic.");
	  return false;
    }
  }
  removeform.submit();
}

function repair_categorys()
{ if(!catrepairform.pagree.checked) 
  { alert("You need to agree before you can repair!");
	return false;
  }
  catrepairform.submit();
}
</script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<div style="float:right; "><iframe name=tank width=230 height=93></iframe></div>
<h1>Integrity Checks</h1>
On this page you will see the results of some data integrity checks for products and categories in your database. 
If there is an image for a product, the product id links to it.
If the image has a legend, you will see it when you hover the mouse over the link.
There are some repair options that are oriented towards saving things. Use the 
<a href="https://github.com/PrestaShop/pscleaner/">PSCleaner</a> if you want to remove anything
that doesn't fit.
<p>
<?php 
echo '
<h2 style="color:red; background-color:yellow;">Repair only when you know what you are doing!</h2>
 - In settings1.php you can set whether repair is allowed.
 - Repair only when you really need a product or category back working<br>
 - Use the delete only to remove product remains that you can\'t get rid off in the Prestashop backoffice<br>
 - Repaired products and categories for which no name is available will be called "dummy" followed by a number after repair<br>
 - There is always some risk with this kind of operation - specially when you have modules installed that
 change the database. Sometimes it helps to reset or remove and re-install such modules. Make a backup<br>
 - Report any problem that you find - either on the forum or directly
 ';

echo '<p><h2>Products</h2>';

$problem_products = array();

echo "Products in "._DB_PREFIX_."product that are not in "._DB_PREFIX_."product_shop: ";
$query = "SELECT p.id_product FROM ". _DB_PREFIX_."product p";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."product_shop ps on p.id_product=ps.id_product";
$query .= " WHERE ps.id_shop is null ORDER BY p.id_product";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
}

echo "<p>Products in "._DB_PREFIX_."product that are not in "._DB_PREFIX_."product_lang: ";
foreach($languages AS $id_lang)
{ foreach($shops AS $id_shop)
  { echo "<br>Lang".$id_lang."-Shop".$id_shop.": ";
    $query = "SELECT p.id_product FROM ". _DB_PREFIX_."product p";
    $query .= " LEFT OUTER JOIN ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_shop =".$id_shop." AND pl.id_lang=".$id_lang;
    $query .= " WHERE pl.id_shop is null ORDER BY p.id_product";
    $res=dbquery($query);
    while ($row=mysqli_fetch_array($res)) 
    { printprod($row["id_product"]).",";
	  $problem_products[] = $row["id_product"];
    }
  }
}
  
echo "<p>Products in "._DB_PREFIX_."product_shop that are not in "._DB_PREFIX_."product: ";
$query = "SELECT DISTINCT ps.id_product FROM ". _DB_PREFIX_."product_shop ps";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
$query .= " WHERE p.id_shop_default is null ORDER BY ps.id_product";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
}

echo "<p>Products in "._DB_PREFIX_."product_lang that are not in "._DB_PREFIX_."product: ";
$query = "SELECT DISTINCT pl.id_product FROM ". _DB_PREFIX_."product_lang pl";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."product p on p.id_product=pl.id_product";
$query .= " WHERE p.id_shop_default is null ORDER BY pl.id_product";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
}

echo "<p>Products with an empty name: ";
$query = "SELECT id_product,id_lang,$id_shop FROM ". _DB_PREFIX_."product_lang";
$query .= " WHERE TRIM(name)='' ORDER BY id_product";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"])."(".$id_lang.",".$id_shop."),";
  $problem_products[] = $row["id_product"];
}

echo "<p>Products in "._DB_PREFIX_."category_product that are not in "._DB_PREFIX_."product:";
$query = "SELECT DISTINCT cp.id_product FROM ". _DB_PREFIX_."category_product cp";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."product p on p.id_product=cp.id_product";
$query .= " WHERE p.id_product is null ORDER BY cp.id_product";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
}

echo "<p>Products in "._DB_PREFIX_."product that are not in "._DB_PREFIX_."category_product:";
$query = "SELECT DISTINCT p.id_product FROM ". _DB_PREFIX_."product p";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
$query .= " WHERE cp.id_product is null ORDER BY p.id_product";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]);
  $problem_products[] = $row["id_product"];
}

echo "<p>Images from which the product id is not in ". _DB_PREFIX_."product:";
$query = "SELECT DISTINCT i.id_product FROM ". _DB_PREFIX_."image i";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."product p on p.id_product=i.id_product";
$query .= " WHERE p.id_shop_default is null ORDER BY i.id_product";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]);
}

echo "<script>problems=['".implode("','",$problem_products)."'];</script>";

if($integrity_repair_allowed &&(sizeof($problem_products)>0))
{ $prods = array_unique($problem_products);
  echo '<p>Product repair will match the ps_product, ps_product_lang and ps_product_shop tables. If a 
product id is present in any of these tables it will create entries for the other(s). If a product has no
category it will place it in Home. Some of these will be products that got damaged. Others will be rests
of product deletions that didn\'t go well. After you have run this you should go to your backoffice to 
delete those products that you don\'t want. You also may need to fill in names and descriptions. After repair
each product will have a translation for all installed languages. If a product was not assigned to a shop
it will be assigned to the shop with the lowest shop id or its default shop. 
Images without product are not handled.<br>
<form name="repairform" target="tank" action="integrity-repair.php" method=post>
<input type="hidden" name="task" value="productrepair"><input type="hidden" name="verbose" value="on">
<input type=checkbox name="pagree"> I did make a backup of the database and want to repair the following product entries now.<br>
<input name="products" value = "'.implode(",",$prods).'" style="width:500px">
<button onclick="return repair_products(this)">Repair products</button></form><br>';
}

if($integrity_delete_allowed &&(sizeof($problem_products)>0))
{ $prods = array_unique($problem_products);
  echo '<form name="removeform" target="tank" action="integrity-repair.php" method=post>
<input type="hidden" name="task" value="productremove"><input type="hidden" name="verbose" value="on">
<input type=checkbox name="pagree"> I did make a backup of the database and want to delete the following product entries now.<br>
<input name="delproducts" value = "" style="width:500px">
<button onclick="return delete_products(this)">Delete products</button></form>';
}

echo '<p><h2>Categories</h2>';
$problem_categorys = array();
echo "Categories in "._DB_PREFIX_."category that are not in "._DB_PREFIX_."category_shop: ";
$query = "SELECT c.id_category FROM ". _DB_PREFIX_."category c";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category_shop cs on c.id_category=cs.id_category";
$query .= " WHERE cs.id_shop is null ORDER BY c.id_category";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
}

echo "<p>Categories in "._DB_PREFIX_."category that are not in "._DB_PREFIX_."category_lang: ";
foreach($languages AS $id_lang)
{ foreach($shops AS $id_shop)
  { echo "<br>Lang".$id_lang."-Shop".$id_shop.": ";
    $query = "SELECT c.id_category FROM ". _DB_PREFIX_."category c";
    $query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category_lang cl on c.id_category=cl.id_category AND cl.id_shop =".$id_shop." AND cl.id_lang=".$id_lang;
    $query .= " WHERE cl.id_shop is null ORDER BY c.id_category";
    $res=dbquery($query);
    while ($row=mysqli_fetch_array($res)) 
    { printcat($row["id_category"]).",";
	  $problem_categorys[] = $row["id_category"];
    }
  }
}
  
echo "<p>Categories in "._DB_PREFIX_."category_shop that are not in "._DB_PREFIX_."category: ";
$query = "SELECT DISTINCT cs.id_category FROM ". _DB_PREFIX_."category_shop cs";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category c on c.id_category=cs.id_category";
$query .= " WHERE c.id_shop_default is null ORDER BY cs.id_category";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
}

echo "<p>Categories in "._DB_PREFIX_."category_lang that are not in "._DB_PREFIX_."category: ";
$query = "SELECT DISTINCT cl.id_category FROM ". _DB_PREFIX_."category_lang cl";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category c on c.id_category=cl.id_category";
$query .= " WHERE c.id_shop_default is null ORDER BY cl.id_category";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
}

echo "<p>Categories with an empty name: ";
$query = "SELECT id_category,id_lang,$id_shop FROM ". _DB_PREFIX_."category_lang";
$query .= " WHERE TRIM(name)='' ORDER BY id_category";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"])."(".$id_lang.",".$id_shop."),";
  $problem_categorys[] = $row["id_category"];
}

echo "<p>Categories without valid parent: ";
$query = "SELECT DISTINCT c.id_category FROM ". _DB_PREFIX_."category c";
$query .= " LEFT OUTER JOIN ". _DB_PREFIX_."category c2 on c.id_parent=c2.id_category";
$query .= " WHERE c2.id_category is null AND c.id_parent!='0' ORDER BY c.id_category";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"])."(".$id_lang.",".$id_shop."),";
  $problem_categorys[] = $row["id_category"];
}

if($integrity_repair_allowed &&(sizeof($problem_categorys)>0))
{ $cats = array_unique($problem_categorys);
  echo 'Category repair works similarly to product repair. See there for more info.<br>
<form name="catrepairform" target="tank" action="integrity-repair.php" method=post>
<input type="hidden" name="task" value="categoryrepair"><input type="hidden" name="verbose" value="on">
<input type=checkbox name="pagree"> I did make a backup of the database and want to repair the category entries now.<br>
<input name="categorys" value = "'.implode(",",$cats).'" style="width:500px">
<button onclick="return repair_categorys(this)">Repair categories</button></form>';
}


function printprod($id_product)
{ global $triplepath;
  $iquery = "SELECT id_image FROM ". _DB_PREFIX_."image WHERE id_product=".$id_product." ORDER BY cover DESC LIMIT 1";
  $ires=dbquery($iquery);
  if($irow=mysqli_fetch_assoc($ires))
  { $id_image = $irow["id_image"];
    $lquery = "SELECT legend FROM ". _DB_PREFIX_."image_lang WHERE id_image=".$id_image." LIMIT 1";
    $lres=dbquery($lquery);
	if($lrow=mysqli_fetch_assoc($lres))
		$legend = $lrow["legend"];
	else 
		$legend = "";
	echo "<a href='".$triplepath.'img/p'.getpath($id_image).'/'.$id_image.".jpg' target=_blank title='".$legend."'>".$id_product."</a>,";
  }
  else
	 echo $id_product.","; 
}

function printcat($id_category)
{ global $triplepath;
  $query = "SELECT name FROM ". _DB_PREFIX_."category_lang WHERE id_category=".$id_category;
  $res=dbquery($query);
  $name = "";
  if(mysqli_num_rows($res)>0)
  { $row=mysqli_fetch_assoc($res);
	$name = $row["name"];
  }
  echo "<a href='".$triplepath.'img/c/'.$id_category.".jpg' target=_blank title='".str_replace("'","",$name)."'>".$id_category."</a>,";
}