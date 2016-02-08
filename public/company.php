<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>
<?php
	if($_GET['company']){
		$company_id = (int)$_GET['company'];
		if(!empty($company_id)){
			$stock = new Stock($company_id);
			if($stock->isFound()){
				$output = "['Time', 'Stock Price'],[0,0],";
				$i = 1;
				foreach($stock->priceChanges as $price){
					$output .="[".$i++." , ".$price[0]."],";
					}
				$company = new StockCompany($company_id);
				$cur_val = Stock::getCurPrice($company->id);
				if($cur_val > $company->day_open){
					$color = "green";
						$direction = "up";
				}else{
					$color = "red";
					$direction = "down";
				}
			}else{
				header('Location: marketplace.php');
			}
		}else{
				header('Location: marketplace.php');
		}
	}else{
		header('Location: marketplace.php');
	}
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="not-ie" lang="en"> <!--<![endif]-->
<head>

	<!-- Basic meta tags -->
	<meta charset="utf-8">
	<title>Company Trend| Virtual Stock Market | E-Summit 2016</title>
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
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          <?php echo $output ;?>
        ]);

        var options = {
        	hAxis : {textPosition :'none'},
          title: 'Stock Performance',
          curveType: 'function',
         
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>

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

				<h1>Company Stats</h1>
				<hr />
				<div class="alert alert-info">
					<p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;"> 
					 Company Name : <strong><?php echo $company->c_name; ?></strong></p>
					 <p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;"> 
						Company Information: <strong><?php echo $company->getInfo(); ?></strong></p>
					<p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;"> 
					 Day's Opening : <strong><?php echo $company->day_open; ?></strong></p>
					<p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;"> 
					 Total Company's Bought Stocks : <strong>
							<?php $company->leftStocks();
								  echo $company->bought_stocks;
							 ?>
							</strong>
					</p>
					<p style="font-family:sans-serif;font-size:2em;margin-top:0.8em;">
					 Present Stock Value : <i class="icon-arrow-<?php echo $direction.' i'.$color;?> fr inc_size" style="float:none;">
					 <strong><?php echo $cur_val; ?></i></strong></p>
				</div>

				 <div id="curve_chart" style="width: 1000px; height: 500px;"></div>
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