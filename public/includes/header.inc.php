<script type="text/javascript" src="js/initial.js"></script>
<header id="header">
<?php
	if($session->is_logged_in()){
		$user = $_SESSION['user_id'];
		$user = new User($user);
	}else{
		$session->logout();
		header('Location: index.php');
	}
	$holder = new StockHolder($session->sh_id);
	$status = $holder->nStatus();
	if($status >= 0){
		$show = 1;
	}else{
		$show = 2;
	}
?>
		<!-- Navigation
		================================================== -->
		<nav class="navbar">
			<div class="navbar-inner">
				<div class="container">
					<!-- Logo -->
					<a class="brand" href="home.php">
						VSM
					</a>
					<ul class="nav" id="esum">
						<li><a href="home.php" title="Home">Home</a></li>
						<li><a href="marketplace.php" title="Marketplace">Marketplace</a></li>
						<li><a href="portfolio.php" title="Portfolio">Portfolio</a></li>
						<li><a href="redeem.php" title="Redeem your points">Redeem</a></li>
						<li><a href="updates.php" title="News and updates">News & Updates</a></li>
						<li><a href="rulebook.php" title="Rulebook">Rulebook</a></li>
						<li><a href="contact.php" title="Contact us">Contact Us</a></li>
					
						<?php if($show==2){ ?>
						<li class="alert alert-error">
							Hello <strong><?php echo $user->name;?> !</strong>
						</li>
						<?php } 
							  if($show==1){?>
						<li class="alert alert-success">
							Hello <strong><?php echo $user->name;?> !</strong>
						</li>
						<?php } ?>
					</ul>
				</div><!-- end .container -->
			</div><!-- end .navbar-inner -->
		</nav>

	</header>
