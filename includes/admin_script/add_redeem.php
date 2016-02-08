<?php require('..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php'); ?>
<?php

	$Product_Name = "HeadPhones";

	$Product_Info= "This is a Fake product.";

	$Product_price = 2000;
	/*
	*	Enter the quantity of product available to give to thee user
	*/
	$quantity = 300;

	Redeem::addProduct($Product_Name,$Product_Info,$Product_price,$quantity);

?>