<?php if( ! validation_errors()) { ?>
<div class="page-header row">
	<h1>Booking Search <small><?php echo count($results); ?> results for <em>'<?php echo $search; ?>'</em></small></h1>
</div>

<table class="table table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th>Customer Name</th>
			<th>Arrival</th>
			<th>Booking Reference</th>
			<th>Resource Booked</th>
			<th>Guests</th>
			<th>Duration</th>
		</tr>
	<thead>
	
	<tbody>
		<?php foreach($results as $booking) { ?>
		<tr>
			<td><?php echo highlight_phrase($booking->customer_firstname . ' ' . $booking->customer_lastname, $search, '<code>', '</code>'); ?></td>
			<td>
				<?php echo mysql_to_format($booking->reservation_start_at); ?>
				<?php echo ($booking->booking_deleted_at != '0000-00-00 00:00:00') ? '<span class="label label-important">CANCELLED</span>' : ''; ?>
			</td>
			<td><?php echo anchor("admin/bookings/show/{$booking->booking_id}", highlight_phrase($booking->booking_reference, $search, '<code>', '</code>')); ?></td>
			<td><?php echo $booking->resource_title; ?></td>
			<td><?php echo $booking->booking_guests; ?></td>
			<td><?php echo duration($booking->reservation_duration); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>


<?php } else {
	echo $template['partials']['form_errors'];
} ?>