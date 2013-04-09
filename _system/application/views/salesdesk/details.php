<?php echo $template['partials']['cart']; 

?>

	<h1 class="page-header">Guest Details</h1>

	<?php echo $template['partials']['form_errors']; ?>

	<?php echo form_open('salesdesk/details', array('class' => 'form-horizontal')); ?>

	<fieldset>
		<div class="control-group">
			<label class="control-label" for="customer_firstname">First Name</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'customer[customer_firstname]',
					'value'	=> set_value('customer[customer_firstname]', booking('customer', 'customer_firstname')),
					'class'	=> 'span3',
					'id'	=> 'customer_firstname'
					));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="customer_lastname">Last Name</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'customer[customer_lastname]',
					'value'	=> set_value('customer[customer_lastname]', booking('customer', 'customer_lastname')),
					'class'	=> 'span3',
					'id'	=> 'customer_lastname'
					));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="customer_email">Email Address</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'customer[customer_email]',
					'value'	=> set_value('customer[customer_email]', booking('customer', 'customer_email')),
					'class'	=> 'span4',
					'id'	=> 'customer_email'
					));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="emailconf">Confirm Email Address</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'emailconf',
					'value'	=> set_value('emailconf', booking('emailconf')),
					'class'	=> 'span4',
					'id'	=> 'emailconf'
					));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="customer_phone">Contact Telephone</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'customer[customer_phone]',
					'value'	=> set_value('customer[customer_phone]', booking('customer', 'customer_phone')),
					'class'	=> 'span3',
					'id'	=> 'customer_phone'
					));
				?>
			</div>
		</div>

		<div class="control-group">
			<label></label>
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" name="customer[customer_accepts_marketing]" value="1" 
					<?php echo set_checkbox('customer[customer_accepts_marketing]', 1,
					booking('customer', 'customer_accepts_marketing') == 1); ?> />
					Would you like to receive the occasional email from <strong><?php echo account('name'); ?></strong> with news and promotions?
				</label>
			
			</div>
		</div>
		
		<div class="control-group">

			<div class="controls">
				<button type="submit" class="btn btn-primary">Continue</button>
			</div>
		</div>
	</fieldset>

	</form>

