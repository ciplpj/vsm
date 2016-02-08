<?php
class StockHolder{
	
	public static $initial_amount;
	private static $table = "stock_holder";
	/*
	*private $fields = array('id','u_id','cur_amt','s_companies','n_status','redeemed_no','leader_pos');
	*/

	public $sh_id;
	public $u_id;
	public $cur_amt;
	public $s_companies;

	public $found =false;
	/*
	*updates the n_status only when the user logs in 
	* IN Future ->
	* Do it with automation script and with initialisation and exit of the automation script
	*/
	public $n_status;
	/*
		place these fields in a get function instead?
	*/
	public $redeemed;
	public $leader_pos;
	

	public function __construct($sh_id){
		global $database;
		$query = "SELECT * FROM ".self::$table;
		$query .=" WHERE id = ".$sh_id;
		$query .=" LIMIT 1 ;";
		$result = $database->query($query);
		if($result){
			$data = $database->fetch_array($result);
			if(!empty($data)){
				$this->sh_id = $data['id'];
				$this->u_id = $data['u_id'];
				$this->cur_amt = $data['cur_amt'];
				$this->s_companies = $data['s_companies'];
				$this->redeemed = $data['redeemed_no'];
				$this->leader_pos = $data['leader_pos'];
				$this->found = true;
				$this->n_status =$this->nStatus();
			}else{
				$this->found = false;
			}
		}else{
			$this->found = false;
		}
	}

	public static function add_new_holder($user){
		$database = new Database();
		$user_id = $user['id'];
		$cur_amt = self::$initial_amount;
		$leader_position = StockHolder::count();
		$query = "INSERT INTO ".self::$table." (u_id,cur_amt,leader_pos) ";
		$query .=" VALUES ($user_id,$cur_amt,$leader_position);";
		$result = $database->query($query);
		
		if($result){
			
			$query2 = "SELECT id FROM ".self::$table." WHERE u_id = ".$user_id." LIMIT 1 ;";
			$shid = $database->query($query2);
			if($shid){
				$shid = $database->fetch_array($shid);
				$shid = $shid['id'];
			}
			unset($database);
			return $shid;
		}else{
			unset($database);
			return false;
		}
	}

	public static function count(){
		$query = "SELECT count(*) FROM ".self::$table;
		$database = new Database();
		$num = $database->query($query);
		if(!$num){
			Log::add('Unable to access StockHolder Table for StockHolder::count',LOG_DB);
		}
		$num = $database->fetch_array($num);
		$num = array_shift($num);
		unset($database);
		return $num;
	}

