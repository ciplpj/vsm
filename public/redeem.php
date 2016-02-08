<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="not-ie" lang="en"> <!--<![endif]-->
<head>

	<!-- Basic meta tags -->
	<meta charset="utf-8">
	<title>Redeem your points | Virtual Stock market | E-Summit 2016</title>
	<meta name="description" content="Plain is a responsible HTML template with 12-column grid based on popular Twitter Bootstrap framework. This theme is suitable as a clean and easily maintainable template for any business, portfolio or single-project site, with support for mobile devices and latest HTML5 &amp; CSS3 user interface components.">

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<link rel="icon" type="image/x-icon" href="favicon.png" />
	<!-- Styles -->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">
	<link href="css/font-awesome/font-awesome.css" rel="stylesheet">
	<!--[if IE 7]>
		<link href="css/font-awesome/font-awesome-ie7.css" rel="stylesheet">
	<![endif]-->
	
	<!-- Web Fonts  -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>

	<!-- Javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/jquery-1.7.2.min.js"><\/script>')</script>
	
	<!-- Internet Explorer condition - HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<style>
	.stock_sel{
width:70px !important;
}

	</style>
</head>
<body>

	<!-- Header
	================================================== -->
	
<?php include('includes/header.inc.php'); ?>
<?php
	if($session->is_logged_in()){
		$sh_id = $session->sh_id;
	}
?>
	<!-- Content
	================================================== -->
	<section id="content" class="container">
<?php
	if($session->is_logged_in()){
		$sh_id = $session->sh_id;
		$holder = new StockHolder($sh_id);
		$holder_stocks = new HolderStocks($sh_id);
		$cur_amt = 0;
		foreach($holder_stocks->holder_stocks as $stock){
			$cur_amt += $stock['s_qty']*Stock::getCurPrice($stock['sc_id']);
		}
	}else{
		header('Location: index.php');
		exit;
	}
?>
</section><!-- end .container -->
<section class="container">
	<div class="row">
		<div class="span12">
			<div class="alert alert-danger" style="font-size:16px; padding:16px 35px 16px 14px" >
				Current cash: <span id="current_cash"><strong>Rs. <?php echo $holder->cur_amt;?></strong></span>  <span class="fr">Holdings: <span id="holdings"><strong>Rs. <?php echo $cur_amt; ?></strong></span></span> 
			</div>
		</div>
	</div>
<?php 
	$products_redeemed = Redeem::userRedeemedProducts($sh_id);
	if(!empty($products_redeemed)){
?>
				<?php 
					$ses_msg = $session->message;
					if(!empty($ses_msg)){
				?>
				<div class="alert alert-success" style="margin-top:1em;">
					<p style="font-family:sans-serif;font-size:1em;"><strong> <?php echo $ses_msg; ?></strong> 
					</p>
				</div>
				<?php } ?>
	<h1>Products Redeemed</h1>
	<div class="row">
		<div class="span12">
			<table class="stacked">
				<thead>
					<tr>
						<th>Product Code</th>
						<th>Product Image</th>
						<th>Product Name</th>
						<th>Price of Product <i class="icon-money"></i></th>
						<th>Reedem Date</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$products = Redeem::getProducts();
						foreach($products_redeemed as $product_r):
							$product = new Redeem($product_r['p_id']);
					?>
					<tr>

						<td><?php echo $product->id; ?></td>
						<td><img src="assests/products/product<?php echo $product->id+100; ?>ecell.jpg" /></td>
						<td><?php echo $product->name; ?></td>
						<td>Rs.<?php echo $product->price; ?></td>
						<td><?php echo strftime("%H:%M:%S %d/%m/%Y",strtotime($product_r['date'])) ?></td>
					</tr>
					<?php endforeach; ?>
					
					
				</tbody>
			</table>
		</div>
	</div>
<br />
<br />
<?php }//endif any product reedemed by user ?>
<h1>Products To Redeem</h1>
	<div class="row">
		<div class="span12">
			<table class="stacked">
				<thead>
					<tr>
						<th>Product Code</th>
						<th>Product Image</th>
						<th>Product Name</th>
						<th>Product Description</th>
						<th>Price of Product <i class="icon-money"></i></th>
						<th>Left Qty</th>
						<th>Buy Product</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$products = Redeem::getProducts();
						foreach($products as $product):

					?>
					<tr>

						<td><?php echo $product['id']; ?></td>
						<td><img src="assests/products/product<?php echo $product["id"]+100; ?>ecell.jpg" /></td>
						<td><?php echo $product['name']; ?></td>
						<td><?php echo $product['info']; ?> <hr /><a href="#" class="button orange fr" style="text-align:center;">Know more</a></td>
						<td>Rs.<?php echo $product['price']; ?></td>
						<td><?php echo $product['qty'];?></td>
						<td><ul class="tags blue">
							<li><a href="action/redeem.php?product=<?php echo $product['id'] ?>" >Buy</a></li>
						</ul></td>
					</tr>
					<?php endforeach; ?>
					
					
				</tbody>
			</table>
		</div>
	</div>

	</section>
<span style="display:none;" id="p_name">Redeem</span>

	<!-- Footer
	================================================== -->
<?php include('includes/footer.inc.php'); ?>

	
	
</body>
</html>		