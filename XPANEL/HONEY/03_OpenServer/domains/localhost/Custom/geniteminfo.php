<?php
$start = $_POST["viewid"];
$itemid = $_POST["iteminfo"];
$my_file = './iteminfo.lua';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file

$dir = './data/sprite/¾Ç¼¼»ç¸®/³²/';
if ($handle2 = opendir($dir)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		if( strpos( strtolower($filename), ".spr" ) !== false)continue;
        $render1 = str_replace("³²_","",$filename);
		$render2 = str_replace(".act","",$render1);
		$render3 = str_replace("_", " ", $render2);
		$data = "\t[". $itemid++ ."] = {\n\t\tunidentifiedDisplayName = \"" . $render3 . "\",\n\t\tunidentifiedResourceName = \"". $render2 . "\",\n\t\tunidentifiedDescriptionName = { \"...\" },\n\t\tidentifiedDisplayName = \"" . $render3 . "\",\n\t\tidentifiedResourceName = \"". $render2 . "\",\n\t\tidentifiedDescriptionName = {\n\t\t\t\"^0000FF [Custom : RO] ^000000\",\n\t\t\t\"^ffffff_^000000\",\n\t\t\t\"^0000CCType:^000000 Costume\",\n\t\t\t\"^0000CCPosition:^000000 Top\",\n\t\t\t\"^0000CCWeight:^000000 0\"\n\t\t},\n\t\tslotCount = 0,\n\t\tClassNum = ". $start++ ."\n\t},\n";
		fwrite($handle, $data);
    }
    closedir($handle2);
}
fclose($handle);
?>