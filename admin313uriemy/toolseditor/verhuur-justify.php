<?php
if(!@include 'approvev.php') die( "approvev.php was not found!");

echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Vakantiehuis Alpenblick - KarinthiŽ - Oostenrijk</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<style>
ul#mainlist 
{ text-align: justify;
  text-align-last: justify
}
ul#mainlist li 
{ float: none!important;
  display: inline-block;
  border: solid;
  margin: 0.25em;
}
ul#mainlist li table 
{ border-spacing: 0;
  border-collapse: collapse;
  background-color: transparent;
}
ul#mainlist li table td
{   width: 40px;
    height: 40px;
    text-align: center;
}
tr.daytags td
{ color: #AAAAAA;	
}
caption {
    padding-top: 8px;
    padding-bottom: 8px;
    color: #777;
    text-align: center;
}

table tr td:nth-child(1) 
{ color: #AAAAAA;
}
</style></head><body>';
print_menubarv();

$prijsindex = 0;
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
/* calculate colors boven */
$query = "SELECT DISTINCT(bovenppw) FROM mwr_prijzen";
$query .= " WHERE enddate >= '".$firstdayofthismonth."' OR startdate < '".$firstdaybeyond."'";
$query .= " ORDER by bovenppw";
$result = dbquery($query);
$num = mysqli_num_rows($result);
$base[0] = 0xEC; $base[1] = 0xFF; $base[2] = 0x4A;
$end[0] = 0x4C; $end[1] = 0x73; $end[2] = 0;
$trap[0] = ($end[0]-$base[0])/($num-1);
$trap[1] = ($end[1]-$base[1])/($num-1);
$trap[2] = ($end[2]-$base[2])/($num-1);
$bcolors = array();
$i=0;
while($row=mysqli_fetch_assoc($result))
{ $bcolors[0][$row["bovenppw"]]= round($base[0]+$i*$trap[0]);
  $bcolors[1][$row["bovenppw"]]= round($base[1]+$i*$trap[1]);
  $bcolors[2][$row["bovenppw"]]= round($base[2]+$i*$trap[2]);
  $i++;
}
/* calculate colors onder */
$query = "SELECT DISTINCT(onderppw) FROM mwr_prijzen";
$query .= " WHERE enddate >= '".$firstdayofthismonth."' OR startdate < '".$firstdaybeyond."'";
$query .= " ORDER by onderppw";
$result = dbquery($query);
$num = mysqli_num_rows($result);
$base[0] = 0x8C; $base[1] = 0xF0; $base[2] = 0x2A;
$end[0] = 0x2C; $end[1] = 0x73; $end[2] = 30;
$trap[0] = ($end[0]-$base[0])/($num-1);
$trap[1] = ($end[1]-$base[1])/($num-1);
$trap[2] = ($end[2]-$base[2])/($num-1);
$ocolors = array();
$i=0;
while($row=mysqli_fetch_assoc($result))
{ $ocolors[0][$row["onderppw"]]= round($base[0]+$i*$trap[0]);
  $ocolors[1][$row["onderppw"]]= round($base[1]+$i*$trap[1]);
  $ocolors[2][$row["onderppw"]]= round($base[2]+$i*$trap[2]);
  $i++;
}


$query = "SELECT * FROM mwr_prijzen";
$query .= " WHERE enddate >= '".$firstdayofthismonth."' OR startdate < '".$firstdaybeyond."'";
$query .= " ORDER BY startdate";
$result = dbquery($query);
echo "<script>prijzen=[";
$first = false;
$prijslijst = array();
$i=0;
while($row = mysqli_fetch_assoc($result))
{ if(!$first) $first = true;
  else echo ",";
  echo '["'.$row["startdate"].'","'.$row["enddate"].'","'.$row["bovenppw"].'","'.$row["bovenppd"].'","'
.$row["bovenminstay"].'","'.$row["onderppw"].'","'.$row["onderppd"].'","'.$row["onderminstay"].'","'
.$row["ppw"].'","'.$row["ppd"].'","'.$row["minstay"].'"]';
  $prijslijst[$i++] = $row;
}
echo "];</script>
";
$alldays = array(); /* this will contain all days of the displayed year */
$month = date("m",strtotime($firstdayofthismonth));
$year = date("Y",strtotime($firstdayofthismonth));
echo '<br><ul id="mainlist" style="list-style-type: none;" >';
for($i=0; $i < 12; $i++)
{ print_month($month, $year);
  $month++;
  if($month > 12) { $month = 1; $year++; }
//  if($i % 2) echo '</td></tr><tr><td>';
//  else echo '</td><td>';
}
echo '</ul>';

function print_month($month, $year)
{ global $months, $prijslijst, $bcolors, $ocolors;
  $prijsindex=0;
  $startday = date("w",strtotime($year."-".$month."-01")); /* zondag=0; maandag=1, etc */
  if($startday == 0) $startday = 7;
  $weeknr = date("W",strtotime($year."-".$month."-01"))+0;
  $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  echo ' <li style="float:left;"><table class="maandtabel">';
  echo '<caption>'.ucfirst($months[$month-1]).' '.$year.'</caption>';
  echo '<tr class=daytags><td></td><td>M</td><td>D</td><td>W</td><td>D</td><td>V</td><td>Z</td><td>Z</td></tr><tr><td>'.$weeknr.'</td>';

  for($j=1; $j<$startday; $j++)
    echo '<td></td>';
  $day = 1;
  for (;$j<=7;$j++)
  { print_day($year, $month, $day++);
    
  }
  echo '</tr><tr><td>'.++$weeknr.'</td>';
  $j=1;
  $weekcount = 1;
  while($day <= $days_in_month)
  { print_day($year, $month, $day);
	if($j++ == 7)
	{ $j=1;
	  echo '</tr>';
	  if($day != $days_in_month)
	  { echo '<tr><td>'.++$weeknr.'</td>';
		$weekcount++;
	  }
	}
    $day++;
  }
  while($weekcount++ < 5)
  { echo '<tr><td></td></tr>';
  }
  echo '</table></li>';
}

  function print_day($year, $month, $day)
  { global $prijslijst,$bcolors,$ocolors,$prijsindex;
    $dd = $year."-".sprintf('%02d', $month)."-".sprintf('%02d', $day);
    if(isset($prijslijst[$prijsindex+1]) && ($dd >= $prijslijst[$prijsindex+1]["startdate"]))
		$prijsindex++;
	$prijsboven = $prijslijst[$prijsindex]["bovenppw"];
	$color = dechex($bcolors[0][$prijsboven]).dechex($bcolors[1][$prijsboven]).dechex($bcolors[2][$prijsboven]);
    $boven = "FF00FF";
	$onder = "00FF00";
    $bovenocc = "left";
	$onderocc = "both";
    echo '<td style="background-image: url(\'verhuur-img.php?boven='.$boven.'&onder='.$onder.'&bovenocc='.$bovenocc.'&onderocc='.$onderocc.'\');">';
	echo $day.'</td>';
  }
 
echo '</ul>';
Echo 'Finished safely';