<!--
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
-->
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Always force latest IE rendering engine & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo account('name'); ?> • Powered by BookYourBeds</title>
	
	<base href="<?php echo base_url(); ?>" />
	
	<!-- Mobile Viewport Fix -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
		
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]--> 

	<?php echo $template['metadata']; ?>

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
<section>
	<header>
		<div class="account_bg" style="background-image: url(<?php echo (setting('account_bg')) ?  setting('account_bg') : site_url('assets/img/default/style_bg.jpg', FALSE); ; ?>);">
			<div class="account_logo">
				<a href="<?php echo site_url(); ?>" style="background-image: url(<?php echo (setting('account_logo')) ?  setting('account_logo') : site_url('assets/img/default/style_logo_200.jpg', FALSE); ; ?>);"><?php echo account('name'); ?></a>
			</div>
		</div>

		<div class="container">
			<h1><?php echo account('name'); ?></h1>

			<?php echo auto_typography(account('description')); ?>

			<?php echo (account('website')) ? auto_link(prep_url(account('website'))) : '' ; ?>
		</div>
	</header>

	<div class="container body">
		
		<?php echo $template['body']; ?>

	</div>

	<footer>
		<a href="http://bookyourbeds.com" target="_blank" class="powered-by-badge">Powered by BookYourBeds.com</a>
	</footer>
</section>

</body>
</html>