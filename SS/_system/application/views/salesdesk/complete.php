<?php if(is_verified($booking)) { ?>
<h1 class="page-header">Booking Complete</h1>
<?php } else { ?>
<h1 class="page-header">Booking Awaiting Verification</h1>

<div class="alert">An email has been sent to <strong><?php echo $booking->customer->customer_email; ?></strong> with a link to verify this booking.</div>
<?php } ?>

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
<h3>Due On Arrival: &pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></h3>

<?php if(! is_verified($booking)) { ?>
<div class="alert alert-danger"><strong>Please note:</strong> This booking is not confirmed until you verify it by following the link in the email that has been sent to <strong><?php echo $booking->customer->customer_email; ?></strong>.</div>
<?php } ?>

<?php if( ! empty($billing_info)) { ?>
<div class="alert">	
	<h3>Billing Information</h3>

	<dl class="dl-horizontal">
		<?php foreach($billing_info as $key => $value) { ?>
		<dt><?php echo $key; ?></dt>
		<dd><?php echo $value; ?></dd>
		<?php } ?>
		<!--<dt>Card Number</dt>
		<dd>**** **** **** <?php echo $gateway_data['Last4Digits']; ?></dd>

		<dt>Card Type</dt>
		<dd><?php echo $gateway_data['CardType']; ?></dd>

		<dt>Amount</dt>
		<dd>&pound;<?php echo as_currency($gateway_data['Amount']); ?></dd>

		<dt>Authorisation Number</dt>
		<dd><?php echo $gateway_data['TxAuthNo']; ?></dd>-->
	</dl>
</div>
<?php } ?>
