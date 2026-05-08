<?php
$itemid = $_POST["itemid"];
$start = $_POST["viewid"];

// Robe Names
$male	='./robe/important/¿äÁ¤ÀÇÆÄ¶õ³¯°³/³²/';
$female	='./robe/important/¿äÁ¤ÀÇÆÄ¶õ³¯°³/¿©/';
// Resources
$collsrc	='./robe/data/texture/À¯ÀúÀÎÅÍÆäÀÌ½º/collection/';
$itemsrc	='./robe/data/texture/À¯ÀúÀÎÅÍÆäÀÌ½º/item/';
$dropsrc 	='./robe/data/sprite/¾ÆÀÌÅÛ/';
$femalesrc	='./robe/data/sprite/¾Ç¼¼»ç¸®/¿©/';
$malesrc	='./robe/data/sprite/¾Ç¼¼»ç¸®/³²/';
// Create Directory
$colldir	='./robe/GENERATED/data/texture/À¯ÀúÀÎÅÍÆäÀÌ½º/collection/';
$itemdir	='./robe/GENERATED/data/texture/À¯ÀúÀÎÅÍÆäÀÌ½º/item/';
$dropdir 	='./robe/GENERATED/data/sprite/¾ÆÀÌÅÛ/';
$wingdir	='./robe/GENERATED/data/sprite/·Îºê/';
// Generated Files
$itemdbdir	='./robe/item_db.txt';
$infodir	='./robe/iteminfo.lua';
$robeiddir	='./robe/spriterobeid.lub';
$robenamedir='./robe/spriterobename.lub';

//Texture Folder
$collfolder = scandir($collsrc);
if (!is_dir($colldir)) 
	mkdir($colldir, 0777, true); //create directories inside folder
for($i = 0; $i < count($collfolder);$i++){
	if($collfolder[$i]=='.' || $collfolder[$i]=='..') continue;
		if (!copy($collsrc . $collfolder[$i], $colldir . strtoupper($collfolder[$i])))
			echo "failed to copy ". $collfolder[$i] ."...\n";
}
//Texture Item
$itemfolder = scandir($itemsrc);
if (!is_dir($itemdir)) 
	mkdir($itemdir, 0777, true); //create directories inside folder
for($i = 0; $i < count($itemfolder);$i++){
	if($itemfolder[$i]=='.' || $itemfolder[$i]=='..') continue;
		if (!copy($itemsrc . $itemfolder[$i], $itemdir . strtoupper($itemfolder[$i])))
			echo "failed to copy ". $itemfolder[$i] ."...\n";
}

//Drop Sprite
$dropfolder = scandir($dropsrc);
if (!is_dir($dropdir)) 
	mkdir($dropdir, 0777, true); //create directories inside folder
for($i = 0; $i < count($dropfolder);$i++){
	if($dropfolder[$i]=='.' || $dropfolder[$i]=='..') continue;
		if (!copy($dropsrc . $dropfolder[$i], $dropdir . $dropfolder[$i]))
			echo "failed to copy ". $dropfolder[$i] ."...\n";
}

//Female Sprite
if ($handle2 = opendir($femalesrc)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		$render1 = str_replace("¿©_","",$filename);
		if( strpos( strtolower($filename), ".spr" ) !== false){
			$postfix = "_¿©.spr";
			$render2 = substr($render1, 0, -4);
		} else {
			$postfix = "_¿©.act";
			$render2 = substr($render1, 0, -4);
		}
		$directory = $wingdir . strtoupper($render2) . "/" . "¿©" . "/";
		
		if (!is_dir($directory)) 
			mkdir($directory, 0777, true); //create directories inside folder
		if($handle3 = opendir($female)){
			while (false !== ($attach = readdir($handle3))) {
				if($attach=='.' || $attach=='..') continue;
				if( strpos( strtolower($attach), substr($postfix,-4) ) === false) continue;
				
				if (!copy($femalesrc . $filename, $directory . $attach))
					echo "failed to copy ". $filename ."...\n";
			}
			closedir($handle3);
		}
    }
    closedir($handle2);
}

