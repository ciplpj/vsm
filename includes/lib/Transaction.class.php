<?php

class Transaction{
	private static $table = "transaction";
	/*
	*private $fields = array('id','sc_id','sh_id','s_qty','price','date','buy_sell','ini_price');
	*/
	public $sh_id;
	public $sc_id;
	public $qty;
	public $price;
	public $b_s;
	public $s_m_p;
	public $date;
	public $set=false;

	
	public function __construct($sh_id,$sc_id,$qty,$price,$b_s,$stock_ini_price){
		$this->sh_id = $sh_id;
		$this->sc_id = $sc_id;
		$this->qty = $qty;
		$this->price = $price;
		$this->b_s = $b_s;
		$this->s_m_p = $stock_ini_price;
		$this->date = Database::date();
		$this->set = true;
	}

	public function save(){
		$database = new Database();
		if($this->set){
			$query = "INSERT INTO ".self::$table." (sh_id,sc_id,s_qty,price,date,buy_sell,ini_price)";
			$query .=" VALUES(".$this->sh_id.", ".$this->sc_id.", ".$this->qty.", ".$this->price.",'".$this->date."',".$this->b_s.",".$this->s_m_p.")";
			$run = $database->query($query);
			if($run){
				unset($database);
				return true;
			}else{
				//TODO:add log entry
				unset($database);
				return false;
			}
		}
	}

	public static function last($sh_id,$sc_id){
		global $database;
		if(!is_null($sh_id) && !is_null($sc_id)){
			$query = "SELECT date FROM ".self::$table;
			$query .=" 	WHERE sc_id = ".$sc_id." AND sh_id = ".$sh_id;
			$query .=" ORDER BY id DESC";
			$run = $database->query($query);
			if($date_fetch = $database->fetch_array($run)){
				$date_fetch = $date_fetch['date'];
				$date_fetch = strftime("%H:%M:%S %d/%m/%Y",strtotime($date_fetch));
				return $date_fetch;
			}else{
				return "No Last Transaction Found";
			}
		}
	}
}