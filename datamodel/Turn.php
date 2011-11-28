<?
#
# Just a data structure to hold all the turn data - actual validation and stuff happends in the game class
#
# @author Sergey Chernyshev
# @version $Rev: 56 $
#
# $Id: Turn.php 56 2008-03-31 02:16:19Z sergey $
#
class Turn 
{
	var $player;				# player making the turn 
	var $before = array();			# amounts of stocks before the card is played
	var $card;				# card that got used during the turn 

	/**
		changes to the prices (new prices) that were triggered by the card and player selection
		defines full price - can go over the range (10-250); real price will be determined on the fly
		must already have rounding to closest higher 10 applied
		only changed prices should have values defined in this array
	*/
	var $price_changes = array();

	var $after = array();			# amounts of stocks after the card is played
	var $bank;				# amount in the bank after the turn 
	
	/**
		Changes (new values) to opponent stock amounts and bank amount triggered by the turn 
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
			the entry for the player making a turn must be null
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

	/**
	 * Pass an array of games to populate turns for these games
	 */
	public static function getTurns($games) {
		global $db;

		$gameshash = array();

		foreach ($games as $game) {
			$gameshash[$game->getID()] = $game;
		}

		$turns_data = array();
		$turns = array();

		if ($stmt = $db->prepare('SELECT
				t.id,
				t.game_id,
				t.number,
				t.player_id,
				t.blue_before, t.red_before, t.yellow_before, t.green_before,
				t.card_id,
				t.blue_change, t.red_change, t.yellow_change, t.green_change,
				t.blue_after, t.red_after, t.yellow_after, t.green_after,
				t.bank,
				oc.player_number as oc_player_number,
				oc.blue_change as oc_blue_change,
				oc.red_change as oc_red_change,
				oc.yellow_change as oc_yellow_change,
				oc.green_change as oc_green_change,
				oc.bank_change as oc_bank_change
			FROM turn t
			LEFT JOIN opponent_changes oc ON t.id = oc.turn_id
			WHERE t.game_id IN ('.implode(', ', array_keys($gameshash)).')
			ORDER BY game_id, number, oc_player_number
			'))
		{
			if (!$stmt->execute())
			{
				throw new Exception("Can't execute statement: ".$stmt->error);
			}
			if (!$stmt->bind_result(
				$id,
				$game_id,
				$number,
				$player_id,
				$blue_before, $red_before, $yellow_before, $green_before,
				$card_id,
				$blue_change, $red_change, $yellow_change, $green_change,
				$blue_after, $red_after, $yellow_after, $green_after,
				$bank,
				$oc_player_number,
				$oc_blue_change,
				$oc_red_change,
				$oc_yellow_change,
				$oc_green_change,
				$oc_bank_change
				))
			{
				throw new Exception("Can't bind result: ".$stmt->error);
			}

			while($stmt->fetch() === TRUE) {
				if (!array_key_exists($id, $turns_data)) {
					$price_changes = array();
					if (!is_null($blue_change)) {
						$price_changes[Card::BLUE] = $blue_change;
					}
					if (!is_null($red_change)) {
						$price_changes[Card::RED] = $red_change;
					}
					if (!is_null($yellow_change)) {
						$price_changes[Card::YELLOW] = $yellow_change;
					}
					if (!is_null($green_change)) {
						$price_changes[Card::GREEN] = $green_change;
					}

					$turns_data[$id] = array(
						'game_id' => $game_id,
						'player' => new Player(null, $player_id),
						'before' => array(
							Card::BLUE => $blue_before,
							Card::RED => $red_before,
							Card::YELLOW => $yellow_before,
							Card::GREEN => $green_before
						),
						'card' => Card::getCard($card_id),
						'price_changes' => $price_changes,
						'after' => array(
							Card::BLUE => $blue_after,
							Card::RED => $red_after,
							Card::YELLOW => $yellow_after,
							Card::GREEN => $green_after
						),
						'bank' => $bank,
						'opponent_changes' => null
					);

					$turns[] = &$turns_data[$id];
				}

				if (!is_null($oc_player_number)) {
					if (!is_array($turns_data[$id]['opponent_changes'])) {
						$turns_data[$id]['opponent_changes'] = array();
					}

					$turns_data[$id]['opponent_changes'][$oc_player_number] = array(
						Card::BLUE => $oc_blue_change,
						Card::RED => $oc_red_change,
						Card::YELLOW => $oc_yellow_change,
						Card::GREEN => $oc_green_change,
						'bank' => $oc_bank_change
					);
				}
			}
			$stmt->close();
		}
		else
		{
			throw new Exception("Can't prepare statement: ".$db->error);
		}

		foreach ($turns as $data) {
			$gameshash[$data['game_id']]->makeTurn(new self(
				$data['player'],
				$data['before'],
				$data['card'],
				$data['price_changes'],
				$data['after'],
				$data['bank'],
				$data['opponent_changes']
			));
		}

		return $games;
	}
}

class InvalidTurnException extends Exception
{
}

