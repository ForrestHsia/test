<?php
//20161224
//game fetch from grid, players, linescore, rawboxscore已臻完備
ini_set("max_execution_time", "864000");
$db_host = "localhost";
$db_id = "forresthsia";
$db_password = "hbo45890";
mysql_connect($db_host,$db_id,$db_password);
$dbselect = mysql_select_db ("mlb_games");
$year = array(2019);
$month = array("03","04","05");
$day = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
//xml to array的php也可以先call進來
//估計不動到它的function的話, 也不會破壞原本就在使用的xml功能與結構
include ("x2agaarf.php");
include ("x2abinco.php");
for($i=0;$i<1;$i++){
	for($j=0;$j<3;$j++){
		for($k=0;$k<31;$k++){
			@$grid = "D:/xampp/htdocs/www/mlb_database/grid/".$year[$i]."/mast/grid_".$year[$i]."_".$month[$j]."_".$day[$k].".xml";
			//讀取每日的grid.xml
			@$xml = simplexml_load_file($grid);
			//然後, 以每日grid內的屬性輸入mysql
			foreach($xml as $games){
				@$game = $games[0]["id"];
				//下為轉換gid格式，以便download XML
				@$gstring = str_replace("-","_",$game);
				@$gstring3 = str_replace("/","_",$gstring);
				@$gstring2 = "gid_".$gstring3;
				@$game_id = $gstring2;
				@$id = (string)$games->attributes()->id;//game_id_number
				@$group = (string)$games->attributes()->group;
				@$game_type = (string)$games->attributes()->game_type;
				@$series = (string)$games->attributes()->series;
				@$series_num = (int)$games->attributes()->series_num;
				@$calendar_event_id = (string)$games->attributes()->calendar_event_id;
				@$game_pk = (int)$games->attributes()->game_pk;
				@$event_time = (string)$games->attributes()->event_time;
				@$status = (string)$games->attributes()->status;
				@$inning = (int)$games->attributes()->inning;
				@$venue = (string)$games->attributes()->venue;
				@$venue_id = (string)$games->attributes()->venue_id;
				@$away_code = (string)$games->attributes()->away_code;
				@$away_team_id = (string)$games->attributes()->away_team_id;
				@$away_name_abbrev = (string)$games->attributes()->away_name_abbrev;
				@$away_team_name = (string)$games->attributes()->away_team_name;
				@$away_score = (int)$games->attributes()->away_score;
				@$home_code = (string)$games->attributes()->home_code;
				@$home_team_id = (string)$games->attributes()->home_team_id;
				@$home_name_abbrev = (string)$games->attributes()->home_name_abbrev;
				@$home_team_name = (string)$games->attributes()->home_team_name;
				@$home_score = (int)$games->attributes()->home_score;
				@$gamequery = 'INSERT INTO games_2019mast (game_id, game_id_number, calendar_event_id, game_group, game_type, series, series_num, game_pk, event_time, status, inning, venue, venue_id, away_code, away_team_id, away_name_abbrev, away_team_name, away_score, home_code, home_team_id, home_name_abbrev, home_team_name, home_score) VALUES ("'.$game_id.'","'.$id.'","'.$calendar_event_id.'","'.$group.'","'.$game_type.'","'.$series.'","'.$series_num.'","'.$game_pk.'","'.$event_time.'","'.$status.'","'.$inning.'","'.$venue.'","'.$venue_id.'","'.$away_code.'","'.$away_team_id.'","'.$away_name_abbrev.'","'.$away_team_name.'","'.$away_score.'","'.$home_code.'","'.$home_team_id.'","'.$home_name_abbrev.'","'.$home_team_name.'","'.$home_score.'")';
				mysql_query($gamequery);
				
				//wind, weather 等等fetch from rawboxscore
				@$rawboxscorexml = "D:/xampp/htdocs/www/mlb_database/rawboxscore/".$year[$i]."/".$gstring2."_rawboxscore.xml";
				@$rawboxscore = simplexml_load_file($rawboxscorexml);
				foreach ($rawboxscore as $a => $b){
					@$wind = (string)$rawboxscore -> attributes() -> wind;
					@$weather = (string)$rawboxscore -> attributes() -> weather;
					@$attendance_1 = (string)$rawboxscore -> attributes() -> attendance;
					@$attendance = str_replace(",","",$attendance_1);
					@$elapsed_time = (string)$rawboxscore -> attributes() -> elapsed_time;
					@$rawboxscorequery = "UPDATE `games_".$year[$i]."`
						          SET `wind` = '".$wind."',
								      `weather` = '".$weather."',
									  `attendance` = '".$attendance."',
									  `elapsed_time` = '".$elapsed_time."'
							    WHERE `game_id` = '".$gstring2."'";
						mysql_query($rawboxscorequery);
				}
				
				//勝投敗投救援 fetch from linescorexml
				@$linescorexml = ("D:/xampp/htdocs/www/mlb_database/linescore/".$year[$i]."/".$gstring2."_linescore.xml");
				@$linescore = file_get_contents($linescorexml);
				@$linescoreresult = xmlstr_to_array($linescore);
				@$winningpitcher = $linescoreresult["winning_pitcher"]["@attributes"]["id"];
				@$losingpitcher = $linescoreresult["losing_pitcher"]["@attributes"]["id"];
				@$savepitcher = $linescoreresult["save_pitcher"]["@attributes"]["id"];
				@$linescorequery = "UPDATE `games_".$year[$i]."` SET
				`winning_pitcher_id` = '".$winningpitcher."',
				`losing_pitcher_id` = '".$losingpitcher."',
				`save_pitcher_id` = '".$savepitcher."'WHERE
				`game_id` = '".$gstring2."'";
				mysql_query($linescorequery);
				
				@$playersxml = ('D:/xampp/htdocs/www/mlb_database/players/'.$year[$i].'/'.$gstring2.'_players.xml');
				@$players = file_get_contents($playersxml);
				@$result = xml2array($players,1);
				@$umpire = $result["game"]["umpires"]["umpire"];
				@$umpire_h_name = $umpire["0_attr"]["name"];
				@$umpire_h_id = $umpire["0_attr"]["id"];
				@$umpire_1b_name = $umpire["1_attr"]["name"];
				@$umpire_1b_id = $umpire["1_attr"]["id"];
				@$umpire_2b_name = $umpire["2_attr"]["name"];
				@$umpire_2b_id = $umpire["2_attr"]["id"];
				@$umpire_3b_name = $umpire["3_attr"]["name"];
				@$umpire_3b_id = $umpire["3_attr"]["id"];
				@$umpire_lf_name = $umpire["4_attr"]["name"];
				@$umpire_lf_id = $umpire["4_attr"]["id"];
				@$umpire_rf_name = $umpire["5_attr"]["name"];
				@$umpire_rf_id = $umpire["5_attr"]["id"];
				@$query = "UPDATE `games_".$year[$i]."`
						          SET `umpire_h_name` = '".$umpire_h_name."',
								      `umpire_h_id` = '".$umpire_h_id."',
									  `umpire_1b_name` = '".$umpire_1b_name."',
								      `umpire_1b_id` = '".$umpire_1b_id."',
									  `umpire_2b_name` = '".$umpire_2b_name."',
								      `umpire_2b_id` = '".$umpire_2b_id."',
									  `umpire_3b_name` = '".$umpire_3b_name."',
								      `umpire_3b_id` = '".$umpire_3b_id."',
									  `umpire_lf_name` = '".$umpire_lf_name."',
								      `umpire_lf_id` = '".$umpire_lf_id."',
									  `umpire_rf_name` = '".$umpire_rf_name."',
								      `umpire_rf_id` = '".$umpire_rf_id."'
								  WHERE `game_id` = '".$gstring2."'";
								  mysql_query($query);
								  
				//inning_all_xml檢測, 看看inning_all.xml是不是沒有download到, 有的時候因雨延賽的話, inning_all.xml裡面就會是空的
				@$inningallxml = "D:/xampp/htdocs/www/mlb_database/inning_all/".$year[$i]."/".$gstring2."_inning_all.xml";
				@$inningall = fopen($inningallxml,"r");
				if (!$inningall){
					@$inningallquery = "UPDATE `games_".$year[$i]."` SET `inning_all_xml` = 'N' WHERE `game_id` = '".$game_id."';";
					mysql_query($inningallquery);
					fclose($inningall);
					
				}else{
					@$inningallquery = "UPDATE `games_".$year[$i]."` SET `inning_all_xml` = 'Y',`inning_all_xml_size` = '".(filesize($inningallxml)/1000)."' WHERE `game_id` = '".$game_id."';";
					mysql_query($inningallquery);
					fclose($inningall);
					
				}
				
				//inning_hit_xml檢測
				@$inninghitxml = "D:/xampp/htdocs/www/mlb_database/inning_hit/".$year[$i]."/".$gstring2."_inning_hit.xml";
				@$inninghit = fopen($inninghitxml,"r");
				if (!$inninghit){
					@$inninghitquery = "UPDATE `games_".$year[$i]."` SET `inning_hit_xml` = 'N' WHERE `game_id` = '".$game_id."';";
					mysql_query($inninghitquery);
					fclose($inninghit);
					
				}else{
					@$inninghitquery = "UPDATE `games_".$year[$i]."` SET `inning_hit_xml` = 'Y',`inning_hit_xml_size` = '".(filesize($inninghitxml)/1000)."' WHERE `game_id` = '".$game_id."';";
					mysql_query($inninghitquery);
					fclose($inninghit);
					
				}
				
				//players_xml 檢測
				@$players_xml = "D:/xampp/htdocs/www/mlb_database/players/".$year[$i]."/".$gstring2."_players.xml";
				@$players = fopen($players_xml,"r");
				if (!$players){
					@$playersquery = "UPDATE `games_".$year[$i]."` SET `players_xml` = 'N' WHERE `game_id` = '".$game_id."';";
					mysql_query($playersquery);
					fclose($players);
					
				}else{
					@$playersquery = "UPDATE `games_".$year[$i]."` SET `players_xml` = 'Y',`players_xml_size` = '".(filesize($players_xml)/1000)."' WHERE `game_id` = '".$game_id."';";
					mysql_query($playersquery);
					fclose($players);
					
				}
				
				//rawboxscore_xml 檢測
				@$rawboxscore_xml = "D:/xampp/htdocs/www/mlb_database/rawboxscore/".$year[$i]."/".$gstring2."_rawboxscore.xml";
				@$rawboxscore = fopen($rawboxscore_xml,"r");
				if (!$rawboxscore){
					@$rawboxscorequery = "UPDATE `games_".$year[$i]."` SET `rawboxscore_xml` = 'N' WHERE `game_id` = '".$game_id."';";
					mysql_query($rawboxscorequery);
					fclose($rawboxscore);
					
				}else{
					@$rawboxscorequery = "UPDATE `games_".$year[$i]."` SET `rawboxscore_xml` = 'Y',`rawboxscore_xml_size` = '".(filesize($rawboxscore_xml)/1000)."' WHERE `game_id` = '".$game_id."';";
					mysql_query($rawboxscorequery);
					fclose($rawboxscore);
					
				}
				
				//linescore_xml 檢測
				@$linescore_xml = "D:/xampp/htdocs/www/mlb_database/linescore/".$year[$i]."/".$gstring2."_linescore.xml";
				@$linescore = fopen($linescore_xml,"r");
				if (!$linescore){
					@$linescorequery = "UPDATE `games_".$year[$i]."` SET `linescore_xml` = 'N' WHERE `game_id` = '".$game_id."';";
					mysql_query($linescorequery);
					fclose($linescore);
					
				}else{
					@$linescorequery = "UPDATE `games_".$year[$i]."` SET `linescore_xml` = 'Y',`linescore_xml_size` = '".(filesize($linescore_xml)/1000)."' WHERE `game_id` = '".$game_id."';";
					mysql_query($linescorequery);
					fclose($linescore);
					
				}
				
			}
		}
	}
}

?>