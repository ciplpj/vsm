<?php

class HolderStocks{
		private static $table = "holder_stocks";
		/*
		*private $fields = array('id','sh_id','sc_id','s_qty','pur_price','s_date');
		*/

		public $sh_id;
		/*
			Every element in the array will denote a stock the holder has
			element will have -> id,sc_id,s_qty,pur_price,cur_amt
		*/
		public $holder_stocks = array();
		private $found = false;


	public function __construct($sh_id=null){
		if(!is_null($sh_id)){
			$this->sh_id = $sh_id;
			if($this->getStocks()){
				$this->found = true;
			}
		}
	}
	//improve algo!
	public function hasStock($sc_id=null){
		$found = in_array($sc_id, array_column($this->holder_stocks,'sc_id'));
		if($found){
			return true;
		}else{
			return false;
		}
	}
	/*
		Gives a list of all the stocks the user has with their current values
    */
	public function getStocks(){
		if(isset($this->sh_id)){
			$database = new Database();
			$query = "SELECT id,sc_id,s_qty,pur_price FROM ".self::$table;
			$query .=" WHERE sh_id = ".$this->sh_id;
			$hstock_wrapper = $database->query($query);
			if($hstock_wrapper){
				while($stock = $database->fetch_array($hstock_wrapper)){
					$cur_price = Stock::getCurPrice($stock['sc_id']);
					
					$this->holder_stocks[] = array('id' => $stock['id'], 'sc_id' => $stock['sc_id'],'s_qty' => $stock['s_qty'],
											 'pur_price'=>$stock['pur_price'],'cur_price'=>$cur_price);
				}
				unset($database);
				return true;
			}else{
				unset($database);
				return false;
			}
		}else{
			unset($database);
			return false;
		}
	}

	public function isFound(){
		return $this->found;
	}

	/*
	*Return the total stocks of a company bought by all users
	*/
	public static function getBoughtStocks($sc_id = null){
		if($sc_id){
			$quantity = 0;
			$database = new Database();
			$query = "SELECT s_qty FROM ".self::$table;
			$query .=" WHERE sc_id=".$sc_id;
			$run = $database->query($query);
			if($run){
				//stocks per user
				while($stk_pu = $database->fetch_array($run)){
					$quantity += $stk_pu['s_qty'];
				}
				unset($database);
				return $quantity;
			}else{
				unset($database);
				return false;
			}
		}
	}

	public function addStock($sc_id,$qty,$stock_cur_price){
		//Checks if some quantity of stock is already present or not
		$search = in_array($sc_id, array_column($this->holder_stocks,'sc_id'));
		$search_index = array_search($sc_id, array_column($this->holder_stocks,'sc_id'));
		$database = new Database();
			if($search){
				//check the searching algorithm
				$id = $this->holder_stocks[$search_index]['id'];
				$query = "SELECT s_qty,pur_price FROM ".self::$table;
				$query .= " WHERE id = ".$id." LIMIT 1";
				$run = $database->query($query);
				if($run){

				$details = $database->fetch_array($run);	
				$new_qty = $details['s_qty'];
				$new_qty += $qty;
				$old_price = $details['pur_price'];
				$new_price = (int)(($old_price + $stock_cur_price)/2);

				$query = "UPDATE ".self::$table;
				$query .=" SET s_qty=".$new_qty.", pur_price=".$new_price;
				$query .=" WHERE id =".$id;
				$run = $database->query($query);
				if($run){
					$trs = new Transaction($this->sh_id,$sc_id,$qty,$stock_cur_price,1,$stock_cur_price);
					$trs->save();
					$this->getStocks();
					return true;

				}else{
					Log::add("Failed to update purchase stock st:$sc_id for sh:$this->sh_id qty:$qty pur_price:$stock_cur_price",LOG_MANUAL);
					return false;
				}
				}else{
					Log::add("Failed to update purchase stock st:$sc_id for sh:$this->sh_id qty:$qty pur_price:$stock_cur_price",LOG_MANUAL);
					return false;	
				}
			}else{
				$query = "INSERT INTO ".self::$table." (sh_id,sc_id,s_qty,pur_price,s_date)";
				$query .=" VALUES ($this->sh_id,$sc_id,$qty,$stock_cur_price,'".Database::date()."')";
				$run = $database->query($query);
				if($run){
					$holder = new StockHolder($this->sh_id);
					$holder->incS_Company();
					$trs = new Transaction($this->sh_id,$sc_id,$qty,$stock_cur_price,1,$stock_cur_price);
					$trs->save();
					$this->getStocks();
					return true;
				}else{
					Log::add("Failed to update purchase stock st:$sc_id for sh:$this->sh_id qty:$qty pur_price:$stock_cur_price",LOG_MANUAL);
					return false;
				}
			}
		

		
		/*
		*Querying the databse again to search for the stockss if available or not
		$database = new Database();
		$query = "SELECT s_qty FROM ".self::$table;
		$query .= "WHERE sh_id = ".$this->sh_id." AND sc_id = "$sc_id;
		$run = $database->query($query);
		if($run && ($database->num_rows($run)>0)){
			//When user already has tht  companies stocks
		}else{
			//when no stocks of that company is present
		}*/

	}

