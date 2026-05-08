<?php
$my_file = './accname.lub';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file

$dir = './data/sprite/¾Ç¼¼»ç¸®/³²/';
if ($handle2 = opendir($dir)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		if( strpos( strtolower($filename), ".spr" ) !== false)continue;
        $render1 = str_replace("³²_","",$filename);
		$render2 = str_replace(".act","",$render1);
		$data = "\t". '[ACCESSORY_IDs.ACCESSORY_' . $render2 . '] = "_' . $render2 . '",' . "\n";
		fwrite($handle, $data);
    }
    closedir($handle2);
}
fclose($handle);

?>