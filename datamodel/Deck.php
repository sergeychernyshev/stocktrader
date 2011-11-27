<?
#
# This class represents a deck of cards for dealing game cards 
#
# @author Sergey Chernyshev
# @version $Rev: 21 $
#
# $Id: Deck.php 21 2008-02-18 09:30:23Z sergey $
#
require_once('Card.php');

class Deck
{
	private $cards = array();

	# takes array of cards as input
	function Deck($cards)
	{
		$this->cards = $cards;
	}

	# removes and returns $amount of cards from deck
	function deal($amount)
	{
		if ($amount > count($this->cards))
		{
			throw new CardDeckException("Can't deal more cards then left in the deck");
		}

		$deal = array();

		for ($i = 0; $i<$amount; $i++)
		{
			$taken_card_in_array = array_splice($this->cards, rand(0, count($this->cards)-1), 1);
			$deal[] = $taken_card_in_array[0];
		}

		return $deal;
	}
}

class CardDeckException extends Exception
{
}
