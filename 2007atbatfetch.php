<?php
/*20181106
在2007裡面, 如果直接從每一局的inning_1等xml出發的話
其實, $inning會有4個array
分別是top, bottom, @attributes跟@root
top, bottom與@attributes都跟inning_all裡面的東西與設定一致
唯獨@root, 因為整個檔案從inning開始, inning是 root node, 所以才多出第4個array叫做@root
*/
include ("x2agaarf.php");
ini_set("max_execution_time", "864000");
set_time_limit(0);
$db_host = "localhost";
$db_id = "forresthsia";
$db_password = "hbo45890";
mysql_connect($db_host,$db_id,$db_password);
mysql_select_db ("mlb_atbat");
$allfile = glob("D:/xampp/htdocs/www/mlb_database/inning_all/2007/*");
/*for($i=1; $i<count($allfile); $i++){
	@$inningfile = file_get_contents($allfile[$i]);
	@$inning = xmlstr_to_array($inningfile);
	@$inning_num = $inning['@attributes']['num'];
	@$half = array("top","bottom");
	for ($u=0;$u<2;$u++){
		@$inninghalf = $inning[$half[$u]];
		$atbat = $inninghalf["atbat"];
		for ($s=0;$s<count($atbat);$s++){
			@$game_id = substr($allfile[$i],49,30);//從第53個字元到第79個字元
			@$atbat_num = $atbat[$s]['@attributes']['num'];
			@$ball = $atbat[$s]['@attributes']['b'];
			@$strike = $atbat[$s]['@attributes']['s'];
			@$outs = $atbat[$s]['@attributes']['o'];
			@$start_tfs = $atbat[$s]['@attributes']['start_tfs'];
			@$start_tfs_zulu = $atbat[$s]['@attributes']['start_tfs_zulu'];
			@$batter = $atbat[$s]['@attributes']['batter'];
			@$batter_stand = $atbat[$s]['@attributes']['stand'];
			@$pitcher = $atbat[$s]['@attributes']['pitcher'];
			@$p_throws = $atbat[$s]['@attributes']['p_throws'];
			@$atbat_description = $atbat[$s]['@attributes']['des'];
			@$atbat_event = $atbat[$s]['@attributes']['event'];
			@$atbat_event_num = $atbat[$s]['@attributes']['event_num'];
			@$atbat_play_guid = $atbat[$s]['@attributes']['play_guid'];
			@$home_team_runs = $atbat[$s]['@attributes']['home_team_runs'];
			@$away_team_runs = $atbat[$s]['@attributes']['away_team_runs'];
			@$hit_id_atbat = $game_id.$batter.$pitcher.$inning_num;
			@$query = 'INSERT INTO atbat_2007 (game_id, inning, inning_half, atbat_num, ball, strike, outs, start_tfs, start_tfs_zulu, batter, batter_stand, pitcher, p_throws, description, event_num, event, play_guid, home_team_runs, away_team_runs, hit_id) VALUES ("'.$game_id.'","'.$inning_num.'","'.$half[$u].'","'.$atbat_num.'","'.$ball.'","'.$strike.'","'.$outs.'","'.$start_tfs.'","'.$start_tfs_zulu.'","'.$batter.'","'.$batter_stand.'","'.$pitcher.'","'.$p_throws.'","'.$atbat_description.'","'.$atbat_event_num.'","'.$atbat_event.'","'.$atbat_play_guid.'","'.$home_team_runs.'","'.$away_team_runs.'","'.$hit_id_atbat.'")';
			mysql_query($query);
		}//半局之間結束;
	}//整局之間結束;
}//每場之間結束;
*/
$allfilehit = glob("D:/xampp/htdocs/www/mlb_database/inning_hit/2007/*");
for($j=0; $j<count($allfilehit); $j++){
	$inninghit = file_get_contents($allfilehit[$j]);
	$result = xmlstr_to_array($inninghit);
	$hit = $result["hip"];//裡面只有hit這個array,都用這個array進行
	for($m=0;$m<count($hit);$m++){
		$game_id_hit = substr($allfilehit[$j], 49, 30);//從第49個字元到第79個字元
		$attributes = $hit[$m]["@attributes"];
		$description = $attributes["des"];
		$x = $attributes["x"];
		$y = $attributes["y"];
		$batter = $attributes["batter"];
		$pitcher = $attributes["pitcher"];
		$type = $attributes["type"];
		$inning = $attributes["inning"];
		$hit_id_hit = $game_id_hit.$batter.$pitcher.$inning;
		$query = "UPDATE `atbat_2007` SET `x` = '".$x."', `y` = '".$y."', `type` = '".$type."' WHERE `hit_id` = '".$hit_id_hit."';";
		mysql_query($query);
	}
}

?>