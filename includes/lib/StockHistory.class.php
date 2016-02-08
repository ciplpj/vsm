<?php

class StockHistory{
		private static $table = "s_history";
		/*
		*private $fields = array('id','sc_id','day_open','day_close','status','date');
		*/

		public $id;
		public $sc_id;
		public $day_open;
		public $day_close;
		public $status;
		private $found = false;
		public $date;

	public function __construct($sc_id = null){
		if($sc_id){
			if(StockCompany::isValid($sc_id)){
				$this->sc_id = $sc_id;
				$this->getDetails();
				$this->found = true;
			}else{
				$this->sc_id = $sc_id;
			}
		}
	}

	public function isFound(){
		return $this->found;
	}

	public function getDetails(){
		$database = new Database();
		$query = "SELECT id,day_open,day_close,date,status FROM ".self::$table;
		$query .=" WHERE sc_id = ".$this->sc_id;
		$query .=" 	ORDER BY id DESC";
		$run =$database->query($query);
		if($run){
			$data = $database->fetch_array($run);
			if(!empty($data)){
				$this->day_open = $data['day_open'];
				$this->day_close = $data['day_close'];
				$this->id = $data['id'];
				$this->status = $data['status'];
				$this->date = $data['date'];
				unset($database);
				return true;
			}
		}else{
			unset($database);
			return false;
		}
	}

	public static function add($sc_id,$ini_s_price){
		$database = new Database();
		//Check what date it returns
		$date = strftime("%Y-%m-%d",time());
		$query = "INSERT INTO ".self::$table." (sc_id,day_open,day_close,status,date)";
		$query .=" VALUES ($sc_id,$ini_s_price,0,0,'$date');";
		$run = $database->query($query);
		if($run){
			unset($database);
			return true;
		}else{
			unset($database);
			return false;
		}
	}

	public function closeDay(){
		if($cur_price = Stock::getCurPrice($this->sc_id)){
			$this->day_close = $cur_price;
			$this->status = ($this->day_open > $this->day_close) ? (((float)$this->day_close)/((float)$this->day_open) -1.0 )*100.0:(((float)$this->day_close)/((float)$this->day_open) -1.0 )*100.0;
			$database = new Database();
			$query = "UPDATE ".self::$table;
			$query .=" SET day_close =".$this->day_close.", status=".$this->status;
			$query .= " WHERE id=".$this->id;
			echo $query;
			$run = $database->query($query);
			if($run){
				unset($database);
				return true;
			}else{
				Log("Couldnt execute StockHistory::closeday close manually for $this->sc_id",LOG_MANUAL);
				return false;
			}
		}else{
			Log("Couldnt execute StockHistory::closeday close manually for $this->sc_id",LOG_MANUAL);
			return false;
		}
	}

	public function getDayOpen(){
		$database = new Database();
		$query = "SELECT day_open FROM ".self::$table;
		$query .=" WHERE sc_id = ".$this->sc_id;
		$query .=" 	ORDER BY id DESC";
		$run = $database->query($query);
		if($run){
			$price = $database->fetch_array($run);
			$price = array_shift($price);
			unset($database);
			return $price;
		}else{
			unset($database);
			return false;
		}
	}

	/*
		Will give last day's closing stock price only when add() is not run before this
	*/
	public function getDayClose(){
		$database = new Database();
		$query = "SELECT day_close FROM ".self::$table;
		$query .=" WHERE sc_id = ".$this->sc_id;
		$query .=" 	ORDER BY id DESC";
		$run = $database->query($query);
		if($run){
			$price = $database->fetch_array($run);
			$price = array_shift($price);
			return $price;
		}else{
			unset($database);
			return false;
		}
	}

	public function getStatus(){
		$database = new Database();
		$query = "SELECT status FROM ".self::$table;
		$query .=" WHERE sc_id = ".$this->sc_id;
		$query .=" 	ORDER BY id DESC";
		$run = $database->query($query);
		if($run){
			$status = $database->fetch_array($run);
			$status = array_shift($status);
			unset($database);
			return $status;
		}else{
			unset($database);
			return false;
		}
	}

	public function update($day_open,$day_close){
		$database = new  Database();
		$day_open = (int)$day_open;
		$day_close = (int)$day_close;
		$query = "UPDATE ".self::$table;
		$query .=" SET day_open =".$day_open.", day_close=".$day_close;
		$query .=" WHERE id =".$this->id;
		$run = $database->query($query);
		if($run){
			$this->day_open = $day_open;
			$this->day_close - $day_close;
			return true;
		}else{
			return false;
		}
	}
}