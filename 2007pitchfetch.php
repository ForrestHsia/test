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
mysql_select_db ("mlb_pitch");
$allfile = glob("D:/xampp/htdocs/www/mlb_database/inning_all/2007/*");
for($i=1; $i<count($allfile); $i++){
	$inningfile = file_get_contents($allfile[$i]);
	$inning = xmlstr_to_array($inningfile);
	@$half = array("top","bottom");
	for($u=0; $u<2; $u++){
		@$inninghalf = $inning[$half[$u]];
		@$atbat = $inninghalf["atbat"];
		/*
		$atbat本身是array, 每個$inninghalf裡面的atbat都會倒到$atbat裡面去, 然後再用foreach把每個atbat分別抓出來
		然而, $atbat裡面還有runner跟action
		這是下一階段的重點
		*/
		for($v=0; $v<count($atbat); $v++){
			@$pitch = $atbat[$v]["pitch"];
			for($w=0; $w<count($pitch); $w++){
				@$attributes = $pitch[$w]["@attributes"];
				@$game_id = substr($allfile[$i],50,30);//從第50個字元到第79個字元
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
				mysql_select_db ("mlb_pitch");
			    @$query = 'INSERT INTO pitch_2007 (game_id, atbat_num, atbat_batter, atbat_batterstand, atbat_sequence, atbat_pitcher, atbat_pitcherthrows, pitch_sequence, pitch_des, pitch_id, pitch_type, pitch_tfs, pitch_tfs_zulu, pitch_x, pitch_y, pitch_event_num, pitch_on1b, pitch_on2b, pitch_on3b, pitch_sv_id, pitch_play_guid, pitch_start_speed, pitch_end_speed, pitch_sz_top, pitch_sz_bot, pitch_pfx_x, pitch_pfx_z, pitch_px, pitch_pz, pitch_x0, pitch_y0, pitch_z0, pitch_vx0, pitch_vy0, pitch_vz0, pitch_ax, pitch_ay, pitch_az, pitch_break_y, pitch_break_angle, pitch_break_length,  pitch_pitch_type, pitch_type_confidence, pitch_zone, pitch_nasty, pitch_spin_dir, pitch_spin_rate, pitch_cc, pitch_mt) VALUES ("'.$game_id.'","'.$atbat_num.'","'.$atbat_batter.'","'.$atbat_batterstand.'","'.$atbat_sequence.'","'.$atbat_pitcher.'","'.$atbat_pitcherthrows.'","'.$pitch_sequence.'","'.$pitch_des.'","'.$pitch_id.'","'.$pitch_type.'","'.$pitch_tfs.'","'.$pitch_tfs_zulu.'","'.$pitch_x.'","'.$pitch_y.'","'.$pitch_event_num.'","'.$pitch_on1b.'","'.$pitch_on2b.'","'.$pitch_on3b.'","'.$pitch_sv_id.'","'.$pitch_play_guid.'","'.$pitch_start_speed.'","'.$pitch_end_speed.'","'.$pitch_sz_top.'","'.$pitch_sz_bot.'","'.$pitch_pfx_x.'","'.$pitch_pfx_z.'","'.$pitch_px.'","'.$pitch_pz.'","'.$pitch_x0.'","'.$pitch_y0.'","'.$pitch_z0.'","'.$pitch_vx0.'","'.$pitch_vy0.'","'.$pitch_vz0.'","'.$pitch_ax.'","'.$pitch_ay.'","'.$pitch_az.'","'.$pitch_break_y.'","'.$pitch_break_angle.'","'.$pitch_break_length.'","'.$pitch_pitch_type.'","'.$pitch_type_confidence.'","'.$pitch_zone.'","'.$pitch_nasty.'","'.$pitch_spin_dir.'","'.$pitch_spin_rate.'","'.$pitch_cc.'","'.$pitch_mt.'")';
				mysql_query($query);
			}
		}
	}
}



?>