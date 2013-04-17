<?php if( ! validation_errors()) { ?>
<h1 class="page-header">Booking Search <small><?php echo count($results); ?> results for <em>'<?php echo $search; ?>'</em></small></h1>

<table class="table table-striped table-condensed table-hover table-responsive">
	<thead class="hidden-tablet hidden-phone">
		<tr>
			<th>Customer Name</th>
			<th>Arrival</th>
			<th>Booking Reference</th>
			<th>Room Booked</th>
			<th>Guests</th>
			<th>Duration</th>
		</tr>
	<thead>
	
	<tbody>
		<?php foreach($results as $booking) { ?>
		<tr>
			<td>
				<div class="responsive-label">Customer Name</div>
				<div class="responsive-content"><?php echo highlight_phrase($booking->customer_firstname . ' ' . $booking->customer_lastname, $search, '<code>', '</code>'); ?></div>
			</td>

			<td>
				<div class="responsive-label">Arrival</div>
				<div class="responsive-content">
					<?php echo mysql_to_format($booking->reservation_start_at); ?>
					<?php echo ($booking->booking_deleted_at != '0000-00-00 00:00:00') ? '<span class="label label-important">CANCELLED</span>' : ''; ?>
				</div>
			</td>

			<td>
				<div class="responsive-label">Booking Reference</div>
				<div class="responsive-content"><?php echo anchor("admin/bookings/show/{$booking->booking_id}", highlight_phrase($booking->booking_reference, $search, '<code>', '</code>')); ?></div>
			</td>
			
			<td>
				<div class="responsive-label">Room Booked</div>
				<div class="responsive-content"><?php echo $booking->resource_title; ?></div>
			</td>
			
			<td>
				<div class="responsive-label">Guests</div>
				<div class="responsive-content"><?php echo $booking->booking_guests; ?></div>
			</td>
			
			<td>
				<div class="responsive-label">Duration</div>
				<div class="responsive-content"><?php echo duration($booking->reservation_duration); ?></div>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>


<?php } else {
	echo $template['partials']['form_errors'];
} ?>