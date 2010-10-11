-- MySQL dump 10.13  Distrib 5.1.49, for pc-linux-gnu (i686)
--
-- Host: localhost    Database: stocktrader
-- ------------------------------------------------------
-- Server version	5.1.49

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date this game was created',
  `number` bigint(10) unsigned NOT NULL COMMENT 'Game number',
  `suffix` char(1) NOT NULL COMMENT 'Game suffix',
  `big_cards_amount` tinyint(1) unsigned NOT NULL COMMENT 'Number of big cards in the game',
  `small_cards_amount` tinyint(1) unsigned NOT NULL COMMENT 'Number of small cards in the game',
  `rounding_up` tinyint(1) unsigned NOT NULL COMMENT 'Rounding :2 up or down',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_number_suffix` (`number`,`suffix`),
  KEY `number` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Holding main game data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
INSERT INTO `game` VALUES (1,'2010-10-11 03:15:50',1,'A',0,0,0);
/*!40000 ALTER TABLE `game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_player_cards`
--

DROP TABLE IF EXISTS `game_player_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_player_cards` (
  `game_id` bigint(10) unsigned NOT NULL COMMENT 'Game ID',
  `player_number` tinyint(1) unsigned NOT NULL COMMENT 'Card holder''s number',
  `card_id` tinyint(1) unsigned NOT NULL COMMENT 'Card ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Set of cards users hold';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_player_cards`
--

