<?php
/*
 * Copy this script to the folder above and populate $versions array with your migrations
 * For more info see: http://www.dbupgrade.org/Main_Page#Migrations
 *
 * Note: this script should be versioned in your code repository so it always reflects current code's
 *       requirements for the database structure.
*/
require_once(dirname(__FILE__).'/dbupgrade/lib.php');
require_once(dirname(__FILE__).'/global.php');
require_once(dirname(__FILE__).'/users/users.php');

$versions = array();
// Add new migrations on top, right below this line.

/* -------------------------------------------------------------------------------------------------------
 * VERSION 6
 * Each of changes to opponent's content is optional,
 * But only one or zero sets per player can be recorded for a turn
*/
$versions[6]['up'][] = "ALTER TABLE opponent_changes
MODIFY blue_change TINYINT(1) UNSIGNED NULL COMMENT 'New amount of blue stocks',
MODIFY red_change TINYINT(1) UNSIGNED NULL COMMENT 'New amount of red stocks',
MODIFY yellow_change TINYINT(1) UNSIGNED NULL COMMENT 'New amount of yellow stocks',
MODIFY green_change TINYINT(1) UNSIGNED NULL COMMENT 'New amount of green stocks',
MODIFY bank_change BIGINT(10) UNSIGNED NULL COMMENT 'New amount in the bank'";
$versions[6]['up'][] = "ALTER TABLE opponent_changes ADD PRIMARY KEY (turn_id, player_number)";

$versions[6]['down'][] = "ALTER TABLE opponent_changes DROP PRIMARY KEY";
$versions[6]['down'][] = "ALTER TABLE opponent_changes
MODIFY blue_change TINYINT(1) UNSIGNED NOT NULL COMMENT 'New amount of blue stocks',
MODIFY red_change TINYINT(1) UNSIGNED NOT NULL COMMENT 'New amount of red stocks',
MODIFY yellow_change TINYINT(1) UNSIGNED NOT NULL COMMENT 'New amount of yellow stocks',
MODIFY green_change TINYINT(1) UNSIGNED NOT NULL COMMENT 'New amount of green stocks',
MODIFY bank_change BIGINT(10) UNSIGNED NOT NULL COMMENT 'New amount in the bank'";

/* -------------------------------------------------------------------------------------------------------
 * VERSION 5
 * Same card can't be dealt twice in one game
*/
$versions[5]['up'][] = "ALTER TABLE game_player_cards ADD PRIMARY KEY (game_id, card_id)";

$versions[5]['down'][] = "ALTER TABLE game_player_cards DROP PRIMARY KEY";

/* -------------------------------------------------------------------------------------------------------
 * VERSION 4
 * Prices can change to up to 500 (before trimming)
*/
$versions[4]['up'][] = "ALTER TABLE turn
MODIFY blue_change SMALLINT(2) UNSIGNED NULL DEFAULT NULL COMMENT 'New price for blue shares',
MODIFY red_change SMALLINT(2) UNSIGNED NULL DEFAULT NULL COMMENT 'New price for red shares',
MODIFY yellow_change SMALLINT(2) UNSIGNED NULL DEFAULT NULL COMMENT 'New price for yellow shares',
MODIFY green_change SMALLINT(2) UNSIGNED NULL DEFAULT NULL COMMENT 'New price for green shares'";

$versions[4]['down'][] = "ALTER TABLE turn
MODIFY blue_change TINYINT(1) UNSIGNED NULL DEFAULT NULL COMMENT 'New price for blue shares',
MODIFY red_change TINYINT(1) UNSIGNED NULL DEFAULT NULL COMMENT 'New price for red shares',
MODIFY yellow_change TINYINT(1) UNSIGNED NULL DEFAULT NULL COMMENT 'New price for yellow shares',
MODIFY green_change TINYINT(1) UNSIGNED NULL DEFAULT NULL COMMENT 'New price for green shares'";

/* -------------------------------------------------------------------------------------------------------
 * VERSION 3
 * Forgot "after" values for turns, adding...
*/
$versions[3]['up'][] = "ALTER TABLE turn
ADD blue_after BIGINT(10) UNSIGNED NOT NULL COMMENT 'Amount of blue shares after move' AFTER green_change,
ADD red_after BIGINT(10) UNSIGNED NOT NULL COMMENT 'Amount of red shares after move' AFTER blue_after,
ADD yellow_after BIGINT(10) UNSIGNED NOT NULL COMMENT 'Amount of yellow shares after move' AFTER red_after,
ADD green_after BIGINT(10) UNSIGNED NOT NULL COMMENT 'Amount of green shares after move' AFTER yellow_after";

