<?php

	class Log{
		private $filepaths = array("log_other","log_db","log_user","log_debug","log_manual","log_hack") ;
		private static $action = array("Anonymous Action","Database Access","User Access","Debugging","Manual Override","Hacking Attempt");

		public function __construct(){
			foreach ($this->filepaths as &$filepath) {
				$filepath = LOG_PATH.DS.$filepath.".txt";
			}
			unset($filepath);
		}
		public static function add($message="",$log_file = 0){
			$log_temp = new Log;
			$message = "\n".$message."\n";
			if(file_exists($log_temp->filepaths[$log_file])){
				//File exists
					$handle = fopen($log_temp->filepaths[$log_file],'a');
			}else {
				//File doesnt exist
					$handle = fopen($log_temp->filepaths[$log_file],'w');
			}
			if(!is_writable($log_temp->filepaths[$log_file])){
				echo "Log Is Write Protected, Kindly Check For Server User Permissions";
				return 0 ;
			}
				$content = $log_temp->create_entry(self::$action[$log_file],$message);
				fwrite($handle,$content);
				fclose($handle);
			}
		

		private function create_entry($action, $message=""){
				$time = strftime(" %Y-%m-%d %H:%M:%S ");
				$content = $time." | ".$action." : ".$message."   IP Address : ".$_SERVER['REMOTE_ADDR']."\n";
				return $content;
		}
	}

?>