

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

				<?php foreach($booking->resources as $resource) { ?>
				<dt>Room</dt>
				<dd><?php echo $resource->resource_title; ?></dd>
				
				<dt>Arrival</dt>
				<dd>
					<?php 
					echo mysql_to_format($resource->reservation_start_at, 'l, j F Y'); 
					?>
				</dd>

				<dt>Duration</dt>
				<dd><?php echo duration($resource->reservation_duration); ?></dd>

				<dt>Total Guests</dt>
				<dd><?php echo "{$booking->booking_guests} guest" . (($booking->booking_guests > 1) ? 's' : ''); ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)</dd>				
				<?php } ?>
			
				<dt>Total Cost</dt>
				<dd>&pound;<?php echo as_currency($booking->booking_price); ?></dd>

				<dt>Deposit Paid</dt>
				<dd>&pound;<?php echo as_currency($booking->booking_deposit); ?></dd>
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

			<h4>Optional Supplements</h4>
			<?php if( ! empty($booking->supplements)) { ?>
			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Supplement</th>
						<th class="span1">Qty</th>
						<th class="span1">Price</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach($booking->supplements as $supplement) { ?>
					<tr>
						<td><?php echo $supplement->supplement_short_description; ?></td>
						<td><?php echo $supplement->stb_quantity; ?></td>
						<td>&pound;<?php echo as_currency($supplement->stb_price); ?></td>
					</tr>
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
				<dt>Room</dt>
				<dd><?php echo $new_resource->resource_title; ?></dd>
				
				<dt>Arrival</dt>
				<dd><?php echo date('l, j F Y', $transfer['start_at']); ?></dd>

				<dt>Duration</dt>
				<dd><?php echo duration($transfer['duration']); ?></dd>

				<dt>Total Guests</dt>
				<dd><?php echo $transfer['booking_guests'] . " guest" . (($transfer['booking_guests'] > 1) ? 's' : ''); ?> (<?php echo $transfer['footprint'] . " {$new_resource->resource_priced_per}" . (($transfer['footprint'] > 1) ? 's' : ''); ?>)</dd>				
			
				<dt>Total Cost</dt>
				<dd>&pound;<?php echo as_currency($transfer['booking_price']); ?></dd>

				<dt>New Deposit</dt>
				<dd>&pound;<?php echo as_currency($transfer['booking_deposit']); ?></dd>
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
	    	
	    	<h4>Optional Supplements</h4>
			<?php if( ! empty($transfer['supplements'])) { ?>
			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Supplement</th>
						<th class="span1">Qty</th>
						<th class="span1">Price</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach($transfer['supplements'] as $supplement) { ?>
					<tr>
						<td><?php echo $supplement['description']; ?></td>
						<td><?php echo $supplement['qty']; ?></td>
						<td>&pound;<?php echo as_currency($supplement['qty'] * $supplement['price']); ?></td>
					</tr>
					<?php } ?>
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
				<button type="submit" class="btn btn-primary">Finish and Confirm Transfer</button>
			</div>
		</div>

	</form>
</div>

</div>
<!--<pre>
<?php print_r($this->session->userdata('transfer_booking')); ?>
</pre>-->