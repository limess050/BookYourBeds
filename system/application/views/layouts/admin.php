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
			
			<ul class="nav">
				<li>
					<?php echo anchor('admin', account('name') . " <span class=\"badge badge-success\">{$this->account->bookings}</span> <span class=\"badge badge-warning\">{$this->account->unverified}</span>"); ?>
				</li>

				<?php if(session('user', 'user_id')) { ?>
				
				<?php if(account('active')) { ?>
				<li><?php echo anchor('admin/bookings', 'Diary'); ?></li>
				<?php } ?>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Rooms <b class="caret"></b></a>
					
					<ul class="dropdown-menu" role="menu">
						<li><?php echo anchor('admin/resources', 'Your Rooms'); ?></li>
						<li><?php echo anchor('admin/availability', 'Availability'); ?></li>
						<li><?php echo anchor('admin/supplements', 'Supplements'); ?></li>
					</ul>
                </li>
			</ul>

			<?php if(account('active')) { ?>
            <?php echo form_open('admin/bookings/search', 'class="navbar-search pull-left"'); ?>
            	<input type="text" class="search-query span2" name="search_terms" placeholder="Search..." />
          	</form>
          	<?php } ?>

			<ul class="nav pull-right">
				<?php if(account('active')) { ?>
				<li><?php echo anchor('admin/salesdesk', 'Sales Desk'); ?></li>
				<?php } ?>
				
				<?php if(session('user', 'user_is_admin') && account('active')) { ?>
				<li class="divider-vertical"></li>

				<li class="dropdown">
				    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog icon-white"></i> <b class="caret"></b></a>
				    <ul class="dropdown-menu">
				        <!--<li><a href="<?php echo site_url('admin/users'); ?>"><i class="icon-user"></i> Users</a></li>-->
				        <li><a href="<?php echo site_url('admin/settings/account'); ?>"><i class="icon-home"></i> Account Settings</a></li>
				        <li><a href="<?php echo site_url('admin/settings/payments'); ?>"><i class="icon-shopping-cart"></i> Payment Options</a></li>
				        <li><a href="<?php echo site_url('admin/settings/invoice'); ?>"><i class="icon-file"></i> Invoice/Receipt Settings</a></li>
				        <li><a href="<?php echo site_url('admin/settings/bookings'); ?>"><i class="icon-book"></i> Booking Settings</a></li>
				        <li><a href="<?php echo site_url('admin/seasons'); ?>"><i class="icon-calendar"></i> Seasons</a></li>
				    </ul>
				</li>
				<?php } ?>
				
				<li class="divider-vertical"></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> <b class="caret"></b></a>
					
					<ul class="dropdown-menu">
						<li><?php echo anchor('signout', 'Sign Out', null, FALSE); ?></li>
						<li><?php echo anchor('admin/users/me', 'Edit Your Details'); ?></li>
					</ul>
				</li>
            </ul>
            <?php } ?>
		</div>
	</div>
</div>


<div class="container">
	<div id="alert_bar" style="display: none;"></div>

	<?php echo $template['body']; ?>

	<footer>
		&copy; Copyright <?php echo date('Y'); ?> <?php if(account('id')) { ?>| <?php echo account('name'); ?><?php echo (account('active')) ? ' | ' . anchor('', 'Go to site...') : ''; } ?>
	</footer>

</div>

<script type="text/javascript">
<!--
	$(document).ready(function() {
		var isCtrl = false;

		$(document).keyup(function (e) {
			if(e.which == 17) isCtrl=false;
		});
		
		$(document).keydown(function (e) {
			if(e.which == 17) isCtrl=true;
			if(e.which == 78 && isCtrl == true) {
				if(MODAL === 0)
				{
					e.preventDefault();
					openModal('admin/bookings/create_modal');
				}
			}
		});




		

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