	public static function shid_by_uid($uid){
		global $database;
		$uid = $database->escape_value($uid);
		$query = "SELECT id FROM ".self::$table;
		$query .=" WHERE u_id = '".$uid."'";
		$query .=" LIMIT 1 ;";
		$result = $database->query($query);
		if($result){
			$id = $database->fetch_array($result);
			if(!empty($id)){
				$id = array_shift($id);
				return $id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	// Class should be instantiated first with an object 
	//Updates the leader board status
	public static function updateLeaderBoard(){
		$records = array();
		$db_conn = new Database();
		$database = new Database();
		$total_ranks = null;
		//$query_num = "SELECT count(DISTINCT cur_amt) FROM ".self::$table;
		$query = "SELECT id,cur_amt,s_companies FROM ".self::$table;
		
		//$rank_num = $db_conn->query($query_num);
		$data = $db_conn->query($query);

		if($data
		// && $rank_num
		   ){
			//$total_ranks = array_shift($database->fetch_array($rank_num));
			while($holder = $database->fetch_array($data)){
				$total_amount = 0;
				if($holder['s_companies']>0){

					$holder_stk = new HolderStocks($holder['id']);
					if($holder_stk->isFound()){
						foreach($holder_stk->holder_stocks as $hs){
							$total_amount += $hs['s_qty']*$hs['cur_price'];
						}	
					}
					$total_amount += $holder['cur_amt'];
					$records[] = array('sh_id'=>$holder['id'],'total_amount' => $total_amount);
				}
			}
			//Sorting Algorithm
			$rank_record = array();
			$column = array_column($records,'total_amount');
		    arsort($column);
			foreach($column as $key=> $value){
			$rank_record[] = $records[$key];
			}
			//after sorting everything out

			//Storing and updating the leaderboard in db
			$rank = 1;
			foreach($rank_record as $record){
				$query_update = "UPDATE ".self::$table;
				$query_update .= " SET leader_pos =".$rank++;
				$query_update .= " WHERE id=".$record['sh_id'];
				$result = $database->query($query_update);
			}
			unset($db_conn);
			unset($rank);

		}else{
			unset($db_conn);
			return false;
		}
	}

	public function amtUpdate($amt){
		global $database;
		if($this->found){
			$query = "UPDATE ".self::$table;
			$query .=" SET cur_amt = ".$amt;
			$query .=" WHERE id=".$this->sh_id." LIMIT 1";
			$run = $database->query($query);
			if($run){
				$this->cur_amt = $amt;
				return true;
			}else{
				Log::add("Amount change of user(sh) :$this->id ; current: $this->cur_amt, Changed: $amt",LOG_MANUAL);
				$this->cur_amt = $amt;
				return false;
			}
		}
	}

	public function redeemed(){
		global $database;
		if($this->found){
			$query = "UPDATE ".self::$table;
			$query .=" SET redeemed_no = ".($this->redeemed + 1);
			$query .=" WHERE id=".$this->sh_id." LIMIT 1";
			$run = $database->query($query);
			if($run){
				$this->redeemed += 1;
				return true;
			}else{
				return false;
			}
		}
	}

	public function incS_Company(){
		$database = new Database();
		if($this->found){
			$query = "UPDATE ".self::$table;
			$query .=" SET s_companies = ".($this->s_companies + 1);
			$query .=" WHERE id=".$this->sh_id." LIMIT 1";
			$run = $database->query($query);
			if($run){
				$this->s_companies += 1;
				unset($database);
				return true;
			}else{
				unset($database);
				return false;
			}

		}
	}

	public function decS_Company(){
		$database = new Database();
		if($this->found){
			$query = "UPDATE ".self::$table;
			$query .=" SET s_companies = ".($this->s_companies - 1);
			$query .=" WHERE id=".$this->sh_id." LIMIT 1";
			$run = $database->query($query);
			if($run){
				$this->s_companies -= 1;
				unset($database);
				return true;
			}else{
				unset($database);
				return false;
			}

		}
	}
	//Updates the n_status as well as returns the new status - user's combined stock profit or loss
	public function nStatus(){
		$database = new Database();
		$n_status = 0;
		$holder_stocks = new HolderStocks($this->sh_id);
		$cur_price =0; 
		$base_price = 0;
		$holder_stocks->holder_stocks;
		foreach ($holder_stocks->holder_stocks as $stock) {
			$cur_price = $cur_price + $stock['s_qty']*Stock::getCurPrice($stock['sc_id']) ;
			$base_price += $stock['s_qty']*$stock['pur_price'];
		}
		if($cur_price > $base_price){
			$n_status = (float) ((float)$cur_price)/(float)$base_price ;
			$n_status =$n_status -1.0;
		}elseif($cur_price < $base_price){
			$n_status = (float) ((float)$cur_price)/(float)$base_price ;
			$n_status = 1.0 - $n_status;
		}else{
			$n_status =0.0;
		}
		$n_status *= 100.0;
		if(isset($this->sh_id)){
		$query = "UPDATE "	.self::$table;
		$query .=" SET n_status = ".$n_status;
		$query .=" WHERE id=".$this->sh_id;
		$run = $database->query($query);
		if($run){
			$this->n_status = $n_status;
			return $n_status;
		}else{
			return $n_status;
		}
		}	
	}

	public static function getLeaderBoard($rank=0){
		$database = new Database();
		$rank_list = array();
		if(!empty($rank)){
			$offset = $rank;
		}else{
			$offset = 0;
		}
		$query = "SELECT id,u_id,cur_amt,n_status,leader_pos,s_companies FROM ".self::$table;
		$query .= " ORDER BY leader_pos ASC";
		$run = $database->query($query);
		if($run){
			if($database->num_rows($run)>0){
				while($data = $database->fetch_array($run)){
					if($data['leader_pos'] == 0 ){
						continue;
					}
					if($offset !=0 && $offset<(int)$data['leader_pos']){
						unset($database);
						return $rank_list;
					}
					$user = new User($data['u_id']);
					$rank_list[] = array('pos' => $data['leader_pos'],
									   'name' => $user->name,
									   'asset' =>HolderStocks::getAllStockPrices($data['id']) + $data['cur_amt'], 
									   'status' => $data['n_status'],
									   'companies' =>$data['s_companies'],
								   	   'email' => $user->user_id
								   	);
				}
				unset($database);
				return $rank_list;
			}
		}
	}
}

?>