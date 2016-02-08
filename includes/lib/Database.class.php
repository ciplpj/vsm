<?php
	class Database{
	private $connection;
	public $last_query;

 function __construct(){
  	 $this->open_connection();
  	 $this->mysql_real_escape_exists = function_exists('mysql_real_escape_string');
  	 $this->magic_quotes_active = get_magic_quotes_gpc();
    }

 public function open_connection(){
		$this->connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		if(!$this->connection){
			Log::add("Database Connection Failed : ".mysql_error()." - Time: ".Database::date(),LOG_DB);
			die("DATABASE CONNECTION FAILED. KINDLY TRY AFTER SOME TIME.");
		}
	}

 public function close_connection(){
  		if(isset($this->connection)){
  			mysqli_close($this->connection);	
  			unset($this->connection);
  		 }
  }

 public function query($sql=""){
  	 $this->last_query = $sql;
  	 $result = mysqli_query($this->connection,$sql);
  	 $this->confirm_query($result);
  		return $result;
  	 }

 public function escape_value($value ="") {
  		$value = mysqli_real_escape_string($this->connection,$value);
  		return $value;
     }

 public function fetch_array($result_array){
  		return mysqli_fetch_assoc($result_array);
 	 }

 public function num_rows($result){
  		return mysqli_num_rows($result);
 	 }

 public function insert_id(){
  		return mysqli_insert_id($this->connection);
  	 }

 public function affected_rows(){
  		return mysqli_affected_rows($this->connection);
  	 }

 private function confirm_query($query=""){
  		if(!$query){
  			$output = "Database Query Failed : ".mysqli_error($this->connection)."\n";
  			$output .="\nLast Sql Query: ".$this->last_query." Time: ".Database::date();
  			Log::add($output,LOG_DB);
        die($output);
  			return false;
  		}
  	}

  public static function date(){
  	 $format = "%Y-%m-%d %H:%M:%S";
  	 return strftime($format);
     }
 
     }

  $database = new Database();

?>