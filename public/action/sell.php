<?php require_once('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>
<?php
	if($session->is_logged_in()){
		$user = $_SESSION['user_id'];
		$user = new User($user);
		$sh_id = (int)$session->sh_id;
	}else{
		$session->logout();
		header('Location: ../portfolio.php');
	}
	if(isset($_GET['company']) && !empty($_GET['company'])){
		$sc_id = (int) $_GET['company'];
		if(!empty($sc_id)){
			$check = "qtyfc".$sc_id;
			if(isset($_POST[$check])){
				$buy_qty = (int)$_POST[$check];
				if(!empty($buy_qty)){
					$stock = new Stock($sc_id);
					$company = new StockCompany($sc_id);
					if($stock->isFound()){
						if($stock->sell($sh_id,$buy_qty)){
							$session->message("Transaction Successful : Sold $buy_qty stock(s) of $company->c_name");
							header('Location: ../portfolio.php');
						}else{
							$session->message("Transaction Unsuccessful for Selling of $buy_qty stock(s) of $company->c_name");
							header('Location: ../portfolio.php');
						}
					}else{
						header('Location: ../portfolio.php');
					}
				}
			}else{
				$session->message("Stock Quantity Not Selected");
				header('Location: ../portfolio.php');
			}

		}else{
			header('Location: ../portfolio.php');
		}
	}else{
		header('Location: ../portfolio.php');
	}
