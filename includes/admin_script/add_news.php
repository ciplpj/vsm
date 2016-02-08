<?php require('..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php'); ?>
<?php


	$Stock_Company_Id = 5;

	$News_Info= "This is a Fake news which increases stock prices.";

	/*
	*	Give the percent change the news will have on the stock's current price
	*/
	$price_change_in_percent = 20;
	/*
	*	Enter the live date and time when you want to release the news!
	*/
	//Type hour in 00-23 format
	$hour = 3;
	
	//Type Min in 00-59 format
	$min = 34;

	//Type day in 1-31 format
	$day = 12;

	$timestamp = mktime($hour,$min,0,2,$day,2016);

	$news_go_live_date = strftime("%Y-%m-%d %H:%M:%S",$timestamp) ;


	News::add($Stock_Company_Id,$News_Info,$price_change_in_percent,$news_go_live_date);

?>