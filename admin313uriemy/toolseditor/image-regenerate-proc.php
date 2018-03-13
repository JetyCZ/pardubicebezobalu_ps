<?php
if(!@include 'approve.php') die( "approve.php was not found!");

/* Note: this script uses only the PHP GD library. Prestashop uses php_image_magician as a shell around that (admin/filemanager/include/php_image_magician.php) */

$imageformat = intval($_POST["imageformat"]);
$library = intval($_POST["glibrary"]);
$duration = intval($_POST["duration"]);
if(isset($_POST["filter"]))
  $filter = intval($_POST["filter"]);
else
  $filter = 22; /* 22=LANCZOS */

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
 echo " <span id=finspan></span></br>";
 if($library==1)
 { echo " using Imagick";
   $filter_array = array(
imagick::FILTER_UNDEFINED => "UNDEFINED",
imagick::FILTER_POINT => "POINT",
imagick::FILTER_BOX => "BOX",
imagick::FILTER_TRIANGLE => "TRIANGLE",
imagick::FILTER_HERMITE => "HERMITE",
imagick::FILTER_HANNING => "HANNING",
imagick::FILTER_HAMMING => "HAMMING",
imagick::FILTER_BLACKMAN => "BLACKMAN",
imagick::FILTER_GAUSSIAN => "GAUSSIAN",
imagick::FILTER_QUADRATIC => "QUADRATIC",
imagick::FILTER_CUBIC => "CUBIC",
imagick::FILTER_CATROM => "CATROM",
imagick::FILTER_MITCHELL => "MITCHELL",
imagick::FILTER_LANCZOS => "LANCZOS",
imagick::FILTER_BESSEL => "BESSEL",
imagick::FILTER_SINC => "SINC");
   if($verbose=="true")
	 echo " with filter ".$filter_array[$filter]."<br>";
  } 
 $starttime = time();
 
$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];
$shownotfound = true;

if(isset($demo_mode) && $demo_mode)
   echo '<script>alert("The script is in demo mode. Nothing is changed!");</script>';
else if(isset($_POST["id_image"]))
{ if(strpos($_POST["id_image"], "-") > 0)
  { $parts = explode("-", $_POST["id_image"]);
	$start = intval($parts[0]);
	$end = intval($parts[1]);
    adapt_timelimit($end - $start + 1);
	$shownotfound = false;
	for($i=$start; $i<=$end; $i++)
	{ regenerate_image($i);	
    }
  }
  else
  { $id_image = intval($_POST["id_image"]); 
    adapt_timelimit(1);
    regenerate_image($id_image);
  }
}
else if(isset($_POST["id_product"]))
{ $id_product = intval($_POST["id_product"]);
  $pquery = 'SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.$id_product.' ORDER BY `id_image` ASC';
  $pres = dbquery($pquery);
  adapt_timelimit(mysqli_num_rows($pres));
  $x = 0;
  while($prow = mysqli_fetch_array($pres))
  { echo $x++." ";
	regenerate_image($prow["id_image"]); 
  }
}	
else if(isset($_POST["id_category"]))
{ $id_category = intval($_POST["id_category"]);
  $pquery = 'SELECT id_image, pl.name AS productname FROM `'._DB_PREFIX_.'image` i';
  $pquery .= " LEFT JOIN ". _DB_PREFIX_."category_product cp on i.id_product=cp.id_product";  
  $pquery .= " LEFT JOIN ". _DB_PREFIX_."product_lang pl on i.id_product=pl.id_product AND id_lang=".$id_lang;
  $pquery .= ' WHERE id_category='.$id_category.' ORDER BY `id_image` ASC';
  $pres = dbquery($pquery);
  adapt_timelimit(mysqli_num_rows($pres));
  $x = 0;
  while($prow = mysqli_fetch_array($pres))
  { echo $x++." ".$prow["productname"];
	regenerate_image($prow["id_image"]); 
  }
}

$endtime = time();
echo '<h1>Finish</h1><script>finspan=document.getElementById("finspan"); finspan.innerHTML="<b>Finished in '.($endtime-$starttime).'s</b>"; parent.dynamo2("<br>");</script>';

