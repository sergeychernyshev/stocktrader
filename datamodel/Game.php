<?
#
# This class represents single game
#
# @author Sergey Chernyshev
# @version $Rev: 64 $
#
# $Id: Game.php 64 2008-05-04 05:03:58Z sergey $
#
require_once(dirname(__FILE__).'/SmallCard.php');
require_once(dirname(__FILE__).'/BigCard.php');
require_once(dirname(__FILE__).'/Turn.php');
require_once(dirname(__FILE__).'/Player.php');

class Game
{
	# database ID
	private $id;

	# game number and suffix
	private $number;
	private $suffix; # A, B, C, D and etc.

	# array of players (number of players equals number of turns in one round)
	private $players;

	# an array of arrays of player cards for each player
	private $playerCards = array();

	# should be 10 rounds with X turns where X is equals number of players
	private $rounds = array();

	# number of big cards
	private $bigCardsAmount;

	# number of small cards
	private $smallCardsAmount;

	# total number of rounds in the game
	private $totalRounds;

	# rounding up (or down) if :2 is used
	private $rounding;

	function __construct($number, $suffix, $players, $bigCardsAmount = 4, $smallCardsAmount = 6, $rounding = true, $decks = null, $turns = null, $id = null)
	{
		$this->id = $id;

		$this->number = $number;
		$this->suffix = $suffix;
		$this->players = $players;
		$this->bigCardsAmount = $bigCardsAmount;
		$this->smallCardsAmount = $smallCardsAmount;
		$this->totalRounds = $bigCardsAmount + $smallCardsAmount;
		$this->rounding = $rounding;

		if (is_null($decks))
		{
			$this->dealCards();
		}
		else
		{
			$this->playerCards = $decks;
		}

		if (!$this->validateGame())
		{
			throw new InvalidGameStateException("Game state is not valid, can't proceed.");
		}

		if (!is_null($turns))
		{
			foreach ($turns as $turn)
			{
				$this->makeTurn($turn);
			}
		}
	}

	public function getID() {
		return $this->id;
	}
	public function getNumber() {
		return $this->number;
	}
	public function getSuffix() {
		return $this->suffix;
	}
	public function getPlayers() {
		return $this->players;
	}
	public function getPlayerCards() {
		return $this->playerCards;
	}
	public function getRounds() {
		return $this->rounds;
	}
	public function getBigCardsAmount() {
		return $this->bigCardsAmount;
	}
	public function getSmallCardsAmount() {
		return $this->smallCardsAmount;
	}
	public function getTotalRounds() {
		return $this->totalRounds;
	}
	public function isroundingUp() {
		return $this->rounding;
	}

	function asString()
	{
		$output = '';

		for ($i=0; $i < count($this->players); $i++)
		{
			$output .= $this->players[$i]->asString().': ';

			$sorthash = array();
			foreach (array_values($this->playerCards[$i]) as $card)
			{
				$sorthash[$card->getSortIndex()][] = $card;
			}

			krsort($sorthash);

			$res = array();
			foreach (array_values($sorthash) as $cards)
			{
				$res = array_merge($res, $cards);
			}

			$cardlabels = array();
			foreach ($res as $card)
			{
				$cardlabels[] = $card->asString();
			}

			$output .= join(', ', $cardlabels)."\n";
		}

		return $output;
	}

	# deal all cards to players
	private function dealCards()
	{
		$big = BigCard::getDeck();
		$small = SmallCard::getDeck();
		
		foreach ($this->players as $player)
		{
			$bigDeal = $big->deal($this->bigCardsAmount);
			$smallDeal = $small->deal($this->smallCardsAmount);
			$this->playerCards[] = array_merge($bigDeal, $smallDeal);
		}
	}

