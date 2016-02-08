<?php require('..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php'); ?>
<?php

	$company_name = "Company A";

	$company_info = "This is a new brand made public recently";

	$initial_stock_price = 2000;

	$threshold_stock_value = 20;

	$total_stocks = 100000000;

	StockCompany::add_new($company_name,$company_info,$initial_stock_price,$threshold_stock_value,$total_stocks);

?>