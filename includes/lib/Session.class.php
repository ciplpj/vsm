<?php require_once(LIB_PATH.DS."Log.class.php");?>
<?php
	
	class Session{
		private $logged_in = false;
		private $found = false;
		public $user_id;
		public $sh_id;
		public $message ;

		function __construct(){
			session_start();
			$this->check_login();
			$this->check_message();
		}

		function check_login(){
			if(isset($_SESSION['user_id'])){
				$this->user_id = $_SESSION['user_id'];
				$this->logged_in = true;
					if(isset($_SESSION['sh_id'])){
						$this->sh_id = $_SESSION['sh_id'];
						$this->found = true;
					}
			}else{
				unset($this->user_id);
				$this->logged_in = false;
			}
		}

		public function is_logged_in(){
			return $this->logged_in;
		}

		public function isFound(){
			return $this->found;
		}
	
	//Write Login Function
		public function login($uid,$sh_id){
			//Check if the user exists
				$this->user_id = $_SESSION['user_id']=$uid;
				$this->sh_id = $_SESSION['sh_id'] = $sh_id;
				$this->logged_in = true;
				$this->found = true;
				$message =" Logged In ";
		}

		public function logout(){
			unset($this->user_id);
			$_SESSION['user_id'] = null;
			unset($_SESSION['user_id']);
			unset($this->sh_id);
			$_SESSION['sh_id'] = null;
			unset($_SESSION['sh_id']);
			$this->logged_in = false;
			$this->found = false;
		}

		public function check_message(){
			if(isset($_SESSION['message'])){
				$this->message = $_SESSION['message'];
				unset($_SESSION['message']);
			}else{
				$this->message ="";
			}
		}

		public function message($msg=""){
			if(!empty($msg)){
				$_SESSION['message'] = $msg;
			}else{
				return $this->message;
			}
		}
	}

$session = new Session();
$message_display = $session->message();
?>