<?php require_once("..".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."Facebook.php"); ?>
<?php require_once("..".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."Facebook".DIRECTORY_SEPARATOR."autoload.php"); ?>
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
	$loginUrl = $helper->getLoginUrl('https://ecelldtu.com/vsm/signup.php', $permissions);
	?>
<html>
	<head>
		<title>VSM : Login</title>
		<style type="text/css">
		.link{
			border: 2px solid black;
			padding: 5px;
			background-color: black;
			color: white;
			border-radius: 5px;
			margin-top:100px;
			margin-left: 40%;
			margin-right: 40%;
			text-align: center;
		}
		</style>
	</head>
	<body>
		<div class="link">
		<?php echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>'; ?>
	</div>
	</body>
</html>

