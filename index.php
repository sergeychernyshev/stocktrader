<?php
require_once(dirname(__FILE__).'/global.php');
require_once(dirname(__FILE__).'/users/users.php');

$user = User::require_login();

// let's get a list of games for current user

?><h1>Stock Trader</h1>

<h2>Welcome, <?php echo $user->getName()?>!</h2>

<p>Your games:</p>
<?php
require_once(dirname(__FILE__).'/datamodel/Player.php');
$player = Player::getPlayer($user);
$games = $player->getGames();
Turn::getTurns($games);

foreach ($games as $game) {
	?><div>
	<a href="game.php?name=<?php echo urlencode($game->getNumber().$game->getSuffix()) ?>"><?php
	$players = $game->getPlayers();
	echo $game->getNumber().$game->getSuffix()?></a>

	<?
	$first = true;
	foreach ($players as $player) {
		echo $first ? '' : ' vs. ';

		$game_user = $player->getUser();
		$is_current = $game_user->isTheSameAs($user);

		if ($is_current) {
			?><b><?php
		} else {
			?><a href="player.php?id=<?php echo htmlentities($player->getID()) ?>"><?php
		}

		echo $game_user->getName();

		if ($is_current) {
			?></b><?php
		} else {
			?></a><?php
		}
		$first = false;
	}?>

	(<?php
	echo count($game->getRounds());
	?>
	of
	<?php
	echo $game->getTotalRounds();
	?>)

	</div>
<?php
}
