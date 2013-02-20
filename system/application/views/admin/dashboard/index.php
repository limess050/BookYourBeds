<h1 class="page-header">Arriving Today</h1>

<?php if(empty($bookings)) { ?>
<p><span class="label label-important">NO BOOKINGS ARRIVING TODAY</span></p>
<?php } else { ?>
<table class="table table-condensed table-striped table-hover" id="arrivals">
	<thead>
		<tr>
			<th>Name</th>
			<th>Booking Reference</th>
			<th>Resource Booked</th>
			<th>Guests</th>
			<th>Beds/Rooms</th>
			<th>Duration</th>
			<th>Bill</th>
		</tr>
	<thead>
	
	<tbody>
		<?php foreach($bookings as $booking) { ?>
		<tr id="booking_<?php echo $booking->booking_id; ?>">
			<td><?php echo $booking->customer_firstname; ?> <?php echo $booking->customer_lastname; ?></td>
			<td><?php echo anchor("admin/bookings/show/{$booking->booking_id}", $booking->booking_reference); ?></td>
			<td><?php echo $booking->resource_title; ?></td>
			<td><?php echo $booking->booking_guests; ?></td>
			<td><?php echo "{$booking->reservation_footprint} {$booking->resource_priced_per}" . (($booking->reservation_footprint > 1) ? 's' : ''); ?></td>
			<td><?php echo duration($booking->reservation_duration); ?></td>
			<td>&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></td>

		</tr>
		<?php } ?>
	</tbody>
</table>
<?php } ?>

<?php if( ! empty($new)) { ?>
<h1 class="page-header">New Bookings</h1>

<table class="table table-condensed table-striped table-hover">
	<thead>
		<tr>
			<th>Customer Name</th>
			<th>Arrival</th>
			<th>Booking Reference</th>
			<th>Resource Booked</th>
			<th>Guests</th>
			<th>Duration</th>
			<th>Deposit Paid</th>
			<th>Outstanding Bill</th>
		</tr>
	<thead>
	
	<tbody>
		<?php foreach($new as $booking) { ?>
		<tr>
			<td><?php echo $booking->customer_firstname . ' ' . $booking->customer_lastname; ?></td>
			<td><?php echo mysql_to_format($booking->reservation_start_at); ?></td>
			<td><?php echo anchor("admin/bookings/show/{$booking->booking_id}", $booking->booking_reference); ?></td>
			<td><?php echo $booking->resource_title; ?></td>
			<td><?php echo $booking->booking_guests; ?></td>
			<td><?php echo duration($booking->reservation_duration); ?></td>
			<td>&pound;<?php echo as_currency($booking->booking_deposit); ?></td>
			<td>&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php } ?>

<?php if( ! empty($unverified)) { ?>
<h1 class="page-header">Bookings Awaiting Verification</h1>

<table class="table table-condensed table-striped table-hover">
	<thead>
		<tr>
			<th>Customer Name</th>
			<th>Arrival</th>
			<th>Booking Reference</th>
			<th>Resource Booked</th>
			<th>Guests</th>
			<th>Duration</th>
			<th>Bill</th>
			<th>Time Remaining</th>
		</tr>
	<thead>
	
	<tbody>
		<?php foreach($unverified as $booking) { ?>
		<tr>
			<td><?php echo $booking->customer_firstname . ' ' . $booking->customer_lastname; ?></td>
			<td><?php echo mysql_to_format($booking->reservation_start_at); ?></td>
			<td><?php echo anchor("admin/bookings/show/{$booking->booking_id}", $booking->booking_reference); ?></td>
			<td><?php echo $booking->resource_title; ?></td>
			<td><?php echo $booking->booking_guests; ?></td>
			<td><?php echo duration($booking->reservation_duration); ?></td>
			<td>&pound;<?php echo as_currency($booking->booking_price); ?></td>
			<td></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php } ?>