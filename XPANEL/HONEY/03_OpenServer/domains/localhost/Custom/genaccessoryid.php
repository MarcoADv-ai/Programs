<?php
$start = $_POST["start"];

$my_file = './accessoryid.lub';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file

$dir = './data/sprite/¾Ç¼¼»ç¸®/³²/';
if ($handle2 = opendir($dir)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		if( strpos( strtolower($filename), ".spr" ) !== false)continue;
        $render1 = str_replace("³²_","",$filename);
		$render2 = str_replace(".act","",$render1);
		$data = "\tACCESSORY_" . $render2 . " = " . $start++ . ",\n";
		fwrite($handle, $data);
    }
    closedir($handle2);
}
fclose($handle);

?>