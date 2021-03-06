<?php
if(!@include 'approve.php') die( "approve.php was not found!");
if(!isset($_REQUEST["graphmode"]))
	$_REQUEST["graphmode"] = "yearmonths";
if($_REQUEST["graphmode"] == "yearmonths")
{ if(!isset($_REQUEST["years"]))
	$_REQUEST["years"] = "2015,2016,".date("Y");
  $years = preg_replace("/[^0-9,]+/i","",$_REQUEST["years"]);
  $year_arr = explode(",",$years);
  $maxyear = max($year_arr);
  $minyear = min($year_arr);

  $periods = array("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");

  $query = "SELECT SUM(total_paid_tax_excl) AS total, YEAR(date_add) as gyear, MONTH(date_add) AS gmonth";
  $query .= " FROM ". _DB_PREFIX_."orders";
  $query .= " WHERE YEAR(date_add) IN (".$years.") AND valid=1";
  $query .= " GROUP BY YEAR(date_add), MONTH(date_add)";
  $res=dbquery($query);
// while ($row=mysqli_fetch_array($res)) 
//	echo $row["gyear"]."-".$row["gmonth"].": ".$row["total"]."<br>";
// mysqli_data_seek($res, 0);
  $theyears = array();

  $idx = 12;
  $oldyear = 0;
  while ($row=mysqli_fetch_array($res)) 
  { if(!array_key_exists($row["gyear"],$theyears))
    { $theyears[$row["gyear"]] = array();
      if($idx != 12)
	  { for(;$idx<=11; $idx++)
	      $theyears[$oldyear][] = "0";
	  }
	  if($row["gyear"] == $minyear)
	 	$idx = intval($row["gmonth"]); /* no zeroes for leading fields */
	  else 
		$idx = 0;
	  $oldyear = $row["gyear"];
    }
    if(intval($row["gmonth"]) > $idx) /* there may be missing months (without sales). The next routine fills them in */
    { for(;($idx+1)<intval($row["gmonth"]); $idx++)
  	  $theyears[$row["gyear"]][] = "0";
    }
    $theyears[$row["gyear"]][] = $row["total"];
    $idx++;
  }

  $testdata = array("2015"=> array("-28","-5","22","30","35","31","40","28","33","36","37"),
			  "2016"=> array("31", "33","32","32","36","37","39","45","34","33","51","33"),
			  "2017"=> array("28", "43"));
  $data = $theyears;
  
  getcolors($data);

  draw(array(
//    "canvas" => $canvas, // if you use desktop app
    "width"=>640, // no need if $canvas set
    "height"=>320, // no need if $canvas set
    "data"=>$data,
    "periods"=>$periods,
    "colors"=>$colors,
    "font"=>"./OpenSans.ttf",
    "font-size"=>11,
    "units"=>"",
    "font-legend"=>"./OpenSans.ttf",
//    "save-to"=>"out.png", // if save-to absent graph will be sent to browser. Ignored if $canvas set
    "background-color"=> array(255,255,255), // RGB format for colors
    "grid-color"=> array(100,100,100),
    "support-count" => 8 // how many (horizontal) support lines have to be used (may be adjusted by function)
  ));
}
else if($_REQUEST["graphmode"] == "168hours")
{ if((!isset($_REQUEST["startdate"]))|| (!check_mysql_date($_REQUEST["startdate"])))
	$_REQUEST["startdate"] = "";
  $startdate = $_REQUEST["startdate"];
  if((!isset($_REQUEST["enddate"]))|| (!check_mysql_date($_REQUEST["enddate"])))
	$_REQUEST["enddate"] = "";
  $enddate = $_REQUEST["enddate"];
  $periods = array();
  $weekdays = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
  for($i=0; $i<168; $i++)
  { if($i%12)
      $periods[$i] = "";
    else if($i%24)
      $periods[$i] = $i;
    else
	  $periods[$i] = $weekdays[$i/24];
  }
  
  if($startdate == "")
  { $query = "SELECT MIN(date_add) AS startdate";
    $query .= " FROM ". _DB_PREFIX_."orders";
    $query .= " WHERE valid=1"; 
	$res=dbquery($query);
	$row=mysqli_fetch_array($res);
	$startdate = $row["startdate"];
  }
  if($enddate == "")
  { $query = "SELECT MAX(date_add) AS enddate";
    $query .= " FROM ". _DB_PREFIX_."orders";
    $query .= " WHERE valid=1"; 
	$res=dbquery($query);
	$row=mysqli_fetch_array($res);
	$enddate = $row["enddate"];
  }
  
  /* calculate number of days for each week day */
  /* note that PHP day-of-weeks are zero based (0=sunday) and Mysql day-of-weeks are 1 based (1=sunday) */
  $date1 = new DateTime($startdate);
  $date2 = new DateTime($enddate);
  $diff = $date2->diff($date1)->format("%a");
  $diffi = intval($diff)+1;
  $dayofweek = $date1->format('w');
  $weeks = floor($diffi/7);
  $restdays = $diffi % 7;
  $dividers = array();
  for($i=1; $i<=7; $i++)
  { $dividers[$i] = $weeks;
    if($restdays-- >0)
	  $dividers[$i]++;
  }

  $query = "SELECT SUM(total_paid_tax_excl) AS total, DAYOFWEEK(date_add) AS weekday";
  $query .= ", (((DAYOFWEEK(date_add)-1)*24)+HOUR(date_add)) as ghour";
  $query .= " FROM ". _DB_PREFIX_."orders";
  $query .= " WHERE valid=1 AND date_add >= '".$startdate."' AND date_add <= '".$enddate."'";  
  $query .= " GROUP BY ((DAYOFWEEK(date_add)*24)+HOUR(date_add))";
  $query .= " ORDER BY ((DAYOFWEEK(date_add)*24)+HOUR(date_add))";  
  $res=dbquery($query);

  $data = array();
  $data["AvgSalesPerHour"] = array();
  for($i=0; $i<168; $i++)
    $data["AvgSalesPerHour"][$i] = 0;
  while ($row=mysqli_fetch_array($res)) 
  { $data["AvgSalesPerHour"][$row["ghour"]] = floatval($row["total"]) / $dividers[$row["weekday"]];
//    echo "PPP ".$dividers[$row["weekday"]]." == ".$row["ghour"]." - ".$data["AvgSalesPerHour"][$row["ghour"]]." - ".$row["total"]."<br>";
  }

  getcolors($data);

  draw(array(
//    "canvas" => $canvas, // if you use desktop app
    "width"=>640, // no need if $canvas set
    "height"=>320, // no need if $canvas set
    "data"=>$data,
    "periods"=>$periods,
    "colors"=>$colors,
    "font"=>"./OpenSans.ttf",
    "font-size"=>11,
    "units"=>"",
    "font-legend"=>"./OpenSans.ttf",
//    "save-to"=>"out.png", // if save-to absent graph will be sent to browser. Ignored if $canvas set
    "background-color"=> array(255,255,255), // RGB format for colors
    "grid-color"=> array(100,100,100),
    "support-count" => 8 // how many (horizontal) support lines have to be used (may be adjusted by function)
  ));
}

