

<div class="page-header row">
	<div class="pull-left">
		<h1><?php echo (! is_verified($booking)) ? 'Unverified ' : ''; ?>Booking <small><?php echo anchor('admin/bookings/show/' . $booking->booking_id, $booking->booking_reference); ?></small></h1>
	</div>


	<?php echo $template['partials']['booking_button_group']; ?>

<div class="row">
	<div class="span6" id="guest">
		
	    <h3>Edit Guest Details</h3>

	    <?php echo form_open('admin/bookings/edit/' . $booking->booking_id, 'class="form-horizontal"', array('customer_id' => $booking->customer->customer_id)); ?>
		    <fieldset>

			    <div class="control-group">
			    	<label class="control-label">First Name</label>
			    	<div class="controls">
			    		<input type="text" class="span3" name="customer[customer_firstname]" value="<?php echo set_value('customer[customer_firstname]', $booking->customer->customer_firstname); ?>" />
			    	</div>

			    </div>

			    <div class="control-group">
			    	<label class="control-label">Last Name</label>
			    	<div class="controls">
			    		<input type="text" class="span3" name="customer[customer_lastname]" value="<?php echo set_value('customer[customer_lastname]', $booking->customer->customer_lastname); ?>" />
			    	</div>

			    </div>

			    <div class="control-group">
			    	<label class="control-label">Email Address</label>
			    	<div class="controls">
			    		<input type="text" class="span4" name="customer[customer_email]" value="<?php echo set_value('customer[customer_email]', $booking->customer->customer_email); ?>" />
			    	</div>

			    </div>

			    <div class="control-group">
					<label class="control-label" for="customer_phone">Telephone</label>
					<div class="controls">
						<input type="text" name="customer[customer_phone]" value="<?php echo set_value('customer[customer_phone]', $booking->customer->customer_phone); ?>" id="customer_phone" class="xlarge">
					</div>
				</div>

				<div class="control-group">

					<div class="controls">
						<button type="submit" class="btn btn-warning btn-large">Save Changes</button>
					</div>
				</div>
			</fieldset>

		</form>
  		
	</div>

	


</div>

<hr />

<div class="row">
	<div class="span7">
		<?php echo $template['partials']['booking_overview']; ?>

		<?php echo $template['partials']['booking_supplements']; ?>		
		
	</div>

	<div class="span5">
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

</div>
