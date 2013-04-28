

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

<h2 class="page-header">Guest Details</h2>

	<?php echo $template['partials']['form_errors']; ?>

	<?php echo form_open('admin/transfer/details/' . $booking->booking_id, array('class' => 'form-horizontal')); ?>

	<fieldset>
		<div class="control-group">
			<label class="control-label" for="customer_firstname">First Name</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'customer[customer_firstname]',
					'value'	=> set_value('customer[customer_firstname]', $booking->customer->customer_firstname),
					'class'	=> 'span3',
					'id'	=> 'customer_firstname'
					));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="customer_lastname">Last Name</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'customer[customer_lastname]',
					'value'	=> set_value('customer[customer_lastname]', $booking->customer->customer_lastname),
					'class'	=> 'span3',
					'id'	=> 'customer_lastname'
					));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="customer_email">Email Address</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'customer[customer_email]',
					'value'	=> set_value('customer[customer_email]', $booking->customer->customer_email),
					'class'	=> 'span4',
					'id'	=> 'customer_email'
					));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="customer_phone">Contact Telephone</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'customer[customer_phone]',
					'value'	=> set_value('customer[customer_phone]', $booking->customer->customer_phone),
					'class'	=> 'span3',
					'id'	=> 'customer_phone'
					));
				?>
			</div>
		</div>
		
		<div class="control-group">

			<div class="controls">
				<button type="submit" class="btn btn-warning btn-large">Continue</button>
			</div>
		</div>
	</fieldset>

	</form>


