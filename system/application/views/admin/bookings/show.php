

<div class="page-header row">
	<div class="pull-left">
		<h1><?php echo (! is_verified($booking)) ? 'Unverified ' : ''; ?>Booking <small><?php echo anchor('admin/bookings/show/' . $booking->booking_id, $booking->booking_reference); ?></small></h1>
	</div>


	<?php echo $template['partials']['booking_button_group']; ?>

<div class="row">
	<div class="span7">
		<?php echo $template['partials']['booking_overview']; ?>

		<?php echo $template['partials']['booking_supplements']; ?>		
	</div>

	<div class="span5">
	    <h3>Guest Details <small><?php echo anchor('admin/bookings/edit/' . $booking->booking_id, 'Edit'); ?></small></h3>

  		<dl class="dl-horizontal">
  			<dt>Full Name</dt>
    		<dd><?php echo $booking->customer->customer_firstname . ' ' . $booking->customer->customer_lastname; ?></dd>

    		<dt>Email Address</dt>
    		<dd><?php echo mailto($booking->customer->customer_email . '?subject=Your Booking: ' . $booking->booking_reference, $booking->customer->customer_email); ?></dd>

    		<dt>Contact Tel.</dt>
    		<dd><?php echo $booking->customer->customer_phone; ?></dd>
		</dl>
	</div>

</div>

<hr />

<div class="row">
	<div class="span6">
		<h3>Booking Data</h3>

		<dl class="dl-horizontal">
			<dt>Total Cost</dt>
    		<dd>&pound;<?php echo as_currency($booking->booking_price); ?></dt>
			
			<dt>Deposit Paid</dt>
    		<dd>&pound;<?php echo as_currency($booking->booking_deposit); ?></dt>
			
			<dt>Payment Outstanding</dt>
    		<dd>&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></dt>

  			<dt>Booking Date</dt>
    		<dd><?php echo mysql_to_format($booking->booking_created_at, 'l, j F Y \a\t H:i'); ?></dd>

    		<!--<dt>Booked By</dt>
    		<dd>
    			Internet booking
    		</dd>
    		<dd><em>MacBackpackers website</em></dd>-->
    		
    	</dl>
	</div>

	<?php if( ! empty($previous)) { ?>
	<div class="span6">
		<h3>Previous Versions</h3>

		<table class="table table-condensed table-striped">
			<thead>
				<tr>
					<th>Room</th>
					<th>Arrival</th>
					<th>Guests</th>
					<th>Duration</th>
				</tr>

			</thead>

			<tbody>
			<?php foreach($previous as $b) { ?>
				<tr>
					<td><?php echo anchor('admin/bookings/show/' . $b->booking_id, $b->resources[0]->resource_title); ?></td>
					<td><?php echo mysql_to_format($b->resources[0]->reservation_start_at); ?></td>
					<td><?php echo $b->booking_guests; ?></td>
					<td><?php echo duration($b->resources[0]->reservation_duration); ?></td>
				</tr>
			<?php } ?>
			<tbody>
		</table>
		
	</div>
	<?php } ?>

	


</div>

<!--<pre>
<?php print_r($booking); ?>
</pre>-->
