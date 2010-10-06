<? require_once('datamodel/Game.php');

$p1 = new Player('Sergey Chernyshev', 'sergeychernyshev', 1);
$p2 = new Player('', 'dr_gonzo', 2);

$game = new Game(1, 'A', array($p1, $p2), 1, 1, true,
	array(
		array(
			new SmallCard(+30, Card::BLUE),
			new MultiplyBy2(Card::GREEN)
		),
		array(
			new SmallCard(-30, Card::RED),
			new DivideBy2(Card::GREEN)
		)
	),
	array(
		new Move(
			$p1,
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			new SmallCard(+30, Card::BLUE),
			array(Card::BLUE => 130, Card::RED => 40),
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			60,
			null
		),
		new Move(
			$p2,
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			new SmallCard(-30, Card::RED),
			array(Card::RED => 10, Card::YELLOW => 160),
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			30,
			null
		),
		new Move(
			$p1,
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			new MultiplyBy2(Card::GREEN),
			array(Card::GREEN => 200, Card::YELLOW => 80),
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			140,
			null
		),
		new Move(
			$p2,
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			new DivideBy2(Card::GREEN),
			array(Card::GREEN => 100, Card::YELLOW => 160),
			array(Card::BLUE => 1, Card::RED => 1, Card::YELLOW => 1, Card::GREEN => 1),
			130,
			null
		)
	)
);
