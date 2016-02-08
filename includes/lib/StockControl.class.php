<?php

class StockControl{
		private static $table = "s_control";

		//Over_Vaue is always in percentage
		/*
		*private $fields = array('id','sc_id','low_v','high_v','override','over_value');
		*/
	
		public $sc_id;
		public $low;
		public $high;
		private $override;
		public $over_value;
		private $found=false;

	public function __construct($sc_id=null){
		if($sc_id){
			$this->sc_id = $sc_id;
			$database = new Database();
			$query = "SELECT  * FROM ".self::$table;
			$query .=" WHERE $sc_id =".$sc_id." LIMIT 1";
			$run = $database->query($query);
			if($run){
				$record = $database->fetch_array($run);
				$this->low = -abs($record['low_v']);
				$this->high = abs($record['high_v']);
				$this->override = $record['override'];
				$this->over_value = $record['over_value'];
				$this->found = true;
				if(Stockcompany::getThreshold($this->sc_id) > $this->over_value){
					$this->over_value = Stockcompany::getThreshold($this->sc_id);
				}
				$this->writeControl();
			}
		}
	}

	public function setOverride($val = 0){
		if($val ==1 || $val==-1 || $val ==0){
			$this->override = $val;
			return true;
		}else{
			return false;
			Log::add("False Called",LOG_HACK);
		}
	}

	public function writeControl(){
		$database = new Database();
		$query = "UPDATE ".self::$table;
		$this->high = abs($this->high);
		$this->low = -abs($this->low);
		$query .=" SET low_v = ".$this->low." , high_v= ".$this->high." , override=".$this->override." , over_value=".$this->over_value;
		$query .=" WHERE sc_id=".$this->sc_id;
		$run = $database->query($query);
		if($run){
			unset($database);
			return true;
		}else{
			Log::add("StockControl : Add to $sc_id : $this->low,$this->high,$this->override,$this->over_value .",LOG_MANUAL);
			unset($database);
			return false;
		}
	}

	public static function add($sc_id){
		$database = new Database();
		$query = "INSERT INTO ".self::$table." (sc_id,low_v,high_v,override,over_value)";
		$query .=" VALUES ($sc_id,-10,10,0,10);";
		$run = $database->query($query);
		if($run){
			unset($database);
			return true;
		}else{
			unset($database);
			return false;
		}
	}

	public function getOverride(){
		return $this->override;
	}
}