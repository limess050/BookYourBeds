<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Always force latest IE rendering engine & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo account('name'); ?></title>
	
	<base href="<?php echo base_url(); ?>" />
	
	<!-- Mobile Viewport Fix -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
		
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]--> 

	<?php echo $template['metadata']; ?>
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
		<!--&copy; Copyright <?php echo date('Y'); ?> | <?php echo account('name'); ?> | <?php echo anchor('admin', 'Sign In...'); ?>-->
		<a href="http://testing.bookyourbeds.com/website" target="_blank" class="powered-by-badge">Powered by BookYourBeds.com</a>
	</footer>
</section>

</body>
</html>