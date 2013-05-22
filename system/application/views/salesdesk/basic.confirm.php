<?php echo form_open('salesdesk/confirm', array('class' => 'form-horizontal')); ?>

<h3>Accommodation Details <small><?php echo anchor('salesdesk/index', 'Edit'); ?></small></h3>
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

		<?php 
		$room_deposit = 0;

		if(setting('deposit') != 'none') { 

		switch(setting('deposit'))
		{
			case 'full':
				$title = 'Payment Required in Full';
				$room_deposit = booking('booking_room_price');
				break;

			case 'first':
				$title = 'First Night as deposit';
				$room_deposit = booking('booking_first_night_price');
				break;

			case 'fraction':
				$title =  setting('deposit_percentage') . '% as deposit';
				$room_deposit = (setting('deposit_percentage') / 100) * booking('booking_room_price');
				break;
		}

		?>
		<tr>
			<td colspan="4"><?php echo $title; ?></td>
			<td>&pound;<?php echo as_currency($room_deposit); ?></td>
		</tr>
		<?php } ?>
	</tbody>

</table>

<?php
$supplement_deposit = 0;
if(booking('supplements')) { ?>
<h3>Optional Supplements <small><?php echo anchor('salesdesk/supplements', 'Edit'); ?></small></h3>
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
		<?php } 

		if(setting('deposit') != 'none' && setting('supplement_deposit') != 'none') 
		{ 

			switch(setting('deposit'))
			{
				case 'full':
					$title = 'Payment Required in Full';
					$supplement_deposit = booking('booking_supplement_price');
					break;

				case 'first':
					switch(setting('supplement_deposit'))
					{
						case 'fraction':
							$title =  setting('supplement_deposit_percentage') . '% as deposit';
							$supplement_deposit = (setting('supplement_deposit_percentage') / 100) * booking('booking_supplement_price');
							break;

						default:
							$title = 'Payment Required in Full';
							$supplement_deposit = booking('booking_supplement_price');
							break;
					}
					break;

				case 'fraction':
					$title =  setting('deposit_percentage') . '% as deposit';
					$supplement_deposit = (setting('deposit_percentage') / 100) * booking('booking_supplement_price');
					break;
			}
		?>
		<tr>
			<td colspan="3"><?php echo $title; ?></td>
			<td>&pound;<?php echo as_currency($supplement_deposit); ?></td>
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

		<?php if ($room_deposit + $supplement_deposit > 0) { ?>
		<tr class="success">
			<td>Payable Now</td>
			<td class="span2">
				<strong>&pound;<?php echo as_currency($room_deposit + $supplement_deposit); ?></strong>
				<?php echo form_hidden('booking_deposit', $room_deposit + $supplement_deposit); ?>
			</td>
		</tr>
		<?php } ?>

		<tr class="error">
			<td>Balance due at <?php echo setting('balance_due'); ?></td>
			<td class="span2"><strong>&pound;<?php echo as_currency($booking->booking_price - $room_deposit - $supplement_deposit); ?></strong></td>
		</tr>
	</tbody>

</table>

<h3>Primary Guest Details <small><?php echo anchor('salesdesk/details', 'Edit'); ?></small></h3>

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
		
<h2 class="page-header">Billing Information</h2>


<?php echo $template['partials']['form_errors']; ?>

	<fieldset>

		<div class="control-group">
			<label class="control-label" for="billing_firstname">First Name</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'billing[firstname]',
					'id'	=> 'billing_firstname',
					'class'	=> 'span3',
					'value'	=> set_value('billing[firstname]', $customer['customer_firstname'])
					));
				?> *
			</div>
		</div> <!-- /clearfix -->

		<div class="control-group">
			<label class="control-label" for="billing_lastname">Last Name</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'billing[lastname]',
					'id'	=> 'billing_lastname',
					'class'	=> 'span3',
					'value'	=> set_value('billing[lastname]', $customer['customer_lastname'])
					));
				?> *
			</div>
		</div> <!-- /clearfix -->

		<div class="control-group">
			<label class="control-label" for="billing_email">Email</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'billing[email]',
					'id'	=> 'billing_email',
					'class'	=> 'span4',
					'value'	=> set_value('billing[email]', $customer['customer_email'])
					));
				?> *
			</div>
		</div> <!-- /clearfix -->

		<div class="control-group">
			<label class="control-label" for="billing_country">Country</label>
			<div class="controls">
				<?php 
            	echo country_dropdown('billing[country]', 
            							set_value('billing[country]'), 
            							'class="span3" onchange="checkUS(this);"');
            	?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="billing_address1">Address 1</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'billing[address1]',
					'id'	=> 'billing_address1',
					'class'	=> 'span4',
					'value'	=> set_value('billing[address1]')
					));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="billing_address2">Address 2</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'billing[address2]',
					'id'	=> 'billing_address2',
					'class'	=> 'span4',
					'value'	=> set_value('billing[address2]')
					));
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="billing_city">Town/City</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'billing[city]',
					'id'	=> 'billing_city',
					'class'	=> 'span3',
					'value'	=> set_value('billing[city]')
					));
				?> *
			</div>
		</div>

		<div class="control-group" <?php echo (set_value('billing[country]') != 'US') ? "style=\"display: none;\"" : ''; ?> id="us_states">
        	<label class="control-label">State</label>
        	<div class="controls">
            	<?php 
            	echo us_state_dropdown('billing[state]', 
            							set_value('billing[state]'),
            							'class="span3"');
            	?> *
            </div>
        </div>

		<div class="control-group">
			<label class="control-label" for="billing_postcode">Postal/ZIP Code</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'billing[postcode]',
					'id'	=> 'billing_postcode',
					'class'	=> 'span2',
					'value'	=> set_value('billing[postcode]')
					));
				?> *
			</div>
		</div>

		<?php if(setting('terms_and_conditions')) { ?>
		<div class="control-group">
           
            
            <div class="controls">
            	<div class="alert alert-success">
            		<strong>Important:</strong> By clicking 'Proceed' below you are agreeing to the <?php echo account('name'); ?> <a data-toggle="modal" href="#myModal" >Terms and Conditions</a>.
				</div>

            </div>
        </div>

        <div class="modal hide" id="myModal"> 
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
		    	<h3><?php echo account('name'); ?> Terms and Conditions</h3>
		  	</div>
		  
		  	<div class="modal-body">
		    	<?php echo auto_typography(setting('terms_and_conditions')); ?>
		  	</div>
		  
		  	<div class="modal-footer">
		    	<a href="#" class="btn btn-primary" data-dismiss="modal">OK</a>
		  	</div>
		</div>



        <?php } ?>
		
	</fieldset>

	<div class="control-group">
		<div class="controls">
 			 <button type="submit" class="btn btn-primary">Proceed<?php echo (setting('payment_gateway') != 'NoGateway') ? ' To Payment' : ''; ?></button>&nbsp;
 			 <a href="<?php echo site_url('salesdesk/reset'); ?>" onclick="return confirm('Are you sure you want to cancel this booking?');" class="btn">Cancel</a>
		</div>
	</div>

</form>
