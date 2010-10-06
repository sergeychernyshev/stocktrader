<?
require_once 'PHPUnit/Framework.php';
require_once('datamodel/BigCard.php');

class BigCardTest extends PHPUnit_Framework_TestCase
{
	public static function bigCardDataProvider()
	{
                return array(
                        array(
				array(Card::BLUE => 130, Card::RED => 40, Card::YELLOW => 100, Card::GREEN => 50),
				new Hundred(Card::BLUE),
				array(Card::BLUE => 230, Card::RED => 10, Card::YELLOW => 80, Card::GREEN => 40),
			),
                );
	}

        /**
         * @dataProvider bigCardDataProvider 
         */
	public function testPriceChangesValidation($original_prices, $card, $price_changes, $expectedException = null)
	{
		if (!is_null($expectedException))
		{
			$this->setExpectedException($expectedException);
		}

		$this->assertTrue($card->validatePriceChanges($original_prices, $price_changes));
	}

	public function testBigDeckMustContain20Cards()
	{
		$deck = BigCard::getDeck();
		$this->assertEquals(20, count($deck->deal(20)));

		try
		{
			$deck->deal(1);
		}
		catch(CardDeckException $e)
		{
			return true;
		}

		$this->fail('Big deck should fail dealing more then 20 cards');
	}
}
