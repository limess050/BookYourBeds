<div id="PayPal" class="gateway_block<?php echo (set_value('setting[payment_gateway]', setting('payment_gateway')) != 'PayPal') ? ' hide' : ''; ?>">
	<div class="control-group">
		<label class="control-label">PayPal Email Address</label>
		<div class="controls">
			<?php echo form_input(array(
								'name'	=> 'setting[paypal_email]',
								'class'	=> 'span4',
								'value'	=> set_value('setting[paypal_email]', setting('paypal_email'))
								));

			?>
			<?php if(ENVIRONMENT != 'production') { ?><span class="help-block alert">For testing purposes use <code>vendor_1360309502_biz@othertribe.com</code></span><?php } ?>

		</div>
	</div>
</div>