	function makeTurn($turn)
	{
		$valid = $this->validateTurn($turn);

		if (!$valid)
		{
			throw new InvalidTurnException("Didn't pass turn validation");
		}

		if (count($this->rounds)>0)
		{
			$lastRound= $this->rounds[count($this->rounds)-1];

			if (count($lastRound) == $this->getNumberOfPlayers())
			{
				# first turn in next round 
				$this->rounds[] = array($turn);
			}
			else
			{
				$this->rounds[count($this->rounds)-1][] = $turn;
			}
		}
		else
		{
			# first turn by first player in first round 
			$this->rounds[] = array($turn);
		}
	}

	function getNumberOfPlayers()
	{
		return count($this->players);
	}

	# TODO validate game state (not including turn - should be called before turns were applied)
	function validateGame()
	{
		return true;
	}

	# check if turn data is not conflicting with, return true or throw an InvalidTurn exception
	function validateTurn($turn)
	{
		/**
			[Rule 4(1)] Check if game still has unplayed turns 
		*/
		if (count($this->rounds) == $this->totalRounds && count($this->rounds[count($this->rounds)-1]) == $this->getNumberOfPlayers())
		{
			throw new InvalidTurnException('All turns in the game are already played');
		}

		/**
			[Rule 3a]
			Validate stock amounts before card was played.
			Value of stocks must be equal or less the value of stocks of the player plus amount in the bank
				after previus (last opponent's) turn.
		*/

		# get the amounts after this player's last turn 
		$before;

		$currentRound; # round in which this turn is being made
		$prevRound; # previous round 
		$playerNumber; # a number of the player making this turn 

		if (count($this->rounds)>1)
		{
			$lastRound = $this->rounds[count($this->rounds)-1];

			if (count($lastRound) == $this->getNumberOfPlayers())
			{
				$currentRound = array();
				$prevRound = $lastRound;
				$playerNumber = 0;
			}
			else
			{
				$currentRound = $lastRound;
				$prevRound = $this->rounds[count($this->rounds)-2];
				$playerNumber = count($currentRound);
			}


			# if last round is full, then new one is current
			# this means that currentRound is never full
			$prevPlayerTurn = $prevRound[$playerNumber];

			$before = array(
				Card::BLUE => $prevPlayerTurn->after[Card::BLUE],
				Card::RED => $prevPlayerTurn->after[Card::RED],
				Card::YELLOW => $prevPlayerTurn->after[Card::YELLOW],
				Card::GREEN => $prevPlayerTurn->after[Card::GREEN],
				'bank' => $prevPlayerTurn->bank
			);
		}
		else
		{
			# this player didn't make a turn yet, it means he had 1 of each and no money in the bank
			$before = array(
				Card::BLUE => 1,
				Card::RED => 1,
				Card::YELLOW => 1,
				Card::GREEN => 1,
				'bank' => 0
			);

			# this is very first turn 
			if (count($this->rounds) == 0)
			{
				$currentRound = array();
				$prevRound = null;
				$playerNumber = 0;
			}
			# first round is just over
			elseif (count($this->rounds[0]) == $this->getNumberOfPlayers())
			{
				$currentRound = array();
				$prevRound = $this->rounds[0];
				$playerNumber = 0;

				# this player actually made one turn before
				$before = array(
					Card::BLUE => $prevRound[0]->after[Card::BLUE],
					Card::RED => $prevRound[0]->after[Card::RED],
					Card::YELLOW => $prevRound[0]->after[Card::YELLOW],
					Card::GREEN => $prevRound[0]->after[Card::GREEN],
					'bank' => $prevRound[0]->bank
				);
			}
			#first round in progress
			else
			{
				$currentRound = $this->rounds[0];
				$prevRound = null;
				$playerNumber = count($currentRound);
			}
		}

		# now lets apply all changes to player's amounts from opponents turns since player's last turn 
		$opponent_turns = array();

		# first, go through previous round if there was previous round and this is not last turn in round 
		if (!is_null($prevRound) && $playerNumber < $this->getNumberOfPlayers()-1)
		{
			for ($i=$playerNumber+1; $i<$this->getNumberOfPlayers(); $i++)
			{
				$opponent_turns[] = $prevRound[$i];
			}
		}
		# now, let's add all turns already in current round 
		$opponent_turns = array_merge($opponent_turns, $currentRound);

		# OK, we got a sequence of opponent turns after player's previous turn, let's apply changes from each turn if they exist
		foreach($opponent_turns as $opponent_turn)
		{
			/**
				if whole change set is null it means there were no changed,
				if change for the player is null, it means this players amounts were not affected.
				In both cases, there are no changes to apply.
			*/
			if (is_null($opponent_turn->opponent_changes) || is_null($opponent_turn->opponent_changes[$playerNumber]))
			{
				continue;
			}

			foreach ($opponent_turn->opponent_changes[$playerNumber] as $key => $value)
			{
				$before[$key] = $value;
			}
		}

		# now, let's calculate the value by multiplying player's stock amounts by their prices from last opponent's turn 

		# first we need to calculate current prices, initial price is 100 for all stocks
		$prices = array(
			Card::BLUE => 100,
			Card::RED => 100,
			Card::YELLOW => 100,
			Card::GREEN => 100
		);

		# let's through all turns that were made in the game and apply changes
		foreach ($this->rounds as $round)
		{
			foreach ($round as $round_turn)
			{
				# get all changed price (only changed ones are defined)
				foreach ($round_turn->price_changes as $color => $new_value)
				{
					$prices[$color] = $new_value;

					# went higher then the range maximum
					if ($prices[$color] > 250)
					{
						$prices[$color] = 250;
					}

					# went lower then the range minimum
					if ($prices[$color] < 10)
					{
						$prices[$color] = 10;
					}
				}
			}
		}

		# ok, we got prices, we got player's last turn stock and bank amounts, let's get the value
		$value_after_last_turn = $before['bank'] +
				$before[Card::BLUE] * $prices[Card::BLUE] +
				$before[Card::RED] * $prices[Card::RED] +
				$before[Card::YELLOW] * $prices[Card::YELLOW] +
				$before[Card::GREEN] * $prices[Card::GREEN];

		$turn_before_value = $before['bank'] +
				$turn->before[Card::BLUE] * $prices[Card::BLUE] +
				$turn->before[Card::RED] * $prices[Card::RED] +
				$turn->before[Card::YELLOW] * $prices[Card::YELLOW] +
				$turn->before[Card::GREEN] * $prices[Card::GREEN];

		# Finally let's compare them and if current value is more then the value after last turn, then turn is invalid
		if ($turn_before_value > $value_after_last_turn)
		{
			throw new InvalidTurnException('Value of stocks purchased before playing the card is higher then player has at the beginning of the turn.');
		}

		/**
			[Rule 2b(2)]
			Price changes must match card definitions
			Cards must validate price changes and throw exceptions if needed
		*/
		$turn->card->validatePriceChanges($prices, $turn->price_changes, $this->rounding);

		/**
			[Rule 3c(4)]
			Player can't sell more stock then he had before the turn 
		*/
		foreach (Card::getColors() as $color)
		{
			if ($turn->after[$color] < $turn->before[$color] && $turn->after[$color] - $turn->before[$color] > $before[$color])
			{
				throw new InvalidTurnException("Amount of ".Card::getColorTitle($color)." stock sold during the turn is higher then at the end of player's previous turn.");
			}
		}

		/**
			[Rule 3c(3)]
			Value of stocks and bank account must be the same right after the prices were changed and at the end of the turn 
		*/
		$compensation = 0;

		$turn_after_value = $before['bank'];

		$prices_after = array();
		foreach (Card::getColors() as $color)
		{
			if (array_key_exists($color, $turn->price_changes))
			{
				$prices_after[$color] = $turn->price_changes[$color];

				/**
					[Rule 5b]
					if price went higher then 250, player immediately gets compensation for it being cut
				*/
				if ($prices_after[$color] > 250)
				{
					$compensation += ($prices_after[$color] - 250) * $turn->before[$color];
					$prices_after[$color] = 250;
				}

				/**
					[Rule 5a(2)]
					if price goes lower then 10, this player gets only limited compensation
				*/
				if ($prices_after[$color] < 10)
				{
					$prices_after[$color] = 10;
				}

				/**
					[Rule 5a(1)]
					if price goes lower when it was before card was played, this and only this player immediately gets compensation
				*/
				if ($prices[$color] > $prices_after[$color])
				{
					$compensation += ($prices[$color] - $prices_after[$color]) * $turn->before[$color];
				}
			}
			else
			{
				$prices_after[$color] = $prices[$color];
			}
			$turn_after_value += $turn->before[$color] * $prices_after[$color];
		}
		$turn_after_value += $compensation;

		$turn_end_value = $turn->bank;
		foreach (Card::getColors() as $color)
		{
			$turn_end_value += $turn->after[$color] * $prices_after[$color];
		}

		if ($turn_after_value != $turn_end_value)
		{
			/*
			echo var_export(array(
				'turn' => $turn,
				'before' => $before,
				'prices_after' => $prices_after,
				'compensation' => $compensation,
				'after_value' => $turn_after_value,
				'end_value' => $turn_end_value)
			);
			*/
			throw new InvalidTurnException('Value of stocks and bank account must be the same right after the prices were changed and at the end of the turn');
		}

		/**
			[Rule 3b(1)] Check if player plays card that he has and didn't use yet
		*/
		$has_card = false;
		foreach ($this->playerCards[$playerNumber] as $card)
		{
			if ($turn->card->equals($card))
			{
				$has_card = true;
				break;
			}
		}

		if (!$has_card)
		{
			$deckLabels = array();
			foreach ($this->playerCards[$playerNumber] as $card)
			{
				$deckLabels[] = $card->asString();
			}

			// echo var_export(array('game' => $this, 'turn' => $turn));
			throw new InvalidTurnException("Player can't use a card that wasn't dealt to him: ".$turn->card->asString()." (player: $playerNumber; deck: ".implode(', ', $deckLabels).')');
		}

		$player_cards = $this->playerCards[$playerNumber];
		#first remove cards that are already played
		foreach ($this->rounds as $round)
		{
			for ($i=0; $i<count($player_cards); $i++)
			{
				if ($turn->card->equals($player_cards[$i]))
				{
					$player_cards = array_splice($player_cards, $i, 1);
					break; # once we shortened the array, 
				}
			}
		}

		$still_in_deck = false;
		foreach ($player_cards as $card)
		{
			if ($turn->card->equals($card))
			{
				$still_in_deck = true;
				break;
			}
		}

		if (!$still_in_deck)
		{
			throw new InvalidTurnException("Player can't use a card that he already used");
		}

		/** 
			[Rule 5b] 	Check if other players get correct compensation for >250 rule
		*/
		foreach ($turn->price_changes as $color => $new_value)
		{
			if ($new_value > 250)
			{
				# let's cycle through opponent turn and see if they got the compensation
				for ($i = 0; $i < count($opponent_turns); $i++)
				{
					# this player had this stock left in last turn 
					if (array_key_exists($color, $opponent_turns[$i]->after))
					{
						$last_stock_amount = $opponent_turns[$i]->after[$color];
						$last_bank_balance = $opponent_turns[$i]->bank;

						# we need opponent's number in a list of players to reference opponent_changes
						$opponentNumber = $playerNumber + $i + 1;

						# if $opponent_turns has less entries then $this->getNumberOfPlayers() - 1
						# it means that we're in the first round and $opponentNumber should be adjusted accordingly
						$opponentNumber += $this->getNumberOfPlayers() - 1 - count($opponent_turns);

						if ($opponentNumber > $this->getNumberOfPlayers() - 1)
						{
							$opponentNumber -= $this->getNumberOfPlayers();
						}

						# apply changes made by consequent turns if any to see final amount of stock and bank balance !!!
						# required for games with more then 2 players (more then one opponent turn)
						for ($j = $i+1; $j < count($opponent_turns); $j++)
						{
							if (array_key_exists($color, $opponent_turns[$j]->opponent_changes[$opponentNumber]))
							{
								$last_stock_amount = $opponent_turns[$j]->opponent_changes[$opponentNumber][$color];
							}

							if (array_key_exists('bank', $opponent_turns[$j]->opponent_changes[$opponentNumber]))
							{
								$last_bank_balance = $opponent_turns[$j]->opponent_changes[$opponentNumber]['bank'];
							}
						}

						$compensation = $last_stock_amount * ($new_value - 250);
						$balance_change = $turn->opponent_changes[$opponentNumber]['bank'] - $last_bank_balance;
						if ($compensation != $balance_change)
						{
							/*
							echo var_export(array(
								'$opponent_turns' => $opponent_turns,
								'$opponentNumber' => $opponentNumber,
								'$compensation' => $compensation,
								'$i' => $i,
								'$j' => $j,
								'$last_stock_amount' => $last_stock_amount,
								'$last_bank_balance' => $last_bank_balance,
								'$balance_change' => $balance_change,
								'$turn' => $turn
							));
							*/

							throw new InvalidTurnException("Player wasn't compensated for stock price going over 250");
						}
					}
				}
				break; # there can be only one stock that went above 250 in single turn 
			}
		}

		/**
			[Rule 4(2)] If this is last turn, check player didn't buy or sell stocks
		*/
		$lastRoundNumber = $playerNumber == 0 ? $this->totalRounds - 1 : $this->totalRounds;
		if (count($this->rounds) == $lastRoundNumber)
		{
			foreach (Card::getColors() as $color)
			{
				if ($turn->after[$color] != $turn->before[$color] || $turn->before[$color] != $before[$color])
				{
					throw new InvalidTurnException("Player can't buys or sell stock during last round.");
				}
			}
		}

		/**
			Check that turn is done by the player who's round it is
		*/
		if (!$this->players[$playerNumber]->compare($turn->player))
		{
			throw new InvalidTurnException("Wrong player is making the turn.");
		}

		/**
			[Rule 6] Check if other players' stock and bank amounts get corrected appropriately when going below 10
		*/

		# let's get an array of price_changes in order of compensation
		$price_changes_below10 = array();
		foreach ($turn->price_changes as $color => $new_value)
		{
			if ($new_value < 10)
			{
				$drop = $prices[$color] - $new_value;
				$price_changes_below10[$drop] = $color;
			}
		}
		krsort($price_changes_below10);

#		echo var_export(array(
#			'changes' => $turn->price_changes,
#			'below10' => $price_changes_below10
#		))."\n";

		if (count($price_changes_below10) > 0)
		{
			# let's cycle through opponent turns and see if amounts got corrected properly
			for ($i = 0; $i < count($opponent_turns); $i++)
			{
				$bank_left = $opponent_turns[$i]->bank; # will calculate overall compensation for the more

				# we need opponent's number in a list of players to reference opponent_changes
				$opponentNumber = $playerNumber + $i + 1;

				# if $opponent_turns has less entries then $this->getNumberOfPlayers() - 1
				# it means that we're in the first round and $opponentNumber should be adjusted accordingly
				$opponentNumber += $this->getNumberOfPlayers() - 1 - count($opponent_turns);

				if ($opponentNumber > $this->getNumberOfPlayers() - 1)
				{
					$opponentNumber -= $this->getNumberOfPlayers();
				}

				foreach ($price_changes_below10 as $drop => $color)
				{
					# this player had this stock left in last turn 
					if (array_key_exists($color, $opponent_turns[$i]->after))
					{
						if ($opponent_turns[$i]->after[$color] < $turn->opponent_changes[$opponentNumber][$color])
						{
							throw new InvalidTurnException("Player didn't have that many stocks before compensation.");
						}

						$bank_left = $bank_left - $turn->opponent_changes[$opponentNumber][$color] * $drop;

						if ($bank_left < 0)
						{
							throw new InvalidTurnException("Not enough money in the bank to pay off the fee that many stocks.");
						}

						if ($opponent_turns[$i]->after[$color] > $turn->opponent_changes[$opponentNumber][$color]
								&& $bank_left >= $drop)
						{
							throw new InvalidTurnException("Must pay off all stocks player has money for.");
						}
					}
				}

				if ($turn->opponent_changes[$opponentNumber] != NULL
					&& $turn->opponent_changes[$opponentNumber]['bank'] != $bank_left)
				{
#					echo var_export(array(
#						'opponent' => $opponentNumber,
#						'changes' => $turn->opponent_changes[$opponentNumber],
#						'bank_left' => $bank_left
#					));

					throw new InvalidTurnException("Bank balances after the fee don't match.");
				}
			}
		}

		return true;
	}

