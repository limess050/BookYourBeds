<h1 class="page-header">Your Booking Failed</h1>

<div class="alert alert-error">
	<?php echo $this->session->flashdata('reason'); ?>
</div>

<a href="<?php echo site_url('scotlandstophostels/sagepay'); ?>" class="btn btn-large btn-primary"><i class="icon icon-repeat icon-white"></i> Try Again</a>
<a href="<?php echo site_url('scotlandstophostels/reset'); ?>" class="btn btn-large"><i class="icon icon-remove"></i> Cancel Booking</a>