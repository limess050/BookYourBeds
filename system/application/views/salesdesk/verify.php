<?php if(! empty($booking)) { ?>
<h1 class="page-header">Booking Verified</h1>

<h3>Reference: <?php echo $booking->booking_reference; ?></h3>

<table class="table table-condensed">
	<thead>	
		<tr>
			<th></th>
			<th>Arriving</th>
			<th>Duration</th>
			<th>Guests</th>
		</tr>

	</thead>

	<tbody>
		<?php foreach($booking->resources as $resource) { ?>
		<tr>
			<td><strong><?php echo $resource->resource_title; ?></strong></td>
			<td><?php echo mysql_to_format($resource->reservation_start_at); ?></td>
			<td><?php echo duration($resource->reservation_duration); ?></td>
			<td><?php echo $booking->booking_guests; ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)</td>
		</tr>
		<?php } ?>
	</tbody>

</table>


<h3>Primary Guest Details</h3>

<dl class="dl-horizontal">
	<dt>Full Name</dt>
	<dd><?php echo $booking->customer->customer_firstname . ' ' . $booking->customer->customer_lastname; ?></dd>

	<dt>Email Address</dt>
	<dd><?php echo $booking->customer->customer_email; ?></dd>

	<dt>Contact Telephone</dt>
	<dd><?php echo $booking->customer->customer_phone; ?></dd>
</dl>




<h3>Total Cost: &pound;<?php echo as_currency($booking->booking_price); ?></h3>
<h3>Deposit Paid: &pound;<?php echo as_currency($booking->booking_deposit); ?></h3>
<h3>Due at <?php echo setting('balance_due'); ?>: &pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></h3>
<?php } else { ?>
<h1 class="page-header">Unable to verify booking</h1>

<p>This link may have expired.</p>

<?php } ?>
