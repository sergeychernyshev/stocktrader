<?
require_once('datamodel/Game.php');

class GameTest extends PHPUnit_Framework_TestCase
{
	public static function gameAndTurnDataProvider()
	{
		$p1 = new Player(new User(1, 'Sergey Chernyshev', 'sergeychernyshev'));
		$p2 = new Player(new User(2, 'Dmitry Roslyakov', 'dr_gonzo'));
		$p3 = new Player(new User(3, 'Julia Kononova', 'ukon'));

		$d2px2m = array(
			array(
				Card::getCard(1), // +30 Blue
				Card::getCard(51) // *2 Green
			),
			array(
				Card::getCard(18), // -30 Red
				Card::getCard(52)  // :2 Green
			)
		); 


		$d2 = array(
			array(
				Card::getCard(1),  // +30 Blue
				Card::getCard(14), // +60 Red
				Card::getCard(7),  // +40 Yellow
				Card::getCard(12), // +50 Green
				Card::getCard(13), // +60 Blue
				Card::getCard(20), // -30 Green

				Card::getCard(33), // 100 Blue
				Card::getCard(34), // 100 Blue
				Card::getCard(36), // *2 Blue
				Card::getCard(42)  // :2 Red
			),
			array(
				Card::getCard(18), // -30 Red
				Card::getCard(30), // -60 Red
				Card::getCard(23), // -40 Yellow
				Card::getCard(28), // -50 Green
				Card::getCard(29), // -60 Blue
				Card::getCard(4),  // +30 Green

				Card::getCard(33), // 100 Blue
				Card::getCard(38), // 100 Red
				Card::getCard(51), // *2 Green
				Card::getCard(37)  // :2 Blue
			)
		); 

		$d3 = array($d2[0], $d2[1],
			array(
				Card::getCard(17), // -30 Blue
				Card::getCard(29), // -60 Blue
				Card::getCard(22), // -40 Red
				Card::getCard(27), // -50 Yellow
				Card::getCard(32), // -60 Green
				Card::getCard(2),  // +30 Red

				Card::getCard(48), // 100 Green
				Card::getCard(38), // 100 Red
				Card::getCard(46), // *2 Yellow
				Card::getCard(47)  // :2 Yellow
			)
		);

                $games = array(
			# 0 
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, true, $d2),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(1), // +30 Blue
					array(Card::BLUE => 130, Card::RED => 40),
					array(Card::BLUE => 0, Card::RED => 8, Card::YELLOW => 0, Card::GREEN => 1),
					10,
					null
				)
			),
			# 1
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, true, $d2),
				new Turn(
					$p1,
					array(Card::BLUE => 2, Card::RED => 0, Card::YELLOW => 0, Card::GREEN => 2),
					Card::getCard(1), // +30 Blue
					array(Card::BLUE => 130, Card::RED => 40),
					array(Card::BLUE => 1, Card::RED => 3, Card::YELLOW => 1, Card::GREEN => 1),
					10,
					null
				)
			),
			# 2
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						)
					)
				),
				new Turn(
					$p2,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(18), // -30 Red
					array(Card::RED => 10, Card::YELLOW => 160),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					30,
					null
				)
			),
			# 3
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, true, $d2),
				new Turn(
					$p1,
					array(Card::BLUE => 2, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(1), // +30 Blue
					array(Card::BLUE => 130, Card::RED => 40),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					0,
					null
				),
				'InvalidTurnException'				
			),
			# 4
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							30,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 0, Card::RED => 36, Card::YELLOW => 0, Card::GREEN => 1),
					Card::getCard(14), // +60 Red
					array(Card::RED => 70, Card::YELLOW => 130),
					array(Card::BLUE => 0, Card::RED => 0, Card::YELLOW => 0, Card::GREEN => 26),
					20,
					null
				),
				'InvalidTurnException'
			),
			# 5 - testing rounding up
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						)
					)
				),
				new Turn(
					$p2,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(37),  // :2 Blue
					array(Card::BLUE => 70, Card::RED => 80),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					60,
					null
				)
			),
			# 6 - must fail
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						)
					)
				),
				new Turn(
					$p2,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(37),  // :2 Blue
					array(Card::BLUE => 60, Card::RED => 80),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					70,
					null
				),
				'InvalidPriceChangeException'
			),
			# 7 - testing rounding down
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, false, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						)
					)
				),
				new Turn(
					$p2,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(37),  // :2 Blue
					array(Card::BLUE => 60, Card::RED => 80),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					70,
					null
				)
			),
			# 8 - must fail
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, false, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						)
					)
				),
				new Turn(
					$p2,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(37),  // :2 Blue
					array(Card::BLUE => 70, Card::RED => 80),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					60,
					null
				),
				'InvalidPriceChangeException'
			),
			# 9 - two player >250 compensation
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, false, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(42),  // :2 Red
							array(Card::GREEN => 200, Card::RED => 50),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							50,
							null
						)
					)
				),
				new Turn(
					$p2,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(51), // *2 Green
					array(Card::GREEN => 400, Card::BLUE => 50),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					200,
					array(
						array( 'bank' => 200 ),
						null
					)
				)
			),
			# 10 - three player >250 compensation
                        array(
				new Game(1, 'A', array($p1, $p2, $p3), 4, 6, false, $d3,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(42),  // :2 Red
							array(Card::GREEN => 200, Card::RED => 50),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							50,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(51), // *2 Green
							array(Card::GREEN => 400, Card::BLUE => 50),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							200,
							array(
								array( 'bank' => 200 ),
								null,
								array( 'bank' => 150 )
							)
						)
					)
				),
				new Turn(
					$p3,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(47),  // :2 Yellow
					array(Card::GREEN => 500, Card::YELLOW => 50),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					450,
					array(
						array( 'bank' => 450 ),
						array( 'bank' => 450 ),
						null
					)
				)
			),
			# 11 - (pass) game with two rounds - last turns must not sell or buy
                        array(
				new Game(1, 'A', array($p1, $p2), 1, 1, true, $d2px2m,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							30,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(51), // *2 Green
					array(Card::GREEN => 200, Card::YELLOW => 80),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					140,
					null
				)
			),
			# 12 - (fail) game with two rounds - last turns must not sell or buy
                        array(
				new Game(1, 'A', array($p1, $p2), 1, 1, true, $d2px2m,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							30,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(51), // *2 Green
					array(Card::GREEN => 200, Card::YELLOW => 80),
					array(Card::BLUE => 1, Card::RED => 15, Card::YELLOW => 1, Card::GREEN => 1),
					0,
					null
				),
				'InvalidTurnException'
			),
			# 13 - (fail) all turns in the game are already played
                        array(
				new Game(1, 'A', array($p1, $p2), 1, 1, true, $d2px2m,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							30,
							null
						),
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(51), // *2 Green
							array(Card::GREEN => 200, Card::YELLOW => 80),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							140,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(52),  // :2 Green
							array(Card::GREEN => 100, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							130,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(51), // *2 Green
					array(Card::GREEN => 200, Card::YELLOW => 80),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					140,
					null
				),
				'InvalidTurnException'
			),
			# 14 (fail) - wrong player
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, true, $d2),
				new Turn(
					$p2,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(1), // +30 Blue
					array(Card::BLUE => 130, Card::RED => 40),
					array(Card::BLUE => 0, Card::RED => 8, Card::YELLOW => 0, Card::GREEN => 1),
					10,
					null
				),
				'InvalidTurnException'
			),
			# 15 - (pass) price goes below 10 - opponent gets fined
                        array(
				new Game(1, 'A', array($p1, $p2), 1, 1, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							30,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(13), // +60 Blue
					array(Card::BLUE => 190, Card::RED => -20),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					60,
					array(
						null,
						array(
							Card::RED => 1,
							'bank' => 0 
						)
					)
				)
			),
			# 16 - (fail) must pay off all stocks player has money for
                        array(
				new Game(1, 'A', array($p1, $p2), 1, 1, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							30,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(13), // +60 Blue
					array(Card::BLUE => 190, Card::RED => -20),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					60,
					array(
						null,
						array(
							Card::RED => 0,
							'bank' => 30
						)
					)
				),
				'InvalidTurnException'
			),
			# 17 - (fail) balances after paying fees don't match
                        array(
				new Game(1, 'A', array($p1, $p2), 1, 1, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							30,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(13), // +60 Blue
					array(Card::BLUE => 190, Card::RED => -20),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					60,
					array(
						null,
						array(
							Card::RED => 1,
							'bank' => 10
						)
					)
				),
				'InvalidTurnException'
			),
			# 18 - (fail) player didn't have that many stocks before compensation
                        array(
				new Game(1, 'A', array($p1, $p2), 1, 1, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							30,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(13), // +60 Blue
					array(Card::BLUE => 190, Card::RED => -20),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					60,
					array(
						null,
						array(
							Card::RED => 2,
							'bank' => 0
						)
					)
				),
				'InvalidTurnException'
			),
			# 19 - (fail) Not enough money in the bank to pay off the fee that many stocks. 
                        array(
				new Game(1, 'A', array($p1, $p2), 1, 1, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 17, Card::YELLOW => 0, Card::GREEN => 1),
							30,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(13), // +60 Blue
					array(Card::BLUE => 190, Card::RED => -20),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					60,
					array(
						null,
						array(
							Card::RED => 2,
							'bank' => 0
						)
					)
				),
				'InvalidTurnException'
			),
			# 20 - (fail) Bank balances after the fee don't match. 
                        array(
				new Game(1, 'A', array($p1, $p2), 1, 1, true, $d2,
					array(
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(1), // +30 Blue
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							Card::getCard(18), // -30 Red
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 17, Card::YELLOW => 0, Card::GREEN => 1),
							30,
							null
						)
					)
				),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					Card::getCard(13), // +60 Blue
					array(Card::BLUE => 190, Card::RED => -20),
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					60,
					array(
						null,
						array(
							Card::RED => 1,
							'bank' => 10
						)
					)
				),
				'InvalidTurnException'
			),
			# TODO write tests to test all the compensation combinatons for other players including thise when player gets both compensation when going above 250 and getting fined when going below 10 all in the same turn. Plus multiple stock fines (100 pushing several values below 10).
                );

		# just run one test for debugging
		#return array($games[11]); 
		return $games;
	}

        /**
         * @dataProvider gameAndTurnDataProvider
         */
	public function testTurnValidation($game, $turn, $expectedException = null)
	{
		if (!is_null($expectedException))
		{
			$this->setExpectedException($expectedException);
		}

		$this->assertTrue($game->validateTurn($turn));
	}
}
