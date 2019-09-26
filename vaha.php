<?php
$file = './vaha.txt';
if(isset($_REQUEST["vaha"])) {
    file_put_contents($file, $_REQUEST["vaha"]);
    echo "OK".$_REQUEST["vaha"];
} else {
    echo file_get_contents($file);
}
?>