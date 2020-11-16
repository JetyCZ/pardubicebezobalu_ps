<?php
echo "1111";
$file = './vaha2.txt';
if (!file_exists($file)) {
    touch($file);
}

//Open the File Stream
$handle = fopen($file, "r+");

//Lock File, error if unable to lock
echo "Getting FLOCK";
if(flock($handle, LOCK_EX)) {
    echo "FLOCK OK";
    $size = filesize($file);

    if(isset($_REQUEST["vaha"])) {
        ftruncate($handle, 0); //Truncate the file to 0
        rewind($handle); //Set write pointer to beginning of file
        fwrite($handle, $_REQUEST["vaha"]); //Write the new Hit Count
        echo "OK".$_REQUEST["vaha"];
    } else {
        $vahaRead = $size === 0 ? 0 : fread($handle, $size);
        echo $vahaRead;
    }
    flock($handle, LOCK_UN); //Unlock File
} else {
    echo "Could not Lock File!";
}

//Close Stream
fclose($handle);

?>