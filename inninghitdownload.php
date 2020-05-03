<?php
//抓inning_hit.xml用的
ini_set("max_execution_time", "864000");
$year = array();
$month = array("03","04","05","06","07","08","09","10","11");
$day = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
for($i=0;$i<1;$i++){
	for($j=0;$j<9;$j++){
		for($k=0;$k<=30;$k++){
			//讀取每日的grid.xml,
			$grid = "D:/xampp/htdocs/www/mlb_database/grid/".$year[$i]."/grid_".$year[$i]."_".$month[$j]."_".$day[$k].".xml";
			@$xml = simplexml_load_file($grid);
			foreach($xml as $games){
				$game = $games[0]["id"];
				$gstring = str_replace("-","_",$game);
				$gstring3 = str_replace("/","_",$gstring);
				$gstring2 = "gid_".$gstring3;
				$content = "";
				$fp_hit = fopen("http://gd2.mlb.com/components/game/mlb/year_".$year[$i]."/month_".$month[$j]."/day_".$day[$k]."/".$gstring2."/inning/inning_hit.xml","rb");
				if (!$fp_hit)
					break;
				while (!feof($fp_hit))
					$content .= fread($fp_hit, 4096);
				fclose($fp_hit);
				$fp_hit=fopen("D:/xampp/htdocs/www/mlb_database/inning_hit/".$year[$i]."/".$gstring2."_inning_hit.xml", "w+");
				fwrite($fp_hit, $content);
				fclose($fp_hit);
			}
		}
	}
}
?>