$versions[3]['down'][] = "ALTER TABLE turn DROP blue_after, DROP red_after, DROP yellow_after, DROP green_after";

/* -------------------------------------------------------------------------------------------------------
 * VERSION 2
 * Player table should have a primary and foreign keys
*/
$versions[2]['up'][] = "ALTER TABLE `player` ADD PRIMARY KEY (  `player_id` )";
$versions[2]['up'][] = "ALTER TABLE `player` MODIFY user_id INT(10) UNSIGNED NOT NULL";
$versions[2]['up'][] = "ALTER TABLE `player` ADD UNIQUE unique_user(`user_id`)";
$versions[2]['up'][] = "ALTER TABLE `player` ADD CONSTRAINT player_user
                                FOREIGN KEY player_user(user_id)
                                REFERENCES ".UserConfig::$mysql_prefix."users(id)
				ON UPDATE CASCADE ON DELETE CASCADE";

$versions[2]['down'][] = "ALTER TABLE `player` DROP FOREIGN KEY player_user"; 
$versions[2]['down'][] = "ALTER TABLE `player` DROP INDEX unique_user"; 
$versions[2]['down'][] = "ALTER TABLE `player` MODIFY user_id BIGINT(10) UNSIGNED NOT NULL";
$versions[2]['down'][] = "ALTER TABLE `player` DROP PRIMARY KEY";

/* -------------------------------------------------------------------------------------------------------
 * VERSION 1
 * Initial data structure mimicking data.sql
*/
$versions[1]['up'][] = "CREATE TABLE `game` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Holding main game data'";
$versions[1]['down'][] = "DROP TABLE `game`";

$versions[1]['up'][] = "CREATE TABLE `game_player_cards` (
  `game_id` bigint(10) unsigned NOT NULL COMMENT 'Game ID',
  `player_number` tinyint(1) unsigned NOT NULL COMMENT 'Card holder''s number',
  `card_id` tinyint(1) unsigned NOT NULL COMMENT 'Card ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Set of cards users hold'";
$versions[1]['down'][] = "DROP TABLE `game_player_cards`";

$versions[1]['up'][] = "CREATE TABLE `game_players` (
  `game_id` bigint(10) unsigned NOT NULL COMMENT 'Game this players play',
  `player_id` bigint(10) unsigned NOT NULL COMMENT 'Player''s id',
  `move_order` tinyint(1) unsigned NOT NULL COMMENT 'Sequence of the move this player make in this game',
  PRIMARY KEY (`game_id`,`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of players playing the game'";
$versions[1]['down'][] = "DROP TABLE `game_players`";

$versions[1]['up'][] = "CREATE TABLE `opponent_changes` (
  `turn_id` bigint(12) unsigned NOT NULL COMMENT 'Number of the turn',
  `player_number` tinyint(1) unsigned NOT NULL COMMENT 'Number of the player affected',
  `blue_change` tinyint(1) unsigned NOT NULL COMMENT 'New amount of blue stocks',
  `red_change` tinyint(1) unsigned NOT NULL COMMENT 'New amount of red stocks',
  `yellow_change` tinyint(1) unsigned NOT NULL COMMENT 'New amount of yellow stocks',
  `green_change` tinyint(1) unsigned NOT NULL COMMENT 'New amount of green stocks',
  `bank_change` bigint(10) unsigned NOT NULL COMMENT 'New amount in the bank'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Opponent stock changes triggered by the turn'";
$versions[1]['down'][] = "DROP TABLE `opponent_changes`";

$versions[1]['up'][] = "CREATE TABLE `player` (
  `player_id` bigint(10) unsigned NOT NULL COMMENT 'Player ID',
  `user_id` bigint(10) unsigned NOT NULL COMMENT 'Player User''s ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Mapping between users and players'";
$versions[1]['down'][] = "DROP TABLE `player`";

$versions[1]['up'][] = "CREATE TABLE `turn` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Game rturn information'";
$versions[1]['down'][] = "DROP TABLE `turn`";

// creating DBUpgrade object with your database credentials and $versions defined above
$dbupgrade = new DBUpgrade($db, $versions);

require_once(dirname(__FILE__).'/dbupgrade/client.php');
