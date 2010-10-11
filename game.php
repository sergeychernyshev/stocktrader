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
<?php
echo $game->asFullTableHTML();
?>
</body>
</html>
