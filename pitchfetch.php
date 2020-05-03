<?php


//要抓半局半局之間的資料
//要從allfetch的code著手
//畢竟grid.xml並沒有半局之間的分別
//==================
//20161224
//pitch fetch未變更
ini_set("max_execution_time", "864000");
set_time_limit(0);
$db_host = "localhost";
$db_id = "forresthsia";
$db_password = "hbo45890";
mysql_connect($db_host,$db_id,$db_password);
$dbselect = mysql_select_db ("mlb_pitch");
$year = array(2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018);
$month = array("11");//20181201, 有November的另計
$day = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
include ("x2agaarf.php");
for($i=0;$i<11;$i++){
	for($j=0;$j<1;$j++){
		for($k=0;$k<31;$k++){
			//讀取每日的grid.xml
			@$grid = "D:/xampp/htdocs/www/mlb_database/grid/".$year[$i]."/grid_".$year[$i]."_".$month[$j]."_".$day[$k].".xml";
			@$xml = simplexml_load_file($grid);
			foreach($xml as $games){
				@$game = $games[0]["id"];
				@$gstring = str_replace("-","_",$game);
				@$gstring3 = str_replace("/","_",$gstring);
				@$gstring2 = "gid_".$gstring3;
				@$inningallxml = ("D:/xampp/htdocs/www/mlb_database/inning_all/".$year[$i]."/".$gstring2."_inning_all.xml");
				@$inningall = file_get_contents($inningallxml);
				@$result = xmlstr_to_array($inningall);
				@$inning = $result["inning"];//裡面有3個array, 分別是top, bottom跟@attributes, 但@attributes的值是inning 裡面的屬性質而已, 就那麼兩三個
				for($t=0; $t<count($inning); $t++){
					for($u=0; $u<2; $u++){
						@$half = array("top","bottom");
						@$inninghalf = $inning[$t][$half[$u]];
					    @$atbat = $inninghalf["atbat"];
						for($v=0; $v<count($atbat); $v++){
							@$pitch = $atbat[$v]["pitch"];
							for($w=0; $w<count($pitch); $w++){
								@$attributes = $pitch[$w]["@attributes"];
								@$game_id = $gstring2;
								@$atbat_num = $atbat[$v]["@attributes"]["num"];//atbat_num是指整場比賽的第幾個打席
    							@$atbat_batter = $atbat[$v]["@attributes"]["batter"];
	    						@$atbat_batterstand = $atbat[$v]["@attributes"]["stand"];
		    					@$atbat_sequence = $v+1;//atbat_sequence是指半局裡的的第幾個打席
			    				@$atbat_pitcher = $atbat[$v]["@attributes"]["pitcher"];
	    						@$atbat_pitcherthrows = $atbat[$v]["@attributes"]["p_throws"];
		    					@$pitch_sequence = $w+1;//pitch_sequence是指整個atbat裡面投的第幾顆球
			    				@$pitch_des = $attributes["des"];
				    			@$pitch_id = $attributes["id"];
					    		@$pitch_type = $attributes["type"];
    							@$pitch_tfs = $attributes["tfs"];
	    						@$pitch_tfs_zulu = $attributes["tfs_zulu"];
		    					@$pitch_x = $attributes["x"];
			    				@$pitch_y = $attributes["y"];
				    			@$pitch_event_num = $attributes["event_num"];
								@$pitch_on1b = $attributes["on_1b"];
								@$pitch_on2b = $attributes["on_2b"];
								@$pitch_on3b = $attributes["on_3b"];
					    		@$pitch_sv_id = $attributes["sv_id"];
						    	@$pitch_play_guid = $attributes["play_guid"];
							    @$pitch_start_speed = $attributes["start_speed"];
    							@$pitch_end_speed = $attributes["end_speed"];
	    						@$pitch_sz_top = $attributes["sz_top"];
		    					@$pitch_sz_bot = $attributes["sz_bot"];
			    				@$pitch_pfx_x = $attributes["pfx_x"];
				    			@$pitch_pfx_z = $attributes["pfx_z"];
					    		@$pitch_px = $attributes["px"];
						    	@$pitch_pz = $attributes["pz"];
							    @$pitch_x0 = $attributes["x0"];
    							@$pitch_y0 = $attributes["y0"];
	    						@$pitch_z0 = $attributes["z0"];
		    					@$pitch_vx0 = $attributes["vx0"];
			    				@$pitch_vy0 = $attributes["vy0"];
				    			@$pitch_vz0 = $attributes["vz0"];
					    		@$pitch_ax = $attributes["ax"];
						    	@$pitch_ay = $attributes["ay"];
							    @$pitch_az = $attributes["az"];
    							@$pitch_break_y = $attributes["break_y"];
	    						@$pitch_break_angle = $attributes["break_angle"];
		    					@$pitch_break_length = $attributes["break_length"];
			    				@$pitch_pitch_type = $attributes["pitch_type"];
				    			@$pitch_type_confidence = $attributes["type_confidence"];
					    		@$pitch_zone = $attributes["zone"];
						    	@$pitch_nasty = $attributes["nasty"];
							    @$pitch_spin_dir = $attributes["spin_dir"];
    							@$pitch_spin_rate = $attributes["spin_rate"];
	    						@$pitch_cc = $attributes["cc"];
		    					@$pitch_mt = $attributes["mt"];
			    				@$query = 'INSERT INTO pitch_'.$year[$i].' (game_id, atbat_num, atbat_batter, atbat_batterstand, atbat_sequence, atbat_pitcher, atbat_pitcherthrows, pitch_sequence, pitch_des, pitch_id, pitch_type, pitch_tfs, pitch_tfs_zulu, pitch_x, pitch_y, pitch_event_num, pitch_on1b, pitch_on2b, pitch_on3b, pitch_sv_id, pitch_play_guid, pitch_start_speed, pitch_end_speed, pitch_sz_top, pitch_sz_bot, pitch_pfx_x, pitch_pfx_z, pitch_px, pitch_pz, pitch_x0, pitch_y0, pitch_z0, pitch_vx0, pitch_vy0, pitch_vz0, pitch_ax, pitch_ay, pitch_az, pitch_break_y, pitch_break_angle, pitch_break_length,  pitch_pitch_type, pitch_type_confidence, pitch_zone, pitch_nasty, pitch_spin_dir, pitch_spin_rate, pitch_cc, pitch_mt) VALUES ("'.$game_id.'","'.$atbat_num.'","'.$atbat_batter.'","'.$atbat_batterstand.'","'.$atbat_sequence.'","'.$atbat_pitcher.'","'.$atbat_pitcherthrows.'","'.$pitch_sequence.'","'.$pitch_des.'","'.$pitch_id.'","'.$pitch_type.'","'.$pitch_tfs.'","'.$pitch_tfs_zulu.'","'.$pitch_x.'","'.$pitch_y.'","'.$pitch_event_num.'","'.$pitch_on1b.'","'.$pitch_on2b.'","'.$pitch_on3b.'","'.$pitch_sv_id.'","'.$pitch_play_guid.'","'.$pitch_start_speed.'","'.$pitch_end_speed.'","'.$pitch_sz_top.'","'.$pitch_sz_bot.'","'.$pitch_pfx_x.'","'.$pitch_pfx_z.'","'.$pitch_px.'","'.$pitch_pz.'","'.$pitch_x0.'","'.$pitch_y0.'","'.$pitch_z0.'","'.$pitch_vx0.'","'.$pitch_vy0.'","'.$pitch_vz0.'","'.$pitch_ax.'","'.$pitch_ay.'","'.$pitch_az.'","'.$pitch_break_y.'","'.$pitch_break_angle.'","'.$pitch_break_length.'","'.$pitch_pitch_type.'","'.$pitch_type_confidence.'","'.$pitch_zone.'","'.$pitch_nasty.'","'.$pitch_spin_dir.'","'.$pitch_spin_rate.'","'.$pitch_cc.'","'.$pitch_mt.'")';
    							mysql_query($query);
		    				}
				    	}
    				}
		    	}
			}
		}
	}
}
?>