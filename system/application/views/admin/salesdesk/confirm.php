<h1 class="page-header">Confirm Booking Details</h1>

<table class="table table-condensed">
	<thead>	
		<tr>
			<th></th>
			<th>Arriving</th>
			<th>Duration</th>
			<th>Guests</th>
		</tr>

	</thead>

	<tbody>
		<?php foreach($resources as $resource) { ?>
		<tr>
			<td><strong><?php echo $resource->account_name; ?></strong><br /><em><?php echo $resource->resource_title; ?></em></td>
			<td><?php echo mysql_to_format($resource->reservation_start_at); ?></td>
			<td><?php echo duration($resource->reservation_duration); ?></td>
			<td><?php echo $booking->booking_guests; ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)</td>
		</tr>
		<?php } ?>
	</tbody>

</table>

<pre>
<?php print_r(booking('supplements')); ?>

<?php echo as_currency(booking('booking_supplement_price')); ?>
</pre>


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




<h3>Total Cost: &pound;<?php echo as_currency($booking->booking_price); ?></h3>
<h2>Pay Now: &pound;<?php echo as_currency($booking->booking_deposit); ?></h2>
		

<?php echo form_open('admin/salesdesk/confirm', array('class' => 'form-horizontal'), array('booking_id' => $booking->booking_id)); ?>


	<div class="control-group">
		<div class="controls">
 			 <button type="submit" class="btn btn-primary">Proceed To Payment</button>&nbsp;
 			 <a class="btn" href="<?php echo site_url('admin/salesdesk/reset'); ?>">Cancel</a>
		</div>
	</div>

</form>



<div class="modal hide" id="myModal"> 
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
    	<h3><?php echo account('name'); ?> Terms and Conditions</h3>
  	</div>
  
  	<div class="modal-body">
    	Foo
  	</div>
  
  	<div class="modal-footer">
    	<a href="#" class="btn btn-primary" data-dismiss="modal">OK</a>
  	</div>
</div>
