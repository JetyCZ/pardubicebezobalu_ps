<?php
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['row']) || !isset($input['id_order'])) 
{ echo '<script>alert("incorrect parameters provided!");</script>';
  return;
}
$row = intval($input['row']);
$id_order = intval($input['id_order']);

echo $row."
";
echo '<table class="triplesearch">';
echo "<tr><td>id</td><td>attr id</td><td>qty</td><td>name</td><td>price</td><td>tot excl</td><td>tot incl</td></tr>";

$query = "SELECT * FROM `"._DB_PREFIX_."order_detail` WHERE id_order='".$id_order."'";
$res=dbquery($query);

while ($row=mysqli_fetch_array($res))
{ echo "<tr>";
  echo "<td>".$row["product_id"]."</td><td>".$row["product_attribute_id"]."</td><td>".$row["product_quantity"]."</td>";
  echo "<td>".$row["product_name"]."</td><td>".number_format($row["product_price"],2)."</td><td>".number_format($row["total_price_tax_excl"],2)."</td>";  
  echo "<td>".number_format($row["total_price_tax_incl"],2)."</td>";  
  echo "</tr>";
}
echo '</table>';