function regenerate_image($id_image)
{ global $library, $imageformat, $triplepath, $shownotfound, $verbose;
  $srcfile = "";
  $condition = "";
  if($imageformat !=0)
	$condition = " AND id_image_type=".$imageformat." "; /* this is set when we want to process only one of the formats (like "large") */
  /* get the image formats and create images */
  $query = 'SELECT name,width,height FROM `'._DB_PREFIX_.'image_type` WHERE products=1 '.$condition.' ORDER BY `name` ASC';
  $res = dbquery($query);
  if($imageformat !=0)
  { $row = mysqli_fetch_array($res);
    $imagename = $id_image."-".$row["name"].".jpg";
    mysqli_data_seek($res, 0);
  }
  
  $dir = $triplepath.'img/p'.getpath($id_image).'/';
  if((!file_exists($dir)) || (!is_dir($dir)))
  { if($shownotfound)
      pecho('Image '.$id_image.' not Found. ');
	return;  
  }

  /* delete old images */
  $files = scandir($dir);
  $files_to_erase = array();
  foreach ($files AS $file)
  { $lfile = strtolower($file);
    if(($file=="..") || ($file==".") || ($lfile=="index.php"))
	  continue;
    if(is_dir($dir.$file))
  	  continue;
    if(($lfile==$id_image.".jpg") || ($lfile==$id_image.".png") || ($lfile==$id_image.".gif") || ($lfile==$id_image.".jpeg") || ($lfile==$id_image.".x-png") || ($lfile==$id_image.".pjpeg"))
    { $srcfile = $file;
      continue;
    }
    if(($imageformat == 0) || ($file==$imagename))
      $files_to_erase[] = $file;
  }
  if($srcfile == "")
  { if($shownotfound)
      pecho('Image '.$id_image.' not Found. ');
	return;  
  }

  if ($verbose=="true") echo "Erasing";
  foreach($files_to_erase AS $file)
  { if(!unlink($dir.'/'.$file))
	  pecho (" error deleting ".$file);
    else if ($verbose=="true")
	  echo " ".$file;
  }
  
  if ($verbose=="true") echo "<br>";
  pecho(" ".$id_image);
  if ($verbose=="true") echo " Creating";
  
  if($library == 1)
    regenerate_image_imagick($id_image, $srcfile, $dir, $res);
  else
	regenerate_image_gd($id_image, $srcfile, $dir, $res);
}


function regenerate_image_imagick($id_image, $srcfile, $dir, $res)
{ global $verbose, $filter;
/* $exif = @exif_read_data($src_file);
  if ($exif && isset($exif['Orientation']))
  { switch ($exif['Orientation']) 
    {  case 3:
			$src_width = $tmp_width;
			$src_height = $tmp_height;
			$rotate = 180;
			break;
		case 6:
			$src_width = $tmp_height;
			$src_height = $tmp_width;
			$rotate = -90;
			break;
		case 8:
			$src_width = $tmp_height;
			$src_height = $tmp_width;
			$rotate = 90;
			break;
		default: 
	}
  }
*/

  $pos = strrpos($srcfile,".");
  $imgroot = substr($srcfile,0,$pos);
  $imgext = substr($srcfile,$pos+1);
  list($width, $height, $imgtype, $attr) = @getimagesize( $dir.$srcfile );
//  echo "width=".$width.", height=".$height." imgtype=".$imgtype." and attr=".$attr."<br>";
  $sourceratio = $width / $height ;
  $newratio = 0;
  $src = new Imagick(realpath($dir.$srcfile));
  $mold = clone $src;
  $imgbase2 = realpath($dir.$srcfile);
  $pos = strrpos($imgbase2,".");
  $imgbase = substr($imgbase2,0,$pos);
  
  while($row = mysqli_fetch_array($res))
  { if ($verbose=="true")
      echo " ".$row["name"];
    $targetwidth = $row["width"];
    $targetheight = $row["height"];
    $targetratio = $targetwidth / $targetheight;

    if($newratio != $targetratio)	/* this makes that we will only once run this section for an image - except for when the different formats have different ratios */
    { if($sourceratio != $targetratio) /* add whitespace when needed */
      { if($height*$targetratio > $width)
	    { $newwidth = $height*$targetratio;
		  $newheight = $height;
		  echo "";
		  $offsetx = ($newwidth - $width)/2;
		  $offsety = 0;
        }
        else
	    { $newheight = $width/$targetratio;
	      $newwidth = $width;
		  $offsetx = 0;
		  $offsety = ($newheight - $height)/2;	  
	    } 
		$mold = clone $src;
//		echo "newwidth=".$newwidth.", newheight=".$newheight." offsetx=".$offsetx." and offsety=".$offsety."<br>";
		$mold->extentImage($newwidth, $newheight, -$offsetx, -$offsety);
      }
      else
      { $mold = clone $src;
	    $newwidth = $width;
	    $newheight = $height;
	  }
	  $newratio = $targetratio;
    }
	$img = clone $mold;
	//Set Imagick Object values
	// Imagick constants can be found here: http://www.php.net/manual/en/imagick.constants.php
//    $img->setImageOpacity(1.0); /* turn off opacity */
	$img->setImageCompression(Imagick::COMPRESSION_JPEG);
	$img->setInterlaceScheme(Imagick::INTERLACE_PLANE); /* alternatives: INTERLACE_NONE and INTERLACE_LINE */
	$img->setImageCompressionQuality(99);
//	$img->gaussianBlurImage(0.05,0.05);
	$img->stripImage();		/* Strips an image of all profiles and comments. Stripping Exif information may effect the rotation */
	// if you're doing a lot of picture resizing, it might be beneficial to use scaleImage instead of resizeImage, as it seems to be much much more efficient.
	// for resizing see http://www.imagemagick.org/Usage/resize/#thumbnail
//	$img->thumbnailImage($targetwidth, $targetheight, Imagick::FILTER_LANCZOS, 1); /* for times of filters see the comments of http://php.net/manual/en/imagick.resizeimage.php */
	$img->resizeImage($targetwidth,$targetheight,$filter, 1);
	//Output the final Image using Imagick
	$img->writeImage($imgbase."-".$row["name"].'.jpg');
	$img->destroy();
  }
  if ($verbose=="true") echo "<br>";
}

