<?php
$start = $_POST["viewid"];
$itemid = $_POST["itemdb"];
$my_file = './item_db.txt';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file

$dir = './data/sprite/¾Ç¼¼»ç¸®/³²/';
if ($handle2 = opendir($dir)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		if( strpos( strtolower($filename), ".spr" ) !== false)continue;
        $render1 = str_replace("³²_","",$filename);
		$render2 = str_replace(".act","",$render1);
		$render3 = str_replace("_", " ", $render2);
		$data = $itemid++ .",". $render2 .",". $render3 .",4,20,,10,,,,0,0xFFFFFFFF,63,2,1024,,0,1,". $start++ .",{},{},{}\n";
		fwrite($handle, $data);
    }
    closedir($handle2);
}
fclose($handle);

?>