<?
#
# This abstract class represents big cards
#
# @author Sergey Chernyshev
# @version $Rev: 61 $
#
# $Id: BigCard.php 2 2008-02-11 02:52:08Z sergey $
#
require_once('Card.php');
require_once('Deck.php');

abstract class BigCard extends Card
{
	# static initializer for the deck - must be called at the top of this file
	static function getDeck()
	{
		return new Deck(array(
			new Hundred(Card::BLUE),
			new Hundred(Card::BLUE),
			new Hundred(Card::BLUE),
			new MultiplyBy2(Card::BLUE),
			new DivideBy2(Card::BLUE),
		
			new Hundred(Card::RED),
			new Hundred(Card::RED),
			new Hundred(Card::RED),
			new MultiplyBy2(Card::RED),
			new DivideBy2(Card::RED),
		
			new Hundred(Card::YELLOW),
			new Hundred(Card::YELLOW),
			new Hundred(Card::YELLOW),
			new MultiplyBy2(Card::YELLOW),
			new DivideBy2(Card::YELLOW),
		
			new Hundred(Card::GREEN),
			new Hundred(Card::GREEN),
			new Hundred(Card::GREEN),
			new MultiplyBy2(Card::GREEN),
			new DivideBy2(Card::GREEN)
		));
	}
}

class Hundred extends BigCard
{
	function Hundred($color)
	{
		$this->color = $color;
	}

	function validatePriceChanges($original_prices, $price_changes)
	{
		if (!array_key_exists($this->color, $original_prices))
		{
			throw new InvalidPriceChangeException("100 card must change the value for it's color");
		}

		if ($original_prices[$this->color] + 100 != $price_changes[$this->color])
		{
			throw new InvalidPriceChangeException('New price for 100 card color must be hugher then original price by 100');
		}

		if (count($price_changes) != 4)
		{
			throw new InvalidPriceChangeException('100 card changes price for all colors');
		}

		$other_changes = array(
			-10 => true,
			-20 => true,
			-30 => true
		);	

		foreach ($price_changes as $color => $new_value)
		{
			# skip card's defined color
			if ($color == $this->color)
			{
				continue;
			}

			$change = $new_value - $original_prices[$color];

			if (!array_key_exists($change, $other_changes))
			{
				throw new InvalidPriceChangeException("When 100 card is applied, the other colors can only change by -10, -20 or -30");
			}

			if (!$other_changes[$change])
			{
				throw new InvalidPriceChangeException("When 100 card is applied, only one other color can change by $change");
			}

			$other_changes[$change] = false;
		}

		return true;
	}

	function asString()
	{
		return '100'.$this->getCardColorLetter();
	}

	function getSortIndex()
	{
		return 10000 + 1000*$this->color + 3;
	}
}

class MultiplyBy2 extends BigCard
{
	function MultiplyBy2($color)
	{
		$this->color = $color;
	}

	function validatePriceChanges($original_prices, $price_changes, $rounding = true)
	{
		if (!array_key_exists($this->color, $original_prices))
		{
			throw new InvalidPriceChangeException("x2 card must change the value for it's color");
		}

		if ($original_prices[$this->color] * 2 != $price_changes[$this->color])
		{
			throw new InvalidPriceChangeException('New price for x2 card color must be twice higher then original price');
		}

		if (count($price_changes) != 2)
		{
			throw new InvalidPriceChangeException('x2 card changes price for two colors');
		}	

		foreach ($price_changes as $color => $new_value)
		{
			# skip card's defined color
			if ($color == $this->color)
			{
				continue;
			}

			/**
				[Rule 7]
				Rounding should be up or down based on game setting ($rounding parameter - true = up)
			*/
			if ($rounding)
			{
				$rounded = (int)(ceil($original_prices[$color] / 20) * 10);
			}
			else
			{
				$rounded = (int)(floor($original_prices[$color] / 20) * 10);
			}

			if ($rounded != $new_value)
			{
				throw new InvalidPriceChangeException("When x2 card is applied, the second color value is divided by 2 and rounded up to nearest 10. For ".Card::getColorTitle($color).', got '.$price_changes[$color].' instead of '.$rounded);
			}
		}

		return true;
	}

	function asString()
	{
		return 'x2'.$this->getCardColorLetter();
	}

	function getSortIndex()
	{
		return 10000 + 1000*$this->color + 2;
	}
}

class DivideBy2 extends BigCard
{
	function DivideBy2($color)
	{
		$this->color = $color;
	}

	function validatePriceChanges($original_prices, $price_changes, $rounding = true)
	{
		if (!array_key_exists($this->color, $original_prices))
		{
			throw new InvalidPriceChangeException(":2 card must change the value for it's color");
		}
  
		/** 
			[Rule 7]
			Rounding should be up or down based on game setting ($rounding parameter - true = up)
		*/
		if ($rounding)
		{
			$rounded = (int)(ceil($original_prices[$this->color] / 20) * 10);
		}
		else
		{
			$rounded = (int)(floor($original_prices[$this->color] / 20) * 10);
		}

		if ($rounded != $price_changes[$this->color])
		{
			throw new InvalidPriceChangeException('New price for :2 card color must be twice smaller then original price, also it must be rounded '.($rounding ? 'up' : 'down').' to nearest 10. For '.Card::getColorTitle($this->color).', got '.$price_changes[$this->color].' instead of '.$rounded);
		}

		if (count($price_changes) != 2)
		{
			throw new InvalidPriceChangeException(':2 card changes price for two colors');
		}	

		foreach ($price_changes as $color => $new_value)
		{
			# skip card's defined color
			if ($color == $this->color)
			{
				continue;
			}

			if ($original_prices[$color] * 2 != $new_value)
			{
				throw new InvalidPriceChangeException('When :2 card is applied, the second color value is multiplied by 2. For '.Card::getColorTitle($color)." got $new_value instead of ".$original_prices[$color]);
			}
		}

		return true;
	}

	function asString()
	{
		return ':2'.$this->getCardColorLetter();
	}

	function getSortIndex()
	{
		return 10000 + 1000*$this->color + 1;
	}
}
