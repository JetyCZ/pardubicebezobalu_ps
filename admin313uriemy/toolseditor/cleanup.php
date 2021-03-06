<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

/* get default language: we use this for the categories, manufacturers */
$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Cleanup</title>
<style>
.comment {background-color:#aabbcc}
#delbutton:disabled {background-color: #aabbcc}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>

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

function dynamo1(data)  /* get product name */
{ var prodname=document.getElementById("prodname");
  prodname.innerHTML = data;
}

function clearlog()
{ var copylist=document.getElementById("copylist");
  copylist.innerHTML = "";
  return false;
}

function RowSubmit(idx)
{ rowform.id_shop.value = eval("configform.id_shop"+idx+".value");
  rowform.id_shop_group.value = eval("configform.id_shop_group"+idx+".value");
  rowform.cvalue.value = eval("configform.cvalue"+idx+".value");
  rowform.fieldname.value = eval("configform.fieldname"+idx+".value");
  rowform.id_configuration.value = eval("configform.id_configuration"+idx+".value"); 
  rowform.verbose.value = configform.verbose.checked;  
  rowform.id_row.value = idx;
  document.rowform.submit();
}

/* prepare submission of cacheform */
function cfprepare()
{ cacheform.verbose.value = configform.verbose.checked;
}

function cffprepare()
{ cacheflagsform.verbose.value = configform.verbose.checked;  
}

function delete_images()
{ if(window.confirm("Do you really want to delete all unused images and move their base img to the /img/tmp directory?"))
  { delimgform.delkey.value="greatmystery";	
    delimgform.action="TE_plugin_cleanup_images.php";
	delimgform.verbose.value = configform.verbose.checked;
    delimgform.submit();
  }
}

function enable_delbutton()
{ document.getElementById("delbutton").disabled = false;	
}

function clear_reset()
{ gatherimgform.reset.checked = false;
  document.getElementById("delbutton").disabled = true;	
}

</script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<div style="float:right; "><iframe name=tank width=230 height=93></iframe></div>
<h1>Image Cleanup</h1>
This page provides a set of functions to reduce the amount of diskspace that your shop occupies.
<form name=configform><p>
<input type=checkbox name=verbose> verbose
</form>
<?php

  /* The following duplicates AdminPerformanceController.php that contains the following code in its postProcess() function:
  		if ((bool)Tools::getValue('empty_smarty_cache'))
		{
			$redirectAdmin = true;
			Tools::clearSmartyCache();
			Tools::clearXMLCache();
			Media::clearCache();
			Tools::generateIndex();
		}
  */
  
  echo '<br><table class="triplemain" style="margin-top:-5px">';
  echo '<thead><tr><td style="text-align:center"><b>Empty image cache</b></td></tr></thead>';
  echo '<tbody><tr><td>Pressing the button below will immediately empty the cache by emptying the \img\tmp directory.</td></tr>';
  echo '<tr><td style="text-align:center"><form name=cacheflagsform action="cleanup-proc.php" method=post target=tank onsubmit=cffprepare()>';
  echo '<input type=hidden name="subject" value="emptyimagecache" ><input type=hidden name=verbose>';
  echo '<input type=submit value="empty image cache"></form></td></tr></table>';
  
  echo '<br><table class="triplemain" style="margin-top:-5px" >';
  echo '<thead><tr><td style="text-align:center"><b>Delete images without product</b></td></tr></thead>';
  echo '<tbody><tr><td>The product deletion process in Prestashop is buggy and often images stay on the hard disk.
  In older and/or larger shops they may use more than 1 GB diskspace.</td></tr>';
  echo '<tr><td style="text-align:center">Deleting those images goes in two steps. The first steps gathers those images. 
  This takes a lot of time. Each time you press the button it will be busy for up to five minutes. And quite likely you 
  will need to press it several times. Each time it will continue where it stopped last time. Once the unused images appear below you can delete them.
  Although this function was tested extensively you are advised to make a backup of the /img/p directory before starting.
  Note that only the first 1000 images are displayed.<br>
  <form name=gatherimgform action="diskspace.php" method=post target=shower onsubmit=cfprepare()>
  <input type=hidden name=embedded value="1">
  <input type=checkbox name=reset> Restart collection &nbsp; &nbsp;
  <input type=submit value="gather unused product images"><input type=hidden name=verbose></form>
  </td></tr>';
  if(file_exists("TE_plugin_cleanup_images.php"))
  { echo '<tr><td style="text-align:center"><form name=delimgform method=post target=tank>';
	echo '<button id=delbutton onclick="delete_images(); return false;" disabled>Delete Unused Images</button>';
	echo '<input type=hidden name=verbose><input type=hidden name=delkey></form></td></tr>';
  }
  else 
    echo '<tr><td colspan=2 id="notpaid">You may gather, but for the cleaning you need to buy a plugin at <a href="https://www.prestools.com/">Prestools</a></td></tr>';
  echo "<tr><td colspan=2>The base files of the deleted images will be transfered to the \\img\\archive directory of your shop.
    (or img\tmp if the achive directory cannot be created).
    You can delete them there if you want.</td></tr></table><p>";

  echo '<iframe name=shower width=800 height=400></iframe';
  echo '</body></html>';


