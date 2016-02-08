<?php require_once('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>
<?php
	if($session->is_logged_in()){
		$user = $_SESSION['user_id'];
		$user = new User($user);
		$sh_id = (int)$session->sh_id;
	}else{
		$session->logout();
		header('Location: ../redeem.php');
	}
	if(isset($_GET['product']) && !empty($_GET['product'])){
		$product_id = (int) $_GET['product'];
		if(!empty($product_id)){
					$product = new Redeem($product_id);
					if($product->found){
						if($product->redeem($sh_id)){
							$session->message("Product $product->name Redeemed Successfully, A request of this Product is raised with us. We will contact you shortly");
							header('Location: ../redeem.php');
						}else{
							$session->message("Product $product->name was not Redeemed. Please Check whether you have enough Money to redeem this product.");
							header('Location: ../redeem.php');
						}
					}else{
						header('Location: ../redeem.php');
					}
		}else{
			header('Location: ../redeem.php');
		}
	}else{
		header('Location: ../redeem.php');
	}
