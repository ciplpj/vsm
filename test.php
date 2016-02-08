<?php require_once("includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."Facebook.php"); ?>
<?php require_once("includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."autoload.php"); ?>
<?php
	session_start();
	$config = array(
						'app_id' => '1108487205858532',
						'app_secret' => '3cf72784651bc90a584236561d5a53cf',
						);
	$fb = new Facebook\Facebook($config);

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email','public_profile']; // Optional permissions
	//first args - callback link
	//seconf arg - permissions you want
	$loginUrl = $helper->getLoginUrl('https://ecelldtu.com/vsm/test1.php', $permissions);

	echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
	?>
