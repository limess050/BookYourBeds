<h1 class="page-header">Confirm Booking Details</h1>

<h3>Accommodation Details <small><?php echo anchor('admin/salesdesk/index', 'Edit'); ?></small></h3>
<table class="table table-condensed">
	<thead>	
		<tr>
			<th></th>
			<th>Arriving</th>
			<th>Duration</th>
			<th>Guests</th>
			<th class="span2">Price</th>
		</tr>

	</thead>

	<tbody>
		<?php foreach($resources as $resource) { ?>
		<tr>
			<td><strong><?php echo $resource->resource_title; ?></strong></td>
			<td><?php echo mysql_to_format($resource->reservation_start_at); ?></td>
			<td><?php echo duration($resource->reservation_duration); ?></td>
			<td><?php echo $booking->booking_guests; ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)</td>
			<td><strong>&pound;<?php echo as_currency($booking->booking_room_price); ?></strong></td>
		</tr>
		<?php } ?>
	</tbody>

</table>

<?php if(booking('supplements')) { ?>
<h3>Optional Supplements <small><?php echo anchor('admin/salesdesk/supplements', 'Edit'); ?></small></h3>
<table class="table table-condensed">
	<thead>	
		<tr>
			<th></th>
			<th>Quantity</th>
			<th>Price</th>
			<th class="span2">Total</th>
		</tr>

	</thead>

	<tbody>
		<?php foreach(booking('supplements') as $supplement) { ?>
		<tr>
			<td><?php echo $supplement['description']; ?></td>
			<td><?php echo $supplement['qty']; ?></td>
			<td>&pound;<?php echo as_currency($supplement['price']); ?></td>
			<td><strong>&pound;<?php echo as_currency($supplement['price'] * $supplement['qty']); ?></strong></td>
		</tr>
		<?php } ?>
	</tbody>

</table>
<?php } ?>

<h3>Grand Total</h3>
<table class="table table-condensed">

	<tbody>
		<tr>
			<td>Total Due</td>
			<td class="span2"><strong>&pound;<?php echo as_currency($booking->booking_price); ?></strong></td>
		</tr>

		<?php if ($booking->booking_deposit) { ?>
		<tr class="success">
			<td>Payable Now</td>
			<td class="span2"><strong>&pound;<?php echo as_currency($booking->booking_deposit); ?></strong></td>
		</tr>
		<?php } ?>

		<tr class="error">
			<td>Balance due at <?php echo setting('balance_due'); ?></td>
			<td class="span2"><strong>&pound;<?php echo as_currency($booking->booking_price - $booking->booking_deposit); ?></strong></td>
		</tr>
	</tbody>

</table>

<h3>Primary Guest Details <small><?php echo anchor('admin/salesdesk/details', 'Edit'); ?></small></h3>

<dl class="dl-horizontal">
	<dt>Full Name</dt>
	<dd><?php echo $customer['customer_firstname'] . ' ' . $customer['customer_lastname']; ?></dd>

	<dt>Email Address</dt>
	<dd><?php echo $customer['customer_email']; ?></dd>

	<dt>Contact Telephone</dt>
	<dd><?php echo $customer['customer_phone']; ?></dd>

	<dt>Accept Marketing</dt>
	<dd><?php echo ( ! empty($customer['customer_accepts_marketing'])) ? 'Yes' : 'No'; ?></dd>
</dl>
		
<?php echo form_open('admin/salesdesk/confirm', array('class' => 'form-horizontal'), array('booking_id' => $booking->booking_id)); ?>


	<div class="control-group">
		<div class="controls">
 			 <button type="submit" class="btn btn-primary">Take Payment and Process Booking</button>&nbsp;
 			 <a href="<?php echo site_url('salesdesk/reset'); ?>" onclick="return confirm('Are you sure you want to cancel this booking?');" class="btn">Cancel</a>
		</div>
	</div>

</form>



