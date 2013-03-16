<?php echo form_open('admin/bookings/checkin'); ?>

<div class="btn-group pull-right">
	<?php if( ! is_verified($booking)) { ?>
	<a href="<?php echo site_url('admin/bookings/remove/' . $booking->booking_id); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this booking?');"><i class="icon-remove icon-white"></i> Remove this booking</a>

	<button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu">
		<li><a href="<?php echo site_url('admin/bookings/verify/' . $booking->booking_id); ?>" onclick="return confirm('Are you sure you want to verify this booking?');"><i class="icon-ok"></i> Verify this booking</a></li>
	</ul>
	<?php } else { 
	if($booking->resources[0]->reservation_start_at == date('Y-m-d 00:00:00') && ! $booking->resources[0]->reservation_checked_in)
	{ 
		$btn_state = 'success';
 
		echo '<button type="submit" class="btn btn-success"><i class="icon-check icon-white"></i> Check-in</button>';
		echo form_hidden(array(
							'redirect' => safe_get_env(),
							"booking[{$booking->booking_id}]" => 1
							)
						);
	} else { 
		if ( ! $booking->booking_acknowledged) { 
			$btn_state = 'warning';
		?>
		<a href="<?php echo site_url('admin/bookings/acknowledge/' . $booking->booking_id); ?>" class="btn btn-<?php echo $btn_state; ?>"><i class="icon-check icon-white"></i> Acknowledge this booking</a>
		<?php } else { 
			$btn_state = 'success';
		?>
		<button href="#" onclick="return false;" class="btn btn-<?php echo $btn_state; ?>"><i class="icon-ok icon-white"></i> Booking acknowledged</button>
		<?php }


	} ?>

	<button class="btn btn-<?php echo $btn_state; ?> dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu">
		<li><a href="<?php echo site_url('admin/bookings/email/' . $booking->booking_id); ?>"><i class="icon-envelope"></i> Email Booking Details</a></li>
		<!--<li><a href="#"><i class="icon-comment"></i> Add Note to Booking</a></li>-->
		<li><a href="<?php echo site_url('admin/bookings/edit/' . $booking->booking_id . '#guest'); ?>"><i class="icon-pencil"></i> Edit Guest Details</a></li>
		<li class="divider"></li>
		<li><a href="<?php echo site_url('admin/bookings/transfer/' . $booking->booking_id); ?>"><i class="icon-random"></i> Transfer Booking</a></li>
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
	<?php } ?>
</div>
</form>