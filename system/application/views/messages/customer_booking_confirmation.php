<h2>Your booking with <?php echo $booking->resources[0]->account_name; ?></h2>

<p><strong>Please review your booking details below - if anything is incorrect please contact your accommodation provider immediately.</strong></p>

<?php echo ( ! empty($message)) ? auto_typography($message) : ''; ?>

<h3>Booking Overview</h3>
<p>Booking Reference: <strong><?php echo $booking->booking_reference; ?></strong><br />
Arrival: <strong><?php echo mysql_to_format($booking->resources[0]->reservation_start_at); ?></strong><br />
Duration: <strong><?php echo duration($booking->resources[0]->reservation_duration); ?></strong>
</p>

<h3>Guest Details</h3>
<p>Name: <strong><?php echo $booking->customer->customer_firstname . ' ' . $booking->customer->customer_lastname; ?></strong><br />
Email Address: <strong><?php echo $booking->customer->customer_email; ?></strong><br />
Telephone: <strong><?php echo $booking->customer->customer_phone; ?></strong></p>

<h3>Rooms Booked</h3>
<table>
	<thead>
		<tr>
			<th>Room Type</th>
			<th>Quantity</th>
			<th>Guests</th>
		</tr>
	</thead>

	<tbody>
<?php foreach($booking->resources as $resource) { ?>
	<tr>
		<td><?php echo $resource->resource_title; ?></td>
		<td><?php echo $resource->reservation_footprint; ?></td>
		<td><?php echo $resource->reservation_guests; ?></td>
	</tr>
<?php } ?>
	</tbody>
</table>


<?php if($booking->has_supplements) { ?>
<h3>Additional Supplements</h3>

<table>
	<thead>
		<tr>
			<th>Supplement</th>
			<th>Quantity</th>
			<th>Price</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($booking->resources as $resource) { 
		foreach($resource->supplements as $supplement) { ?>
		<tr>
			<td><?php echo $supplement->supplement_short_description; ?> (<?php echo $resource->resource_title; ?>)</td>
			<td><?php echo $supplement->stb_quantity; ?></td>
			<td>&pound;<?php echo as_currency($supplement->stb_quantity * $supplement->stb_price); ?></td>
		</tr>
		<?php }
		} ?>

	</tbody>
</table>

<?php } ?>

<h3>Payments</h3>
<p>Total Amount: <strong>&pound;<?php echo as_currency($booking->booking_price); ?></strong><br />
Deposit Paid: <strong>&pound;<?php echo as_currency($booking->booking_deposit); ?></strong><br />
Due at <?php echo $balance_due; ?>: <strong>&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></strong></p>

<h3>Accommodation Details</h3>

<p><strong><?php echo $booking->resources[0]->account_name; ?></strong><br />
<?php echo $booking->resources[0]->account_address1; ?><br />
<?php echo ( ! empty($booking->resources[0]->account_address2)) ? $booking->resources[0]->account_address2 . '<br />' : '' ; ?>
<?php echo $booking->resources[0]->account_city; ?><br />
<?php echo $booking->resources[0]->account_postcode; ?><br />
<?php echo $countries[$booking->resources[0]->account_country]; ?>

<?php echo ( ! empty($booking->resources[0]->account_phone)) ? '<br /><br />Telephone: ' . $booking->resources[0]->account_phone : '' ; ?></p>

<?php echo ( ! empty($instructions)) ? auto_typography($instructions) : ''; ?>

<?php echo ( ! empty($terms)) ? '<h3>Terms and Conditions</h3>' . auto_typography($terms) : ''; ?>
