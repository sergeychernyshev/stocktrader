<?php
require_once(dirname(__FILE__).'/global.php');
require_once(dirname(__FILE__).'/users/users.php');

$user = User::require_login();

// let's get a list of games for current user

require_once(dirname(__FILE__).'/header.php');
?>
<h2>Welcome, <?php echo $user->getName()?>!</h2>

<p>Your games:</p>
<?php
require_once(dirname(__FILE__).'/datamodel/Player.php');
$player = Player::getPlayer($user);
$games = $player->getGames();
Turn::getTurns($games);

foreach ($games as $game) {
	?><div>
	<a href="game.php?id=<?php echo urlencode($game->getID()) ?>">Game <?php
	$players = $game->getPlayers();
	echo $game->getNumber().$game->getSuffix()?></a>
	[round <?php
	$current_round = count($game->getRounds());

	if ($current_round == 0 ) {
		$current_round = 1;
	}

	echo $current_round;
	?>
	of
	<?php
	echo $game->getTotalRounds();
	?>]

	&mdash;

	<?php
	$first = true;
	foreach ($players as $player) {
		echo $first ? '' : ' vs. ';

		$game_user = $player->getUser();
		$is_current = $game_user->isTheSameAs($user);

		if ($is_current) {
			?><b><?php
		}

		echo $game_user->getName();

		if ($is_current) {
			?></b><?php
		} else {
			?></a><?php
		}
		$first = false;
	}
	?>

	</div>
<?php
}

require_once(dirname(__FILE__).'/footer.php');
