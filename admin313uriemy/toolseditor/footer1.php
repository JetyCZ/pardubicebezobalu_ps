<?php

echo '<hr style="border: 1px dotted #CCCCCC;" />';
echo '<center><a href="#top">Top</a></center>';
echo '<center>Prestashop version: '._PS_VERSION_.'. Prestools version 1.21m. Release date: 6-mar-2018. ';
echo "PHP version ".phpversion()." and MySQL version ";
$result = dbquery("SELECT version() AS version");
$query_data = mysqli_fetch_array($result);
echo $query_data["version"];
if (_PS_VERSION_ >= "1.7.0")
{ $result = dbquery("SELECT @@foreign_key_checks;");
  $data = mysqli_fetch_array($result);
  echo " FK=".$data[0]." ";
}
echo '</center>';

if(sizeof($prestools_notbought)==0)
{ echo '<center>Prestools full version</center>';
  echo '<center>For support you can go to the <a href="https://www.prestashop.com/forums/topic/185401-free-script-prestools-suite-mass-edit-order-edit-and-much-more/">thread on the Prestashop forum</a> or use the contact form on the Prestools website.</center>';
}
else 
{ echo '<center>In this installation the following functions are not supported and in demo mode: '.implode(",",$prestools_notbought).
	'. You can buy plugin(s) to use them at <a href="http://www.prestools.com/12-prestools-suite-plugins">Prestools.com</a>.</center>';
  echo '<center>For support go to the <a href="https://www.prestashop.com/forums/topic/185401-free-script-prestools-suite-mass-edit-order-edit-and-much-more/">thread on the Prestashop forum</a></center>';
  echo '<center>For purchased plugins you can also get support at the Prestools website.</center>';
  echo '<script>var notpaid_fld = document.getElementById("notpaid");
	if(notpaid_fld)
		notpaid_fld.innerHTML = \'Some fields ('.implode(",",$prestools_notbought).') are in demo mode. You can buy plugin(s) to use them at <a href="http://www.prestools.com/12-prestools-suite-plugins">Prestools.com</a>.\';
	</script>';
 }





