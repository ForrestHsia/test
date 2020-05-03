<?php

//日期形式是固定的, 所以把年分, 月份跟日期用array的方式先用好
//20200126
//mlb把xml鎖起來了, grid is not supported anymore, 目前先用master 跟 mini頂著2019先
$year = array();
$month = array("03","04","05","06","07","08","09","10","11");
$day = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
for($i=0;$i<2;$i++){
	for($j=0;$j<7;$j++){
		for($k=0;$k<=30;$k++){
			$content = "";
			$fp = fopen("http://gd2.mlb.com/components/game/mlb/year_".$year[$i]."/month_".$month[$j]."/day_".$day[$k]."/grid.xml","rb");
			if (!$fp)
				break;
			while (!feof($fp))
				$content .= fread($fp, 4096);
			fclose($fp);
			//先把grid.xml抓下來, 放在local裡面再慢慢弄
			$fp=fopen("D:/xampp/htdocs/www/mlb_database/grid/".$year[$i]."/grid_".$year[$i]."_".$month[$j]."_".$day[$k].".xml", "w+");
			fwrite($fp, $content);
			fclose($fp);
		}
	}
}
?>


