<?php
require_once(dirname(__FILE__).'/global.php');
require_once(dirname(__FILE__).'/datamodel/Game.php');

$user = User::require_login();

require_once(dirname(__FILE__).'/archive/1A.php');
?>
<html>
<head>
	<title>Game - Stock game</title>
	<link rel="stylesheet" type="text/css" href="game.css"/>

</head>
<body>
<div id="gamenumber">Game <span id="gamenum"><?php echo $game->getNumber() ?></span><span id="gamesuffix"><?php echo $game->getSuffix() ?></span></div>

<div id="gameplayers">
<?php
$players = $game->getPlayers();

for ($p=0; $p < count($players); $p++)
{
?>
	<div class="player" id="player<?php echo $p+1 ?>">
	<?php echo $players[$p]->asString() ?>
	</div>
<?php
}
?>
</div>

<?php
$totalPlayers = $p;

$prices = array(
	Card::BLUE => 100,
	Card::RED => 100,
	Card::YELLOW => 100,
	Card::GREEN => 100,
);

?>
<table id="gameturns">
<?php
$turns = $game->getTurns();

for ($t=0; $t < count($turns); $t++)
{
	$turn = $turns[$t];

	?>
	<!-- START turn <?php echo $t+1 ?> -->
	<?php

	# printing moves
	for ($m=0; $m < $totalPlayers; $m++)
	{
		?>
		<tr class="move player<?php echo $m+1 ?>move" id="move<?php echo $t+1 ?>-<?php echo $m+1 ?>">
		<?php
		if ($m == 0)
		{
			?><td class="turnnum" rowspan="<?php echo $totalPlayers ?>"><?php echo $t+1?></td><?php
		}

		if (array_key_exists($m, $turn))
		{
			$move = $turn[$m];

			# printing amounts before
			?>
			<td class="blue-before"><?php echo $move->before[Card::BLUE] ?></td>
			<td class="red-before"><?php echo $move->before[Card::RED] ?></td>
			<td class="yellow-before"><?php echo $move->before[Card::YELLOW] ?></td>
			<td class="green-before"><?php echo $move->before[Card::GREEN] ?></td>

			<td class="card"><?php echo $move->card->asString() ?></td>

			<?php
			foreach (array_keys($move->price_changes) as $color)
			{
				$prices[$color] = $move->price_changes[$color];
			}
			?>

			<td class="blue-price<?php echo (array_key_exists(Card::BLUE, $move->price_changes) ? ' changed' : '') ?>"><?php echo $prices[Card::BLUE] ?></td>

			<td class="red-price<?php echo (array_key_exists(Card::RED, $move->price_changes) ? ' changed' : '') ?>"><?php echo $prices[Card::RED] ?></td>
			<td class="yellow-price<?php echo (array_key_exists(Card::YELLOW, $move->price_changes) ? ' changed' : '') ?>"><?php echo $prices[Card::YELLOW] ?></td>
			<td class="green-price<?php echo (array_key_exists(Card::GREEN, $move->price_changes) ? ' changed' : '') ?>"><?php echo $prices[Card::GREEN] ?></td>

			<?php
			# TODO: calculate updates to after amounts made by consequent moves
			?>
			<td class="blue-after"><?php echo $move->after[Card::BLUE] ?></td>
			<td class="red-after"><?php echo $move->after[Card::RED] ?></td>
			<td class="yellow-after"><?php echo $move->after[Card::YELLOW] ?></td>
			<td class="green-after"><?php echo $move->after[Card::GREEN] ?></td>

			<?php
			# TODO: calculate updates to bank balance made by consequent moves
			?>
			<td class="bank"><?php echo $move->bank ?></td>
		<?php
		}
		else
		{
		?>
			<td></td>
			<td></td>
			<td></td>
			<td></td>

			<td></td>
			
			<td></td>
			<td></td>
			<td></td>
			<td></td>

			<td></td>
			<td></td>
			<td></td>
			<td></td>

			<td></td>
		<?php
		}
		?>
		</tr>
	<?php
	}
	?>
	<!-- END turn <?php echo $t+1 ?> -->
<?php
}
?>
</table>

</body>
</html>
