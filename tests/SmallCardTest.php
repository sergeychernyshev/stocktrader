<?
require_once 'PHPUnit/Framework.php';
require_once('datamodel/SmallCard.php');

class SmallCardTest extends PHPUnit_Framework_TestCase
{
	public static function smallCardDataProvider()
	{
                return array(
                        array(
				array(Card::BLUE => 130, Card::RED => 40, Card::YELLOW => 100, Card::GREEN => 50),
				new SmallCard(+30, Card::BLUE),
				array(Card::BLUE => 160, Card::YELLOW => 40)
			),
                        array(
				array(Card::BLUE => 130, Card::RED => 40, Card::YELLOW => 100, Card::GREEN => 50),
				new SmallCard(+30, Card::BLUE),
				array(Card::BLUE => 160, Card::YELLOW => 50),
				'InvalidPriceChangeException'				
			)
                );
	}

        /**
         * @dataProvider smallCardDataProvider 
         */
	public function testPriceChangesValidation($original_prices, $card, $price_changes, $expectedException = null)
	{
		if (!is_null($expectedException))
		{
			$this->setExpectedException($expectedException);
		}

		$this->assertTrue($card->validatePriceChanges($original_prices, $price_changes));
	}

	public function testSmallDeckMustContain32Cards()
	{
		$deck = SmallCard::getDeck();
		$this->assertEquals(32, count($deck->deal(32)));

		try
		{
			$deck->deal(1);
		}
		catch (Exception $a)
		{
			return true;
		}

		$this->fail('Small deck should fail dealing more then 32 cards');
	}
}
