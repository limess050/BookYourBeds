

<div class="page-header row">
	<div class="pull-left">
		<h1>Transfer <?php echo (! is_verified($booking)) ? 'Unverified ' : ''; ?>Booking <small><?php echo $booking->booking_reference; ?></small></h1>
	</div>


	<?php echo $template['partials']['booking_button_group']; ?>

</div>

<div class="row">
	<div class="span6">
		<div class="alert alert-danger">
			<h3>Current Booking</h3>

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

			<h4>Customer Details</h4>

			<dl class="dl-horizontal">
	  			<dt>Full Name</dt>
	    		<dd><?php echo $booking->customer->customer_firstname . ' ' . $booking->customer->customer_lastname; ?></dd>

	    		<dt>Email Address</dt>
	    		<dd><?php echo $booking->customer->customer_email; ?></dd>

	    		<dt>Contact Tel.</dt>
	    		<dd><?php echo $booking->customer->customer_phone; ?></dd>
			</dl>

			<h3>Rooms Booked</h3>

			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Room Type</th>
						<th>Quantity</th>
						<th>Guests</th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php foreach($booking->resources as $resource) { ?>
					<tr>
						<td><?php echo $resource->resource_title; ?></td>
						<td><?php echo $resource->reservation_footprint; ?></td>
						<td><?php echo $resource->reservation_guests; ?></td>
						<td>&pound;<?php echo as_currency($resource->reservation_price); ?></td>
						<td><?php echo ($resource->reservation_start_at == date('Y-m-d 00:00:00') && $resource->reservation_checked_in) ? '<span class="label label-success">CHECKED IN</span>' : ''; ?></td>
					</tr>	
					<?php } ?>
				</tbody>
			</table>

			<?php if($booking->has_supplements) { ?>
			<h3>Supplements <small><?php echo anchor('admin/bookings/supplements/' . $booking->booking_id, 'Edit'); ?></small></h3>

			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Supplement</th>
						<th></th>
						<th class="span1">Qty</th>
						<th class="span1">Total Price</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach($booking->resources as $resource) { ?>
					<?php foreach($resource->supplements as $supplement) { ?>
					<tr>
						<td><?php echo $supplement->supplement_short_description; ?></td>
						<td><?php echo $resource->resource_title ?></td>
						<td><?php echo $supplement->stb_quantity; ?></td>
						<td>&pound;<?php echo as_currency($supplement->stb_price); ?></td>
					</tr>
					<?php } ?>
					<?php } ?>
				</tbody>


			</table>


			<?php } ?>







			
		</div>

		
	</div>

	<div class="span6">
		<div class="alert alert-success">
		    <h3>New Booking Details</h3>

		    <dl class="dl-horizontal">
		    	<dt>Arrival</dt>
				<dd><?php echo date('l, j F Y', session('transfer_booking', 'start_at')); ?></dd>

				<dt>Duration</dt>
				<dd><?php echo duration( session('transfer_booking', 'duration')); ?></dd>

				<dt>Total Guests</dt>
				<dd><?php echo session('transfer_booking', 'booking_guests'); ?></dd>	
		    </dl>

		    <h4>Customer Details</h4>

			<dl class="dl-horizontal">
	  			<dt>Full Name</dt>
	    		<dd><?php echo $transfer['customer']['customer_firstname'] . ' ' . $transfer['customer']['customer_lastname']; ?></dd>

	    		<dt>Email Address</dt>
	    		<dd><?php echo $transfer['customer']['customer_email']; ?></dd>

	    		<dt>Contact Tel.</dt>
	    		<dd><?php echo $transfer['customer']['customer_phone']; ?></dd>
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
					<?php foreach(session('transfer_booking', 'resources') as $resource) { ?>
					<tr>
						<td><?php echo $resource['resource_title']; ?></td>
						<td><?php echo ceil($resource['guests'] / $resource['resource_booking_footprint']); ?></td>
						<td><?php echo $resource['guests']; ?></td>
						<td>&pound;<?php echo as_currency(ceil($resource['guests'] / $resource['resource_booking_footprint']) * $resource['resource_single_price']); ?></td>
						
					</tr>	
					<?php } ?>
				</tbody>
			</table>


			<h3>Optional Supplements</h3>
		    
			<?php if( ! empty($transfer['supplements'])) { ?>
			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Supplement</th>
						<th class="span1">Qty</th>
						<th class="span1">Total Price</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach($transfer['supplements'] as $resource) { 
					foreach($resource as $supplement) { ?>
					<tr>
						<td><?php echo $supplement['description']; ?></td>
						<td><?php echo $supplement['qty']; ?></td>
						<td>&pound;<?php echo as_currency($supplement['qty'] * $supplement['price']); ?></td>
					</tr>
					<?php }
					} ?>
				</tbody>


			</table>


			<?php } ?>
	    </div>
	</div>

