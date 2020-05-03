<?php
$xmltest = file_get_contents("D:/xampp/htdocs/www/mlb_database/inning_all/2017/gid_2017_04_01_pitmlb_tormlb_1_inning_all.xml");
$dom = new DOMDocument();

$dom -> loadXML($xmltest);

$pitchs = $dom -> getElementsByTagName('pitch');
$runners = $dom -> getElementsByTagName('runner');

foreach ($pitchs as $pitchs){
	$pitchx = $pitchs -> getAttribute("x");
	echo $pitchx;
	echo "</BR>";
}

foreach ($runners as $runners){
	$runnerstart = $runners -> getAttribute("start");
	echo $runnerstart;
	echo "</BR>";
}



?>