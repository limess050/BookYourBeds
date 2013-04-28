

<div class="page-header row">
	<div class="pull-left">
		<h1><?php echo (! is_verified($booking)) ? 'Unverified ' : ''; ?>Booking <small><?php echo anchor('admin/bookings/show/' . $booking->booking_id, $booking->booking_reference); ?></small></h1>
	</div>


	<?php echo $template['partials']['booking_button_group']; ?>


<div class="row">

	<div class="span10">
		<h3>Edit Supplements</h3>


		<!--<pre>
		<?php print_r($_supplements); ?>
		</pre>-->

		<?php echo $template['partials']['form_errors']; ?>

		<?php echo form_open('admin/bookings/supplements/' . $booking->booking_id, array('class' => 'form-horizontal')); ?>

		<table class="table">
		<?php 

		foreach($supplements as $supplement) { ?>
		<tr>
			<td><h4><?php echo $supplement->supplement_short_description; ?> <small>&pound;<?php echo as_currency($supplement->resource_price) . ' ' .
																									(($supplement->supplement_per_guest) ? 'per person' : 'per ' . $resources[0]->resource_priced_per) . ' ' .
																									(($supplement->supplement_per_day) ? 'per night' : 'per stay') ; ?></small></h4>

				<?php echo auto_typography($supplement->supplement_long_description); ?>
			</td>
			
			<td class="span4">
				<?php
				// The total number of options
				$opt_count = ($supplement->supplement_per_guest) ? $booking->booking_guests : $resources[0]->reservation_footprint;
				$multiply = ($supplement->supplement_per_day) ? $resources[0]->reservation_duration : 1;

				$options = array(
								0	=> '0'
								);

				for($i = 1; $i <= $opt_count; $i++)
				{
					$options[$i] = $i . ' ' . (($supplement->supplement_per_guest) ? 'person' : $resources[0]->resource_priced_per) . (($i > 1) ? 's' : '') . ' - &pound;' . as_currency($supplement->resource_price * $multiply * $i);
				}

				echo form_dropdown("supplements[{$supplement->supplement_id}][qty]", 
									$options, 
									set_value("supplements[{$supplement->supplement_id}][qty]", ( ! empty($_supplements[$supplement->supplement_id])) ? $_supplements[$supplement->supplement_id]->stb_quantity : 0), 
									'class="span3"');
				
				echo form_hidden(array(
									"supplements[{$supplement->supplement_id}][price]" => ($supplement->resource_price * $multiply),
									"supplements[{$supplement->supplement_id}][description]" => $supplement->supplement_short_description
									));
				?>

			</td>
		</tr>


		<?php } ?>
		</table>

		<button type="submit" class="btn btn-large btn-warning">Update Supplements</button>

		</form>
	</div>

</div>
<hr />

<div class="row">
	<div class="span7">
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

				<?php foreach($booking->resources as $resource) { ?>
				<dt>Room</dt>
				<dd><?php echo $resource->resource_title; ?></dd>
				
				<dt>Arrival</dt>
				<dd>
					<?php 
					echo anchor('admin/bookings?timestamp=' . human_to_unix($resource->reservation_start_at), mysql_to_format($resource->reservation_start_at, 'l, j F Y')); 
					echo ($resource->reservation_start_at == date('Y-m-d 00:00:00') && $resource->reservation_checked_in) ? ' <span class="label label-success">CHECKED IN</span>' : '';
					?>
				</dd>

				<dt>Duration</dt>
				<dd><?php echo duration($resource->reservation_duration); ?></dd>

				<dt>Total Guests</dt>
				<dd><?php echo "{$booking->booking_guests} guest" . (($booking->booking_guests > 1) ? 's' : ''); ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)</dd>				
				<?php } ?>
			
				<dt>Payment Outstanding</dt>
				<dd>&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></dd>
			</dl>
		</div>		
	</div>

	<div class="span5">
	    <h3>Guest Details <small><?php echo anchor('admin/bookings/edit/' . $booking->booking_id, 'Edit'); ?></small></h3>

  		<dl class="dl-horizontal">
  			<dt>Full Name</dt>
    		<dd><?php echo $booking->customer->customer_firstname . ' ' . $booking->customer->customer_lastname; ?></dd>

    		<dt>Email Address</dt>
    		<dd><?php echo mailto($booking->customer->customer_email . '?subject=Your Booking: ' . $booking->booking_reference, $booking->customer->customer_email); ?></dd>

    		<dt>Contact Tel.</dt>
    		<dd><?php echo $booking->customer->customer_phone; ?></dd>
		</dl>
	</div>

</div>

<hr />

<div class="row">
	<div class="span6">
		<h3>Booking Data</h3>

		<dl class="dl-horizontal">
			<dt>Total Cost</dt>
    		<dd>&pound;<?php echo as_currency($booking->booking_price); ?></dt>
			
			<dt>Deposit Paid</dt>
    		<dd>&pound;<?php echo as_currency($booking->booking_deposit); ?></dt>
			
			<dt>Payment Outstanding</dt>
    		<dd>&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></dt>

  			<dt>Booking Date</dt>
    		<dd><?php echo mysql_to_format($booking->booking_created_at, 'l, j F Y \a\t H:i'); ?></dd>

    		<!--<dt>Booked By</dt>
    		<dd>
    			Internet booking
    		</dd>
    		<dd><em>MacBackpackers website</em></dd>-->
    		
    	</dl>
	</div>

	<?php if( ! empty($previous)) { ?>
	<div class="span6">
		<h3>Previous Versions</h3>

		<table class="table table-condensed table-striped">
			<thead>
				<tr>
					<th>Room</th>
					<th>Arrival</th>
					<th>Guests</th>
					<th>Duration</th>
				</tr>

			</thead>

			<tbody>
			<?php foreach($previous as $b) { ?>
				<tr>
					<td><?php echo anchor('admin/bookings/show/' . $b->booking_id, $b->resources[0]->resource_title); ?></td>
					<td><?php echo mysql_to_format($b->resources[0]->reservation_start_at); ?></td>
					<td><?php echo $b->booking_guests; ?></td>
					<td><?php echo duration($b->resources[0]->reservation_duration); ?></td>
				</tr>
			<?php } ?>
			<tbody>
		</table>
		
	</div>
	<?php } ?>

	


</div>