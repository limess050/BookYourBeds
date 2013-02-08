You have a new booking!<br /><br />

Visit <?php echo anchor('admin/bookings/show/' . $booking->booking_id); ?> to acknowledge this booking.<br /><br />

<?php foreach($booking->resources as $resource) { ?>
Room Booked: <?php echo $resource->resource_title; ?><br />
Arriving: <?php echo mysql_to_format($resource->reservation_start_at); ?><br />
Duration: <?php echo duration($resource->reservation_duration); ?><br />
Guests: <?php echo $booking->booking_guests; ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)<br /><br />
<?php } ?>
<br />
Guest Details<br />
Name: <?php echo $booking->customer->customer_firstname . ' ' . $booking->customer->customer_lastname; ?><br />
Email Address: <?php echo $booking->customer->customer_email; ?><br />
Telephone: <?php echo $booking->customer->customer_phone; ?><br /><br /><br />

Payments<br />
Total Amount: £<?php echo as_currency($booking->booking_price); ?><br />
Deposit Paid: £<?php echo as_currency($booking->booking_deposit); ?><br />
Due on Arrival: £<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?><br /><br /><br />


Generated By BookYourBeds
