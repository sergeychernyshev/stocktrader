<?
#
# This abstract class represents small cards
#
# @author Sergey Chernyshev
# @version $Rev: 61 $
#
# $Id: SmallCard.php 2 2008-02-11 02:52:08Z sergey $
#
require_once('Card.php');

class SmallCard extends Card
{
	private $value;

	function SmallCard($value, $color)
	{
		$this->value = $value;
		$this->color = $color;
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
			throw new InvalidPriceChangeException('Small card changes price for two colors');
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

	# static initializer for the deck - must be called at the top of this file
	static function getDeck()
	{
		return new Deck(array(
			new SmallCard(+30, Card::BLUE),
			new SmallCard(+30, Card::RED),
			new SmallCard(+30, Card::YELLOW),
			new SmallCard(+30, Card::GREEN),
			new SmallCard(+40, Card::BLUE),
			new SmallCard(+40, Card::RED),
			new SmallCard(+40, Card::YELLOW),
			new SmallCard(+40, Card::GREEN),
			new SmallCard(+50, Card::BLUE),
			new SmallCard(+50, Card::RED),
			new SmallCard(+50, Card::YELLOW),
			new SmallCard(+50, Card::GREEN),
			new SmallCard(+60, Card::BLUE),
			new SmallCard(+60, Card::RED),
			new SmallCard(+60, Card::YELLOW),
			new SmallCard(+60, Card::GREEN),

			new SmallCard(-30, Card::BLUE),
			new SmallCard(-30, Card::RED),
			new SmallCard(-30, Card::YELLOW),
			new SmallCard(-30, Card::GREEN),
			new SmallCard(-40, Card::BLUE),
			new SmallCard(-40, Card::RED),
			new SmallCard(-40, Card::YELLOW),
			new SmallCard(-40, Card::GREEN),
			new SmallCard(-50, Card::BLUE),
			new SmallCard(-50, Card::RED),
			new SmallCard(-50, Card::YELLOW),
			new SmallCard(-50, Card::GREEN),
			new SmallCard(-60, Card::BLUE),
			new SmallCard(-60, Card::RED),
			new SmallCard(-60, Card::YELLOW),
			new SmallCard(-60, Card::GREEN)
		));
	}
}
