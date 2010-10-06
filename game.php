<? require_once('datamodel/Game.php');

require_once('archive/1A.php');
?>
<html>
<head>
	<title>Game - Stock game</title>
	<link rel="stylesheet" type="text/css" href="game.css"/>

</head>
<body>
<?
echo $game->asFullTableHTML();
?>
</body>
</html>
