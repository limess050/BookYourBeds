<li class="well well-small media">
	<a class="pull-left" href="#">
		<img class="media-object" data-src="holder.js/64x64">
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Add payment options</h4>

		<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p>

		<a href="#" onclick="$('#payment_form').slideToggle(); return false;">Do this...</a>

		<?php echo form_open('admin/dashboard/wizard', 'class="form-horizontal' . ((empty($_payment_options_open)) ? ' hide' : '') . '" id="payment_form"', array('_form' => 'payment_options')); ?>
			<?php echo $template['partials']['form_errors']; ?>
			<fieldset>
				<legend>Deposit</legend>

				<div class="control-group">
					<label class="control-label">Deposit</label>
					<div class="controls">
						<label class="radio">
							<?php echo form_radio(
												array(
												    'name'		=> 'setting[deposit]',
												    'value'		=> 'none',
												    'checked'	=> set_radio('setting[deposit]', 'none', TRUE),
												    'onchange'	=> 'toggleGateway();'
												    )
												);
							?>
							No payment up front
						</label>
						
						<label class="radio">
							<?php echo form_radio(
												array(
												    'name'		=> 'setting[deposit]',
												    'value'		=> 'full',
												    'checked'	=> set_radio('setting[deposit]', 'full'),
												    'onchange'	=> 'toggleGateway();'
												    )
												);
							?>
							Payment in full
						</label>

						<label class="radio">
							<?php echo form_radio(
												array(
												    'name'		=> 'setting[deposit]',
												    'value'		=> 'first',
												    'checked'	=> set_radio('setting[deposit]', 'first'),
												    'onchange'	=> 'toggleGateway();'
												    )
												);
							?>
							First night
						</label>

						<label class="radio">
							<?php echo form_radio(
												array(
												    'name'		=> 'setting[deposit]',
												    'value'		=> 'fraction',
												    'checked'	=> set_radio('setting[deposit]', 'fraction'),
												    'onchange'	=> 'toggleGateway();'
												    )
												);
							?>
							<div class="input-append">
								<?php  echo form_input(array(
											'name'	=> 'setting[deposit_percentage]',
											'class'	=> 'span1',
											'value'	=> set_value('setting[deposit_percentage]')
											));
								?>
  								<span class="add-on">%</span>			
							</div>
							
							 of full price

						</label>
					</div>
				</div>

				
			</fieldset>

			<script type="text/javascript">
			<!--
				function toggleGateway()
				{
					if($('input[name="payment"]:checked').val() == 'none')
					{
						$('#gateway').slideUp();
					} else
					{
						$('#gateway').slideDown();
					}
				}
			-->
			</script>

			<fieldset id="gateway" class="<?php echo (set_value('setting[deposit]') == null || set_value('setting[deposit]') == 'none') ? 'hide' : ''; ?>">
				<legend>Payment Gateway</legend>

				<div class="control-group">
					<label class="control-label">PayPal Email Address</label>
					<div class="controls">
						<?php echo form_input(array(
											'name'	=> 'setting[paypal_email]',
											'class'	=> 'span4',
											'value'	=> set_value('setting[paypal_email]')
											));

						echo form_hidden('setting[payment_gateway]', 'paypal');
						?>
						<span class="help-block alert">For testing purposes use <code>vendor_1360309502_biz@othertribe.com</code></span>
					</div>
				</div>

			</fieldset>

			<div class="control-group">
				
				<div class="controls">
					<button type="submit" class="btn btn-primary">Save Changes</button>
				</div>
			</div>

		</form>	
	</div>
</li>