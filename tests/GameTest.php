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
				new SmallCard(+30, Card::BLUE),
				new MultiplyBy2(Card::GREEN)
			),
			array(
				new SmallCard(-30, Card::RED),
				new DivideBy2(Card::GREEN)
			)
		); 


		$d2 = array(
			array(
				new SmallCard(+30, Card::BLUE),
				new SmallCard(+60, Card::RED),
				new SmallCard(+40, Card::YELLOW),
				new SmallCard(+50, Card::GREEN),
				new SmallCard(+60, Card::BLUE),
				new SmallCard(-30, Card::GREEN),

				new Hundred(Card::BLUE),
				new Hundred(Card::BLUE),
				new MultiplyBy2(Card::BLUE),
				new DivideBy2(Card::RED)
			),
			array(
				new SmallCard(-30, Card::RED),
				new SmallCard(-60, Card::RED),
				new SmallCard(-40, Card::YELLOW),
				new SmallCard(-50, Card::GREEN),
				new SmallCard(-60, Card::BLUE),
				new SmallCard(+30, Card::GREEN),

				new Hundred(Card::BLUE),
				new Hundred(Card::RED),
				new MultiplyBy2(Card::GREEN),
				new DivideBy2(Card::BLUE)
			)
		); 

		$d3 = array($d2[0], $d2[1],
			array(
				new SmallCard(-30, Card::BLUE),
				new SmallCard(-60, Card::BLUE),
				new SmallCard(-40, Card::RED),
				new SmallCard(-50, Card::YELLOW),
				new SmallCard(-60, Card::GREEN),
				new SmallCard(+30, Card::RED),

				new Hundred(Card::GREEN),
				new Hundred(Card::RED),
				new MultiplyBy2(Card::YELLOW),
				new DivideBy2(Card::YELLOW)
			)
		);

                $games = array(
			# 0 
                        array(
				new Game(1, 'A', array($p1, $p2), 4, 6, true, $d2),
				new Turn(
					$p1,
					array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
					new SmallCard(+30, Card::BLUE),
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
					new SmallCard(+30, Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
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
					new SmallCard(-30, Card::RED),
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
					new SmallCard(+30, Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
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
					new SmallCard(+60, Card::RED),
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
							new SmallCard(+30, Card::BLUE),
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
					new DivideBy2(Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
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
					new DivideBy2(Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
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
					new DivideBy2(Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
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
					new DivideBy2(Card::BLUE),
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
							new DivideBy2(Card::RED),
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
					new MultiplyBy2(Card::GREEN),
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
							new DivideBy2(Card::RED),
							array(Card::GREEN => 200, Card::RED => 50),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							50,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new MultiplyBy2(Card::GREEN),
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
					new DivideBy2(Card::YELLOW),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
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
					new MultiplyBy2(Card::GREEN),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
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
					new MultiplyBy2(Card::GREEN),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
							array(Card::RED => 10, Card::YELLOW => 160),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							30,
							null
						),
						new Turn(
							$p1,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new MultiplyBy2(Card::GREEN),
							array(Card::GREEN => 200, Card::YELLOW => 80),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							140,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new DivideBy2(Card::GREEN),
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
					new MultiplyBy2(Card::GREEN),
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
					new SmallCard(+30, Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
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
					new SmallCard(+60, Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
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
					new SmallCard(+60, Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
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
					new SmallCard(+60, Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
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
					new SmallCard(+60, Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
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
					new SmallCard(+60, Card::BLUE),
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
							new SmallCard(+30, Card::BLUE),
							array(Card::BLUE => 130, Card::RED => 40),
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							60,
							null
						),
						new Turn(
							$p2,
							array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
							new SmallCard(-30, Card::RED),
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
					new SmallCard(+60, Card::BLUE),
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
