<?
#
# Just a data structure to hold all the move's data - actual validation and stuff happends in the game class
#
# @author Sergey Chernyshev
# @version $Rev: 56 $
#
# $Id: Move.php 56 2008-03-31 02:16:19Z sergey $
#
class Move
{
	var $player;				# player making the move
	var $before = array();			# amounts of stocks before the card is played
	var $card;				# card that got used during the move

	/**
		changes to the prices (new prices) that were triggered by the card and user selection
		defines full price - can go over the range (10-250); real price will be determined on the fly
		must already have rounding to closest higher 10 applied
		only changed prices should have values defined in this array
	*/
	var $price_changes = array();

	var $after = array();			# amounts of stocks after the card is played
	var $bank;				# amount in the bank after the move
	
	/**
		Changes (new values) to opponent stock amounts and bank amount triggered by the move
		if change for specific color didn't happen, it will contain no entry for this key (color / 'bank')

		Each entry must be in the form of array
		$opponent_changes[<i>] = array(
			Card::BLUE => <new value>,
			Card::RED => <new value>,
			Card::YELLOW => <new value>,
			Card::GREEN => <new value>,
			'bank' => <new value>
		);

		With entries only for those keys that got changed.

		Entries in the array should match the order of players in the game,
			if there were no changes, the value must be null,
			the entry for the user making a move must be null
		If there were no changes to any values, then pass null instead of array
	*/
	var $opponent_changes = array();

	function __construct(
		$player,
		$before,
		$card,
		$price_changes,
		$after,
		$bank,
		$opponent_changes
		)
	{
		$this->player = $player;
		$this->before = $before;
		$this->card = $card;
		$this->price_changes = $price_changes;
		$this->after = $after;
		$this->bank = $bank;
		$this->opponent_changes = $opponent_changes;
	}
}

class InvalidMoveException extends Exception
{
}

