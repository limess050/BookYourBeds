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
			</tr>
		</thead>

		<tbody>
			<?php foreach($booking->resources as $resource) { ?>
			<tr>
				<td><?php echo $resource->resource_title; ?></td>
				<td><?php echo $resource->reservation_footprint; ?></td>
				<td><?php echo $resource->reservation_guests; ?></td>
				<td><?php echo ($resource->reservation_start_at == date('Y-m-d 00:00:00') && $resource->reservation_checked_in) ? '<span class="label label-success">CHECKED IN</span>' : ''; ?></td>
			</tr>	
			<?php } ?>
		</tbody>
	</table>
		
</div>