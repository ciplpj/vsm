<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="not-ie" lang="en"> <!--<![endif]-->
<head>

	<!-- Basic meta tags -->
	<meta charset="utf-8">
	<title>LeaderBoard | Virtual Stock Market | E-Summit 2016</title>
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
	<!-- Content
	================================================== -->
	<section id="content" class="container">
		<div class="row">
			<div class="span12" style="border=black solid 2px;">
				<h1><a class="button icon-chevron-left" href="home.php">Back </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Leader Board </h1>
				<?php
				$positions = StockHolder::getLeaderBoard(0);
				?>
				<table class="stacked">
				<thead>
					<tr>
						<th>Rank</th>
						<th>Name</th>
						<th>Assets Worth <i class="icon-money"></i></th>
						<th>Current Status</th>
						<th>Stock Companies</th>
						<th>UserID</th>
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
						<td><?php echo $position['asset']; ?></td>
						<td class="td_<?php echo $color; ?>"><?php echo round($add.$position['status'],2); ?>
							<i class="icon-arrow-<?php echo $direction.' i'.$color;?> fr inc_size"></i>
						</td>
						<td><?php echo $position['companies']; ?></td>
						<td><?php echo $position['email']; ?></td>
						
					</tr>
					
					<?php endforeach;?>
					
					
				</tbody>
			</table>
			</div>
		</div>
	</section><!-- end .container -->


	<!-- end .row -->


	</section>
<span style="display:none;" id="p_name">Home</span>
	<!-- Footer
	================================================== -->
<?php include('includes/footer.inc.php'); ?>

	
	
</body>
</html>		