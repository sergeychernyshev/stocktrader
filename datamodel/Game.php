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
require_once(dirname(__FILE__).'/Move.php');
require_once(dirname(__FILE__).'/Player.php');

class Game
{
	# game number and suffix
	private $number;
	private $suffix; # A, B, C, D and etc.

	# array of players (number of players equals number of moves in one turn)
	private $players;

	# an array of arrays of player cards for each player
	private $playerCards = array();

	# should be 10 turns with X moves where X is equals number of players
	private $turns = array();

	# number of big cards
	private $bigCardsAmount;

	# number of small cards
	private $smallCardsAmount;

	# total number of turns in the game
	private $totalTurns;

	# rounding up (or down) if :2 is used
	private $rounding;

	function __construct($number, $suffix, $players, $bigCardsAmount = 4, $smallCardsAmount = 6, $rounding = true, $decks = null, $moves = null)
	{
		$this->number = $number;
		$this->suffix = $suffix;
		$this->players = $players;
		$this->bigCardsAmount = $bigCardsAmount;
		$this->smallCardsAmount = $smallCardsAmount;
		$this->totalTurns = $bigCardsAmount + $smallCardsAmount;
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

		if (!is_null($moves))
		{
			foreach ($moves as $move)
			{
				$this->makeMove($move);
			}
		}
	}

