

<div class="page-header row">
	<div class="pull-left">
		<h1>Transfer <?php echo (! is_verified($booking)) ? 'Unverified ' : ''; ?>Booking <small><?php echo $booking->booking_reference; ?></small></h1>
	</div>


	<?php echo $template['partials']['booking_button_group']; ?>

</div>

<div class="row">
	<div class="span6">
		<div class="heavy-border">
			<h3>Current Booking Overview</h3>

			<?php echo (is_cancelled($booking)) ? '<span class="label label-important">BOOKING CANCELLED ON ' . strtoupper(mysql_to_format($booking->booking_deleted_at, 'l, j F Y')) . '</span>' : ''; ?>


			<dl class="dl-horizontal">
				<dt>Booking Reference</dt>
				<dd><?php echo $booking->booking_reference; ?></dd>

				<dt>Arrival</dt>
				<dd>
					<?php 
					echo anchor('admin/bookings?timestamp=' . human_to_unix($booking->resources[0]->reservation_start_at), mysql_to_format($booking->resources[0]->reservation_start_at, 'l, j F Y')); 
					?>
				</dd>

				<dt>Duration</dt>
				<dd><?php echo duration($booking->resources[0]->reservation_duration); ?></dd>

				<dt>Total Guests</dt>
				<dd><?php echo $booking->booking_guests; ?></dd>

				<dt>Payment Outstanding</dt>
				<dd>&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></dd>
			</dl>

			<h3>Rooms Booked</h3>

			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Room Type</th>
						<th>Quantity</th>
						<th>Guests</th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php foreach($booking->resources as $resource) { ?>
					<tr>
						<td><?php echo $resource->resource_title; ?></td>
						<td><?php echo $resource->reservation_footprint; ?></td>
						<td><?php echo $resource->reservation_guests; ?></td>
						<td>&pound;<?php echo as_currency($resource->reservation_price); ?></td>
						<td><?php echo ($resource->reservation_start_at == date('Y-m-d 00:00:00') && $resource->reservation_checked_in) ? '<span class="label label-success">CHECKED IN</span>' : ''; ?></td>
					</tr>	
					<?php } ?>
				</tbody>
			</table>
			
			<?php if($booking->has_supplements) { ?>
			<h3>Supplements <small><?php echo anchor('admin/bookings/supplements/' . $booking->booking_id, 'Edit'); ?></small></h3>

			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Supplement</th>
						<th></th>
						<th class="span1">Qty</th>
						<th class="span1">Price</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach($booking->resources as $resource) { ?>
					<?php foreach($resource->supplements as $supplement) { ?>
					<tr>
						<td><?php echo $supplement->supplement_short_description; ?></td>
						<td><?php echo $resource->resource_title ?></td>
						<td><?php echo $supplement->stb_quantity; ?></td>
						<td>&pound;<?php echo as_currency($supplement->stb_price); ?></td>
					</tr>
					<?php } ?>
					<?php } ?>
				</tbody>


			</table>


			<?php } ?>
		</div>	

		
	</div>

	<div class="span6">
	    <h3>New Booking Details</h3>

	    <dl class="dl-horizontal">
	    	<dt>Arrival</dt>
			<dd><?php echo date('l, j F Y', session('transfer_booking', 'start_at')); ?></dd>

			<dt>Duration</dt>
			<dd><?php echo duration( session('transfer_booking', 'duration')); ?></dd>

			<dt>Total Guests</dt>
			<dd><?php echo session('transfer_booking', 'booking_guests'); ?></dd>	
	    </dl>

	    <h3>Rooms Booked</h3>

			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Room Type</th>
						<th>Quantity</th>
						<th>Guests</th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php foreach(session('transfer_booking', 'resources') as $resource) { ?>
					<tr>
						<td><?php echo $resource['resource_title']; ?></td>
						<td><?php echo ceil($resource['guests'] / $resource['resource_booking_footprint']); ?></td>
						<td><?php echo $resource['guests']; ?></td>
						<td>&pound;<?php echo as_currency(ceil($resource['guests'] / $resource['resource_booking_footprint']) * $resource['resource_single_price']); ?></td>
						
					</tr>	
					<?php } ?>
				</tbody>
			</table>


	    
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