LOCK TABLES `game_player_cards` WRITE;
/*!40000 ALTER TABLE `game_player_cards` DISABLE KEYS */;
INSERT INTO `game_player_cards` VALUES (1,1,1),(1,1,51),(1,2,18),(1,2,52);
/*!40000 ALTER TABLE `game_player_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_players`
--

DROP TABLE IF EXISTS `game_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_players` (
  `game_id` bigint(10) unsigned NOT NULL COMMENT 'Game this players play',
  `player_id` bigint(10) unsigned NOT NULL COMMENT 'Player''s id',
  `move_order` tinyint(1) unsigned NOT NULL COMMENT 'Sequence of the move this player make in this game',
  PRIMARY KEY (`game_id`,`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of players playing the game';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_players`
--

LOCK TABLES `game_players` WRITE;
/*!40000 ALTER TABLE `game_players` DISABLE KEYS */;
INSERT INTO `game_players` VALUES (1,1,1),(1,2,3);
/*!40000 ALTER TABLE `game_players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opponent_changes`
--

DROP TABLE IF EXISTS `opponent_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opponent_changes` (
  `turn_id` bigint(12) unsigned NOT NULL COMMENT 'Number of the turn',
  `player_number` tinyint(1) unsigned NOT NULL COMMENT 'Number of the player affected',
  `blue_change` tinyint(1) unsigned NOT NULL COMMENT 'New amount of blue stocks',
  `red_change` tinyint(1) unsigned NOT NULL COMMENT 'New amount of red stocks',
  `yellow_change` tinyint(1) unsigned NOT NULL COMMENT 'New amount of yellow stocks',
  `green_change` tinyint(1) unsigned NOT NULL COMMENT 'New amount of green stocks',
  `bank_change` bigint(10) unsigned NOT NULL COMMENT 'New amount in the bank'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Opponent stock changes triggered by the turn';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opponent_changes`
--

LOCK TABLES `opponent_changes` WRITE;
/*!40000 ALTER TABLE `opponent_changes` DISABLE KEYS */;
/*!40000 ALTER TABLE `opponent_changes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player`
--

DROP TABLE IF EXISTS `player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player` (
  `player_id` bigint(10) unsigned NOT NULL COMMENT 'Player ID',
  `user_id` bigint(10) unsigned NOT NULL COMMENT 'Player User''s ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Mapping between users and players';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player`
--

LOCK TABLES `player` WRITE;
/*!40000 ALTER TABLE `player` DISABLE KEYS */;
INSERT INTO `player` VALUES (1,51),(2,52);
/*!40000 ALTER TABLE `player` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turn`
--

DROP TABLE IF EXISTS `turn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `turn` (
  `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Move ID',
  `game_id` bigint(10) unsigned NOT NULL COMMENT 'Game this move belongs to',
  `player_id` bigint(10) unsigned NOT NULL COMMENT 'Player who made this move',
  `number` tinyint(1) unsigned NOT NULL COMMENT 'Move sequence number',
  `blue_before` bigint(10) unsigned NOT NULL COMMENT 'Amount of blue shares before move',
  `red_before` bigint(10) unsigned NOT NULL COMMENT 'Amount of red shares before move',
  `yellow_before` bigint(10) unsigned NOT NULL COMMENT 'Amount of yellow shares before move',
  `green_before` bigint(10) unsigned NOT NULL COMMENT 'Amount of green shares before move',
  `card_id` tinyint(1) unsigned NOT NULL COMMENT 'Card ID',
  `blue_change` tinyint(1) unsigned DEFAULT NULL COMMENT 'New price for blue shares',
  `red_change` tinyint(1) unsigned DEFAULT NULL COMMENT 'New price for red shares',
  `yellow_change` tinyint(1) unsigned DEFAULT NULL COMMENT 'New price for yellow shares',
  `green_change` tinyint(1) unsigned DEFAULT NULL COMMENT 'New price for green shares',
  `bank` bigint(10) unsigned NOT NULL COMMENT 'Bank balance',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Game rturn information';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turn`
--

LOCK TABLES `turn` WRITE;
/*!40000 ALTER TABLE `turn` DISABLE KEYS */;
/*!40000 ALTER TABLE `turn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `u_account_users`
--

DROP TABLE IF EXISTS `u_account_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `u_account_users` (
  `account_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `role` tinyint(4) unsigned NOT NULL DEFAULT '0',
  KEY `user_account` (`account_id`),
  KEY `account_user` (`user_id`),
  CONSTRAINT `account_user` FOREIGN KEY (`user_id`) REFERENCES `u_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `u_account_users_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `u_accounts` (`id`),
  CONSTRAINT `u_account_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `u_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `u_account_users`
--

LOCK TABLES `u_account_users` WRITE;
/*!40000 ALTER TABLE `u_account_users` DISABLE KEYS */;
INSERT INTO `u_account_users` VALUES (73,51,1),(74,52,1);
/*!40000 ALTER TABLE `u_account_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `u_accounts`
--

DROP TABLE IF EXISTS `u_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `u_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `plan` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Payment plan ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `u_accounts`
--

LOCK TABLES `u_accounts` WRITE;
/*!40000 ALTER TABLE `u_accounts` DISABLE KEYS */;
INSERT INTO `u_accounts` VALUES (73,'FREE (Sergey Chernyshev)',0),(74,'FREE (Dr. Gonzo)',0);
/*!40000 ALTER TABLE `u_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `u_activity`
--

DROP TABLE IF EXISTS `u_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `u_activity` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of activity',
  `user_id` int(10) unsigned NOT NULL COMMENT 'User ID',
  `activity_id` int(2) unsigned NOT NULL COMMENT 'Activity ID',
  KEY `time` (`time`),
  KEY `user_id` (`user_id`),
  KEY `activity_id` (`activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Stores user activities';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `u_activity`
--

LOCK TABLES `u_activity` WRITE;
/*!40000 ALTER TABLE `u_activity` DISABLE KEYS */;
INSERT INTO `u_activity` VALUES ('2010-10-07 03:16:07',51,1009),('2010-10-11 01:34:46',52,1009);
/*!40000 ALTER TABLE `u_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `u_googlefriendconnect`
--

DROP TABLE IF EXISTS `u_googlefriendconnect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `u_googlefriendconnect` (
  `user_id` int(10) unsigned NOT NULL COMMENT 'User ID',
  `google_id` varchar(255) NOT NULL COMMENT 'Google Friend Connect ID',
  `userpic` text NOT NULL COMMENT 'Google Friend Connect User picture',
  PRIMARY KEY (`user_id`,`google_id`),
  CONSTRAINT `gfc_user` FOREIGN KEY (`user_id`) REFERENCES `u_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `u_googlefriendconnect`
--

LOCK TABLES `u_googlefriendconnect` WRITE;
/*!40000 ALTER TABLE `u_googlefriendconnect` DISABLE KEYS */;
/*!40000 ALTER TABLE `u_googlefriendconnect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `u_invitation`
--

DROP TABLE IF EXISTS `u_invitation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `u_invitation` (
  `code` char(10) NOT NULL COMMENT 'Code',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When invitation was created',
  `issuedby` bigint(10) unsigned NOT NULL DEFAULT '1' COMMENT 'User who issued the invitation. Default is Sergey.',
  `sentto` text COMMENT 'Note about who this invitation was sent to',
  `user` bigint(10) unsigned DEFAULT NULL COMMENT 'User name',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `u_invitation`
--

LOCK TABLES `u_invitation` WRITE;
/*!40000 ALTER TABLE `u_invitation` DISABLE KEYS */;
/*!40000 ALTER TABLE `u_invitation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `u_oid_associations`
--

DROP TABLE IF EXISTS `u_oid_associations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `u_oid_associations` (
  `server_url` blob NOT NULL,
  `handle` varchar(255) NOT NULL DEFAULT '',
  `secret` blob NOT NULL,
  `issued` int(11) NOT NULL DEFAULT '0',
  `lifetime` int(11) NOT NULL DEFAULT '0',
  `assoc_type` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`server_url`(255),`handle`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `u_oid_associations`
--

LOCK TABLES `u_oid_associations` WRITE;
/*!40000 ALTER TABLE `u_oid_associations` DISABLE KEYS */;
/*!40000 ALTER TABLE `u_oid_associations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `u_oid_nonces`
--

DROP TABLE IF EXISTS `u_oid_nonces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `u_oid_nonces` (
  `server_url` text NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `salt` varchar(40) NOT NULL DEFAULT '',
  UNIQUE KEY `server_url` (`server_url`(255),`timestamp`,`salt`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `u_oid_nonces`
--

LOCK TABLES `u_oid_nonces` WRITE;
/*!40000 ALTER TABLE `u_oid_nonces` DISABLE KEYS */;
/*!40000 ALTER TABLE `u_oid_nonces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `u_user_preferences`
--

DROP TABLE IF EXISTS `u_user_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `u_user_preferences` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `current_account_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `preference_current_account` (`current_account_id`),
  CONSTRAINT `preference_user` FOREIGN KEY (`user_id`) REFERENCES `u_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `u_user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `u_users` (`id`),
  CONSTRAINT `u_user_preferences_ibfk_2` FOREIGN KEY (`current_account_id`) REFERENCES `u_accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `u_user_preferences`
--

LOCK TABLES `u_user_preferences` WRITE;
/*!40000 ALTER TABLE `u_user_preferences` DISABLE KEYS */;
INSERT INTO `u_user_preferences` VALUES (51,73),(52,74);
/*!40000 ALTER TABLE `u_user_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `u_users`
--

DROP TABLE IF EXISTS `u_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `u_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of registration',
  `name` text NOT NULL,
  `username` varchar(25) DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `pass` varchar(40) NOT NULL COMMENT 'Password digest',
  `salt` varchar(13) NOT NULL COMMENT 'Salt',
  `temppass` varchar(13) DEFAULT NULL COMMENT 'Temporary password used for password recovery',
  `temppasstime` timestamp NULL DEFAULT NULL COMMENT 'Temporary password generation time',
  `requirespassreset` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Flag indicating that user must reset their password before using the site',
  `fb_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Facebook user ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `fb_id` (`fb_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `u_users`
--

LOCK TABLES `u_users` WRITE;
/*!40000 ALTER TABLE `u_users` DISABLE KEYS */;
INSERT INTO `u_users` VALUES (51,'2010-10-07 03:16:07','Sergey Chernyshev','sergeychernyshev','stocktrader@antispam.sergeychernyshev.com','05b8b486f9ca1f43af471139210eb55b9023d1e1','4cad3b77c8906',NULL,NULL,0,NULL),(52,'2010-10-11 01:34:46','Dr. Gonzo','drgonzo','drgonzo@antispam.sergeychernyshev.com','bf0240038d062b577b45843f39caf7a75cdb6d74','4cb269b61beb9',NULL,NULL,0,NULL);
/*!40000 ALTER TABLE `u_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-10-11  2:25:02
