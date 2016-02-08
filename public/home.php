<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="not-ie" lang="en"> <!--<![endif]-->
<head>

	<!-- Basic meta tags -->
	<meta charset="utf-8">
	<title>Home | Virtual Stock Market | E-Summit 2016</title>
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

</head>
<body>

	<!-- Header
	================================================== -->
	
<?php include('includes/header.inc.php'); ?>
<?php 
	$sh_id = $session->sh_id;
	$holder = new StockHolder($sh_id);
	if($holder->n_status > 0){
		$color = "green";
		$direction = "up";
	}else{
		$color = "red";
		$direction = "down";
	}
?>
	<!-- Content
	================================================== -->
	<section id="content" class="container">
		<div class="row">
			<div class="span5" style="background-color:white;padding:20px;">
				<h1 style="display:inline;">User Stats</h1><a class="button large icon-chevron-right" href="action/logout.php" style="float:right;">Logout </a>
				<hr />
				<div class="alert alert-info">
					<p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;"> 
					 Current Rank : <strong><?php echo $holder->leader_pos; ?></strong></p>
					<p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;">
					 Present Cash : <strong><?php echo $holder->cur_amt; ?></strong></p>
					<p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;"> 
					 Invested Companies : <strong><?php echo $holder->s_companies; ?></strong></p>
					<p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;"> 
					 Net Status : <strong>
						<i class="icon-arrow-<?php echo $direction.' i'.$color;?> fr inc_size" style="float:none;">
							<?php echo abs(round($holder->n_status,4)); ?>
						</i></strong>	
					</p>
					<p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;"> 
						Redeemed Products: <strong><?php echo $holder->redeemed; ?></strong></p>
					
				</div>
				
			<div class="alert alert-warning" style="margin-top:4em;margin-bottom:0px;">
				<p style="font-family:sans-serif;font-size:1em;"><strong> Visit Portfolio To Manage Your Stocks</strong> 
				</p>
			</div>
			<a class="button green icon-chevron-right" href="portfolio.php" style="float:right;">Portfolio</a>
			<div class="alert alert-warning" style="clear:both;margin-bottom:0px;margin-top:2em;">
				<p style="font-family:sans-serif;font-size:1em;"><strong> Visit MarketPlace To View And Buy Stocks</strong> 
				</p>
			</div>
			<a class="button green icon-chevron-right" href="portfolio.php" style="float:right;">Marketplace
					</a>
			<div class="alert alert-warnning" style="clear:both;margin-bottom:0px;margin-top:2em;">
				<p style="font-family:sans-serif;font-size:1em;"><strong>Visit News & Updates To View News and Updates about Stocks and Virtual World</strong> 
				</p>
			</div>
			<a class="button green icon-chevron-right" href="portfolio.php" style="float:right;">News &amp; Updates
					</a>
			</div>
			<div class="span6">
				<h1>Leader Board &nbsp;&nbsp;&nbsp;<a class="button icon-chevron-right" href="leaderboard.php">View All </a></h1>
				<?php
				$positions = StockHolder::getLeaderBoard(10);
				?>
				<table class="stacked">
				<thead>
					<tr>
						<th>Rank</th>
						<th>Name</th>
						<th>Status</th>
						<th>Assets Worth <i class="icon-money"></i></th>
					</tr>
				</thead>
				<tbody>
					
						<?php foreach($positions as $position):	
							if($position['status']>0){
								$direction = "up";
								$color = "green";
								$add = "+";
							}else{
								$direction = "down";
								$color = "red";
								$add = "-";
							}
						?>
						
					<tr>
						<td><?php echo $position['pos']; ?></td>
						<td><?php echo $position['name']; ?></td>
						<td class="td_<?php echo $color; ?>"><?php echo round($add.$position['status'],2); ?>
							<i class="icon-arrow-<?php echo $direction.' i'.$color;?> fr inc_size"></i>
						</td>
						<td><?php echo $position['asset']; ?></td>
					</tr>
					
					<?php endforeach;?>
					
					
				</tbody>
			</table>
			</div>
		</div>
	</section><!-- end .container -->

<span style="display:none;" id="p_name">Home</span>
	<!-- Footer
	================================================== -->
<?php include('includes/footer.inc.php'); ?>

	
	
</body>
</html>		