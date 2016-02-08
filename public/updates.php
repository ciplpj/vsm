<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="not-ie" lang="en"> <!--<![endif]-->
<head>

	<!-- Basic meta tags -->
	<meta charset="utf-8">
	<title>News & Updates | Virtual Stock Market | E-Summit 2016</title>
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

</head>
<body>

	<!-- Header
	================================================== -->
	
<?php include('includes/header.inc.php'); ?>
<?php
	if($session->is_logged_in()){
		$sh_id = $session->sh_id;
	}
?>>
	<!-- Content
	================================================== -->
	<section id="content" class="container">

</section><!-- end .container -->

<section class="container">
	<h1>News & Updates</h1>
	<section class="row">
		<article class="span12">
			<div class="accordion stacked" id="accordion" style="max-height:550px;overflow:auto;">
				<?php
					$news = new News(1);
					foreach($news->news as $news_item):
				?>
				<div class="accordion-group">
				  <div class="accordion-heading">
					<a class="accordion-toggle active" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
					  <?php echo $news_item['data']; ?>
					</a>
				  </div>
				  <div id="collapse1" class="accordion-body in" style="height: auto; ">
					<div class="accordion-inner">
					  <?php echo $news_item['data']; ?>
					</div>
				  </div>
				</div>
				<?php endforeach; ?>
		  </div>
		</article>
	</section>
	<!-- end .row -->


	</section>
<span style="display:none;" id="p_name">News & Updates</span>
	<!-- Footer
	================================================== -->
<?php include('includes/footer.inc.php'); ?>

	
	
</body>
</html>		