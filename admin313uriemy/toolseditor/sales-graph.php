<?php
if(!@include 'approve.php') die( "approve.php was not found!");
// APPROVE.PHP CAN BE AT TOP IF IT WILL NOT SEND ANY HEADERS OR TEXT TO CLIENT WINDOW

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Sales Graph</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style type="text/css">
body {font-family:arial; font-size:13px}
form {width:260px;}
label,span {height:20px; padding:5px 0; line-height:20px;}
label {width:130px; display:block; float:left; clear:left}
label[for="costumer_id"] {float:left; clear:left}
span {float:left; clear:right}
input {border:1px solid #CCC}
input[type="text"] {width:120px; height:24px; margin:3px 0; float:left; clear:right; padding:0 0 0 2px; border-radius:3px; background:#F9F9F9}
	input[type="text"]:focus {background:#FFF}
select {width:120px; border:1px solid #CCC}
input[type="submit"] {clear:both; display:block; color:#FFF; background:#000; border:none; height:24px; padding:2px 4px; cursor:pointer; border-radius:3px}
input[type="submit"]:hover {background:#333}
</style>
<script type="text/javascript">
oldmode = "yearmonths";

function submitgraph()
{ var graphmode = graphform.graphmode.value;
  if(graphmode == "yearmonths")
  { var yarr = document.getElementsByName("years[]");
    var mychecked = [];
    var ylength = yarr.length;
    var j=0;  
    for(k=0;k< ylength;k++)
    { if(yarr[k].checked)
        mychecked[j++] = yarr[k].value;
    }
    var years = mychecked.join(",");
    var mygraph = document.getElementById("graphimg");
    mygraph.src = "sales-graph-img.php?graphmode=yearmonths&years="+years;
  }
  else if(graphmode == "168hours")
  { var startdate = graphform.startdate.value;
	var enddate = graphform.enddate.value;
    var mygraph = document.getElementById("graphimg");
    mygraph.src = "sales-graph-img.php?graphmode=168hours&startdate="+startdate+"enddate="+enddate;
  }
}

function changemode()
{ if(oldmode=="yearmonths")
  { var elt = document.getElementById("yearmonthscontent");
    elt.style.display = "none";
  }
  else if(oldmode=="168hours")
  { var elt = document.getElementById("168hourscontent");
    elt.style.display = "none";
  }
  var graphmode = graphform.graphmode.value;
  if(graphmode=="yearmonths")
  { var elt = document.getElementById("yearmonthscontent");
    elt.style.display = "block";
	oldmode = "yearmonths";	
  }
  else if(graphmode=="168hours")
  { var elt = document.getElementById("168hourscontent");
    elt.style.display = "block";
	oldmode = "168hours";
  }
}
</script>
<script type="text/javascript" src="utils8.js"></script>
</head>
<body>
<?php print_menubar();

if (!function_exists('imagettftext')) 
{ echo "You need to have the GD library and its freetype extension installed for this function!";
  include "footer1.php";
  echo '</body></html>';
  return;
}
?>
<table><tr><td>
<form name=graphform>
<input type=submit onclick="submitgraph(); return false;" value="Update graph"><p>
Mode: <br><select name=graphmode onchange="changemode(); return false;">
<option selected value="yearmonths">Montly sales year-on-year</option><option value="168hours">168 hours</option></select>
<div id=yearmonthscontent><table><tr><td style="vertical-align:top">Years:</td><td>
<?php
$query="SELECT MIN(year(date_add)) AS oyear FROM "._DB_PREFIX_."orders";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$oyear = intval($row["oyear"]);
$pyear = intval(date("Y"));
for($y=$pyear; $y>=$oyear; $y--)
{ $checked = "";
  if(($y+3) > $pyear)
	$checked = " checked";
  echo '<input type="checkbox" name="years[]" value="'.$y.'" '.$checked.'/> '.$y.'<br>';
}

?>
<!-- HERE WE CALL THE SAME SCRIPT WITH IMG PARAMETER TO INDICATE WE WANT SEE IMAGE -->
</form></td></tr></table></div>
<div id=168hourscontent style="display:none">
Start date:<br>
<input name=startdate><br>
End date:<br>
<input name=enddate><br>
<i>date format yyyy-mm-dd</i>
</div></td><td style="vertical-align:top">
<img id=graphimg src="sales-graph-img.php?img" alt="Test Graph"/></td></tr></table>

<?php

include "footer1.php";
echo '</body></html>';


