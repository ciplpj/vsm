<?php

class Stock{
	
	private static $table = "stock_market";
	/*
	*private $fields = array('id','sc_id','cur_val','date_t');
	*/

	public $sc_id;
	public $cur_amt;
	public $threshold;
	private $found = false;
	/*
		will store all the price changed with their times!
	*/
	public $priceChanges = array();

	public function __construct($sc_id=null){
		if($sc_id && StockCompany::isValid($sc_id)){
			$this->sc_id = $sc_id;
			$this->cur_amt = $this->getCurPrice($this->sc_id);
			$this->getPriceChanges();
			$this->threshold = StockCompany::getThreshold($this->sc_id) ? StockCompany::getThreshold($this->sc_id): 0;
			$this->found = true;
		}else{
			$this->sc_id = $sc_id;
		}
	}
	
	public function isFound(){
		return $this->found;
	}

	public static function getCurPrice($sc_id=null){
		if($sc_id){
			$database = new Database();
			$query = "SELECT cur_val FROM ".self::$table;
			$query .= " WHERE sc_id = ".$sc_id;
			$query .=" ORDER BY id DESC";
			$stockprice_wrapper = $database->query($query);
			if($stockprice_wrapper){
				$temp = $database->fetch_array($stockprice_wrapper);
				$price = array_shift($temp);
				unset($database);
				return $price;
			}else{
				unset($database);
			    return 0;
		    }
	    }else{
	    	unset($database);
	    	return 0;
	    }
    }

    public function updatePrice(){
    	$this->cur_amt = Stock::getCurPrice($this->sc_id);
    }

    //Also Updates the current amount of the stock
    public function getPriceChanges(){
    	if(isset($this->sc_id)){
    		$database = new Database();
    		$datbase = new Database();
    		$query = "SELECT id,cur_val,date_t FROM ".self::$table;
    		$query .=" WHERE sc_id = ".$this->sc_id;
    		$query .=" ORDER BY id DESC";
    		$amt_chg_wrp = $database->query($query);
    		if($amt_chg_wrp){
    			$flag = 0;
    			while($p_change=$datbase->fetch_array($amt_chg_wrp)){
    				
    				//Updating the current amount
    				if($flag===0){
    					$this->cur_amt = $p_change['cur_val'];
    					$flag++;
    				}
    				
    				$this->priceChanges[] = array($p_change['cur_val'],$p_change['date_t']);
    				unset($database);
    			}
    		}else{
    			$this->priceChanges[]=0;
    			unset($database);
    		}
    	}
    }

    public static function getAllStocks(){
    	$stocks_id = StockCompany::getStocksID();
    	if($stocks_id){
    		$stocks = array();
    		foreach($stocks_id as $sc_id){
    			$stock_obj = new Stock($sc_id);
    			$stocks[] = $stock_obj;
    		}
    		return $stocks;
        }else{
        	return false;
        }
	}

	public static function addToMarket($sc_id,$s_price,$date=null){
		$database = new Database();
		$date = is_null($date) ? Database::date() : $date;
		$query = "INSERT INTO ".self::$table." (sc_id,cur_val,date_t)";
		$query .=" VALUES ($sc_id,$s_price,'$date')";
		$run = $database->query($query);
		if($run){
			unset($database);
			return true;
		}else{
			unset($database);
			return false;
		}
	}	

	public function buy($sh_id,$qty=0){
		global $session;
		$database = new Database();

		$sh_id = $database->escape_value($sh_id);
		$qty = (int)$qty;
		if($this->found){
			$StockCompany = new StockCompany($this->sc_id);
			/*
			Check if the user has enough money to buy the stocks
			*/
			$holder = new StockHolder($sh_id);
			if($holder->found){
				$stock_cur_price = Stock::getCurPrice($this->sc_id);
				if($holder->cur_amt > ($stock_cur_price*$qty)){
					if($StockCompany->leftStocks()>$qty){

						$holder_stocks = new HolderStocks($sh_id);
						//this adds the stock to holder list and also creates a transaction for it
						$success = $holder_stocks->addStock($this->sc_id,$qty,$stock_cur_price);
						//Updates the amount of money holder has
						$success = $holder->amtUpdate($holder->cur_amt - ($stock_cur_price*$qty));
						return true;
					
					}else{
						$session->message("Stocks of {$StockCompany->c_name} are unavailable in the marketplace");
						return false;
					}
				}else{
					$session->message('You Don\'t have enough money to buy '.$qty.' stocks of '.$StockCompany->c_name);
					return false;
				}
			}
		}else{
			//check this!
			$session->message('Stock Unavailable');
			return false;
		}
	}

