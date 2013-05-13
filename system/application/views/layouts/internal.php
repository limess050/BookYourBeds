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

	<title>BookYourBeds :: Internal Administration</title>
	
	<base href="<?php echo base_url(); ?>" />
	
	<!-- Mobile Viewport Fix -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	
			
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]--> 
	
	<script type="text/javascript">
	    var APPPATH_URI = "<?php //echo APPPATH_URI;?>";
	    var BASE_URL = "<?php //echo rtrim(site_url(), '/').'/';?>";
	    var BASE_URI = "<?php //echo BASE_URI;?>";
	    var MODAL = 0;
	</script>

	<?php echo $template['metadata']; ?>
</head>

<body>

<div id="well" style="display: none;">
	<div id="modal">
		
	</div>
</div>

<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a href="<?php echo site_url('internal'); ?>" class="brand">BookYourBeds Internal Administration</a>

			<?php if (session('internal_user', 'internal_user_id')) { ?>

			<ul class="nav">
				<li><?php echo anchor('internal/accounts', 'Accounts'); ?></li>
			</ul>


			<ul class="nav pull-right">
				
				
				<li class="divider-vertical"></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> <b class="caret"></b></a>
					
					<ul class="dropdown-menu">
						<li><?php echo anchor('internal/signout', 'Sign Out', null, FALSE); ?></li>
						<li><?php echo anchor('internal/users/me', 'Edit Your Details'); ?></li>
					</ul>
				</li>
            </ul>

			<?php } ?>
		</div>
	</div>
</div>


<div class="container">

	<?php echo $template['body']; ?>

	<footer>
		&copy; Copyright <?php echo date('Y'); ?> | BookYourBeds.com
	</footer>

</div>

<script type="text/javascript">
<!--
	$(document).ready(function() {
		

		<?php if($this->session->flashdata('msg')) { ?>
		growl('<?php echo $this->session->flashdata('msg'); ?>', '');
		<?php } ?>
		<?php if( ! empty($_flash_msg)) { ?>
		growl('<?php echo $_flash_msg; ?>', '');
		<?php } ?>
	});
-->
</script>

<!-- Growl-style alerts. Grrrr! -->
<div id="growl"></div>

</body>
</html>