/* make sure there are enough colors */
function getcolors($data)
{ global $colors;
	$colors = array(
    "red"   => array(200,0,0),
    "green" => array(0,200,0),
    "blue"  => array(0,0,200),
    "xx"   => array(200,200,0),
    "ee" => array(0,200,200),
    "qq"  => array(200,0,200),
    "rr"  => array(50,100,150),
    "ss"  => array(250,100,150),
    "rwr"  => array(50,200,150),
    "ss2"  => array(150,100,250));

  if(sizeof($colors) < sizeof($data))
  { $diff = sizeof($data) - sizeof($colors);
    for($i=sizeof($colors); $i<sizeof($data); $i++)
       $colors[$i] = array(rand(0,255),rand(0,255),rand(0,255));
  }
}

function draw($params=array()) {
    // check that we have enough params to draw graph
	$error = 0;
    if (empty($params)) $error = 1;
    if (empty($params["canvas"]) && (empty($params["width"]) || empty($params["height"])) ) $error = 2;
	if (empty($params["periods"])) $error = 3;
	if(sizeof($params["data"]) == 0)
	{ $params["data"]["*"] = array("0");
	}
	if (empty($params["colors"])) $error = 5;
	if($error != 0)	{
        printf("Not enough input parameters for drawing - error ".$error.":\n");
        print_r($params);
        return;
    }

    $font = empty($params["font"]) ? './OpenSans.ttf' : $params["font"];
    $font_size = empty($params["font-size"]) ? 10 : $params["font-size"];

    // graph canvas
    if (empty($params["canvas"])) {
        $canvas_width = $params["width"];
        $canvas_height = $params["height"];
        $canvas = @imagecreatetruecolor($canvas_width, $canvas_height) or die("Unable to create image canvas.");
    } else {
        $canvas = $params["canvas"];
        $canvas_width = imagesx($canvas);
        $canvas_height = imagesy($canvas);
    }

    // graph draw area (where grid and graph placed)
    $draw_area_multiplier = 0.8; // by default wi will draw graph in 80% area of canvas
    $draw_width = round($canvas_width*$draw_area_multiplier);
    $draw_height = round($canvas_height*$draw_area_multiplier);
    // zero point for GD
    $draw_x0 = round(($canvas_width-$draw_width)/2);
    $draw_y0 = round(($canvas_height-$draw_height)/2);

    // standard colors
    $color_black = imagecolorallocate($canvas,0,0,0);
    $color_white = imagecolorallocate($canvas,255,255,255);
    $color_grey = imagecolorallocate($canvas,127,127,127); // used for grid lines

    // setting canvas background, default:white
    if (is_array($params["background-color"]) && count($params["background-color"]) > 2) {
        $a = $params["background-color"];
        $bg_color = imagecolorallocate($canvas, $a[0], $a[1], $a[2]);
        imagefill($canvas, 0, 0, $bg_color);
    } else {
        imagefill($canvas, 0, 0, $color_white);
    }

    // choosing grid color. default: grey (127,127,127)
    if (is_array($params["grid-color"]) && count($params["grid-color"]) > 2) {
        $a = $params["grid-color"];
        $grid_color = imagecolorallocate($canvas, $a[0], $a[1], $a[2]);
    }
    $grid_line_style = array(
        $grid_color,$grid_color,
        IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT
    );
    imagesetstyle($canvas,$grid_line_style);

    $data = $params["data"];

    /*
     * vertical lines for grid
     */
    // calculating max available grids in timeline
    $period_count = 0;
    foreach ($data as $key=>$value)
        $period_count = max($period_count, count($value));
//    if ($period_count < 1) die("No data found for periods.");
    // we have param $periods with period labels and need to sure that the count of periods in data is the same as in $periods
    $periods = $params["periods"];

	$period_count = count($periods);
    if (count($periods) < $period_count) printf("There are not enough labels for data periods.");
    // now we have periods counted and can create vertical lines for them
    $period_width = $draw_width/($period_count+0.4); //+0.4 gives us 0.2width left and 0.2width right place for horizontal lines
//die("PPOP".$period_width);
    $human_x0 = $draw_x0 + $period_width*0.2; // x of first (zero) point
    for ($i=0; $i < $period_count; $i++) {
		if(($periods[$i]=="") && ($i != $period_count-1)) continue;
        $x = $human_x0+$period_width*$i;
        $y0 = $draw_y0; //top
        $y = $draw_y0+$draw_height; //bottom
        imageline($canvas, $x ,$y0, $x, $y, IMG_COLOR_STYLED);

        $bbox = imagettfbbox($font_size, 0, $font, $periods[$i]);
        $xt = $x - ($bbox[2]-$bbox[0])/3; $yt = $y + 1.5*$font_size + ($bbox[3]-$bbox[5])/3;
        imagettftext($canvas,$font_size,0, $xt, $yt, $grid_color, $font, $periods[$i]);
    }

    /*
     * horizontal lines for grid
     */
    // discovering max & min Y data values
    $labels = array_keys($data);
//	print_r($data); die();
    $max_value = $data[$labels[0]][0]; // setting to the first element
    $min_value = $data[$labels[0]][0]; // too
    foreach ($data as $key=>$val) {
        $max_value = max($max_value, max($val));
        $min_value = min($min_value, min($val));
    }
    $support_count = (empty($params["support-count"]))? 7 : $params["support-count"] ;
	if($min_value > 0) $min_value = 0;
	if($max_value < 10) 
		$max_value = 10;
	/* calculate rounded limits */
	$twiple = intval(substr($max_value,0,2))+1;
	$rlen = strlen(round($max_value));
	$rounder = pow(10, $rlen-2);
	if($twiple == 10) {$support_count=5; $graph_high_value=10*$rounder;}
	else if($twiple <= 12) {$support_count=6; $graph_high_value=12*$rounder;}
	else if($twiple <= 14) {$support_count=7; $graph_high_value=14*$rounder;}	
	else if($twiple <= 15) {$support_count=6; $graph_high_value=15*$rounder;}
	else if($twiple <= 20) {$support_count=8; $graph_high_value=20*$rounder;}
	else if($twiple <= 25) {$support_count=5; $graph_high_value=25*$rounder;}
	else if($twiple <= 30) {$support_count=6; $graph_high_value=30*$rounder;}
	else if($twiple <= 35) {$support_count=7; $graph_high_value=35*$rounder;}
	else if($twiple <= 40) {$support_count=7; $graph_high_value=40*$rounder;}
	else if($twiple <= 50) {$support_count=5; $graph_high_value=50*$rounder;}
	else if($twiple <= 60) {$support_count=6; $graph_high_value=60*$rounder;}
	else if($twiple <= 70) {$support_count=7; $graph_high_value=70*$rounder;}
	else if($twiple <= 80) {$support_count=4; $graph_high_value=80*$rounder;}
	else {$support_count=5; $graph_high_value=100*$rounder;}	

//    $graph_high_value = ceil($max_value/$rounder)*$rounder;
    $graph_low_value = floor($min_value/$rounder)*$rounder;
    $graph_range = $graph_high_value - $graph_low_value;
    $support_height_val = ceil($graph_range / $support_count / 10) *10;
    // correcting high-low with support height
    $graph_high_value = ceil($graph_high_value/$support_height_val) * $support_height_val;
    $graph_low_value = floor($graph_low_value/$support_height_val) * $support_height_val;
    $graph_range = $graph_high_value - $graph_low_value;

    $support_values = array();
    for ($i=0; $i<$support_count*2-1; $i++) {
        $new_line = $graph_high_value - $i*$support_height_val;
        if ($new_line >= $graph_low_value)
            $support_values[] = $new_line;
        else break;
    }

    // vertical scaling
    $value_range = $graph_range;
    $value_per_point =  ($draw_height - $draw_height/(count($support_values))*0.2) / $value_range;

    $xl_s = $draw_x0; $xl_e = $draw_x0 + $draw_width - $period_width*1.1;
    $yl = $draw_y0 + $draw_height/(count($support_values))*0.1; // start top line position
    $support_height = $support_height_val * $value_per_point;

    foreach ($support_values as $i=>$val) {
        $yl += ($i==0) ? 0 : $support_height;
        imageline($canvas, $xl_s ,$yl, $xl_e, $yl, (($val==0)? $grid_color :IMG_COLOR_STYLED));
        $label = $val==0 ? "0" : $val; // trick for display "-0" as "0" when all data is negative
        $bbox = imagettfbbox($font_size, 0, $font, $label);
        $xt = $draw_x0-5 - ($bbox[2]-$bbox[0]); $yt = $yl + ($bbox[3]-$bbox[5])/3;
        imagettftext($canvas,$font_size,0, $xt, $yt, $grid_color, $font, $label);
    }

    $human_y0 = $yl; // y of (zero) line

    // plotting main graph
    $colors = array();
    foreach ($params["colors"] as $value) {
        $colors[] = imagecolorallocate($canvas,$value[0],$value[1],$value[2]);
    }
    imagesetthickness($canvas,3);

    $color_index = 0;
    $it_is_first_line = true;
    foreach ($data as $label=>$values) {
        $line_color = $colors[$color_index];
        foreach ($values as $i=>$val) {
            if ($i==0) {
                $start_x = $it_is_first_line ? $human_x0+$period_width*(count($periods)-count($values)) : $human_x0;
                $start_y = $human_y0 - ($val-$graph_low_value)*$value_per_point;
			//	$start_x=$human_x0;
                $it_is_first_line = false;
			//	die("GGG".$start_x." -- ".$start_y." XX ".count($periods)."--".count($values));
                continue;
            }
            $end_x = $start_x+$period_width;
            $end_y = $human_y0 - ($val-$graph_low_value)*$value_per_point;
            imageline($canvas,$start_x,$start_y,$end_x,$end_y,$line_color);
            $start_x = $end_x;
            $start_y = $end_y;
        }
        $color_index++;
    }

    // legend and units
    $text_center = $canvas_width - ($canvas_width - $draw_width) / 4 -$period_width/2;
    $units_label_height = 0;
    if (!empty($params["units"])) {
        $text = "Units:\n".$params["units"];
        $bbox = imagettfbbox($font_size, 0, $font, $text);
        $text_height = ($bbox[1]-$bbox[7]) * 1.2;
        $units_label_height = $text_height;
        $line_height = $text_height / 2;
        $text_top = ($canvas_height - $draw_height) / 2 + $line_height;
        $ar = explode("\n",$text);
        foreach ($ar as $i=>$val) {
            $bbox = imagettfbbox($font_size, 0, $font, $val);
            $left = $text_center - ($bbox[2]-$bbox[0]) / 2;
            imagettftext($canvas,$font_size,0, $left, $text_top+$i*$line_height, $grid_color, $font, $val);
        }
    }
    $font = empty($params["font-legend"]) ? $font : $params["font-legend"];
    $text = implode("\n",$labels);
    $bbox = imagettfbbox($font_size, 0, $font, $text);
    $text_height = ($bbox[1]-$bbox[7]) * 1.2;
    $line_height = $text_height / count($labels);
    $text_top =  ($canvas_height - $text_height / 2) / 2 + $units_label_height / 2;
    $ar = $labels;
    foreach ($ar as $i=>$val) {
        $bbox = imagettfbbox($font_size, 0, $font, $val);
        $left = $text_center - ($bbox[2]-$bbox[0]) / 2;
        imagettftext($canvas,$font_size,0, $left, $text_top+$i*$line_height, $colors[$i], $font, $val);
    }

    // showing result
    if (empty($params["canvas"])) {
        if (empty($params["save-to"])) {
            $sapi = php_sapi_name();
            if( $sapi != 'cli' ) {
                $file='';
                $lineno='';
                if (!headers_sent($file,$lineno)) {
                    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
                    header("Cache-Control: no-cache, must-revalidate");
                    header("Pragma: no-cache");
                    header('Content-type: image/png');
                } else {
                    die("Headers already sent by ".$file." in line no. ".$lineno);
                }
            }
            imagepng($canvas);
        } else {
            imagepng($canvas, $params["save-to"]);
        }
        imagedestroy($canvas);
    }
}