	public function sell($sh_id,$qty=0){
		global $session;
		$database = new Database();
		$sh_id = $database->escape_value($sh_id);
		$qty = (int)$qty;
		if($this->isFound()){
			$StockCompany = new StockCompany($this->sc_id);
			/*
			Check if the user has the stock or not
			*/
			$holder_stocks = new HolderStocks($sh_id);
			$holder = new StockHolder ($sh_id);
			if($holder_stocks->isFound()){
				if($holder_stocks->hasStock($this->sc_id)){
					$index = array_search($this->sc_id,array_column($holder_stocks->holder_stocks,'sc_id')) ;
					$qty_having = $holder_stocks->holder_stocks[$index]['s_qty'];
					if($qty <= $qty_having){

						$stock_cur_price = Stock::getCurPrice($this->sc_id);
						//This removes stocks from holder's list
						$holder_stocks->removeStock($this->sc_id,$qty,$stock_cur_price);
						//Updates the price of the holder
						$holder->amtUpdate($holder->cur_amt+($stock_cur_price*$qty));
						return true;
					}else{
						$session->message("You Dont have $qty stocks of $StockCompany->c_name to sell, You only have $qty_having stocks");
						return false;	
					}
				}else{
					$session->message("You Dont have $StockCompany->c_name stocks to sell");
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function avgPrice(){
		if($this->found){
			$dates = array_column($this->priceChanges,1);
			$dates = array_filter($dates,function($date) {
				if(strtotime($date) <= time() && (strtotime(strftime("%Y-%m-%d",time())))<strtotime($date)){
					return true;
				}else{
					return false;
				}
			});
			$avg_price = new StockCompany($this->sc_id);
			$avg_price = $avg_price->day_open;
			$index = array_keys($dates);
			if(!empty($index)){
				$add_price = 0;
				for($i = 0;$i < count($index);$i++){
					$add_price += $this->priceChanges[$index[$i]][0];
				}
				return ($avg_price +$add_price)/count($index)+1;
			}else{
				return $avg_price;
			}
		}
	}

	public function stockUpdate(){
		$stock_control = new StockControl($this->sc_id);
		$cur_price = Stock::getCurPrice($this->sc_id);
		if($stock_control->getOverride() == -1){
			$val = mt_rand(-abs($stock_control->low),-abs($stock_control->high));
			$new_val = $cur_price + $val;
			
			/*
			* Check if over_value as been reached or not! if it has
			* then terminate the control function
			*/
			if($stock_control->over_value >= $new_val){

				$stock_control->setOverride(0);
				$stock_control->writeControl();
				Stock::addToMarket($this->sc_id,$new_val);
				return true;
			}
			if($this->threshold < $new_val){
				Stock::addToMarket($this->sc_id,$new_val);
				return true;
			}else{
				/*
				* Reached ground value so turn overide off now!
				*/
				$stock_control->setOverride(0);
				$stock_control->writeControl();
				$val = mt_rand(0,abs(2*$stock_control->high));
				$new_val = $cur_price + $val;
				Stock::addToMarket($this->sc_id,$new_val);
				return true;
			}
		}elseif($stock_control->getOverride() == 1){
			$val = mt_rand(abs($stock_control->low),abs($stock_control->high));
			$new_val = $cur_price + $val;
			/*
			* Check if over_value as been reached or not! if it has
			* then terminate the control function
			*/
			if($stock_control->over_value <= $new_val){

				$stock_control->setOverride(0);
				$stock_control->writeControl();
				Stock::addToMarket($this->sc_id,$new_val);
				return true;
			}
			Stock::addToMarket($this->sc_id,$new_val);
			return true;
		}else{
			$news = News::getNewsForStock($this->sc_id);
			if(is_array($news) && !empty($news)){
				$new = array_shift($news);
				if($new['p_change']>0){

					if($new['price_value'] > $cur_price ){
						$val_diff  = $new['price_value'] - $cur_price ;
						if($val_diff > 400){
							$reduce = (int) $val_diff/(mt_rand(40,80));
							$new_val = $cur_price + $reduce ;
							Stock::addToMarket($this->sc_id,$new_val);
							return true;
						}elseif($val_diff>200){
							$reduce = (int) $val_diff/(mt_rand(20,40));
							$new_val = $cur_price + $reduce ;
							Stock::addToMarket($this->sc_id,$new_val);
							return true;

						}elseif($val_diff>100){
							$reduce = (int) $val_diff/(mt_rand(10,20));
							$new_val = $cur_price + $reduce ;
							Stock::addToMarket($this->sc_id,$new_val);
							return true;
						}else{
							$reduce = mt_rand(0,3);
							$new_val = $cur_price + $reduce ;
							Stock::addToMarket($this->sc_id,$new_val);
							return true;
						}
					}else{
						News::newsDone($new['id']);
					}

				}else{//Negative price changee in news
					  if($new['price_value'] < $cur_price ){
						$val_diff  = $cur_price - $new['price_value'];
						if($val_diff > 400){
							$reduce = (int) $val_diff/(mt_rand(40,80));
							$new_val = $cur_price - $reduce ;
							Stock::addToMarket($this->sc_id,$new_val);
							return true;
						}elseif($val_diff>200){
							$reduce = (int) $val_diff/(mt_rand(20,40));
							$new_val = $cur_price - $reduce ;
							Stock::addToMarket($this->sc_id,$new_val);
							return true;

						}elseif($val_diff>100){
							$reduce = (int) $val_diff/(mt_rand(10,20));
							$new_val = $cur_price - $reduce ;
							Stock::addToMarket($this->sc_id,$new_val);
							return true;
						}else{
							$reduce = mt_rand(0,3);
							$new_val = $cur_price - $reduce ;
							/*
							* If news tries to reduce the price lower than the thereshold price the news is completed automatically
							*/
							if($this->threshold < $new_val){
								Stock::addToMarket($this->sc_id,$new_val);
								return true;
							}else{
								$new_val = 2*$this->threshold - $new_val;
								News::newsDone($new['id']);
								Stock::addToMarket($this->sc_id,$new_val);
								return true;
							}
							return true;
						}
					}else{
						News::newsDone($new['id']);
					}
				}
			}//endif (when news was there)
				/*
				* When no news is present and override is disabled
				* Two Options - 1)Do as per trading of stock 2) rand value
				*/
			$stock_company = new StockCompany($this->sc_id);
			$cur_bought_stocks = HolderStocks::getBoughtStocks($this->sc_id);
			$prev_bought_stocks = $stock_company->bought_stocks;
			//Update the stock buying for next iteration!!
			$stock_company->writeBoughtStocks();
			//if 1) gives nothing!
				if($cur_bought_stocks == $prev_bought_stocks){
					$new_val = array(mt_rand(-1,1),mt_rand(-3,3),mt_rand(-2,2),mt_rand(-5,5),mt_rand(-4,4),mt_rand(-5,5),mt_rand(-2,3),mt_rand(-3,2),mt_rand(-2,2)
									,mt_rand(-6,6));
					$index = mt_rand(0,9);
					$temp = $new_val[$index];
					$new_val = $cur_price + $new_val[$index];

					if($new_val > $this->threshold){
						Stock::addToMarket($this->sc_id,$new_val);
						return true;
					}else{
						$new_val = $cur_price + abs($temp);
						Stock::addToMarket($this->sc_id,$new_val);
						return true;
					}
				}

			//if choice between 1 and 2 then
				$choice = mt_rand(1,100);
				if($choice <50){
					$new_val = array(mt_rand(-1,1),mt_rand(-3,3),mt_rand(-2,2),mt_rand(-5,5),mt_rand(-4,4),mt_rand(-5,5),mt_rand(-2,3),mt_rand(-3,2),mt_rand(-2,2)
									,mt_rand(-6,6));
					$index = mt_rand(0,9);
					$temp = $new_val[$index];
					$new_val = $cur_price + $new_val[$index];
					if($new_val > $this->threshold){
						Stock::addToMarket($this->sc_id,$new_val);
						return true;
					}else{
						$new_val = $cur_price + abs($temp);
						Stock::addToMarket($this->sc_id,$new_val);
						return true;
					}
				}else{
					if($cur_bought_stocks > $prev_bought_stocks){
						$new_val = $cur_price + mt_rand(1,10);
						if($new_val > $this->threshold){
						Stock::addToMarket($this->sc_id,$new_val);
						return true;
						}
					}else{
						$new_val = $cur_price + mt_rand(-1,-10);
						if($new_val > $this->threshold){
						Stock::addToMarket($this->sc_id,$new_val);
						return true;
						}else{
							$new_val = $cur_price + mt_rand(1,10);
							Stock::addToMarket($this->sc_id,$new_val);
							return true;
						}
					}

				}	
		} //if there was no override settings
	}
}