//Male Sprite
if ($handle2 = opendir($malesrc)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		$render1 = str_replace("³²_","",$filename);
		if( strpos( strtolower($filename), ".spr" ) !== false){
			$postfix = "_³².spr";
			$render2 = substr($render1, 0, -4);
		} else {
			$postfix = "_³².act";
			$render2 = substr($render1, 0, -4);
		}
		$directory = $wingdir . strtoupper($render2) . "/" . "³²" . "/";
		
		if (!is_dir($directory)) 
			mkdir($directory, 0777, true); //create directories inside folder
		if($handle3 = opendir($male)){
			while (false !== ($attach = readdir($handle3))) {
				if($attach=='.' || $attach=='..') continue;
				if( strpos( strtolower($attach), substr($postfix,-4) ) === false) continue;
				
				if (!copy($malesrc . $filename, $directory . $attach))
					echo "failed to copy ". $filename ."...\n";
			}
			closedir($handle3);
		}
    }
    closedir($handle2);
}

/*
$itemdbdir	='./robek/item_db.txt';
$infodir	='./robek/iteminfo.lua';
$robeiddir	='./robek/spriterobeid.lub';
$robenamedir='./robek/spriterobename.lub';
*/

//Item_db.txt
$dbid = $itemid;
$dbview = $start;
$handle = fopen($itemdbdir, 'w') or die('Cannot open file:  '.$itemdbdir); //implicitly creates file
if ($handle2 = opendir($colldir)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		$render = str_replace(".BMP","",strtoupper($filename));
		$data = $dbid++ .",". $render .",". $render .",4,20,,10,,,,0,0xFFFFFFFF,63,2,8192,,0,1,". $dbview++ .",{},{},{}\n";
		fwrite($handle, $data);
    }
    closedir($handle2);
}
fclose($handle);

//ItemInfo.lua
$luaid = $itemid;
$luaview = $start;
$handle = fopen($infodir, 'w') or die('Cannot open file:  '.$infodir); //implicitly creates file
if ($handle2 = opendir($colldir)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		$render = str_replace(".BMP","",strtoupper($filename));
		$data = "\t[". $luaid++ ."] = {\n\t\tunidentifiedDisplayName = \"" . $render . "\",\n\t\tunidentifiedResourceName = \"". $render . "\",\n\t\tunidentifiedDescriptionName = { \"...\" },\n\t\tidentifiedDisplayName = \"" . $render . "\",\n\t\tidentifiedResourceName = \"". $render . "\",\n\t\tidentifiedDescriptionName = {\n\t\t\t\"[^0000FF Celestia RO ^000000]\",\n\t\t\t\"^ffffff_^000000\",\n\t\t\t\"^0000CCType:^000000 Costume\",\n\t\t\t\"^0000CCPosition:^000000 Garment\",\n\t\t\t\"^0000CCWeight:^000000 0\"\n\t\t},\n\t\tslotCount = 0,\n\t\tClassNum = ". $luaview++ ."\n\t},\n";
		fwrite($handle, $data);
    }
    closedir($handle2);
}
fclose($handle);

//ItemInfo.lua
$robeid = $itemid;
$robeview = $start;
$handle = fopen($robeiddir, 'w') or die('Cannot open file:  '.$robeiddir); //implicitly creates file
if ($handle2 = opendir($colldir)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		$render = str_replace(".BMP","",strtoupper($filename));
		$data = "\tROBE_" . $render . " = " . $robeview++ . ",\n";
		fwrite($handle, $data);
    }
    closedir($handle2);
}
fclose($handle);

//ItemInfo.lua
$nameid = $itemid;
$nameview = $start;
$handle = fopen($robenamedir, 'w') or die('Cannot open file:  '.$robenamedir); //implicitly creates file
if ($handle2 = opendir($colldir)) {
    while (false !== ($filename = readdir($handle2))) {
		if($filename=='.' || $filename=='..')continue;
		$render = str_replace(".BMP","",strtoupper($filename));
		$data = "\t". '[SPRITE_ROBE_IDs.ROBE_' . $render . '] = "_' . $render . '",' . "\n";
		fwrite($handle, $data);
    }
    closedir($handle2);
}
fclose($handle);
?>