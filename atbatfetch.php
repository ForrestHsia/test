<?php
//20161224
//atbat fetch from inng_all跟inning_hit已臻完整
//待修正mysql的設定後全部重run, hit_x跟hit_y的輸入速度已大幅改善
ini_set("max_execution_time", "864000");
set_time_limit(0);
$db_host = "localhost";
$db_id = "forresthsia";
$db_password = "hbo45890";
mysql_connect($db_host,$db_id,$db_password);
$dbselect = mysql_select_db ("mlb_atbat");
$year = array(2018);
$month = array("03","04","05","06","07","08","09","10");
$day = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
include ("x2agaarf.php");
for($i=0;$i<1;$i++){
	for($j=0;$j<8;$j++){
		for($k=0;$k<31;$k++){
			//讀取每日的grid.xml
			@$grid = "D:/xampp/htdocs/www/mlb_database/grid/".$year[$i]."/grid_".$year[$i]."_".$month[$j]."_".$day[$k].".xml";
			@$xml = simplexml_load_file($grid);
			//然後, 以每日grid內的id屬性質帶入$mlbgamexml
			foreach($xml as $games){
				@$game = $games[0]["id"];
				@$gstring = str_replace("-","_",$game);
				@$gstring3 = str_replace("/","_",$gstring);
				@$gstring2 = "gid_".$gstring3;
				@$game_id = $gstring2;
				@$inningallxml = simplexml_load_file("D:/xampp/htdocs/www/mlb_database/inning_all/".$year[$i]."/".$gstring2."_inning_all.xml");
				foreach($inningallxml as $game => $inning){
					$inning_num = (string)$inning -> attributes() -> num;
					//每個inning之間,有top跟bottom
					//不要視作array處理，讓"$inning->"直接到$inning -> top/bottom就好
					for($l=0;$l<2;$l++){
						@$half = array("top","bottom");
						foreach($inning -> $half[$l] as $halfinning){
							foreach ($halfinning as $atbat){
								@$atbat_num = (string)$atbat -> attributes() -> num;
								@$ball = (string)$atbat -> attributes() -> b;
								@$strike = (string)$atbat -> attributes() -> s;
								@$outs = (string)$atbat -> attributes() -> o;
								@$start_tfs = (string)$atbat -> attributes() -> start_tfs;
								@$start_tfs_zulu = (string)$atbat -> attributes() -> start_tfs_zulu;
								@$batter = (string)$atbat -> attributes() -> batter;
								@$batter_stand = (string)$atbat -> attributes() -> stand;
								@$pitcher = (string)$atbat -> attributes() -> pitcher;
								@$p_throws = (string)$atbat -> attributes() -> p_throws;
								@$atbat_description = (string)$atbat -> attributes() -> des;
								@$atbat_event = (string)$atbat -> attributes() -> event;
								@$atbat_event_num = (string)$atbat -> attributes() -> event_num;
								@$atbat_play_guid = (string)$atbat -> attributes() -> play_guid;
								@$home_team_runs = (string)$atbat -> attributes() -> home_team_runs;
								@$away_team_runs = (string)$atbat -> attributes() -> away_team_runs;
								@$hit_id = $gstring2.$batter.$pitcher.$inning_num;
								@$query = 'INSERT INTO atbat_'.$year[$i].' (game_id, inning, inning_half, atbat_num, ball, strike, outs, start_tfs, start_tfs_zulu, batter, batter_stand, pitcher, p_throws, description, event_num, event, play_guid, home_team_runs, away_team_runs, hit_id) VALUES ("'.$game_id.'","'.$inning_num.'","'.$half[$l].'","'.$atbat_num.'","'.$ball.'","'.$strike.'","'.$outs.'","'.$start_tfs.'","'.$start_tfs_zulu.'","'.$batter.'","'.$batter_stand.'","'.$pitcher.'","'.$p_throws.'","'.$atbat_description.'","'.$atbat_event_num.'","'.$atbat_event.'","'.$atbat_play_guid.'","'.$home_team_runs.'","'.$away_team_runs.'","'.$hit_id.'")';
								mysql_query($query);
							}
						}//半局之間結束;
					}//整局之間結束;
				}//每場之間結束;
			    @$inninghitxml = ("D:/xampp/htdocs/www/mlb_database/inning_hit/".$year[$i]."/".$gstring2."_inning_hit.xml");
				@$inninghit = file_get_contents($inninghitxml);
				@$result = xmlstr_to_array($inninghit);
				@$hit = $result["hip"];//裡面只有hit這個array,都用這個array進行
				for($m=0;$m<count($hit);$m++){
					@$attributes = $hit[$m]["@attributes"];
					@$description = $attributes["des"];
					@$x = $attributes["x"];
					@$y = $attributes["y"];
					@$batter = $attributes["batter"];
					@$pitcher = $attributes["pitcher"];
					@$type = $attributes["type"];
					@$inning = $attributes["inning"];
					@$hit_id = $gstring2.$batter.$pitcher.$inning;
					@$query = "UPDATE `atbat_".$year[$i]."` SET `x` = '".$x."', `y` = '".$y."', `type` = '".$type."' WHERE `hit_id` = '".$hit_id."';";
					mysql_query($query);
					}
			}
		}
	}
}
?>