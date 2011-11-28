<?
#
# This abstract class represents small cards
#
# @author Sergey Chernyshev
# @version $Rev: 61 $
#
# $Id: SmallCard.php 2 2008-02-11 02:52:08Z sergey $
#
require_once(dirname(__FILE__).'/Card.php');

class SmallCard extends Card
{
	public static $deck;

	private $value;

	function SmallCard($id, $value, $color)
	{
		$this->id = $id;
		$this->value = $value;
		$this->color = $color;

		Card::addCard($this);
	}

	function getSortIndex()
	{
		return 1000*$this->color + $this->value;
	}

	function validatePriceChanges($original_prices, $price_changes)
	{
		if (!array_key_exists($this->color, $original_prices))
		{
			throw new InvalidPriceChangeException("Small card must change the value for it's color");
		}

		if ($original_prices[$this->color] + $this->value != $price_changes[$this->color])
		{
			throw new InvalidPriceChangeException('Price change for small card color must match card value');
		}

		if (count($price_changes) != 2)
		{
			throw new InvalidPriceChangeException('Small card always changes price for exactly two colors');
		}	

		$other_color_change = (90 - abs($this->value)) * ($this->value > 0 ? -1 : 1);
		foreach ($price_changes as $color => $new_value)
		{
			# skip card's defined color
			if ($color == $this->color)
			{
				continue;
			}

			if ($original_prices[$color] + $other_color_change != $new_value)
			{
				throw new InvalidPriceChangeException("When small card is applied, the second color change in the other direction and absolute of change value must be equal to 90 - absolute of card's value");
			}
		}

		return true;
	}

	function asString()
	{
		return ($this->value > 0 ? '+'.$this->value : $this->value).$this->getCardColorLetter();
	}

	public static function getDeck() {
		return self::$deck;
	}

	# static initializer for the deck - must be called at the bottom of this file
	public static function createDeck()
	{
		if (is_null(self::$deck)) {
			self::$deck = new Deck(array(
				new SmallCard(1,	+30,	Card::BLUE),
				new SmallCard(2,	+30,	Card::RED),
				new SmallCard(3,	+30,	Card::YELLOW),
				new SmallCard(4,	+30,	Card::GREEN),
				new SmallCard(5,	+40,	Card::BLUE),
				new SmallCard(6,	+40,	Card::RED),
				new SmallCard(7,	+40,	Card::YELLOW),
				new SmallCard(8,	+40,	Card::GREEN),
				new SmallCard(9,	+50,	Card::BLUE),
				new SmallCard(10,	+50,	Card::RED),
				new SmallCard(11,	+50,	Card::YELLOW),
				new SmallCard(12,	+50,	Card::GREEN),
				new SmallCard(13,	+60,	Card::BLUE),
				new SmallCard(14,	+60,	Card::RED),
				new SmallCard(15,	+60,	Card::YELLOW),
				new SmallCard(16,	+60,	Card::GREEN),

				new SmallCard(17,	-30,	Card::BLUE),
				new SmallCard(18,	-30,	Card::RED),
				new SmallCard(19,	-30,	Card::YELLOW),
				new SmallCard(20,	-30,	Card::GREEN),
				new SmallCard(21,	-40,	Card::BLUE),
				new SmallCard(22,	-40,	Card::RED),
				new SmallCard(23,	-40,	Card::YELLOW),
				new SmallCard(24,	-40,	Card::GREEN),
				new SmallCard(25,	-50,	Card::BLUE),
				new SmallCard(26,	-50,	Card::RED),
				new SmallCard(27,	-50,	Card::YELLOW),
				new SmallCard(28,	-50,	Card::GREEN),
				new SmallCard(29,	-60,	Card::BLUE),
				new SmallCard(30,	-60,	Card::RED),
				new SmallCard(31,	-60,	Card::YELLOW),
				new SmallCard(32,	-60,	Card::GREEN)
			));
		}
	}
}
SmallCard::createDeck();
