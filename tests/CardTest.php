<?
require_once('datamodel/Card.php');

class CardTest extends PHPUnit_Framework_TestCase
{
	public static function cardDataProvider()
	{
		return array(
			array('b', Card::getCard(1)), // +30 Blue
			array('r', Card::getCard(2)), // +30 Red
			array('y', Card::getCard(3)), // +30 Yellow
			array('g', Card::getCard(4)), // +30 Green

			array('b', Card::getCard(33)), // +100 Blue
			array('r', Card::getCard(38)), // +100 Red
			array('y', Card::getCard(43)), // +100 Yellow
			array('g', Card::getCard(48)), // +100 Green

			array('b', Card::getCard(37)), // :2 Blue
			array('r', Card::getCard(42)), // :2 Red
			array('y', Card::getCard(47)), // :2 Yellow
			array('g', Card::getCard(52)), // :2 Green

			array('b', Card::getCard(36)), // *2 Blue
			array('r', Card::getCard(41)), // *2 Red
			array('y', Card::getCard(46)), // *2 Yellow
			array('g', Card::getCard(51)) // *2 Green
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
