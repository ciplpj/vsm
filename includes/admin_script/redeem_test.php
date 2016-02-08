<?php require('..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php'); ?>
<?php
	$product = new redeem(1);
	$product->redeem(1);

	$products = Redeem::getProducts();
	 foreach ($products as $product) {
	 	print_r($product);
	 }

?>