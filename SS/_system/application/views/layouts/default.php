<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Always force latest IE rendering engine & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>BookYourBeds</title>
	
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
			<a class="brand">BookYourBeds</a>

			<ul class="nav pull-right">
				<li><?php echo anchor('signup', 'Create a New Account'); ?></li>
				<li class="divider-vertical"></li>
				<li><?php echo anchor('signin', 'Sign In'); ?></li>
            </ul>
		</div>
	</div>
</div>


<div class="container">

	<?php echo $template['body']; ?>

	<footer>
		&copy; Copyright <?php echo date('Y'); ?> | BookYourBeds.com
	</footer>

</div>


</body>
</html>