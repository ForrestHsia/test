<?php
include ("x2agaarf.php");
ini_set("max_execution_time", "864000");
set_time_limit(0);
$db_host = "localhost";
$db_id = "forresthsia";
$db_password = "hbo45890";
mysql_connect($db_host,$db_id,$db_password);
$dbselect = mysql_select_db ("mlb_atbat");
$year = array(2011, 2012, 2013, 2014, 2015, 2016,2017,2018);
for($i=0;$i<count($year);$i++){
	$allfile = glob("D:/xampp/htdocs/www/mlb_database/inning_all/".$year[$i]."/*");
	for($j=0;$j<count($allfile);$j++){
		$inningfile = file_get_contents($allfile[$j]);
		$xml = xmlstr_to_array($inningfile);
		$xmlinning = $xml["inning"];//此變數為每個inning的array
		$xmlinningkeyname = array_keys($xmlinning);//此變數為每個inning array的key name
		foreach($xmlinningkeyname as $inning){
			for($u=0; $u<2; $u++){
				@$half = array("top","bottom");
				@$xmlinninghalfatbat = $xmlinning[$inning][$half[$u]];//此變數為top/bottom的array
				for($t=0;$t<count(array_keys($xmlinninghalfatbat));$t++){
					@$game_id = substr($allfile[$j],49,30);
					@$xmlinninghalfatbatkey = array_keys($xmlinninghalfatbat);//此變數為top/bottom array的key name, atbat跟action就在這裡
					@$inning_1 = $inning+1;
					@$xmlinninghalfatbatcount = count($xmlinninghalfatbat[$xmlinninghalfatbatkey[$t]]);
					@$xmlinninghalfatbatcount_2 = count($xmlinninghalfatbat[$xmlinninghalfatbatkey[$t]], COUNT_RECURSIVE);
					for($o=0;$o<count($xmlinninghalfatbat["atbat"]);$o++){
						for($p=0;$p<count(array_keys($xmlinninghalfatbat["atbat"][$o]));$p++){
							@$pitcharraykey = array_keys($xmlinninghalfatbat["atbat"][$o]);
							@$pitcharraykeynumber = count(($xmlinninghalfatbat["atbat"][$o][$pitcharraykey[$p]]));
							@$pitcharraykeynumber_2 = count(($xmlinninghalfatbat["atbat"][$o][$pitcharraykey[$p]]), COUNT_RECURSIVE);
							if($pitcharraykey[$p] == '@attributes'){
								echo $game_id.", ".$inning_1.", ";
								echo $half[$u].", ";
								echo $pitcharraykey[$p]." is missed.";
								
							}else{
							@$query = 'INSERT INTO pitchtagnamecheck (fileposition, year, game_id, inning, inning_half, inning_half_name, inning_half_number, inning_half_number_2) VALUES ("'.$allfile[$j].'","'.$year[$i].'","'.$game_id.'","'.$inning_1.'","'.$half[$u].'","'.$pitcharraykey[$p].'","'.$pitcharraykeynumber.'","'.$pitcharraykeynumber_2.'")';
							mysql_query($query);
							echo $inning_1.", ";
							echo $half[$u].", ";
							echo $pitcharraykey[$p]."X";//pitch, runner, po, @attributes位於此
							echo $pitcharraykeynumber.", ";//pitch, runner, po, @attributes數量位於此
							echo $pitcharraykeynumber_2.", ";echo "<BR>";//pitch, runner, po, @attributes下一層數量位於此
							}
			}
			}			
			}
		}
}
}}


?>