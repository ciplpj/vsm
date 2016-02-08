<?php
class News{
	private static $table="news";
	/*
	*private $fields=array('id','sc_id','data','date_uploaded','p_change','live_date','status','price_value','done');
	*/

	
	public $news = array();
	public $news_status;
	public $news_found = false;
	
	public function __construct($status=0){
		global $database;
		if($status ===1){
			$status =1;
		}else{
			$status =0;
		}
		$query = "SELECT * FROM ".self::$table;
		$query .= " WHERE status=".$status;
		$run = $database->query($query);
		if($run){
			while($news = $database->fetch_array($run)){
				$this->news[] = array('id' => $news['id'],'sc_id' => $news['sc_id'],'data' => $news['data'],'p_change' => $news['p_change'],
									  'live_date' => $news['live_date']);
			}
			$this->news_found = true;
			$this->news_status = $status;
		}
	}

	public function Update(){
		global $database;
		$news_status = array();
		if($this->news_status==0){
			$news_array = array();
			foreach ($this->news as $news_item) {
				$news_status[$news_item['id']] = strtotime(str_replace("-", "/",$news_item['live_date']));
			}
			foreach ($news_status as $id =>$timestamp){
				$time = time();
				if($time<$timestamp){
					$query = "UPDATE ".self::$table;
					$query .=" SET status=1 WHERE id=".$id;
					$run = $database->query($query);
				}
			}
			$query = "SELECT * FROM ".self::$table;
			$query .= " WHERE status=0";
			$run = $database->query($query);
			if($run){
				//Make the news array blank to fill it in!
				$this->news = array();
				while($news = $database->fetch_array($run)){
					$this->news[] = array('id' => $news['id'],'sc_id' => $news['sc_id'],'data' => $news['data'],'p_change' => $news['p_change'],
										  'live_date' => $news['live_date']);
				}
			}
	    }
    } 

    public static function getNewsForStock($sc_id=null){
    	global $database;
    	if($sc_id != null){
    		$stock_news = array();
    		$query = "SELECT * FROM ".self::$table;
			$query .= " WHERE sc_id=".$sc_id." AND done=0 AND status=1 ORDER BY id ASC";
			$run = $database->query($query);
			if($run){
				while($fetch_news = $database->fetch_array($run)){
					$news_time = strtotime(str_replace("-", "/",$fetch_news['live_date']));
					$time_cur = time();
					$time_prev = $time_cur - 600;
					if($news_time > $time_prev){
						$stock_news[]= array('id' => $fetch_news['id'],'p_change'=>$fetch_news['p_change'],
											 'price_value'=>$fetch_news['price_value']);
					}
				}
				return $stock_news;
			}else{
				return false;
			}
		}
    }

    public static function newsDone($id){

    	if(!is_null($id)){
    		global $database;
    		$query = "UPDATE ".self::$table;
    		$query .=" SET done = 1 WHERE id = ".$id;
    		$run = $database->query($query);
    		if($run){
	    		return true;
    		}else{
	    		return false;
    		}
    	}
    }

	public static function add($sc_id,$data,$p_change,$live_date){
		global $database;
		$sc_id = (int)$sc_id;
		$data  = $database->escape_value($data);
		$p_change = (int) $p_change;
		$date = Database::date();
		$price_value = (float)(Stock::getCurPrice($sc_id)*(1.0+(float)((float)$p_change/(float)100.0)));
		$query = "INSERT INTO ".self::$table." (sc_id,data,date_uploaded,p_change,live_date,status,price_value,done)";
		$query .="	VALUES($sc_id,'".$data."'".",'$date',$p_change,'$live_date',0,$price_value,0);";
		$run = $database->query($query);
		if($run){
			return true;
		}else{
			//TODO:When news update fails!
			return false;
		}
	}

}