	public function removeStock($sc_id,$qty,$stock_cur_price){

		//Checks if some quantity of stock is already present or not
		$search = in_array($sc_id, array_column($this->holder_stocks,'sc_id'));
		$search_index = array_search($sc_id, array_column($this->holder_stocks,'sc_id'));
		$database = new Database();
			if($search){
				//check the searching algorithm
				$id = $this->holder_stocks[$search_index]['id'];
				$query = "SELECT s_qty,pur_price FROM ".self::$table;
				$query .= " WHERE id = ".$id." LIMIT 1";
				$run = $database->query($query);
				if($run){

					$details = $database->fetch_array($run);	
					$new_qty = $details['s_qty'];
					$new_qty -= $qty;
					$purchase_price = $details['pur_price'];
					if($new_qty > 0){
					
						$query = "UPDATE ".self::$table;
						$query .=" SET s_qty=".$new_qty;
						$query .=" WHERE id =".$id;
					}else{
						$query =" DELETE FROM ".self::$table;
						$query .=" WHERE id =".$id." LIMIT 1";
						$holder = new StockHolder($this->sh_id);
						$holder->decS_Company();
					}
						$run = $database->query($query);
						if($run){
							$trs = new Transaction($this->sh_id,$sc_id,$qty,$stock_cur_price,-1,$purchase_price);
							$trs->save();
							$this->getStocks();
							return true;
						}else{
							Log::add("Failed to update sale stock st:$sc_id for sh:$this->sh_id qty:$qty sale_price:$stock_cur_price",LOG_MANUAL);
							return false;
						}
				}else{
					Log::add("Failed to update sale stock st:$sc_id for sh:$this->sh_id qty:$qty sale_price:$stock_cur_price",LOG_MANUAL);
					return false;	
				}
			}else{
				Log::add("A Try to sale other stocks by sh_id:$this->sh_id, stock:$sc_id, qty:$qty, price: $stock_cur_price",LOG_HACK);
				return false;
			}

	}








	public static function getAllStockPrices($sh_id=null){
		$database = new Database();
		$total_amount = 0;
		if($sh_id){
			
			$query = "SELECT sc_id,s_qty FROM ".self::$table;
			$query .=" WHERE sh_id = ".$sh_id;
			$holder_stocks_wrapper = $database->query($query);
			if($holder_stocks_wrapper){
				while($holder_stock= $database->fetch_array($holder_stocks_wrapper)){
					$total_amount = $holder_stock['s_qty']*Stock::getCurPrice($holder_stock['sc_id']);
				}
				unset($database);
				return $total_amount;
			}else{
				unset($database);
				return 0;
			}
		}else{
			unset($database);
			return false;
		}
	}

	
}