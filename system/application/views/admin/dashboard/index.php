<div class="row-fluid" id="dash-tabs">
	<?php
	$_t = array(
				'today'	=> 'Arriving<br />Today', 
				'new'	=> 'New<br />Bookings',
				'unverified'	=> 'Unverified<br />Bookings', 
				'cancelled'		=> 'Cancelled<br />Bookings'
				);

	foreach($_t as $tab => $label)
	{
		$_count = count($tabs[$tab]);

		?>
		<div class="span3">
			<a href="#<?php echo $tab; ?>" class="dash-tab tab-<?php echo $tab; ?> <?php echo ($_count == 0) ? 'tab-empty': ''; ?>" data-toggle="tab">
				<h3><?php echo $_count; ?></h3>
				<span><?php echo $label; ?></span>
			</a>
		</div>
		<?php
	}

	?>


</div>

<div class="row-fluid tab-content">
	<div class="tab-pane" id="today">
		<?php if(empty($tabs['today'])) { ?>
		<div class="well">
			<h3 class="marker today">No Bookings Arriving Today</h3>
		</div>
		<?php } else { ?>
		<h1 class="page-header">Arriving Today</h1>

		<?php echo form_open('admin/bookings/checkin'); ?>
		<table class="table table-condensed table-striped table-hover table-responsive" id="arrivals">
			<thead class="hidden-tablet hidden-phone">
				<tr>
					<th>Customer Name</th>
					<th>Booking Reference</th>
					<th>Room Booked</th>
					<th class="span1">Guests</th>
					<th>Beds/Rooms</th>
					<th>Duration</th>
					<th>Bill</th>
					<th class="checkin_col"></th>
				</tr>
			<thead>
			
			<tbody>
				<?php foreach($tabs['today'] as $booking) { ?>
				<tr id="booking_<?php echo $booking->booking_id; ?>">
					<td>
						<div class="responsive-label">Customer Name</div>

						<div class="responsive-content"><?php echo $booking->customer_firstname; ?> <?php echo $booking->customer_lastname; ?></div>

					</td>
					
					<td>
						<div class="responsive-label">Reference</div>
						<div class="responsive-content"><?php echo anchor("admin/bookings/show/{$booking->booking_id}", $booking->booking_reference); ?></div>

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
						<div class="responsive-label">Beds/Rooms</div>
						<div class="responsive-content"><?php echo "{$booking->reservation_footprint} {$booking->resource_priced_per}" . (($booking->reservation_footprint > 1) ? 's' : ''); ?></div>

					</td>
					
					<td>
						<div class="responsive-label">Duration</div>
						<div class="responsive-content"><?php echo duration($booking->reservation_duration); ?></div>

					</td>
					
					<td>
						<div class="responsive-label">Bill</div>
						<div class="responsive-content">&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></div>

					</td>
					
					<td>
						<div class="responsive-content no-label">
							<input type="submit" value="CHECK-IN" name="booking[<?php echo $booking->booking_id; ?>]" class="btn btn-mini btn-success hidden-tablet hidden-phone" />
							<input type="submit" value="CHECK-IN" name="booking[<?php echo $booking->booking_id; ?>]" class="btn btn-block btn-large btn-success hidden-desktop" />
						</div>

					</td>

				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php echo form_hidden(array('redirect' => safe_get_env())); ?>
		</form>
		<?php } ?>

	</div>

	<div class="tab-pane" id="new">
		<?php if(empty($tabs['new'])) { ?>
		<div class="well">
			<h3 class="marker new">No New Bookings</h3>
		</div>

		<?php } else { ?>
		<h1 class="page-header">New Bookings</h1>

		<table class="table table-condensed table-striped table-hover table-responsive">
			<thead class="hidden-tablet hidden-phone">
				<tr>
					<th>Customer Name</th>
					<th>Arrival</th>
					<th>Booking Reference</th>
					<th>Room Booked</th>
					<th class="span1">Guests</th>
					<th>Duration</th>
					<th>Deposit Paid</th>
					<th>Outstanding Bill</th>
				</tr>
			<thead>
			
			<tbody>
				<?php foreach($tabs['new'] as $booking) { ?>
				<tr>
					<td>
						<div class="responsive-label">Customer Name</div>
						<div class="responsive-content"><?php echo $booking->customer_firstname . ' ' . $booking->customer_lastname; ?></div>
					</td>
					
					<td>
						<div class="responsive-label">Arrival</div>
						<div class="responsive-content"><?php echo mysql_to_format($booking->reservation_start_at); ?></div>
					</td>

					<td>
						<div class="responsive-label">Reference</div>
						<div class="responsive-content"><?php echo anchor("admin/bookings/show/{$booking->booking_id}", $booking->booking_reference); ?></div>
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
					
					<td>
						<div class="responsive-label">Deposit Paid</div>
						<div class="responsive-content">&pound;<?php echo as_currency($booking->booking_deposit); ?></div>
					</td>
					
					<td>
						<div class="responsive-label">Outstanding Bill</div>
						<div class="responsive-content">&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

	</div>

	<div class="tab-pane" id="unverified">
		
		<?php if(empty($tabs['unverified'])) { ?>
		<div class="well">
			<h3 class="marker unverified">No Bookings Awaiting Verification</h3>
		</div>
		<?php } else { ?>
		<h1 class="page-header">Bookings Awaiting Verification</h1>

		<table class="table table-condensed table-striped table-hover table-responsive">
			<thead class="hidden-tablet hidden-phone">
				<tr>
					<th>Customer Name</th>
					<th>Arrival</th>
					<th>Booking Reference</th>
					<th>Room Booked</th>
					<th>Guests</th>
					<th>Duration</th>
					<th>Bill</th>
					<th>Time Remaining</th>
				</tr>
			<thead>
			
			<tbody>
				<?php foreach($tabs['unverified'] as $booking) { ?>
				<tr>
					<td>
						<div class="responsive-label">Customer Name</div>
						<div class="responsive-content"><?php echo $booking->customer_firstname . ' ' . $booking->customer_lastname; ?></div>
					</td>
					
					<td>
						<div class="responsive-label">Arrival</div>
						<div class="responsive-content"><?php echo mysql_to_format($booking->reservation_start_at); ?></div>
					</td>

					<td>
						<div class="responsive-label">Reference</div>
						<div class="responsive-content"><?php echo anchor("admin/bookings/show/{$booking->booking_id}", $booking->booking_reference); ?></div>
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
					
					<td>
						<div class="responsive-label">Bill</div>
						<div class="responsive-content">&pound;<?php echo as_currency($booking->booking_price); ?></div>

					</td>
					<td></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

	</div>

	<div class="tab-pane" id="cancelled">
		<?php if(empty($tabs['cancelled'])) { ?>
		<div class="well">
			<h3 class="marker cancelled">No New Cancellations</h3>
		</div>
		<?php } else { ?>
		<h1 class="page-header">New Cancellations</h1>

		<table class="table table-condensed table-striped table-hover table-responsive">
			<thead class="hidden-tablet hidden-phone">
				<tr>
					<th>Customer Name</th>
					<th>Arrival</th>
					<th>Booking Reference</th>
					<th>Room Booked</th>
					<th class="span1">Guests</th>
					<th>Duration</th>
					<th>Deposit Paid</th>
					<th>Date Cancelled</th>
				</tr>
			<thead>
			
			<tbody>
				<?php foreach($tabs['cancelled'] as $booking) { ?>
				<tr>
					<td>
						<div class="responsive-label">Customer Name</div>
						<div class="responsive-content"><?php echo $booking->customer_firstname . ' ' . $booking->customer_lastname; ?></div>
					</td>
					
					<td>
						<div class="responsive-label">Arrival</div>
						<div class="responsive-content"><?php echo mysql_to_format($booking->reservation_start_at); ?></div>
					</td>

					<td>
						<div class="responsive-label">Reference</div>
						<div class="responsive-content"><?php echo anchor("admin/bookings/show/{$booking->booking_id}", $booking->booking_reference); ?></div>
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

					<td>
						<div class="responsive-label">Deposit Paid</div>
						<div class="responsive-content">&pound;<?php echo as_currency($booking->booking_deposit); ?></div>
					</td>
					
					<td>
						<div class="responsive-label">Date Cancelled</div>
						<div class="responsive-content"><?php echo mysql_to_format($booking->booking_deleted_at); ?></div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

	</div>

</div>

<script>
  $(function () {
    $('#dash-tabs a:first').tab('show');
  })
</script>
