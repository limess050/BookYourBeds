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
			
			<?php if(session('user', 'user_id')) { ?>
			<ul class="nav">
				<li>
					<?php 

		
					echo anchor('admin', account('name') . " <span class=\"badge badge-success\">{$this->account->bookings}</span>"); ?>
				</li>

				<li><?php echo anchor('admin/bookings', 'Diary'); ?></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Rooms <b class="caret"></b></a>
					
					<ul class="dropdown-menu" role="menu">
						<li><?php echo anchor('admin/resources', 'Your Rooms'); ?></li>
						<li><?php echo anchor('admin/availability', 'Availability'); ?></li>
					</ul>
                </li>
			</ul>

            <?php echo form_open('admin/bookings/search', 'class="navbar-search pull-left"'); ?>
            	<input type="text" class="search-query span2" name="search_terms" placeholder="Search..." />
          	</form>

			<ul class="nav pull-right">
				<li><?php echo anchor('admin/salesdesk', 'Sales Desk'); ?></li>

				<?php if(session('user', 'user_is_admin')) { ?>
				<li class="divider-vertical"></li>

				<li class="dropdown">
				    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog icon-white"></i> <b class="caret"></b></a>
				    <ul class="dropdown-menu">
				        <li><a href="<?php echo site_url('admin/users'); ?>"><i class="icon-user"></i> Users</a></li>
				    </ul>
				</li>
				<?php } ?>
				
				<li class="divider-vertical"></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo session('user', 'user_firstname') . ' ' . session('user', 'user_lastname'); ?> <b class="caret"></b></a>
					
					<ul class="dropdown-menu">
						<li><?php echo anchor('admin/signout', 'Sign Out'); ?></li>
						<li><?php echo anchor('admin/users/me', 'Edit User Account'); ?></li>
						<!--<li class="divider"></li>
						<li><?php echo anchor('admin/account', 'Settings'); ?></li>
						<li><?php echo anchor('admin/users', 'Users'); ?></li>
						<li><?php echo anchor('admin/seasons', 'Seasons'); ?></li>-->
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
		&copy; Copyright <?php echo date('Y'); ?> | <?php echo account('name'); ?> | <?php echo anchor('', 'Go to site...'); ?>
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