Your booking with <?php echo $booking->resources[0]->account_name; ?><br /><br />

Booking Reference: <?php echo $booking->booking_reference; ?><br /><br />

<?php echo ( ! empty($message)) ? auto_typography($message) : ''; ?>


Guest Details<br />
Name: <?php echo $booking->customer->customer_firstname . ' ' . $booking->customer->customer_lastname; ?><br />
Email Address: <?php echo $booking->customer->customer_email; ?><br />
Telephone: <?php echo $booking->customer->customer_phone; ?><br /><br />

<?php foreach($booking->resources as $resource) { ?>
Room Booked: <?php echo $resource->resource_title; ?><br />
Arriving: <?php echo mysql_to_format($resource->reservation_start_at); ?><br />
Duration: <?php echo duration($resource->reservation_duration); ?><br />
Guests: <?php echo $resource->reservation_guests; ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)<br /><br />
<?php } ?>

Additional Supplements:<br />
<?php if( ! $booking->has_supplements) { ?>
You have not requested any additional supplements.<br /><br />
<?php } else { ?>
<?php foreach($booking->resources as $resource) { 
foreach($resource->supplements as $supplement) { ?>
<?php echo $supplement->supplement_short_description; ?><br />
<?php echo auto_typography($supplement->supplement_long_description); ?><br />
&pound;<?php echo as_currency($supplement->stb_quantity * $supplement->stb_price); ?><br /><br />
<?php }
} ?>
<?php } ?>

Payments<br />
Total Amount: £<?php echo as_currency($booking->booking_price); ?><br />
Deposit Paid: £<?php echo as_currency($booking->booking_deposit); ?><br />
Due on Arrival: £<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?><br /><br />

<?php echo ( ! empty($instructions)) ? auto_typography($instructions) : ''; ?>

Generated By BookYourBeds
