<!--
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
-->
<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>BookYourBeds.com :: Bookings made simple!</title>
	<meta name="description" content="Book Your Beds Admin">
	<meta name="author" content="Book Your Beds">
	<meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; user-scalable=0;">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="stylesheet" href="/assets/css/default.css">
	<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<script type="text/javascript" src="assets/js/jquery.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-transition.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-modal.js"></script>

	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-42055908-1', 'bookyourbeds.com');
	ga('send', 'pageview');
	</script>	
</head>

<body>

<header>
	<div class="masthead">
		<ul>
			<li class="pull-left"><a href="<?php echo site_url('signup'); ?>" class="btn btn-warning">SIGN UP</a></li>
			<li class="pull-right"><a href="<?php echo site_url('signin'); ?>" class="btn btn-warning">LOGIN</a></li>
			<li class="logo"><a href="<?php echo site_url(); ?>">Home</a></li>
		</ul>

	</div>

	

</header>

<section>
	<div class="container">
		<?php echo $template['body']; ?>
	</div>

</section>


<footer>
	<div class="container">
		<div class="row">
			<div class="span3 offset1">
				Need to get in touch? <a href="ma&#105;&#108;to&#58;&#37;6&#68;&#97;%&#54;9l&#64;%&#54;2o%6Fky%6Furb&#101;&#37;64%73&#46;%6&#51;om">Email us.</a>
			</div>

			<div class="span4 centre-block">
				<a href="http://bookyourbeds.com/faq.html">Frequently Asked Questions</a>
			</div>

			<div class="span3">
				Want updates? Follow <a href="https://twitter.com/BookYourBeds">@BookYourBeds</a>
			</div>
		</div>

		<div class="row">
			<div class="span3 offset1">
				&copy; <?php echo date('Y'); ?> The Bed Booker Ltd.
			</div>

			<div class="span3 offset4">
				or <a href="http://eepurl.com/Btq69" target="_blank">Subscribe to our newsletter</a>
			</div>
		</div>
	</div>

</footer>

</body>

</html>