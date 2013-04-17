<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title><?php echo account('name'); ?></title>
	
	<base href="<?php echo base_url(); ?>" />

	<meta name="description" content="Book Your Beds Admin">
	<meta name="author" content="Book Your Beds">
	<meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; user-scalable=0;">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	
	<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<?php echo $template['metadata']; ?>

</head>

<body>

<header class="masthead">
	<ul>
		<li class="mobile-menu"><a href="#" onclick="$('.sidebar').toggleClass('show'); return false;">Menu</a></li>
		<li class="branding"><h3>Book Your Beds</h3></li>
		<li class="mobile-branding"><?php echo anchor('admin', 'Dashboard'); ?></li>
		<?php if(account('active')) { ?>
		<li class="pull-right"><a href="#" onclick="$('#search-form').toggle(); $(this).toggleClass('active'); return false;">Search</a></li>
		<?php } ?>
	</ul>

	<?php echo form_open('admin/bookings/search', 'id="search-form"'); ?>
    	<input type="text" name="search_terms" placeholder="Search for something..." />
  	</form>
</header>

<nav class="sidebar">
	<ul class="side-nav">
		<li class="nav-dashboard hidden-phone <?php echo select_if_current('dashboard'); ?>"><?php echo anchor('admin', 'Dashboard'); ?></li>
		<?php if(account('active')) { ?>
		<li class="nav-diary <?php echo select_if_current('bookings'); ?>"><?php echo anchor('admin/bookings', 'Diary'); ?></li>
		<?php } ?>
		<li class="nav-rooms <?php echo select_if_current('resources'); ?>"><?php echo anchor('admin/resources', 'Rooms'); ?></li>
		<li class="nav-availability hidden-phone <?php echo select_if_current('availability'); ?>"><?php echo anchor('admin/availability', 'Availability'); ?></li>
		<li class="nav-supplements <?php echo select_if_current('supplements'); ?>"><?php echo anchor('admin/supplements', 'Supplements'); ?></li>
		
		<?php if(account('active')) { ?>
		<li class="nav-salesdesk <?php echo select_if_current('salesdesk'); ?>"><?php echo anchor('admin/salesdesk', 'Sales Desk'); ?></li>
		<?php } ?>
	</ul>

	<ul class="side-nav bottom">
		<li class="nav-settings <?php echo select_if_current('settings'); ?>"><?php echo anchor('admin/settings/account', 'Settings'); ?></li>
		<li class="nav-user"><a href="<?php echo site_url('signout', FALSE); ?>">Signout</a></li>
		<li class="nav-help"><a href="<?php echo site_url('admin/remote/help?curi=' . $this->uri->rsegment(1) . '/' .  $this->uri->rsegment(2)); ?>" data-target="#help_modal" data-toggle="modal">Help</a></li>
	</ul>
</nav>

<div class="body">
	<div class="container-fluid">
		<?php echo $template['body']; ?>

	</div>
</div>

<div id="help_modal" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Help</h3>
    </div>
    
    <div class="modal-body">
    	<div style="text-align: center;"><?php echo image('ajax-loader.gif'); ?></div>
    </div>
	<!--<div class="modal-footer">
	    <a href="#" class="btn" data-dismiss="modal">Close</a>
	</div>-->
</div>

</body>

</html>