<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Vakantiehuis planner</title>
<style>
</style>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
</head><body>
<?php 
if(!@include 'approvev.php') die( "approvev.php was not found!");
print_menubarv();
$input = $_GET;

/*
$time = time();
$time = $time - (100*24*3600);
for($i=0; $i<2500; $i++)
{   $datum = date("Y-m-d", $time);
    echo $datum."<br>";
   $time = $time + (24 * 3600);
	$query="INSERT INTO vakantiehuis SET datum='".$datum."', bezetting='0'";
	$res=dbquery($query);   
}
*/
$maanden = array("januari","februari","maart","april","mei","juni","juli","augustus","september", "oktober","november","december");
$weekdagen = array("zondag","maandag","dinsdag","woensdag","donderdag","vrijdag","zaterdag");

$time = time() - (30*24*3600);
$datum = date("Y-m-d", $time);

/*	$query="select * from vakantiehuis WHERE datum > '".$datum."' ORDER BY datum LIMIT 130";
	$result=dbquery($query);
	while($datarow = mysqli_fetch_array($result))
	{ echo $datarow["datum"]." - ".$datarow["id_huurder"]."<br>";
	}
*/
function print_month($month, $year)
{ global $maanden, $weekdagen;
  $startdate = new DateTime($year.'/'.$month.'/01');
  $day_of_week = date_format($startdate, "w");
  $testdate = new DateTime($year.'/'.$month.'/31');
  $days_in_month = date_format($testdate, "d");
  if($days_in_month != 31)
    $days_in_month = 31 - $days_in_month;
  
  $monthday = 1;
  $blob = '<table class="maandtabel" border=1><tr><td colspan=7>'.$maanden[$month-1].'</td></tr>';
  $blob .= '<tr><td>Z</td><td>M</td><td>D</td><td>W</td><td>D</td><td>V</td><td>Z</td></tr>';
  $blob .= '<tr>';
  for($day=0; $day< $day_of_week; $day++)
  {  $blob .= '<td>&nbsp;</td>';
  }
  for(;$day<7; $day++)
     $blob .= '<td>'.$monthday++.'</td>';

  while ($monthday <= $days_in_month)
  { if($day++ >=6)
    { $day = 0;
	  $blob .= '</tr><tr>';
	}
	$blob .= '<td id="dag_'.$year.'_'.$month.'_'.$monthday.'">'.$monthday++.'</td>';
  }
  for(;$day<6; $day++)
     $blob .= '<td>&nbsp;</td>'; 
  
  $blob .= '</table>';
  echo $blob; 
}

function make_color_array()
{ global $firstdayofthismonth, $color_array;
  $lastday = date ("Y-m-d", strtotime("+365 day", strtotime($firstdayofthismonth)));
  $query = 'SELECT DISTINCT weekhuur_boven from vakantiehuis WHERE datum >="'.$firstdayofthismonth.'" AND datum <"'.$lastday.'"';
  $query .= 'WHERE weekhuur_boven > 0 ORDER BY weekhuur_boven';
  $res = dbquery($query);
  $count = mysqli_num_rows($res);
  if($count<=1) $count = 2;
  $stepred = floor(176/($count-1));
  $stepgreen = floor(180/($count-1));
  $color_array = array();
  $red = 193;
  $green = 60;
  $x =0;
  while ($row=mysqli_fetch_array($res))
  { $rgb = array($red, $green, 0);
    $hex = '';
	for($i=0; $i < 3; $i++) {
		// Convert the decimal digit to hex
		$hexDigit = dechex($rgb[$i]);
		// Add a leading zero if necessary
		if(strlen($hexDigit) == 1) {
		$hexDigit = "0" . $hexDigit;
		}
		// Append to the hex string
		$hex .= $hexDigit;
	}
	$color_array[$row["weekhuur_boven"]] = array($hex, $x++);
	$red = $red - $stepred;
	$green = $green + $stepgreen;
  }
}

if(isset($_GET["begindatum"]))
{ if(time() < time($_GET["begindatum"]))
  { $dateparts = explode("-", $_GET["begindatum"]);
    $firstdayofthismonth = $dateparts[0]."-".$dateparts[1]."-01";
	$firstdaybeyond = ($dateparts[0]+1)."-".$dateparts[1]."-01";
  }
}

if(!isset ($firstdayofthismonth))
{ $firstdayofthismonth = date("Y-m-01");
  $nextyear = date("Y")+1;
  $firstdaybeyond = (date("Y")+1).date("-m-01");
}

print_month(6, 2015);
print_month(7, 2015);
?>
