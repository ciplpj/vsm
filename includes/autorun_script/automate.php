<?php require('..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php'); ?>
<?php
	/*
	* Settings to be checked everytime!
	* Add more Functionality for Initialise
	*/
	ini_set('max_execution_time', '0');
	$script_r = 1;
	$initialise = 1;

	function check_setting(){
		global $database;
		global $script_r,$initialise;
		$query = "SELECT * FROM admin";
		$run=$database->query($query);
		if($run){
			$setting = $database->fetch_array($run);
			$script_r = $setting['script_r'];
			$initialise = $setting['initialise'];
		}
	}

	function reset_setting(){
		global $database;
		$settings_query = "UPDATE admin SET script_r = 1,initialise=1";
		$run = $database->query($settings_query);
	}
	/*
	* When initialising everything
	*/
	check_setting();
if($script_r ==1){
	if($initialise == 1){
	
	/*initialise stock controls
	*first check if s_history for today is created or not it not create it else edit it!
	* then start uploading news
	*then update stocks
	*check id the setting to end has been initiated
	*when ending - 
	*close all day_closings!! direct function in stockhistory
	* also update StockCompany - dayclose = cur price and dayopen = cur price as well!
	*/
		$histories = array();
		$companies = array();
		$stocks = Stock::getAllStocks();


		foreach($stocks as $stock){
			$histories[] = new StockHistory($stock->sc_id);
			$companies[] = new StockCompany($stock->sc_id);
		}
	/*
	   S_history created or updated here
	*/
	//TODO: fetch the day_opening value from company instead from stock market
		foreach($histories as $key => $history){

			$day_open = $companies[$key]->day_open;
			if(strtotime(str_replace("-", "/",$history->date)) == strtotime(strftime("$Y/%m/%d",time()))){
			//so the entry for this record already exists
				$history->update($day_open,0);
			}else{
				StockHistory::add($history->sc_id,$day_open);
			}	
		}
	/*
	*	Update the bought stockss of the company inn the current market scenario!
	*/
		foreach($companies as $company){
			$company->writeBoughtStocks();
		}
	/*
	*	Free up space which otherwise will go unused forever
	*/
		unset($histories);
		unset($companies);
		unset($history);
		unset($key);
		unset($stock);
		unset($stocks);
	}
	while(1){

		$stocks = Stock::getAllStocks();
		$news = new News(0);
		/*
		* First Start Releasing News Which We Have Stored With us
		*/
		$news->update();

		/*
		*Then Start Updating The Stocks
		*/
		foreach($stocks as $stock){
			$stock->stockUpdate();
		}
		StockHolder::updateLeaderBoard();
		
		/*
		* TODO : update stocks bought by each company now
		* right now it gets updated in stock update only when no news or override is present!
		*/
		unset($news);
		unset($stock);
		unset($stocks);
		/*
		*Check to see if the end of script execution is demanded
		*/
		usleep(100000);
		check_setting();
		if($script_r==1){
			continue;
		}else{
			break;
		}
	}//end while

	/*
	* Script end starts here where the script has to 
	* close all day_closings!! direct function in stockhistory
	* also update StockCompany - dayclose = cur price and dayopen = cur price as well!
	*/
	$histories = array();
	$companies = array();
	$controls = array();
	$stocks = Stock::getAllStocks();


	foreach($stocks as $stock){
		$histories[] = new StockHistory($stock->sc_id);
		$companies[] = new StockCompany($stock->sc_id);
		$controls[] = new StockControl($stock->sc_id);
	}

	foreach($histories as $history){
		$history->closeDay();
	}

	foreach ($companies as $company) {
		$cur_val = Stock::getCurPrice($company->id);
		$company->updateDayClose($cur_val);
		$company->updateDayOpen($cur_val);
	}

	foreach($controls as $control){
		$control->setOverride(0);
		$control->writeControl();
	}

	StockHolder::updateLeaderBoard();	

	/*
	* Make the settings reset once the script has ended 
	* for next execution of the script so that stocks can be initialised first
	*/
	reset_setting();
}

?>