</div>

<div class="row">
	<div class="span6 offset6">
	<?php echo form_open('admin/transfer/confirm/' . $booking->booking_id,  array('class' => 'form-horizontal'), array('original_deposit' => $booking->booking_deposit)); ?>

<div class="control-group">
	<label class="control-label" for="customer_lastname">New Total Cost</label>
	<div class="controls">
		<div class="input-prepend">
			<span class="add-on">&pound;</span>
			<?php
			echo form_input(array(
								'name'	=> '_booking_deposit',
								'class'	=> 'span2',
								'value'	=> as_currency(session('transfer_booking', 'booking_price')),
								'disabled'	=> 'disabled'
								));
			?>
		</div>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="customer_lastname">New Deposit</label>
	<div class="controls">
		<div class="input-prepend">
			<span class="add-on">&pound;</span>
			<?php
			echo form_input(array(
								'name'	=> '_booking_deposit',
								'class'	=> 'span2',
								'value'	=> as_currency(session('transfer_booking', 'booking_deposit')),
								'disabled'	=> 'disabled'
								));
			?>
		</div>
	</div>
</div>


<?php
// Is the new deposit more than has already been paid?
$pay = 0;
if(session('transfer_booking', 'booking_deposit') > $booking->booking_deposit)
{
	$pay = session('transfer_booking', 'booking_deposit') - $booking->booking_deposit;
} else if(session('transfer_booking', 'booking_price') < $booking->booking_deposit)
{
	// If not, is the new total price less than the deposit already paid?
	$pay = session('transfer_booking', 'booking_price') - $booking->booking_deposit;
}

if($pay >= 0)
{ ?>

<div class="control-group success">
	<label class="control-label" for="customer_lastname">Additional Payment</label>
	<div class="controls">
		<div class="input-prepend">
			<span class="add-on">&pound;</span>
			<?php
			echo form_input(array(
								'name'	=> 'booking_deposit',
								'class'	=> 'span2',
								'value'	=> as_currency($pay)
								));
			echo form_hidden('booking_refund', '0');
			?>
		</div>
	</div>
</div>


<?php } else { ?>
<div class="control-group error">
	<label class="control-label" for="customer_lastname">Refund</label>
	<div class="controls">
		<div class="input-prepend">
			<span class="add-on">&pound;</span>
			<?php
			echo form_input(array(
								'name'	=> 'booking_refund',
								'class'	=> 'span2',
								'value'	=> as_currency(abs($pay))
								));
			echo form_hidden('booking_deposit', '0');
			?>
		</div>
	</div>
</div>

<?php } ?>
<div class="control-group">
	<div class="controls">
		<label>
			<?php echo form_checkbox(array(
										'name'	=> 'email_customer',
										'value'	=> 1,
										'checked'	=> set_checkbox('email_customer', 1, TRUE)
										)); ?>
			Email new booking details to guest

		</label>
	</div>
</div>

		
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-warning btn-large">Finish and Confirm Transfer</button>
			</div>
		</div>

	</form>
</div>

</div>
<!--<pre>
<?php print_r($this->session->userdata('transfer_booking')); ?>
</pre>-->