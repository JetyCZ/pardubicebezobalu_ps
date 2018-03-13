<?php 
if(!@include 'approvev.php') die( "approvev.php was not found!");

if(!isset($_GET['verbose']))
  $verbose = "off";
$errstring = "";
$input = $_GET;

function float($arg)
{ return floatval(str_replace(",",".",$arg));
}

$startdate = $input["startdate"];
if(!validateDate($startdate, 'Y-m-d')) colordie("Ongeldige startdatum");
if($input["mode"] != "delete")
{ $enddate = $input["enddate"];
  if(!validateDate($enddate, 'Y-m-d')) colordie("Ongeldige einddatum");
  $bovenppd= float($input["bovenppd"]);
  $bovenppw= float($input["bovenppw"]);
  $bovenminstay = intval($input["bovenminstay"]);
  $onderppd= float($input["onderppd"]);
  $onderppw= float($input["onderppw"]);
  $onderminstay = intval($input["onderminstay"]);
  $ppd= float($input["ppd"]);
  $ppw= float($input["ppw"]);
  $minstay = intval($input["minstay"]);
}
echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<script>
function newwin()
{ nwin = window.open("","NewWindow", "scrollbars,menubar,toolbar, status,resizable,location");
  content = document.body.innerHTML;
  if(nwin != null)
  { nwin.document.write("<html><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' /></head><body>"+content+"</body></html>");
    nwin.document.close();
  }
}
</script></head><body>';
echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';

$refscript = $_SERVER['HTTP_REFERER'];
if($refscript == "")
{ $refscript = "vak_prijzen.php";
} 
else if (strpos($_SERVER['HTTP_REFERER'], "vak_prijzen.php"))
{ $refscript = "vak_prijzen.php?".$_SERVER['QUERY_STRING'];
}

if($input["mode"] == "insert")
{ $query = 'INSERT into mwr_prijzen SET startdate="'.$startdate.'", enddate="'.$enddate.'", 
bovenppd="'.$bovenppd.'", bovenppw="'.$bovenppw.'", bovenminstay="'.$bovenminstay.'",
onderppd="'.$onderppd.'", onderppw="'.$onderppw.'", onderminstay="'.$onderminstay.'",
ppd="'.$ppd.'", ppw="'.$ppw.'", minstay="'.$minstay.'"';
  dbquery($query);
}
else if($input["mode"] == "delete")
{ $query = 'DELETE FROM mwr_prijzen WHERE startdate="'.$startdate.'"';
  $res = dbquery($query);
  if($res)
    echo '<script>parent.finish_deletion("'.$startdate.'")</script>';
}

echo "<br>Finished successfully!<p>Go back to <a href='".$refscript."'>Huurhuur page</a>";

echo "</body></html>";

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

?>
