<?
require_once 'PHPUnit/Framework.php';
require_once('datamodel/Card.php');

class CardTest extends PHPUnit_Framework_TestCase
{
	public static function cardDataProvider()
	{
                return array(
                        array('b', new SmallCard(+30, Card::BLUE)),
                        array('r', new SmallCard(+30, Card::RED)),
                        array('y', new SmallCard(+30, Card::YELLOW)),
                        array('g', new SmallCard(+30, Card::GREEN)),

                        array('b', new Hundred(Card::BLUE)),
                        array('r', new Hundred(Card::RED)),
                        array('y', new Hundred(Card::YELLOW)),
                        array('g', new Hundred(Card::GREEN)),

                        array('b', new DivideBy2(Card::BLUE)),
                        array('r', new DivideBy2(Card::RED)),
                        array('y', new DivideBy2(Card::YELLOW)),
                        array('g', new DivideBy2(Card::GREEN)),

                        array('b', new MultiplyBy2(Card::BLUE)),
                        array('r', new MultiplyBy2(Card::RED)),
                        array('y', new MultiplyBy2(Card::YELLOW)),
                        array('g', new MultiplyBy2(Card::GREEN))
                );
	}

        /**
         * @dataProvider cardDataProvider 
         */
	public function testCardColorLetters($letter, $card)
	{
		$this->assertEquals($letter, $card->getCardColorLetter());
	}
}
