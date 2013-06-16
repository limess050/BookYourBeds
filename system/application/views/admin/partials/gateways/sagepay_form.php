<div id="SagePay_Form" class="gateway_block<?php echo (set_value('setting[payment_gateway]', setting('payment_gateway')) != 'SagePay_Form') ? ' hide' : ''; ?>">
	<div class="control-group">
		<label class="control-label">SagePay Vendor ID</label>
		<div class="controls">
			<?php echo form_input(array(
								'name'	=> 'setting[sagepay_form_vendor_id]',
								'class'	=> 'span2',
								'value'	=> set_value('setting[sagepay_form_vendor_id]', setting('sagepay_form_vendor_id'))
								));

			?> 
			<?php if(ENVIRONMENT != 'production') { ?><span class="help-block alert">For testing purposes use <code>applecart</code></span><?php } ?>

		</div>
	</div>

	<div class="control-group">
		<label class="control-label">SagePay Crypt</label>
		<div class="controls">
			<?php echo form_input(array(
								'name'	=> 'setting[sagepay_form_crypt]',
								'class'	=> 'span3',
								'value'	=> set_value('setting[sagepay_form_crypt]', setting('sagepay_form_crypt'))
								));

				echo form_hidden('setting[sagepay_form_encryption_type]', 'AES');

			?>
			<?php if(ENVIRONMENT != 'production') { ?><span class="help-block alert">For testing purposes use <code>oG1PDrzXanmXe5JE</code></span><?php } ?>

		</div>
	</div>

</div>