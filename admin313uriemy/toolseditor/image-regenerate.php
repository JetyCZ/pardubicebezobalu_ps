<?php
if(!@include 'approve.php') die( "approve.php was not found!");
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Image Regenerate</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
ul.imexamples 
{ list-style-type: none;	
}
ul.imexamples li
{ float: left;
  margin: 2px;	
}
ul.imexamples li div
{ position: relative;
  display: block;	
}

</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script type="text/javascript">
function regenerateProdImages()
{ prodform.verbose.value = topform.verbose.checked;
  prodform.imageformat.value = topform.imageformat.value;
  prodform.glibrary.value = topform.glibrary.value;
  prodform.duration.value = topform.duration.value;  
  prodform.target = "tank";
  prodform.action = "image-regenerate-proc.php";
  prodform.submit();
}

function regenerateCatImages()
{ catform.verbose.value = topform.verbose.checked;
  catform.imageformat.value = topform.imageformat.value;
  catform.glibrary.value = topform.glibrary.value;
  catform.duration.value = topform.duration.value;  
  catform.target = "tank";
  catform.action = "image-regenerate-proc.php";
  catform.submit();
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

function dynamo2(data)  /* add to copy list */
{ var genlist=document.getElementById("genlist");
  genlist.innerHTML = data;
}

function change_library(flag)
{ var myspan = document.getElementById("imagickspan");
  if(flag == 0)
	  myspan.style.display = "none";
  else
	  myspan.style.display = "inline";	
}

function generate_im_examples()
{ if(topform.imageformat.selectedIndex == 0)
  { alert("You must select a specific format to create examples in!");
	return;
  }
  var id_image = parseInt(topform.id_image.value);
  if((isNaN(id_image)) || (id_image == 0))
  { alert("You must fill in one image id!");
	return;
  }
  topform.id_image.value = id_image;
  var format = topform.imageformat.value;
  if(topform.imageformat.selectedIndex == 0)
  { alert('You must select another image format than "All"');
	return;
  }  
  LoadPage("image-examples.php?action=generate&id_image="+id_image+"&imageformat="+format,dynamo2);
}

function clear_im_examples()
{ var id_image = parseInt(topform.id_image.value);
  topform.id_image.value = id_image;
  if(id_image == 0)
  { alert("You must fill in one image id!");
	return;
  }
  LoadPage("image-examples.php?action=clear&id_image="+id_image,dynamo2);
}

function empty_log()
{ var genlist=document.getElementById("genlist");
  genlist.innerHTML = "";
}
</script>
</head>
<body>
<?php 
  print_menubar();

  echo '<table style="width:100%"><tr><td style="width:70%"><center><b>Image Regenerate</b></center><br>';
  echo 'Regenerate a selection of your images. 
  When selecting All formats ALL old image formats of this image: only the original file will remain before the new images are
  generated. 
  <b>It will always create .jpg files</b> - at a slightly better quality than Prestashop (png production is not supported). 
  Just like Prestashop it uses the PHP functions for image manipulation. However, if Imagick is installed it will use that. <br>
  Watermarks are not supported at the moment. For ImageMagick a very high quality has been selected so it may be slow!
  <br>With Imagick you can generate examples to compare the filters.<p>';
  echo '</td><td style="width:50%; text-align:right"><iframe name=tank width="300" height="70"></iframe></td></tr></table>';

  echo '<table style="width:100%" border=1><tr><td style="width:100%"><form name=topform method=post target=tank action="image-regenerate-proc.php">';
  if( class_exists("Imagick") )
  { echo 'Select graphics library: <input type=radio name=glibrary value=0 onchange="change_library(0)"> GD
 &nbsp; <input type=radio name=glibrary checked value=1 onchange="change_library(1)"> Imagick ';
/* see here for speed of the filters: http://php.net/manual/en/imagick.resizeimage.php */
    $imagick_version = Imagick::getVersion();
    $imagick_version_number = $imagick_version['versionNumber'];
    $imagick_version_string = $imagick_version['versionString'];
	
    echo ' '.$imagick_version_number.' <span id="imagickspan">with filter <select name=filter>
<option value="'.imagick::FILTER_BESSEL.'">BESSEL</option>
<option value="'.imagick::FILTER_BLACKMAN.'">BLACKMAN</option>
<option value="'.imagick::FILTER_BOX.'">BOX</option>
<option value="'.imagick::FILTER_CATROM.'">CATROM</option>
<option value="'.imagick::FILTER_CUBIC.'">CUBIC</option>
<option value="'.imagick::FILTER_GAUSSIAN.'">GAUSSIAN</option>
<option value="'.imagick::FILTER_HAMMING.'">HAMMING</option>
<option value="'.imagick::FILTER_HANNING.'">HANNING</option>
<option value="'.imagick::FILTER_HERMITE.'">HERMITE</option>
<option value="'.imagick::FILTER_LANCZOS.'" selected>LANCZOS</option>
<option value="'.imagick::FILTER_MITCHELL.'">MITCHELL</option>
<option value="'.imagick::FILTER_POINT.'">POINT</option>
<option value="'.imagick::FILTER_QUADRATIC.'">QUADRATIC</option>
<option value="'.imagick::FILTER_SINC.'">SINC</option>
<option value="'.imagick::FILTER_TRIANGLE.'">TRIANGLE</option>
</select> &nbsp; &nbsp; <input type=submit onclick="generate_im_examples(); return false;" value="Generate examples">
 &nbsp; &nbsp; <input type=submit onclick="clear_im_examples(); return false;" value="Clear examples"></span>';
    echo '</td></tr><tr><td>';
  }
  else echo '<input type=hidden name=glibrary value=0>';
  echo 'Duration: <input name=duration size=3> &nbsp; when left empty 2 sec per image is assumed. Enter nr of secs if you need more.';
  echo '</td></tr><tr><td>';
  $query = 'SELECT name,width,height,id_image_type FROM `'._DB_PREFIX_.'image_type` WHERE products=1 ORDER BY `name` ASC';
  $res = dbquery($query);
  echo 'Select image format: <select name=imageformat><option value=0>All</option>';
  while($row = mysqli_fetch_array($res))
    echo "<option value=".$row["id_image_type"].">".$row["name"]."(".$row["height"]."x".$row["width"].")</option>";
  echo '</select> &nbsp; &nbsp; &nbsp; &nbsp; <input type=checkbox name=verbose> verbose';
  echo '</td></tr><tr><td>'; 
  echo '<center><b>Regenerate image(s) by image id</b></center>';
  echo 'Image id or range of id\'s (like "22-37"): <input name=id_image> ';
  echo ' &nbsp <input type=submit></form>';
  echo '</td></tr><tr><td>';
  echo '<center><b>Regenerate all images of one product</b></center><form name=prodform method=post onsubmit="regenerateProdImages(); return false;">';
  echo 'Product id: <input name=id_product><input type=hidden name=verbose><input type=hidden name=imageformat>';
  echo ' &nbsp <input type=hidden name=glibrary value=0><input name=duration type=hidden><input type=submit></form>';
  echo '</td></tr><tr><td>';
  echo '<center><b>Regenerate all images of all products of one category</b></center><form name=catform method=post onsubmit="regenerateCatImages(); return false;">';
  echo 'Category id: <input name=id_category><input type=hidden name=verbose><input type=hidden name=imageformat>';
  echo ' &nbsp <input type=hidden name=glibrary value=0><input name=duration type=hidden><input type=submit></form>';
  
  echo '</td></tr></table>';
  echo '<input type=button value="clear log" style="float:right;" onclick="empty_log(); return false;">';
  echo '<span id=genlist style="color:red;"></span>';
  echo '</body></html>';