	function asFullTableHTML()
	{
		$output = '';

		# printing game number
		$output .= '<div id="gamenumber">';
		$output .= '<span id="gamenum">'.$this->number.'</span>';
		$output .= '<span id="gamesuffix">'.$this->suffix.'</span>';
		$output .= "</div>\n\n";

		# printing players table
		$output .= '<div id="gameplayers">'."\n";
		for ($p=0; $p < count($this->players); $p++)
		{
			$output .= '<div class="player" id="player'.($p+1).'">';
			$output .= $this->players[$p]->asString();
			$output .= "</div>\n";
		}
		$output .= "</div>\n\n";

		$totalPlayers = $p;

		$prices = array(
			Card::BLUE => 100,
			Card::RED => 100,
			Card::YELLOW => 100,
			Card::GREEN => 100,
		);

		# printing turns 
		$output .= '<table id="gameturns">';
		for ($t=0; $t < count($this->turns); $t++)
		{
			$turn = $this->turns[$t];

			$output .= "\n<!-- START turn ".($t+1)." -->";

			# printing moves
			for ($m=0; $m < $totalPlayers; $m++)
			{
				$output .= "\n".'<tr class="move player'.($m+1).'move" id="move'.($t+1).'-'.($m+1).'">';

				if ($m == 0)
				{
					$output .= '<td class="turnnum" rowspan="'.$totalPlayers.'">'.($t+1)."</td>";
				}

				$output .= "\n";

				if (array_key_exists($m, $turn))
				{
					$move = $turn[$m];

					# printing amounts before
					$output.= '<td class="blue-before">'.$move->before[Card::BLUE].'</td>';
					$output.= '<td class="red-before">'.$move->before[Card::RED].'</td>';
					$output.= '<td class="yellow-before">'.$move->before[Card::YELLOW].'</td>';
					$output.= '<td class="green-before">'.$move->before[Card::GREEN]."</td>\n";

					# printing card
					$output.= '<td class="card">'.$move->card->asString()."</td>\n";

					# printing prices
					foreach (array_keys($move->price_changes) as $color)
					{
						$prices[$color] = $move->price_changes[$color];
					}

					$output.= '<td class="blue-price'.(array_key_exists(Card::BLUE, $move->price_changes) ? ' changed' : '').'">';
					$output.= $prices[Card::BLUE];
					$output.= '</td>';

					$output.= '<td class="red-price'.(array_key_exists(Card::RED, $move->price_changes) ? ' changed' : '').'">';
					$output.= $prices[Card::RED];
					$output.= '</td>';

					$output.= '<td class="yellow-price'.(array_key_exists(Card::YELLOW, $move->price_changes) ? ' changed' : '').'">';
					$output.= $prices[Card::YELLOW];
					$output.= '</td>';

					$output.= '<td class="green-price'.(array_key_exists(Card::GREEN, $move->price_changes) ? ' changed' : '').'">';
					$output.= $prices[Card::GREEN];
					$output.= "</td>\n";

					# printing amounts after
					$blue_after = $move->after[Card::BLUE];
					$red_after = $move->after[Card::RED];
					$yellow_after = $move->after[Card::YELLOW];
					$green_after = $move->after[Card::GREEN];

					# TODO: calculate updates to after amounts made by consequent moves
					$output.= '<td class="blue-after">'.$blue_after.'</td>';
					$output.= '<td class="red-after">'.$red_after.'</td>';
					$output.= '<td class="yellow-after">'.$yellow_after.'</td>';
					$output.= '<td class="green-after">'.$green_after."</td>\n";

					# TODO: calculate updates to bank balance made by consequent moves
					$output.= '<td class="bank">'.$move->bank.'</td>';
				}
				else
				{
					$output.= '<td></td>';
					$output.= '<td></td>';
					$output.= '<td></td>';
					$output.= '<td></td>';

					$output.= '<td></td>';

					$output.= '<td></td>';
					$output.= '<td></td>';
					$output.= '<td></td>';
					$output.= '<td></td>';

					$output.= '<td></td>';
					$output.= '<td></td>';
					$output.= '<td></td>';
					$output.= '<td></td>';

					$output.= '<td></td>';
				}

				
				$output .= "\n</tr>\n";
			}

			$output .= "<!-- END turn ".($t+1)." -->\n";
		}
		$output .= "</table>\n";

		return $output;
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

	function makeMove($move)
	{
		$valid = $this->validateMove($move);

		if (!$valid)
		{
			throw new InvalidMoveException("Didn't pass move validation");
		}

		if (count($this->turns)>0)
		{
			$lastTurn = $this->turns[count($this->turns)-1];

			if (count($lastTurn) == $this->getNumberOfPlayers())
			{
				# first move in next turn 
				$this->turns[] = array($move);
			}
			else
			{
				$this->turns[count($this->turns)-1][] = $move;
			}
		}
		else
		{
			# first move by first player in first turn
			$this->turns[] = array($move);
		}
	}

	function getNumberOfPlayers()
	{
		return count($this->players);
	}

	# TODO validate game state (not including moves - should be called before moves were applied)
	function validateGame()
	{
		return true;
	}

	# check if move data is not conflicting with, return true or throw an InvalidMove exception
	function validateMove($move)
	{
		/**
			[Rule 4(1)] Check if game still has unplayed moves
		*/
		if (count($this->turns) == $this->totalTurns && count($this->turns[count($this->turns)-1]) == $this->getNumberOfPlayers())
		{
			throw new InvalidMoveException('All moves in the game are already played');
		}

		/**
			[Rule 3a]
			Validate stock amounts before card was played.
			Value of stocks must be equal or less the value of stocks of the user plus amount in the bank
				after previus (last opponent's) move.
		*/

		# get the amounts after this user's last move
		$before;

		$currentTurn; # turn in which this move is being made
		$prevTurn; # previous turn
		$playerNumber; # a number of the player making this move

		if (count($this->turns)>1)
		{
			$lastTurn = $this->turns[count($this->turns)-1];

			if (count($lastTurn) == $this->getNumberOfPlayers())
			{
				$currentTurn = array();
				$prevTurn = $lastTurn;
				$playerNumber = 0;
			}
			else
			{
				$currentTurn = $lastTurn;
				$prevTurn = $this->turns[count($this->turns)-2];
				$playerNumber = count($currentTurn);
			}


			# if last turn is full, then new one is current
			# this means that currentTurn is never full
			$prevUserMove = $prevTurn[$playerNumber];

			$before = array(
				Card::BLUE => $prevUserMove->after[Card::BLUE],
				Card::RED => $prevUserMove->after[Card::RED],
				Card::YELLOW => $prevUserMove->after[Card::YELLOW],
				Card::GREEN => $prevUserMove->after[Card::GREEN],
				'bank' => $prevUserMove->bank
			);
		}
		else
		{
			# this user didn't make a move yet, it means he had 1 of each and no money in the bank
			$before = array(
				Card::BLUE => 1,
				Card::RED => 1,
				Card::YELLOW => 1,
				Card::GREEN => 1,
				'bank' => 0
			);

			# this is very first move
			if (count($this->turns) == 0)
			{
				$currentTurn = array();
				$prevTurn = null;
				$playerNumber = 0;
			}
			# first turn is just over
			elseif (count($this->turns[0]) == $this->getNumberOfPlayers())
			{
				$currentTurn = array();
				$prevTurn = $this->turns[0];
				$playerNumber = 0;

				# this user actually made one move before
				$before = array(
					Card::BLUE => $prevTurn[0]->after[Card::BLUE],
					Card::RED => $prevTurn[0]->after[Card::RED],
					Card::YELLOW => $prevTurn[0]->after[Card::YELLOW],
					Card::GREEN => $prevTurn[0]->after[Card::GREEN],
					'bank' => $prevTurn[0]->bank
				);
			}
			#first turn in progress
			else
			{
				$currentTurn = $this->turns[0];
				$prevTurn = null;
				$playerNumber = count($currentTurn);
			}
		}

		# now lets apply all changes to user's amounts from opponents moves since users last move
		$opponent_moves = array();

		# first, go through previous turn if there was previous turn and this is not last move in turn
		if (!is_null($prevTurn) && $playerNumber < $this->getNumberOfPlayers()-1)
		{
			for ($i=$playerNumber+1; $i<$this->getNumberOfPlayers(); $i++)
			{
				$opponent_moves[] = $prevTurn[$i];
			}
		}
		# now, let's add all moves already in current turn
		$opponent_moves = array_merge($opponent_moves, $currentTurn);

		# OK, we got a sequence of opponent moves after user's previous move, let's apply changes from each move if they exist
		foreach($opponent_moves as $opponent_move)
		{
			/**
				if whole change set is null it means there were no changed,
				if change for the player is null, it means this players amounts were not affected.
				In both cases, there are no changes to apply.
			*/
			if (is_null($opponent_move->opponent_changes) || is_null($opponent_move->opponent_changes[$playerNumber]))
			{
				continue;
			}

			foreach ($opponent_move->opponent_changes[$playerNumber] as $key => $value)
			{
				$before[$key] = $value;
			}
		}

		# now, let's calculate the value by multiplying user stock amounts by their prices from last opponent's move

		# first we need to calculate current prices, initial price is 100 for all stocks
		$prices = array(
			Card::BLUE => 100,
			Card::RED => 100,
			Card::YELLOW => 100,
			Card::GREEN => 100
		);

		# let's through all moves that were made in the game and apply changes
		foreach ($this->turns as $turn)
		{
			foreach ($turn as $turn_move)
			{
				# get all changed price (only changed ones are defined)
				foreach ($turn_move->price_changes as $color => $new_value)
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

		# ok, we got prices, we got user's last move stock and bank amounts, let's get the value
		$value_after_last_move = $before['bank'] +
				$before[Card::BLUE] * $prices[Card::BLUE] +
				$before[Card::RED] * $prices[Card::RED] +
				$before[Card::YELLOW] * $prices[Card::YELLOW] +
				$before[Card::GREEN] * $prices[Card::GREEN];

		$move_before_value = $before['bank'] +
				$move->before[Card::BLUE] * $prices[Card::BLUE] +
				$move->before[Card::RED] * $prices[Card::RED] +
				$move->before[Card::YELLOW] * $prices[Card::YELLOW] +
				$move->before[Card::GREEN] * $prices[Card::GREEN];

		# Finally let's compare them and if current value is more then the value after last move, then move is invalid
		if ($move_before_value > $value_after_last_move)
		{
			throw new InvalidMoveException('Value of stocks purchased before playing the card is higher then user has at the beginning of the move.');
		}

		/**
			[Rule 2b(2)]
			Price changes must match card definitions
			Cards must validate price changes and throw exceptions if needed
		*/
		$move->card->validatePriceChanges($prices, $move->price_changes, $this->rounding);

		/**
			[Rule 3c(4)]
			Player can't sell more stock then he had before the move
		*/
		foreach (Card::getColors() as $color)
		{
			if ($move->after[$color] < $move->before[$color] && $move->after[$color] - $move->before[$color] > $before[$color])
			{
				throw new InvalidMoveException("Amount of ".Card::getColorTitle($color)." stock sold during the move is higher then at the end of user's previous move.");
			}
		}

		/**
			[Rule 3c(3)]
			Value of stocks and bank account must be the same right after the prices were changed and at the end of the move
		*/
		$compensation = 0;

		$move_after_value = $before['bank'];

		$prices_after = array();
		foreach (Card::getColors() as $color)
		{
			if (array_key_exists($color, $move->price_changes))
			{
				$prices_after[$color] = $move->price_changes[$color];

				/**
					[Rule 5b]
					if price went higher then 250, user immediately gets compensation for it being cut
				*/
				if ($prices_after[$color] > 250)
				{
					$compensation += ($prices_after[$color] - 250) * $move->before[$color];
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
					$compensation += ($prices[$color] - $prices_after[$color]) * $move->before[$color];
				}
			}
			else
			{
				$prices_after[$color] = $prices[$color];
			}
			$move_after_value += $move->before[$color] * $prices_after[$color];
		}
		$move_after_value += $compensation;

		$move_end_value = $move->bank;
		foreach (Card::getColors() as $color)
		{
			$move_end_value += $move->after[$color] * $prices_after[$color];
		}

		if ($move_after_value != $move_end_value)
		{
			/*
			echo var_export(array(
				'move' => $move,
				'before' => $before,
				'prices_after' => $prices_after,
				'compensation' => $compensation,
				'after_value' => $move_after_value,
				'end_value' => $move_end_value)
			);
			*/
			throw new InvalidMoveException('Value of stocks and bank account must be the same right after the prices were changed and at the end of the move');
		}

		/**
			[Rule 3b(1)] Check if user plays card that he has and didn't use yet
		*/
		$has_card = false;
		foreach ($this->playerCards[$playerNumber] as $card)
		{
			if ($move->card->equals($card))
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
				$deckLabels[] = $card->toString();
			}

			echo var_export(array('game' => $this, 'move' => $move));
			throw new InvalidMoveException("Player can't use a card that wasn't dealt to him: ".$move->card->toString()." (player: $playerNumber; deck: ".implode(', ', $deckLabels).')');
		}

		$player_cards = $this->playerCards[$playerNumber];
		#first remove cards that are already played
		foreach ($this->turns as $turn)
		{
			for ($i=0; $i<count($player_cards); $i++)
			{
				if ($move->card->equals($player_cards[$i]))
				{
					$player_cards = array_splice($player_cards, $i, 1);
					break; # once we shortened the array, 
				}
			}
		}

		$still_in_deck = false;
		foreach ($player_cards as $card)
		{
			if ($move->card->equals($card))
			{
				$still_in_deck = true;
				break;
			}
		}

		if (!$still_in_deck)
		{
			throw new InvalidMoveException("Player can't use a card that he already used");
		}

		/** 
			[Rule 5b] 	Check if other users get correct compensation for >250 rule
		*/
		foreach ($move->price_changes as $color => $new_value)
		{
			if ($new_value > 250)
			{
				# let's cycle through opponent moves and see if they got the compensation
				for ($i = 0; $i < count($opponent_moves); $i++)
				{
					# this user had this stock left in last move
					if (array_key_exists($color, $opponent_moves[$i]->after))
					{
						$last_stock_amount = $opponent_moves[$i]->after[$color];
						$last_bank_balance = $opponent_moves[$i]->bank;

						# we need opponent's number in a list of players to reference opponent_changes
						$opponentNumber = $playerNumber + $i + 1;

						# if $opponent_moves has less entries then $this->getNumberOfPlayers() - 1
						# it means that we're in the first turn and $opponentNumber should be adjusted accordingly
						$opponentNumber += $this->getNumberOfPlayers() - 1 - count($opponent_moves);

						if ($opponentNumber > $this->getNumberOfPlayers() - 1)
						{
							$opponentNumber -= $this->getNumberOfPlayers();
						}

						# apply changes made by consequent moves if any to see final amount of stock and bank balance !!!
						# required for games with more then 2 players (more then one opponent move)
						for ($j = $i+1; $j < count($opponent_moves); $j++)
						{
							if (array_key_exists($color, $opponent_moves[$j]->opponent_changes[$opponentNumber]))
							{
								$last_stock_amount = $opponent_moves[$j]->opponent_changes[$opponentNumber][$color];
							}

							if (array_key_exists('bank', $opponent_moves[$j]->opponent_changes[$opponentNumber]))
							{
								$last_bank_balance = $opponent_moves[$j]->opponent_changes[$opponentNumber]['bank'];
							}
						}

						$compensation = $last_stock_amount * ($new_value - 250);
						$balance_change = $move->opponent_changes[$opponentNumber]['bank'] - $last_bank_balance;
						if ($compensation != $balance_change)
						{
							/*
							echo var_export(array(
								'$opponent_moves' => $opponent_moves,
								'$opponentNumber' => $opponentNumber,
								'$compensation' => $compensation,
								'$i' => $i,
								'$j' => $j,
								'$last_stock_amount' => $last_stock_amount,
								'$last_bank_balance' => $last_bank_balance,
								'$balance_change' => $balance_change,
								'$move' => $move
							));
							*/

							throw new InvalidMoveException("Player wasn't compensated for stock price going over 250");
						}
					}
				}
				break; # there can be only one stock that went above 250 in single move
			}
		}

		/**
			[Rule 4(2)] If this is last move, check player didn't buy or sell stocks
		*/
		$lastTurnNumber = $playerNumber == 0 ? $this->totalTurns - 1 : $this->totalTurns;
		if (count($this->turns) == $lastTurnNumber)
		{
			foreach (Card::getColors() as $color)
			{
				if ($move->after[$color] != $move->before[$color] || $move->before[$color] != $before[$color])
				{
					throw new InvalidMoveException("Player can't buys or sell stock during last turn.");
				}
			}
		}

		/**
			Check that move is done by the user who's turn it is
		*/
		if (!$this->players[$playerNumber]->compare($move->player))
		{
			throw new InvalidMoveException("Wrong player is making the move.");
		}

		/**
			[Rule 6] Check if other users' stock and bank amounts get corrected appropriately when going below 10
		*/

		# let's get an array of price_changes in order of compensation
		$price_changes_below10 = array();
		foreach ($move->price_changes as $color => $new_value)
		{
			if ($new_value < 10)
			{
				$drop = $prices[$color] - $new_value;
				$price_changes_below10[$drop] = $color;
			}
		}
		krsort($price_changes_below10);

#		echo var_export(array(
#			'changes' => $move->price_changes,
#			'below10' => $price_changes_below10
#		))."\n";

		if (count($price_changes_below10) > 0)
		{
			# let's cycle through opponent moves and see if amounts got corrected properly
			for ($i = 0; $i < count($opponent_moves); $i++)
			{
				$bank_left = $opponent_moves[$i]->bank; # will calculate overall compensation for the more

				# we need opponent's number in a list of players to reference opponent_changes
				$opponentNumber = $playerNumber + $i + 1;

				# if $opponent_moves has less entries then $this->getNumberOfPlayers() - 1
				# it means that we're in the first turn and $opponentNumber should be adjusted accordingly
				$opponentNumber += $this->getNumberOfPlayers() - 1 - count($opponent_moves);

				if ($opponentNumber > $this->getNumberOfPlayers() - 1)
				{
					$opponentNumber -= $this->getNumberOfPlayers();
				}

				foreach ($price_changes_below10 as $drop => $color)
				{
					# this user had this stock left in last move
					if (array_key_exists($color, $opponent_moves[$i]->after))
					{
						if ($opponent_moves[$i]->after[$color] < $move->opponent_changes[$opponentNumber][$color])
						{
							throw new InvalidMoveException("User didn't have that many stocks before compensation.");
						}

						$bank_left = $bank_left - $move->opponent_changes[$opponentNumber][$color] * $drop;

						if ($bank_left < 0)
						{
							throw new InvalidMoveException("Not enough money in the bank to pay off the fee that many stocks.");
						}

						if ($opponent_moves[$i]->after[$color] > $move->opponent_changes[$opponentNumber][$color]
								&& $bank_left >= $drop)
						{
							throw new invalidmoveexception("Must pay off all stocks player has money for.");
						}
					}
				}

				if ($move->opponent_changes[$opponentNumber] != NULL
					&& $move->opponent_changes[$opponentNumber]['bank'] != $bank_left)
				{
#					echo var_export(array(
#						'opponent' => $opponentNumber,
#						'changes' => $move->opponent_changes[$opponentNumber],
#						'bank_left' => $bank_left
#					));

					throw new invalidmoveexception("Bank balances after the fee don't match.");
				}
			}
		}

		return true;
	}
}

class InvalidGameStateException extends Exception
{
}
