<?php
require_once(dirname(dirname(__FILE__)).'/datamodel/Game.php');

$p1 = new Player(User::getUser(51));
$p2 = new Player(User::getUser(52));

$game = new Game(1, 'A', array($p1, $p2), 1, 1, true,
	array(
		array(
			Card::getCard(1), // +30 Blue
			Card::getCard(51), // *2 Green
		),
		array(
			Card::getCard(18), // -30 Red
			Card::getCard(52)  // :2 Green
		)
	),
	array(
		new Turn(
			$p1,
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			Card::getCard(1), // +30 Blue
			array(Card::BLUE => 130, Card::RED => 40),
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			60,
			null
		),
		new Turn(
			$p2,
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			Card::getCard(18), // -30 Red
			array(Card::RED => 10, Card::YELLOW => 160),
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			30,
			null
		),
		new Turn(
			$p1,
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			Card::getCard(51), // *2 Green
			array(Card::GREEN => 200, Card::YELLOW => 80),
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			140,
			null
		),
		new Turn(
			$p2,
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			Card::getCard(52),  // :2 Green
			array(Card::GREEN => 100, Card::YELLOW => 160),
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			130,
			null
		)
	)
);