	// methods for DB connectivity
	public static function getPlayerGames($player) {
		global $db;

		$game_data = array();

		if ($stmt = $db->prepare('SELECT
				id,
				number,
				suffix,
				big_cards_amount,
				small_cards_amount,
				rounding_up,
				gpp.player_id
			FROM game g
			INNER JOIN game_players gp ON g.id = gp.game_id
			INNER JOIN game_players gpp ON g.id = gpp.game_id
			WHERE gp.player_id = ?
			ORDER BY number, suffix, gpp.move_order
			'))
		{
			$player_id = $player->getID();

			if (!$stmt->bind_param('i', $player_id))
			{
				 throw new Exception("Can't bind parameter".$stmt->error);
			}
			if (!$stmt->execute())
			{
				throw new Exception("Can't execute statement: ".$stmt->error);
			}
			if (!$stmt->bind_result(
				$id,
				$number,
				$suffix,
				$big_cards_amount,
				$small_cards_amount,
				$rounding_up,
				$player_id))
			{
				throw new Exception("Can't bind result: ".$stmt->error);
			}

			while($stmt->fetch() === TRUE) {
				if (array_key_exists($id, $game_data)) {
					$game_data[$id]['players'][] = new Player(null, $player_id);
				} else {
					$game_data[$id] = array(
						'number' => $number,
						'suffix' => $suffix,
						'players' => array(
							new Player(null, $player_id)
						),
						'big_cards_amount' => $big_cards_amount,
						'small_cards_amount' => $small_cards_amount,
						'rounding_up' => $rounding_up ? true : false
					);
				}
			}
			$stmt->close();
		}
		else
		{
			throw new Exception("Can't prepare statement: ".$db->error);
		}

		$game_decks = array();

		if ($stmt = $db->prepare('SELECT game_id, player_number, card_id
			FROM game_player_cards
			WHERE game_id IN ('.implode(', ', array_keys($game_data)).')'))
		{
			if (!$stmt->execute())
			{
				throw new Exception("Can't execute statement: ".$stmt->error);
			}
			if (!$stmt->bind_result($game_id, $player_number, $card_id))
			{
				throw new Exception("Can't bind result: ".$stmt->error);
			}

			while($stmt->fetch() === TRUE) {
				$game_decks[$game_id][$player_number][] = Card::getCard($card_id);
			}
			$stmt->close();
		}
		else
		{
			throw new Exception("Can't prepare statement: ".$db->error);
		}

		$games = array();

		foreach ($game_data as $id => $data) {
			$games[] = new Game(
				$data['number'],
				$data['suffix'],
				$data['players'],
				$data['big_cards_amount'],
				$data['small_cards_amount'],
				$data['rounding_up'],
				$game_decks[$id],
				null,
				$id
			);
		}

		return $games;
	}
}

class InvalidGameStateException extends Exception
{
}
