<?php


//20161224
//players, coach以及umpire list全部一併fetch from players.xml
ini_set("max_execution_time", "86400");
set_time_limit(0);
$db_host = "localhost";
$db_id = "forresthsia";
$db_password = "hbo45890";
mysql_connect($db_host,$db_id,$db_password);
$dbselect = mysql_select_db ("mlb_umpire");
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
			foreach($xml as $games){
				@$game = $games[0]["id"];
				@$gstring = str_replace("-","_",$game);
				@$gstring3 = str_replace("/","_",$gstring);
				@$gstring2 = "gid_".$gstring3;
				@$playersxml = ("D:/xampp/htdocs/www/mlb_database/players/".$year[$i]."/".$gstring2."_players.xml");
				@$players = file_get_contents($playersxml);
				@$playersresult = xmlstr_to_array($players);
				@$team = $playersresult["team"];//裡面只有兩個array, 分別是客隊跟主隊的team array
				@$umpire = $playersresult["umpires"]["umpire"];//umpire的級別同於team
				for($w=0;$w<count($umpire);$w++){
					@$game_id = $gstring2;
					@$umpireattributes = $umpire[$w]["@attributes"];
					@$umpireposition = $umpireattributes["position"];
					@$umpirename = $umpireattributes["name"];
					@$umpireid = $umpireattributes["id"];
					@$umpirefirst = $umpireattributes["first"];
					@$umpirelast = $umpireattributes["last"];
					$dbselect = mysql_select_db ("mlb_umpire");
					@$query = 'INSERT INTO umpire_'.$year[$i].' (game_id, umpire_id, umpire_position, umpire_name, umpire_first, umpire_last) VALUES ("'.$game_id.'","'.$umpireid .'","'.$umpireposition.'","'.$umpirename.'","'.$umpirefirst.'","'.$umpirelast.'")';
                	mysql_query($query);
				}
				for($t=0;$t<count($team);$t++){
					@$teamattributes = $team[$t]["@attributes"];
					@$teamcoach = $team[$t]["coach"];// 裡面有著隊上登錄的所有coach資料, 10幾個array不等
					@$teamplayer = $team[$t]["player"];// 裡面有著隊上登錄的所有player資料, 30或32個array不等
					for($u=0;$u<count($teamplayer);$u++){
						@$teamplayerattributes = $teamplayer[$u]["@attributes"];
						@$playercurrentposition = $teamplayerattributes["current_position"];
						@$playerbats = $teamplayerattributes["bats"];
						@$game_id = $gstring2;
						@$teamtype = $teamattributes["type"];
						@$teamid = $teamattributes["id"];
                		@$teamname = $teamattributes["name"];
                		@$playerid = $teamplayerattributes["id"];
                		@$playerfirst = $teamplayerattributes["first"];
                		@$playerlast = $teamplayerattributes["last"];
                		@$playernum = $teamplayerattributes["num"];
                		@$playerrl = $teamplayerattributes["rl"];
                		@$playerbats = $teamplayerattributes["bats"];
                		@$playerposition = $teamplayerattributes["position"];
                		@$playercurrentposition = $teamplayerattributes["current_position"];
                		@$playergameposition = $teamplayerattributes["game_position"];
                		@$playerbatorder = $teamplayerattributes["bat_order"];
                		@$playerstatus = $teamplayerattributes["status"];
						$dbselect = mysql_select_db ("mlb_players");
                		@$query = 'INSERT INTO players_'.$year[$i].' (game_id, team_type, team_id, team_name, player_id, player_first, player_last, player_num, player_rl, player_bats, player_position, player_currentposition, player_gameposition, player_batorder, player_status) VALUES ("'.$game_id.'","'.$teamtype.'","'.$teamid.'","'.$teamname.'","'.$playerid.'","'.$playerfirst.'","'.$playerlast.'","'.$playernum.'","'.$playerrl.'","'.$playerbats.'","'.$playerposition.'","'.$playercurrentposition.'","'.$playergameposition.'","'.$playerbatorder.'","'.$playerstatus.'")';
                		mysql_query($query);
						}
					for($v=0;$v<count($teamcoach);$v++){
						@$teamcoachattributes = $teamcoach[$v]["@attributes"];
						@$game_id = $gstring2;
						@$teamtype = $teamattributes["type"];
						@$teamid = $teamattributes["id"];
                		@$teamname = $teamattributes["name"];
                		@$coachid = $teamcoachattributes["id"];
                		@$coachfirst = $teamcoachattributes["first"];
                		@$coachlast = $teamcoachattributes["last"];
                		@$coachnum = $teamcoachattributes["num"];
                		@$coachposition = $teamcoachattributes["position"];
						$dbselect = mysql_select_db ("mlb_coach");
                		@$query = 'INSERT INTO coach_'.$year[$i].' (game_id, team_type, team_id, team_name, coach_position, coach_first, coach_last, coach_id, coach_num) VALUES ("'.$game_id.'","'.$teamtype.'","'.$teamid.'","'.$teamname.'","'.$coachposition.'","'.$coachfirst.'","'.$coachlast.'","'.$coachid.'","'.$coachnum.'")';
                		mysql_query($query);
						}
				}
			}
		}
	}
}
?>