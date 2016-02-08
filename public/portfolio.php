<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="not-ie" lang="en"> <!--<![endif]-->
<head>

	<!-- Basic meta tags -->
	<meta charset="utf-8">
	<title>Portfolio | Virtual Stock market | E-Summit 2016</title>

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
		$s_no =0;
		foreach($holder_stocks->holder_stocks as $stock){
			$cur_amt += $stock['s_qty']*Stock::getCurPrice($stock['sc_id']);
		}
	}else{
		header('Location: index.php');
		exit;
	}
?>
<section class="container">
<h1>Portfolio & Statistics</h1>
	<div class="row">
		<div class="span12">
			<div class="alert alert-danger" style="font-size:16px; padding:16px 35px 16px 14px" >
				Current cash: <span id="current_cash"><strong>Rs. <?php echo $holder->cur_amt;?></strong></span>  <span class="fr">Holdings: <span id="holdings"><strong>Rs. <?php echo $cur_amt; ?></strong></span></span> 
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="span12">
				<?php 
					$ses_msg = $session->message;
					if(!empty($ses_msg)){
				?>
				<div class="alert alert-warning" style="/*margin-top:1em;*/">
					<p style="font-family:sans-serif;font-size:1em;"><strong> <?php echo $ses_msg; ?></strong> 
					</p>
				</div>
				<?php } ?>
				<table class="stacked port" style="font-size:16px;">
					<thead>
						<tr>
							<th>S.No</th>
							<th>Stock Name</th>
							<th>Average Price <i class="icon-money"></i></th>
							<th>Present Stock Price <i class="icon-money"></i></th>
							<th>Profit / Loss</th>
							<th>No. of Stocks</th>
							<th>Last Transaction</th>
							<th>Sell Stocks</th>
						</tr>
					</thead>
					<tbody>
					<?php //for($y = 0;$y< sizeof($stock_arr); $y++ ){ 
							foreach($holder_stocks->holder_stocks as $stock):
					if($stock['pur_price'] > Stock::getCurPrice($stock['sc_id'])){
						$stat = "Loss";
						$col = "green";
					}else{
						$stat = "Profit";
						$col = "blue";
					}
					$s_no++;
					$company = new StockCompany($stock['sc_id']);
					$stock_obj = new Stock($stock['sc_id']);
					
					?>
						<tr>
							<td><?php echo $s_no;?></td>
							<td><span id="comp_<?php //echo $stock_arr[$y][0];?>"><?php echo $company->c_name; ?> </span></td>
							<td><span id="avg_stock">Rs. <?php echo $stock_obj->avgPrice();?></span>  </td>
							<td>Rs. <span class="current" id="present_st_price_<?php echo $stock_obj->sc_id;?>"><?php echo $stock_obj->cur_amt;;?> </span> </td>
							<td><ul class="tags <?php  echo $col;  ?>">
								<li><a href="<?php echo "company.php?company=$company->id" ?>"><?php  echo $stat;  ?></a></li>
							</ul></td>
							<td>
								<form action= "action/sell.php?company=<?php echo $company->id ?>" method="post">
								<select  name ="qtyfc<?php echo $stock['sc_id'] ?>" class="stock_sel" id="select<?php echo $stock_obj->sc_id;?>">
										<?php for($k=0; $k<$stock['s_qty']; $k++){ ?>
										<option><?php echo ($k+1);?></option><?php } ?>
								</select>	
										<span style="display:none;" class="numb_stock" id="numb_st_<?php echo $stock['sc_id'];?>"><?php echo 'Ajax_Data';?></span>
							</td>
						<td><?php echo Transaction::last($sh_id,$stock['sc_id']);?></td>
						<td><input type="submit" value="Sell" class="button green"></form></td>
						</tr>
						<?php endforeach;?>	
					</tbody>
				</table>	   
		</div>
	</div>
	
	</section>
<span style="display:none;" id="p_name">Portfolio</span>

	<!-- Footer
	================================================== -->
<?php include('includes/footer.inc.php'); ?>

	
	
</body>
</html>		