<?php
require_once(dirname(__FILE__).'/global.php');
require_once(dirname(__FILE__).'/datamodel/Game.php');

$user = User::require_login();

$game = Game::getGameByID($_GET['id']);
Turn::getTurns(array($game));

$players = $game->getPlayers();

$has_access = false;
foreach ($players as $player) {
	$player_user = $player->getUser();

	if ($player_user->isTheSameAs($user)) {
		$has_access = true;
		break;
	}
}

if (!$has_access) {
	header('HTTP/1.0 403 Access denied');
	exit;
}

$TITLE = 'Game '.$game->getNumber().$game->getSuffix();

$STYLES[] = 'game.css';

require_once(dirname(__FILE__).'/header.php');
?>
<div id="gamenumber">Game <span id="gamenum"><?php echo $game->getNumber() ?></span><span id="gamesuffix"><?php echo $game->getSuffix() ?></span></div>

<div id="gameplayers">
<?php

for ($p=0; $p < count($players); $p++)
{
?>
	<div class="player player<?php echo $p+1 ?>">
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
<table id="gamerounds">
<?php
$rounds = $game->getRounds();

for ($t=0; $t < count($rounds); $t++)
{
	$round = $rounds[$t];

	?>
	<!-- START round <?php echo $t+1 ?> -->
	<?php

	# printing turns 
	for ($m=0; $m < $totalPlayers; $m++)
	{
		?>
		<tr class="turn player<?php echo $m+1 ?>turn" id="turn<?php echo $t+1 ?>-<?php echo $m+1 ?>">
		<?php
		if ($m == 0)
		{
			?><td class="roundnum" rowspan="<?php echo $totalPlayers ?>"><?php echo $t+1?></td><?php
		}

		if (array_key_exists($m, $round))
		{
			$turn = $round[$m];

			# printing amounts before
			?>
			<td class="turnplayer player<?php echo $m+1 ?>"><?php echo $players[$m]->asString() ?></td>

			<td class="blue-before"><?php echo $turn->before[Card::BLUE] ?></td>
			<td class="red-before"><?php echo $turn->before[Card::RED] ?></td>
			<td class="yellow-before"><?php echo $turn->before[Card::YELLOW] ?></td>
			<td class="green-before"><?php echo $turn->before[Card::GREEN] ?></td>

			<td class="card"><?php echo $turn->card->asString() ?></td>

			<?php
			foreach (array_keys($turn->price_changes) as $color)
			{
				$prices[$color] = $turn->price_changes[$color];
			}
			?>

			<td class="blue-price<?php echo (array_key_exists(Card::BLUE, $turn->price_changes) ? ' changed' : '') ?>"><?php echo $prices[Card::BLUE] ?></td>

			<td class="red-price<?php echo (array_key_exists(Card::RED, $turn->price_changes) ? ' changed' : '') ?>"><?php echo $prices[Card::RED] ?></td>
			<td class="yellow-price<?php echo (array_key_exists(Card::YELLOW, $turn->price_changes) ? ' changed' : '') ?>"><?php echo $prices[Card::YELLOW] ?></td>
			<td class="green-price<?php echo (array_key_exists(Card::GREEN, $turn->price_changes) ? ' changed' : '') ?>"><?php echo $prices[Card::GREEN] ?></td>

			<?php
			# TODO: calculate updates to after amounts made by consequent turns 
			?>
			<td class="blue-after"><?php echo $turn->after[Card::BLUE] ?></td>
			<td class="red-after"><?php echo $turn->after[Card::RED] ?></td>
			<td class="yellow-after"><?php echo $turn->after[Card::YELLOW] ?></td>
			<td class="green-after"><?php echo $turn->after[Card::GREEN] ?></td>

			<?php
			# TODO: calculate updates to bank balance made by consequent turns
			?>
			<td class="bank"><?php echo $turn->bank ?></td>
		<?php
		}
		else
		{
		?>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>

			<td>&nbsp;</td>
			
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>

			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>

			<td>&nbsp;</td>
		<?php
		}
		?>
		</tr>
	<?php
	}
	?>
	<!-- END round`<?php echo $t+1 ?> -->
<?php
}
?>
</table>

<?php
require_once(dirname(__FILE__).'/footer.php');
