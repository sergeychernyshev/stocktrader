<?php require_once(dirname(__FILE__).'/users/users.php');

if (!isset($TITLE)) {
	$TITLE = null;
}
if (!isset($SECTION)) {
	$SECTION = null;
}

if (!isset($current_user)) {
	$current_user = User::get();
}

?><!DOCTYPE HTML>
<html version="HTML+RDFa 1.1" lang="en"
	xmlns:og="http://opengraphprotocol.org/schema/"
	xmlns:fb="http://developers.facebook.com/schema/"
>
<head>
<title><?php if (isset($TITLE)) { echo htmlentities($TITLE).' | '; } ?>Stock Trader</title>
<link rel="stylesheet" type="text/css" href="stocktrader.css"/>
<?php
if (isset($STYLES)) {
	foreach ($STYLES as $_style) {
		?><link rel="stylesheet" type="text/css" href="<?php echo $_style; ?>"/><?php
	}
}

if (isset($SCRIPTS)) {
	foreach ($SCRIPTS as $_script) {
		if (is_array($_script)) {
			if (array_key_exists('condition', $_script)) {
				?><!--[<?php echo $_script['condition'] ?>]><script type="text/javascript" src="<?php echo $_script['url']; ?>"></script><![endif]-->
<?php
			}
		} else {
			?><script type="text/javascript" src="<?php echo $_script; ?>"></script>
<?php
		}
	}
}

if (isset($googleAnalyticsProfile)) {?>
<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '<?php echo $googleAnalyticsProfile ?>']);
_gaq.push(['_setAllowAnchor', true]);
_gaq.push(['_setCustomVar', 1, 'User Type', <?php if (is_null($current_user)) { ?>'Anonymous'<?php }else{ ?>'Member'<?php } ?>, 2]);
_gaq.push(['_trackPageview']);
_gaq.push(['_trackPageLoadTime']);

(function() {
var ga = document.createElement('script');
ga.src = ('https:' == document.location.protocol ?
    'https://ssl' : 'http://www') +
    '.google-analytics.com/ga.js';
ga.setAttribute('async', 'true');
document.documentElement.firstChild.appendChild(ga);
})();
</script>
<?php }?>

<?php if (isset($homePageMetaTags) && $SECTION == 'home') { echo $homePageMetaTags; } ?>

</head>
<body>
<div id="header">
<div id="navbox">
<?php require_once(dirname(__FILE__).'/users/navbox.php'); ?>
</div>
<div id="title"><a href="./">Stock Trader</a></div>
</div>
<div id="main">
