SELECT *  FROM `games_2016` WHERE `status` LIKE 'Cancelled'
ORDER BY `games_2016`.`game_type` ASC



SELECT * FROM `games_2017` 
WHERE `game_type` != 'S' and
 `game_type` != 'e' and 
 `game_group` != 'WBC' and
 `status` != 'Postponed' and
(`inning_all_xml` != "Y" or
  `inning_hit_xml` != "Y" or
  `players_xml` != "Y" or
  `rawboxscore_xml` != "Y"  
)
ORDER BY `game_group` DESC
 
 
 
 
 
SELECT * FROM `games_2017` 
WHERE `game_type` != 'S' and
 `game_type` != 'e' and 
 `game_group` != 'WBC' and
 `status` != 'Postponed'
ORDER BY `game_group` DESC