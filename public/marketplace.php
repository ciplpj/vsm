<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="not-ie" lang="en"> <!--<![endif]-->
<head>

	<!-- Basic meta tags -->
	<meta charset="utf-8">
	<title>Marketplace | Virtual Stock market | E-Summit 2016</title>
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
	<!-- Content
	================================================== -->
	<section id="content" class="container">

</section><!-- end .container -->
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

<section class="container">
<h1>Marketplace</h1>  <div class="loader_market">
                                    <center>
                                        <img class="loading-image" src="img/loader.gif" alt="loading.." height="70" width="70">
                                    </center>
                                </div>
	<div class="row">
		<div class="span12">
			<div class="alert alert-danger" style="font-size:16px; padding:16px 35px 16px 14px" >
				Current cash: <span id="current_cash"><strong>Rs. <?php echo $holder->cur_amt;?></strong></span>  <span class="fr">Holdings: <span id="holdings"><strong>Rs. <?php echo $cur_amt; ?></strong></span></span> 
			</div>
			<?php 
				$ses_msg = $session->message;
				if(!empty($ses_msg)){
			?>
			<div class="alert alert-warning" style="margin-top:1em;">
				<p style="font-family:sans-serif;font-size:1em;"><strong> <?php echo $ses_msg; ?></strong> 
				</p>
			</div>
			<?php } ?>
			<table class="stacked">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Company Name</th>
						<th>Stock value <i class="icon-money"></i></th>
						<th>Trend</th>
						<th>No. of Stocks</th>
						<th>Buy Stocks</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$stocks_id = StockCompany::getStocksID();
						$s_no =1;
					?>
					
						<?php foreach($stocks_id as $sc_id):
								$stock = new Stock($sc_id);
								$company = new StockCompany($sc_id);
								if($company->day_open > $stock->cur_amt){
									$color = 'red';
									$direction = 'down';
								}else{
									$color = 'green';
									$direction = 'up';
								}
						?>
						
					<tr>
						<td><?php echo $s_no++.".";?></td>
						<td><span id="comp_<?php echo $s_no;?>"><?php echo $company->c_name;?></span></td>
						<td id="td_st_<?php echo $s_no;?>" class="td_<?php echo $color;?>">Rs. <span id="present_st_price_<?php echo $s_no;?>"><?php echo $stock->cur_amt;?></span> <i id="c_arr_<?php echo $s_no;?>" class="icon-arrow-<?php echo $direction.' i'.$color;?> fr inc_size"></i> </td>
						<td><ul class="tags blue">
							<li><a href="<?php echo "company.php?company=$company->id" ?>">Trend</a></li>
						</ul></td>
						<td>
							<form action="action/buy.php?company=<?php echo $company->id ;?>" method=post>
							<select name="qtyfc<?php echo $company->id;?>" class="stock_sel" id="select<?php echo $s_no;?>">
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
										<option>6</option>
										<option>7</option>
										<option>8</option>
										<option>9</option>
										<option>10</option>
							</select>
								
						</td>
						<td><input class="button orange" type="submit" name = "submit" value="Buy"></form></td>
					</tr>
					
					<?php endforeach;?>
					
					
				</tbody>
			</table>
		</div>
	</div>

	</section>
<span style="display:none;" id="p_name">Marketplace</span>

	<!-- Footer
	================================================== -->
<?php include('includes/footer.inc.php'); ?>

	
	
</body>
</html>		