<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>


<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="not-ie" lang="en"> <!--<![endif]-->
<head>

	<!-- Basic meta tags -->
	<meta charset="utf-8">
	<title>Contact Us | Virtual Stock Market | E-Summit 2016</title>
	<meta name="description" content="Plain is a responsible HTML template with 12-column grid based on popular Twitter Bootstrap framework. This theme is suitable as a clean and easily maintainable template for any business, portfolio or single-project site, with support for mobile devices and latest HTML5 &amp; CSS3 user interface components.">

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Styles -->
	<link href="css/style.css" rel="stylesheet">
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

<!-- Shared on MafiaShare.net  --><!-- Shared on MafiaShare.net  --></head>
<body>

	<!-- Header
	================================================== -->
	<?php include('includes/header.inc.php'); ?>


	<!-- Content
	================================================== -->
	<section id="content" class="container">


	<div class="row">
		<div class="span4 offset1">
			<h3>Social networks</h3>
			<br />
			<p class="active lead">
				<a class="social-network twitter"></a> &nbsp;
				<a class="social-network facebook"></a> &nbsp;
				<a class="social-network linkedin"></a> &nbsp;
				<a class="social-network dribbble"></a> &nbsp;
				<a class="social-network pinterest"></a>
			</p>
			<br />
			<h3>Where to find us</h3>
			<p class="lead">
				Delhi Technological University <br />
				New Bawana Road,Delhi<br />
				<a>Get Directions</a>
			</p>
		</div>
		<div class="span6 pull1">

			<h3>Contact us</h3><br />
				<!-- Contact form
				================================================== -->

				<!-- form message -->
				<div id="contact-form-response" class="alert hidden"></div>

				<form id="contact-form" class="row">
					<div class="span3">
						<label>Name</label>
						<input type="text" name="name" placeholder="Your name" />
					</div>
					<div class="span3">
						<label>E-mail</label>
						<input type="email" name="email" placeholder="@" />
					</div>
					<div class="span6">
						<label>Your message</label>
						<textarea style="width:450px" name="message" rows="4" placeholder="Leave us your message"></textarea>
						<p>
							<a id="contact-form-submit" class="button yellow fr">Send &nbsp;<i class="icon-chevron-right"></i></a>
						</p>
					</div>
					<input type="hidden" name="bot" value="" />
				</form>
		</div>
	</div>
	</section>
<span style="display:none;" id="p_name">Contact</span>

	<!-- Footer
	================================================== -->

	<?php include('includes/footer.inc.php'); ?>

</body>
</html>		