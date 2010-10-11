<?
#
# This abstract class represents both small and big cards
#
# @author Sergey Chernyshev
# @version $Rev: 61 $
#
# $Id: Card.php 2 2008-02-11 02:52:08Z sergey $
#
require_once(dirname(__FILE__).'/Deck.php');
require_once(dirname(__FILE__).'/SmallCard.php');
require_once(dirname(__FILE__).'/BigCard.php');

abstract class Card
{
	# numbers actually mean sorting order - highest first
	const BLUE =	4;
	const RED =	3;
	const YELLOW =	2;
	const GREEN =	1;

	# yeach element of array is contains color letter for corresponding color
	static private $colors = array (
			Card::BLUE => array( 'letter' => 'b', 'title' => 'blue' ),
			Card::RED => array( 'letter' => 'r', 'title' => 'red' ),
			Card::YELLOW => array( 'letter' => 'y', 'title' => 'yellow' ),
			Card::GREEN => array( 'letter' => 'g', 'title' => 'green' )
		);

	var $id;
	var $color;

	private static $cards = array();

	public static function addCard($card) {
		self::$cards[$card->getId()] = $card;
	}

	public static function getCard($id) {
		return self::$cards[$id]; 
	}

	function getId() {
		return $this->id;
	}

	function getCardColorLetter()
	{
		return Card::getColorLetter($this->color);
	}

	static function getColors()
	{
		return array_keys(Card::$colors);
	}

	static function getColorLetter($color)
	{
		if (array_key_exists($color, Card::$colors))
		{
			return Card::$colors[$color]['letter'];
		}
		else
		{
			return null;
		}
	}

	static function getColorTitle($color)
	{
		if (array_key_exists($color, Card::$colors))
		{
			return Card::$colors[$color]['title'];
		}
		else
		{
			return null;
		}
	}

	# function for comparing two cards, should be used instead of == / ===
	function equals($card)
	{
		return $card->asString() == $this->asString(); # simple implementation
	}

	abstract static function getDeck();

	# returns sort index for the card - used when a list of cards is printed
	abstract function getSortIndex();

	abstract function asString();

	# validates price changess
	abstract function validatePriceChanges($original_prices, $price_changes);
}

class InvalidPriceChangeException extends Exception 
{
}
