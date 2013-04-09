

<div class="page-header row">
	<div class="pull-left">
		<h1><?php echo (! is_verified($booking)) ? 'Unverified ' : ''; ?>Booking <small><?php echo $booking->booking_reference; ?></small></h1>
	</div>


	<?php echo $template['partials']['booking_button_group']; ?>

</div>

<div class="row">
	<div class="span7">
		<div class="heavy-border">
			<h3>Booking Overview</h3>

			<?php if ( ! empty($new)) 
			{ 
				echo '<a href="' . site_url('admin/bookings/show/' . $new->booking_id) . '" class="label label-info">BOOKING TRANSFERRED TO ' . strtoupper(mysql_to_format($new->resources[0]->reservation_start_at, 'l, j F Y')) . '</a>';
			} else
			{
				echo (is_cancelled($booking)) ? '<span class="label label-important">BOOKING CANCELLED ON ' . strtoupper(mysql_to_format($booking->booking_deleted_at, 'l, j F Y')) . '</span>' : '';
			} ?>


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
			
				<dt>Payment Outstanding</dt>
				<dd>&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></dd>
			</dl>
		</div>

		<?php if( ! empty($booking->supplements)) { ?>
		<h3>Supplements</h3>

		<table class="table table-condensed table-striped">
			<thead>
				<tr>
					<th>Supplement</th>
					<th class="span1">Qty</th>
					<th class="span1">Price</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach($booking->supplements as $supplement) { ?>
				<tr>
					<td><?php echo $supplement->supplement_short_description; ?></td>
					<td><?php echo $supplement->stb_quantity; ?></td>
					<td>&pound;<?php echo as_currency($supplement->stb_price); ?></td>
				</tr>
				<?php } ?>
			</tbody>


		</table>


		<?php } ?>
		
	</div>

	<div class="span5">
	    <h3>Guest Details</h3>

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
