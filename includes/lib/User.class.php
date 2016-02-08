<?php  ?>
<?php

	class User{
		/*
		*signup_d, email and phone_no. can be recovered afterwards
		*/
		private static $table = "user";
		/*
		*private $fields = array('id','user_id','token','name','signup_d','phone','college');
		*/
		private $id;
		public $user_id;
		public $name;
		private $detail = array("signup_d","phone");
		private $found=false;

		public function __construct($id){

			$query = "SELECT * FROM ".self::$table." WHERE id = $id LIMIT 1 ";
			$database = new Database();
			$success = $database->query($query);
			if($success){
				$user_details = $database->fetch_array($success);
				if(!empty($user_details)){
					$this->id = $user_details['id'];
					$this->user_id = $user_details['user_id'];
					$this->name = $user_details['name'];
					$this->found = true;
					unset($database);
				}else{
					$this->found = true;
					unset($database);
				}
			}else{
				$this->found = false;
				unset($database);
			}
		}
		//Add a new user to the database
		/*TODO:
			when user added but not stock holder then give user provision to update his record of holder!!
		*/
		/*
		returns array with u_id and sh_id keys containing the ids or false.
		*/
		public static function add_new_user($user_id,$token,$name,$signup_d,$phone,$college){
			$database = new Database();
			$id_array = array();
			$user_id = $database->escape_value(trim($user_id));
			$name = $database->escape_value(trim($name));
			$signup_d = $database->escape_value(trim($signup_d));
			$phone = $database->escape_value(trim($phone));
			$college = $database->escape_value(trim($college));

			$query = "INSERT INTO ".self::$table." (user_id,token,name,signup_d,phone,college)";
			$query .=" VALUES ('$user_id','$token','$name','$signup_d',$phone,'$college');";
			$success = $database->query($query);

			$query_id = "SELECT id FROM ".self::$table." WHERE user_id='$user_id'";
			$success_query_id = $database->query($query_id);

			if($success && $success_query_id){
				$user_fetch = $database->fetch_array($success_query_id);
				if($user_fetch){
					$id_array['u_id'] = $user_fetch['id'];
					$add_success = StockHolder::add_new_holder($user_fetch);
					$id_array['sh_id'] = $add_success;
				}else{
					$id_array['uid'] = null;
					$add_success = null;
				}
								
				if($add_success){
					unset($database);
					return $id_array;
				}else{
					unset($database);
					return $id_array;
				}
			}else{
				unset($database);
				return false;
			}
		}

		public static function get_id_by_uid($u_id){
			$database = new Database();
			$u_id = $database->escape_value(trim($u_id));
			$query = "SELECT id FROM ".self::$table;
			$query .=" WHERE user_id = '$u_id'";
			$success = $database->query($query);
			if($success){
				$uid = $database->fetch_array($success);
				if(!empty($uid)){
					$uid = array_shift($uid);
					unset($database);
					return $uid;
				}else{
					unset($database);
					return false;
				}
				

			}else{
				unset($database);
				return false;
			}
		}

		public function get_detail($field = 0){
			
			$database = new Database();
			if(isset($this->id)&&$this->found){
				$this->detail["$field"];
				$query = "SELECT ".$this->detail["$field"] ." FROM ".self::$table;
				$query .=" WHERE id = ".$this->id." LIMIT 1 ;";
				$result = $database->query($query);
				if($result){
					$fetch_detail = $database->fetch_array($result);
					if($fetch_detail){
						$fetch_detail = array_shift($fetch_detail);
						unset($database);
						return $fetch_detail;
					}else{
						return false;
					 }
				}else{
					return false;
				 }
			}else{
				return false;
			 }
		}

		public function id_getter(){
			return $this->id;
		}

		public function IsFound(){
			return $this->found;
		}

		
	}
?>