<?php require_once("includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."Facebook.php"); ?>
<?php require_once("includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."autoload.php"); ?>
<?php
	session_start();
	$config = array(
						'app_id' => '1108487205858532',
						'app_secret' => '3cf72784651bc90a584236561d5a53cf',
						);
	$fb = new Facebook\Facebook($config);

	// $user_id = $fb->getUser();

	// if($user_id){
	// 	try{
	// 		$user_profile = $facebook->api('/me','GET');
	// 		echo "<pre>";
	// 		print_r($user_profile);
	// 		echo "</pre>";
	// 	}catch(FacebookApiException $e){
	// 		//if the user is logged out, you can have a
	// 		// user ID even though the access token is invalid
	// 		//In this case, we'll get an exception, so we'll
	// 		//just ask the user to login again

	// 		$login_url = $facebook->getLoginUrl();
	// 		echo "Please <a href=\"".$login_url."\">login.</a>" ;
	// 		error_log($e->getType());
	// 		error_log($e->getMessage());
	// 	}
	// }else{
	// 	//No user print a link to get a user login
	// 	$login_url = $facebook->getLoginUrl();
	// 	echo "No log.Please <a href=\"".$login_url."\">login.</a>";
	// }

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email','public_profile']; // Optional permissions
	//first args - callback link
	//seconf arg - permissions you want
	$loginUrl = $helper->getLoginUrl('https://ecelldtu.com/vsm/test1.php', $permissions);

	echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
	?>
