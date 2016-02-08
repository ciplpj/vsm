<?php

class Redeem{
	private static $table_rp = "redeem_products";
	private static $table_ru = "redeemed";
	/*
	*private $field1 = array('id','product_name','product_info','price','in_stock','qty','date');
	*private $field2 = array('id','sh_id','p_id','date');
	*/

	public $id;
	public $name;
	public $info;
	public $price;
	public $in_stock;
	public $qty;
	public $found=false;

	public function __construct($id = null){
		global $database;
		if($id != null){
			$id = (int)$id;
			$query = "SELECT * FROM ".self::$table_rp;
			$query .=" WHERE  id =".$id." LIMIT 1";
			$run = $database->query($query);
			if($run){
				$data = $database->fetch_array($run);
				$this->id = $data['id'];
				$this->name = $data['product_name'];
				$this->info = $data['product_info'];
				$this->price = $data['price'];
				$this->in_stock = $data['in_stock'];
				$this->qty = $data['qty'];
				$this->found = true;
			}
		}
	}

	public static function userRedeemedProducts($sh_id = null){
		global $database;
		$red_pro = array();
		if(!is_null($sh_id)){
			$sh_id = (int)$sh_id;
			$query = "SELECT * FROM ".self::$table_ru;
			$query .=" WHERE  sh_id =".$sh_id;
			$run = $database->query($query);
			if($run){
					while($data = $database->fetch_array($run)){
						$red_pro[]= array('p_id' =>$data['p_id'] ,'date'=>$data['date']);
					}
			return $red_pro;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function redeem($sh_id){
		global $database;
		global $session;
		$holder = new StockHolder($sh_id);
		if($this->qty > 0){
			if($holder->cur_amt > $this->price){
				$date = Database::date();
				$query = "INSERT INTO ".self::$table_ru." (sh_id,p_id,date)";
				$query .=" VALUES ($sh_id,$this->id,'$date')";
				$run = $database->query($query);
				if($run){
					$holder->amtUpdate($holder->cur_amt - $this->price);
					$holder->redeemed();
					$this->reduce_qty(1);
					return true;
				}else{
					$session->message('Your Request Couldn\'t Be Processed At This Moment Please Try Again');
					return false;
				}
			}else{
				$session->message('You Dont Have Enough Virtual Money To Buy This Product');
				return false;
			}
		}
	}

	public function reduce_qty($qty = 0 ){
		global $database;
		$update_qty = $this->qty - $qty;
		$query = "UPDATE ".self::$table_rp;
		$query .=" SET qty =".$update_qty." WHERE id = ".$this->id." LIMIT 1";
		$run = $database->query($query);
		if($run){
			return true;
		}else{
			return false;
		}
	}

	public static function addProduct($name,$info,$price,$qty){
		global $database;
		$name = $database->escape_value($name);
		$info = $database->escape_value($info);
		$price = (int)$price;
		$qty = (int)$qty;
		$date = Database::date();
		$query = "INSERT INTO ".self::$table_rp." (product_name,product_info,price,qty,date,in_stock)";
		$query .= " VALUES ('".$name."','".$info."',$price,$qty,'$date',1)";
		$run = $database->query($query);
		if($run){
			return true;
		}else{
			return false;
		}
	}

	public static function getProducts(){
		global $database;
		$Products = array();
		$query = "SELECT * FROM ".self::$table_rp." WHERE in_stock =1";
		$run = $database->query($query);
		if($run){
			while($data = $database->fetch_array($run)){
				$Products[] = array('id' =>$data['id'],'name'=>$data['product_name'],'info'=>$data['product_info'],'price' => $data['price']
									,'qty'=>$data['qty']);
				return $Products;
			}
		}else{
				return false;
			}
	}
}
