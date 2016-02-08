<?php require('..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php'); ?>
<?php
	
	

	$Email_ID = "hello@gmail.com";

	$Token= "This is a Fake Token.";

	$User_Name = "Ankit";

	//Date the user was added
	$Signup_date = Database::date();

	$Mobile = 1234567899;

	$College = "DTU";

	User::add_new_user($Email_ID,$Token,$User_Name,$Signup_date,$Mobile,$College);

?>