function regenerate_image_gd($id_image, $srcfile, $dir, $res)
{ global $verbose;
  $pos = strrpos($srcfile,".");
  $imgroot = substr($srcfile,0,$pos);
  $imgext = substr($srcfile,$pos+1);
  list($width, $height, $imgtype, $attr) = @getimagesize( $dir.'/'.$srcfile );
//  echo "width=".$width.", height=".$height." imgtype=".$imgtype." and attr=".$attr."<br>";
  $sourceratio = $width / $height ;
  $newratio = 0;
  
  /* image type constants are defined here: http://php.net/manual/en/function.exif-imagetype.php */
  if($imgtype == IMAGETYPE_GIF)
	$src = imagecreatefromgif($dir.'/'.$srcfile);
  else if($imgtype == IMAGETYPE_PNG)
	$src = imagecreatefrompng($dir.'/'.$srcfile);
  else if($imgtype == IMAGETYPE_JPEG)
	$src = imagecreatefromjpeg($dir.'/'.$srcfile);
  else if($imgtype == IMG_WBMP)
	$src = imagecreatefromwbmp($dir.'/'.$srcfile);	  
  else if($imgtype == IMAGETYPE_BMP)
	$src = imagecreatefrombmp($dir.'/'.$srcfile);	/* not a GD library function, but defined below */
  else /* if we get an unknown format we try it as a jpg */
  { $src = imagecreatefromjpeg($dir.'/'.$srcfile);
	pecho ('<b>Unknown image type '.$imgtype.'</b><br>');
  }
  
  if(!$src)
  { pecho ('<b>Image creation failed for file '.$srcfile.'</b><br>');
    return;
  }

  while($row = mysqli_fetch_array($res))
  { if ($verbose=="true")
      echo " ".$row["name"];
    $targetwidth = $row["width"];
    $targetheight = $row["height"];
    $targetratio = $targetwidth / $targetheight;

    if($newratio != $targetratio)	/* this makes that we will only once run this section for an image - except for when the different formats have different ratios */
    { /* php_image_magician does not add whitespace, it only crops when the width-height relation is not correct. So we need a custom routine */
      if($sourceratio != $targetratio)
      { if($height*$targetratio > $width)
	    {  $newwidth = $height*$targetratio;
	       $newheight = $height;
        }
        else
	    { $newheight = $width/$targetratio;
	      $newwidth = $width;
	    } 
	
        $img = imagecreatetruecolor ( $newwidth , $newheight ); /* create black image */
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $white);							/* make it white */
	
	    /* in the first step we copy the src on the possibly with whitespace enlarged target */
        imagecopyresampled($img, $src, ($newwidth-$width)/2, ($newheight-$height)/2, 0, 0, $width, $height, $width, $height);
      }
      else
      { $img = $src;
	    $newwidth = $width;
	    $newheight = $height;
	  }
	  $newratio = $targetratio;
    }
    $img2 = imagecreatetruecolor ( $targetwidth , $targetheight ); /* create black image */
    $white = imagecolorallocate($img2, 255, 255, 255);
    imagefill($img2, 0, 0, $white);							/* make it white */
	
	/* in the second step we resize the image */
	/* we do not enlarge images. If it is too small we put a white border around it */
	if($targetheight < $newheight)
      imagecopyresampled($img2, $img, 0, 0, 0, 0, $targetwidth, $targetheight, $newwidth, $newheight);   
	else
	  imagecopyresampled($img2, $img, ($targetwidth-$newwidth)/2, ($targetheight-$newheight)/2, 0, 0, $newwidth, $newheight, $newwidth, $newheight);

	imageinterlace($img2, 1); /* make the image progressive */
    imagejpeg($img2, $dir.'/'.$imgroot."-".$row["name"].'.jpg', 94); /* image, filename, quality */
  }
  if ($verbose=="true") echo "<br>";
}

/* the function below comes from admin/filemanager/include/utils.php. Prestashop has introduced it in the php_image_magician code */
function fix_strtolower($str)
{   if( function_exists( 'mb_strtoupper' ) )
	  return mb_strtolower($str);
    else
	  return strtolower($str);
}

function adapt_timelimit($numrecs)
{ global $duration;
  $msg = $numrecs.' images to regenerate.';
  if(($numrecs > 15) || ($duration != 0))
  { $timr = $numrecs*2;
    if($duration > $timr)
	   $timr = $duration;
    set_time_limit ($timr);
	$msg .= ' The timeout has been extended to '.$timr.' seconds.';
  }
  echo '<script>parent.dynamo2("'.$msg.'");</script>';
}

function pecho($txt)
{ echo $txt;
  echo '<script>parent.dynamo2("'.$txt.'");</script>';
}





