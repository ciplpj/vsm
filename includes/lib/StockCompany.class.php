<?php
	class StockCompany{

		private static $table = "company_stock";
		/*
		*private $fields = array('id','c_name','info','thres_stock_val','ini_s_price','total_stocks','day_open_price','prev_day_close_price'
		*						'bought_stocks');
		*/
		public $id;
		public $c_name;
		public $thres_stock_val;
		public $ini_s_price;
		public $total_stocks;
		public $bought_stocks;
		public $day_open;
		public $found = false;

		public function __construct($sc_id=null){
			$database = new Database();
			if($sc_id){
				$query = "SELECT id,c_name,thres_stock_val,ini_s_price,total_stocks,bought_stocks,day_open_price FROM ".self::$table;
				$query .=" WHERE id = ".$sc_id;
				$run = $database->query($query);
				if($run){
					$record = $database->fetch_array($run);
					if(!empty($record)){
					
						$this->id = $record['id'];
						$this->c_name= $record['c_name'];
						$this->thres_stock_val = $record['thres_stock_val'];
						$this->ini_s_price = $record['ini_s_price'];
						$this->total_stocks = $record['total_stocks'];
						$this->bought_stocks = $record['bought_stocks'];
						$this->day_open = $record['day_open_price'];
						$this->found = true;
						unset($database);
					}else{
						$this->found = false;
					}
				}
			}else{
				$this->found = false;
			}

		}
		
		public function leftStocks(){
			$b_stk = HolderStocks::getBoughtStocks($this->id) ? HolderStocks::getBoughtStocks($this->id) : 0;
				$this->bought_stocks = $b_stk;
				return ($this->total_stocks - $b_stk);
		}

		public function writeBoughtStocks(){
			$database = new Database();
			$query = "UPDATE ".self::$table;
			$this->leftStocks();
			$query .=" SET bought_stocks=".$this->bought_stocks;
			$run = $database->query($query);
			if($run){
				return true;
			}else{
				return false;
			}
		}

		public static function add_new($c_name,$info,$ini_s_price,$thres_stock_val=20,$total_stocks=1000000){
			$database = new Database();
			$c_name = $database->escape_value($c_name);
			$info = $database->escape_value($info);
			$ini_s_price = $database->escape_value($ini_s_price);
			$thres_stock_val = (int)$thres_stock_val;
			$total_stocks = (int)$total_stocks;

			$query = "INSERT INTO ".self::$table." (c_name,info,ini_s_price,thres_stock_val,total_stocks,day_open_price,prev_day_close_price,bought_stocks) ";
			$query .=" VALUES ('$c_name','$info',$ini_s_price,$thres_stock_val,$total_stocks,$ini_s_price,0,0)";
			$insert = $database->query($query);
			if($insert){
				$query = "SELECT id FROM ".self::$table;
				$query .=" WHERE c_name = '".$c_name."' LIMIT 1";
				$find = $database->query($query);
				if($find){
					$sc_id = $database->fetch_array($find);
					$sc_id = $sc_id['id'];
					if(Stock::addToMarket($sc_id,$ini_s_price)){
						if(StockHistory::add($sc_id,$ini_s_price)){
							if(StockControl::add($sc_id)){
								return true;
							}else{
								Log::add("Failed to add to StockControl - $sc_id",LOG_MANUAL);
								return false;
							}
						}else{
							Log::add("Failed to add to StockHistory,StockControl - $sc_id",LOG_MANUAL);
							return false;
						}
					}else{
						Log::add("Failed to add to StockMarket,StockHistory,StockControl - $sc_id",LOG_MANUAL);
						return false;
					}
			    }else{
			    	Log::add("Failed to find new stockCompany record (add in StockMarket,StockHistory,StockControl)-$sc_id ",LOG_MANUAL);
			    }
			}else{
				return false;
			}


		}

		public function getInfo(){
				if(isset($this->id)){
				$database = new Database();
				$query = "SELECT info FROM ".self::$table;
				$query .=" WHERE id = ".$this->id;
				$run = $database->query($query);
				if($run){
					$info = $database->fetch_array($run);
					$info = array_shift($info);
					unset($database);
					return $info;
				}else{
					unset($database);
					return false;
				}
			}
		}

		public static function isValid($sc_id=null){
			if($sc_id){
				$database = new Database();
				$query = "SELECT * FROM ".self::$table;
				$query .=" WHERE id = ".$sc_id;
				$result = $database->query($query);
				if($database->fetch_array($result)){
					return true;
				}
			}else{
				return false;
			}
		}

		public static function getStocksID(){
			
			$database = new Database();
			$stockids = array();
			$query = "SELECT id FROM ".self::$table;
			$run = $database->query($query);
			if($run){
				while($stk_id = $database->fetch_array($run)){
					$stockids[] = $stk_id['id'];
				}
				return $stockids;
			}else{
				return false;
			}
		}

		public static function getThreshold($sc_id=null){
			if($sc_id){
				$database = new Database();
				$query = "SELECT id,thres_stock_val FROM ".self::$table;
				$query .=" WHERE id = ".$sc_id." LIMIT 1";
				$run = $database->query($query);
				if($run){
					$value = $database->fetch_array($run);
					$value = $value['thres_stock_val'];
					unset($database);
					return $value;
				}else{
				unset($database);
				return false;	
				}
			}else{
				unset($database);
				return false;
			}
		}

		public function updateDayOpen($day_open){
			$database = new  Database();
			$day_open = (int)$day_open;
			$query = "UPDATE ".self::$table;
			$query .=" SET day_open_price =".$day_open;
			$query .=" WHERE id =".$this->id;
			$run = $database->query($query);
			if($run){
				unset($database);
				return true;
			}else{
				unset($database);
				return false;
			}
		}

		public function updateDayClose($day_close){
			$database = new  Database();
			$day_close = (int)$day_close;
			$query = "UPDATE ".self::$table;
			$query .=" SET prev_day_close_price =".$day_close;
			$query .=" WHERE id =".$this->id;
			$run = $database->query($query);
			if($run){
				unset($database);
				return true;
			}else{
				unset($database);
				return false;
			}
		}
	}

?>