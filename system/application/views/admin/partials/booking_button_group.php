<div class="btn-group pull-right">
	<?php if ( ! $booking->booking_acknowledged) { 
		$btn_state = 'warning';
	?>
	<a href="<?php echo site_url('admin/bookings/acknowledge/' . $booking->booking_id); ?>" class="btn btn-<?php echo $btn_state; ?>"><i class="icon-check icon-white"></i> Acknowledge this booking</a>
	<?php } else { 
		$btn_state = 'success';
	?>
	<button href="#" onclick="return false;" class="btn btn-<?php echo $btn_state; ?>"><i class="icon-ok icon-white"></i> Booking acknowledged</button>
	<?php } ?>
	<button class="btn btn-<?php echo $btn_state; ?> dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu">
		<li><a href="<?php echo site_url('admin/bookings/email/' . $booking->booking_id); ?>"><i class="icon-envelope"></i> Email Booking Details</a></li>
		<!--<li><a href="#"><i class="icon-comment"></i> Add Note to Booking</a></li>-->
		<li><a href="<?php echo site_url('admin/bookings/edit/' . $booking->booking_id . '#guest'); ?>"><i class="icon-pencil"></i> Edit Guest Details</a></li>
		<!--<li><a href="#"><i class="icon-random"></i> Transfer Booking</a></li>-->
		<li class="divider"></li>
		<li><?php 
			if (is_cancelled($booking)) 
			{
				echo anchor('admin/bookings/uncancel/' . $booking->booking_id,
							'<i class="icon-ok"></i> Uncancel Booking',
							'onclick="return confirm(\'Are you sure you want to uncancel this booking?\');"');
			} else
			{
				echo anchor('admin/bookings/cancel/' . $booking->booking_id,
							'<i class="icon-remove"></i> Cancel Booking',
							'onclick="return confirm(\'Are you sure you want to cancel this booking?\');"');
			}
		 ?></li>
	</ul>
</div>