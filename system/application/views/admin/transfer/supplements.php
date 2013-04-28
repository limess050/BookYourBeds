

<div class="page-header row">
	<div class="pull-left">
		<h1>Transfer <?php echo (! is_verified($booking)) ? 'Unverified ' : ''; ?>Booking <small><?php echo $booking->booking_reference; ?></small></h1>
	</div>


	<?php echo $template['partials']['booking_button_group']; ?>

</div>

<div class="row">
	<div class="span7">
		<div class="heavy-border">
			<h3>Current Booking Overview</h3>

			<?php echo (is_cancelled($booking)) ? '<span class="label label-important">BOOKING CANCELLED ON ' . strtoupper(mysql_to_format($booking->booking_deleted_at, 'l, j F Y')) . '</span>' : ''; ?>

			<dl class="dl-horizontal">
				<dt>Booking Reference</dt>
				<dd><?php echo $booking->booking_reference; ?></dd>

				<?php foreach($booking->resources as $resource) { ?>
				<dt>Room</dt>
				<dd><?php echo $resource->resource_title; ?></dd>
				
				<dt>Arrival</dt>
				<dd>
					<?php 
					echo anchor('admin/bookings?timestamp=' . human_to_unix($resource->reservation_start_at), mysql_to_format($resource->reservation_start_at, 'l, j F Y')); 
					echo ($resource->reservation_start_at == date('Y-m-d 00:00:00') && $resource->reservation_checked_in) ? ' <span class="label label-success">CHECKED IN</span>' : '';
					?>
				</dd>

				<dt>Duration</dt>
				<dd><?php echo duration($resource->reservation_duration); ?></dd>

				<dt>Total Guests</dt>
				<dd><?php echo "{$booking->booking_guests} guest" . (($booking->booking_guests > 1) ? 's' : ''); ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)</dd>				
				<?php } ?>
			
				<dt>Total Cost</dt>
				<dd>&pound;<?php echo as_currency($booking->booking_price); ?></dd>

				<dt>Deposit Paid</dt>
				<dd>&pound;<?php echo as_currency($booking->booking_deposit); ?></dd>
			</dl>
		</div>
		
	</div>

	<div class="span5">
	    <h3>New Booking Details</h3>

	    <dl class="dl-horizontal">
			<dt>Room</dt>
			<dd><?php echo $new_resource->resource_title; ?></dd>
			
			<dt>Arrival</dt>
			<dd><?php echo date('l, j F Y', session('transfer_booking', 'start_at')); ?></dd>

			<dt>Duration</dt>
			<dd><?php echo duration( session('transfer_booking', 'duration')); ?></dd>

			<dt>Total Guests</dt>
			<dd><?php echo session('transfer_booking', 'booking_guests') . " guest" . ((session('transfer_booking', 'booking_guests') > 1) ? 's' : ''); ?> (<?php echo session('transfer_booking', 'footprint') . " {$new_resource->resource_priced_per}" . ((session('transfer_booking', 'footprint') > 1) ? 's' : ''); ?>)</dd>				
		
			<dt>Total Cost</dt>
			<dd>&pound;<?php echo as_currency(session('transfer_booking', 'booking_price')); ?></dd>
		</dl>
	    
	</div>

</div>

<h2 class="page-header">Optional Supplements</h2>

	<?php echo $template['partials']['form_errors']; ?>

	<?php echo form_open('admin/transfer/supplements/' . $booking->booking_id, array('class' => 'form-horizontal')); ?>

	<table class="table">
	<?php 
	

	foreach($supplements as $supplement) { ?>
	<tr>
		<td><h4><?php echo $supplement->supplement_short_description; ?> <small>&pound;<?php echo as_currency($supplement->resource_price) . ' ' .
																								(($supplement->supplement_per_guest) ? 'per person' : 'per ' . $new_resource->resource_priced_per) . ' ' .
																								(($supplement->supplement_per_day) ? 'per night' : 'per stay') ; ?></small></h4>

			<?php echo auto_typography($supplement->supplement_long_description); ?>
		</td>
		
		<td class="span4">
			<?php
			// The total number of options
			$opt_count = ($supplement->supplement_per_guest) ? session('transfer_booking', 'booking_guests') : session('transfer_booking', 'footprint');
			$multiply = ($supplement->supplement_per_day) ? session('transfer_booking', 'duration') : 1;

			$options = array(
							0	=> '0'
							);

			for($i = 1; $i <= $opt_count; $i++)
			{
				$options[$i] = $i . ' ' . (($supplement->supplement_per_guest) ? 'person' : $new_resource->resource_priced_per) . (($i > 1) ? 's' : '') . ' - &pound;' . as_currency($supplement->resource_price * $multiply * $i);
			}

			if( ! empty( $_supplements[$supplement->supplement_id]['qty']))
			{
				$_supplements[$supplement->supplement_id]['qty'] = ($_supplements[$supplement->supplement_id]['qty'] > $opt_count) ? $opt_count : $_supplements[$supplement->supplement_id]['qty'];
			}

			echo form_dropdown("supplements[{$supplement->supplement_id}][qty]", 
								$options, 
								set_value("supplements[{$supplement->supplement_id}][qty]", ( ! empty($_supplements[$supplement->supplement_id])) ? $_supplements[$supplement->supplement_id]['qty'] : 0), 
								'class="span3"');
			
			echo form_hidden(array(
								"supplements[{$supplement->supplement_id}][price]" => ($supplement->resource_price * $multiply),
								"supplements[{$supplement->supplement_id}][description]" => $supplement->supplement_short_description
								));
			?>

		</td>
	</tr>


	<?php } ?>
	</table>

	
		
	<div class="control-group">

		<div class="controls">
			<button type="submit" class="btn btn-warning btn-large">Continue</button>
		</div>
	</div>

</form>