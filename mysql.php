<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "esms";

$con = mysqli_connect($host, $user , $password, $database);
if (!$con)
 {die('Could not connect: ' . mysql_error());} 
 	
 	mysqli_query($con, "CREATE DATABASE IF NOT EXISTS `esms`");
    //mysqli_select_db( $database , $con); 
    
	mysqli_query($con, "SET character_set_results=utf8");
	mb_language('uni'); 
	mb_internal_encoding('UTF-8');
	mysqli_query($con, "set names 'utf8'");



	mysqli_query($con, "CREATE TABLE IF NOT EXISTS `esms_users`(
					`userID` bigint(12) NOT NULL AUTO_INCREMENT ,
					`username` varchar(80) NOT NULL,
					`password` varchar(80) NOT NULL,
					`temp_password` varchar(80) NULL,
					`email` varchar(80) NOT NULL,
                                        `level` int(2) NOT NULL DEFAULT 1,
					`code` varchar(80) NOT NULL,
					`active` int(1) NOT NULL,
					`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					`remember_token` varchar(100) NULL,
					
					PRIMARY KEY(`userID`),
					UNIQUE(`username`,`email`)
					)
					DEFAULT CHARACTER SET = utf8
				");

	mysqli_query($con, "CREATE TABLE IF NOT EXISTS `esms_teams` (
					`teamID` bigint(12) NOT NULL AUTO_INCREMENT,
					`tag` varchar(30) NOT NULL,
					`name` varchar(60) NOT NULL,
					`captain` bigint(12) NOT NULL,
					`facebook` varchar(80) NULL,
					`twitter` varchar(80) NULL,
					`website` varchar(80) NULL,
					`about` text NULL,
					`avatar` varchar(255) NULL,
					`country` varchar(255) NULL,

					`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

					PRIMARY KEY (`teamID`),
					UNIQUE(`name`,`tag`)
					)
					DEFAULT CHARACTER SET = utf8
				");

	mysqli_query($con, "CREATE TABLE IF NOT EXISTS `esms_players`(
					`playerID` bigint(12) NOT NULL AUTO_INCREMENT,
					`userID` bigint(12) NOT NULL,
					`name` varchar(60) NULL,
					`last_name` varchar(60) NULL,
					`nick` varchar(100) NULL,
					`teamID` bigint(12) NULL,
					`avatar` varchar(255) NULL,
					`position` varchar(255) NULL,
					`bio` text NULL,
					`country` varchar(255) NULL,
					`facebook` varchar(80) NULL,
					`twitter` varchar(80) NULL,
					`website` varchar(80) NULL,
					`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

					PRIMARY KEY(`playerID`),
					FOREIGN KEY(`userID`) REFERENCES esms_users(userID) ON DELETE CASCADE ON UPDATE CASCADE,
					FOREIGN KEY(`teamID`) REFERENCES esms_teams(teamID) ON DELETE SET NULL ON UPDATE CASCADE
					)
					DEFAULT CHARACTER SET = utf8
				");

	mysqli_query($con, "ALTER TABLE `esms_teams` ADD FOREIGN KEY (`captain`) REFERENCES esms_players(playerID) ");

	mysqli_query($con, "CREATE TABLE IF NOT EXISTS `esms_tournaments` (
					`tournamentID` bigint(12) NOT NULL AUTO_INCREMENT,
					`starting` TIMESTAMP NOT NULL,
					`max_teams` int(5) NULL,
					`name` varchar(255) NULL,
					`prizepool` varchar(255) NULL,
					`reg_open` int(1) NULL,
                                        `type` varchar(30) NOT NULL,
                                        `cover` varchar(255) NULL,
					`winnerID` bigint(12) NULL,
					`second_place` bigint(12) NULL,
					`third_place` bigint(12) NULL,
					`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

					PRIMARY KEY(`tournamentID`),
					FOREIGN KEY(`winnerID`) REFERENCES esms_teams(teamID) ON DELETE NO ACTION ON UPDATE CASCADE,
					FOREIGN KEY(`second_place`) REFERENCES esms_teams(teamID) ON DELETE NO ACTION ON UPDATE CASCADE,
					FOREIGN KEY(`third_place`) REFERENCES esms_teams(teamID) ON DELETE NO ACTION ON UPDATE CASCADE
					)
					DEFAULT CHARACTER SET = utf8
				");

	mysqli_query($con, "CREATE TABLE IF NOT EXISTS `esms_matches` (
					`matchID` bigint(12) NOT NULL AUTO_INCREMENT,
					`host` bigint(12) NOT NULL,
					`guest` bigint(12) NOT NULL,
					`winnerID` bigint(12) NULL,
					`time` TIMESTAMP NULL,
					`tournamentID` bigint(12) NOT NULL,
					`tournament_phase` varchar(60) NULL,
					`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

					PRIMARY KEY(`matchID`),
					FOREIGN KEY(`host`) REFERENCES esms_teams(teamID) ON DELETE NO ACTION ON UPDATE CASCADE,
					FOREIGN KEY(`guest`) REFERENCES esms_teams(teamID) ON DELETE NO ACTION ON UPDATE CASCADE,
					FOREIGN KEY(`tournamentID`) REFERENCES esms_tournaments(tournamentID) ON DELETE CASCADE ON UPDATE CASCADE
					)
					DEFAULT CHARACTER SET = utf8
				");
	mysqli_query($con, "CREATE TABLE IF NOT EXISTS `esms_player_scores` (
					`locID` bigint(12) NOT NULL AUTO_INCREMENT,
					`tournamentID` bigint(12) NOT NULL,
					`matchID` bigint(12) NOT NULL,
					`playerID` bigint(12) NOT NULL,
					`k` int(5) NULL,
					`d` int(5) NULL,
					`a` int(5) NULL,
					`cs` int(5) NULL,
					`entity` varchar(30) NULL,
					`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

					PRIMARY KEY(`locID`),
					FOREIGN KEY(`tournamentID`) REFERENCES esms_tournaments(tournamentID) ON DELETE CASCADE ON UPDATE CASCADE,
					FOREIGN KEY(`matchID`) REFERENCES esms_matches(matchID) ON DELETE NO ACTION ON UPDATE CASCADE,
					FOREIGN KEY(`playerID`) REFERENCES esms_players(playerID) ON DELETE CASCADE ON UPDATE CASCADE
					)
					DEFAULT CHARACTER SET = utf8
				");
        
        mysqli_query($con, "CREATE TABLE IF NOT EXISTS `esms_player_invites` (
					`locID` bigint(12) NOT NULL AUTO_INCREMENT,
					`inviter` bigint(12) NOT NULL,
					`invited` bigint(12) NOT NULL,
					`team` bigint(12) NOT NULL,
					`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

					PRIMARY KEY(`locID`),
					FOREIGN KEY(`inviter`) REFERENCES esms_players(playerID) ON DELETE CASCADE ON UPDATE CASCADE,
					FOREIGN KEY(`invited`) REFERENCES esms_players(playerID) ON DELETE CASCADE ON UPDATE CASCADE,
					FOREIGN KEY(`team`) REFERENCES esms_teams(teamID) ON DELETE CASCADE ON UPDATE CASCADE
					)
					DEFAULT CHARACTER SET = utf8
				");
        
        mysqli_query($con, "CREATE TABLE IF NOT EXISTS `esms_tour_applies` (
					`locID` bigint(12) NOT NULL AUTO_INCREMENT,
					`tournament` bigint(12) NOT NULL,
					`team` bigint(12) NOT NULL,
					`played` int(5) NULL DEFAULT 0,
					`won` int(5) NULL DEFAULT 0,
					`lost` int(5) NULL DEFAULT 0,
					`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

					PRIMARY KEY(`locID`),
					FOREIGN KEY(`tournament`) REFERENCES esms_tournaments(tournamentID) ON DELETE CASCADE ON UPDATE CASCADE,
					FOREIGN KEY(`team`) REFERENCES esms_teams(teamID) ON DELETE CASCADE ON UPDATE CASCADE
					)
					DEFAULT CHARACTER SET = utf8
				");
