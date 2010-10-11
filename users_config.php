<?php
require_once(dirname(__FILE__).'/global.php');
UserConfig::setDB($db);

if (isset($facebookAPIKey)) {
	require_once(dirname(__FILE__).'/users/modules/facebook/index.php');
	UserConfig::$modules[] = new FacebookAuthenticationModule($facebookAPIKey, $facebookSecret);
}

if (isset($googleFriendConnectSiteID)) {
	require_once(dirname(__FILE__).'/users/modules/google/index.php');
	UserConfig::$modules[] = new GoogleAuthenticationModule($googleFriendConnectSiteID);
}

require_once(dirname(__FILE__).'/users/modules/usernamepass/index.php');
UserConfig::$modules[] = new UsernamePasswordAuthenticationModule();

UserConfig::$SESSION_SECRET = $sessionSecret;

#UserConfig::$header = dirname(__FILE__).'/header.php';
#UserConfig::$footer = dirname(__FILE__).'/footer.php';
