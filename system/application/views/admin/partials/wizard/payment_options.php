<li class="well well-small media">
	<a class="pull-left" href="#">
		<img src="/assets/img/wizard/payment-icon.png" />
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Add payment options</h4>

		<p>Select appropriate payment settings</p>

		<a href="#" onclick="$('#payment_form').slideToggle(); return false;">Click here...</a>

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
									    'checked'	=> set_radio('setting[deposit]', 'none', ((setting('deposit') == 'none') || ! setting('deposit'))),
										'onclick'	=> 'depositSelected(this);'
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
									    'checked'	=> set_radio('setting[deposit]', 'full', (setting('deposit') == 'full')),
										'onclick'	=> 'depositSelected(this);'
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
									    'checked'	=> set_radio('setting[deposit]', 'first', (setting('deposit') == 'first')),
										'onclick'	=> 'depositSelected(this);'
									    )
									);
				?>
				First night
			</label>

			<!--// Dealing with supplements -->
			<div id="supplement_deposit" style="margin: 0 0 20px 40px; display: none;">
				How would you like to handle <strong>Optional Supplements</strong>:
				<label class="radio">
					<?php echo form_radio(
										array(
										    'name'		=> 'setting[supplement_deposit]',
										    'value'		=> 'none',
										    'checked'	=> set_radio('setting[supplement_deposit]', 'none', (setting('supplement_deposit') == 'none'))
										    )
										);
					?>
					Pay full amount at check-in
				</label>

				<label class="radio">
					<?php echo form_radio(
										array(
										    'name'		=> 'setting[supplement_deposit]',
										    'value'		=> 'full',
										    'checked'	=> set_radio('setting[supplement_deposit]', 'full', (setting('supplement_deposit') == 'full') || ! setting('supplement_deposit'))
										    )
										);
					?>
					Pay full amount up-front
				</label>

				<label class="radio">
					<?php echo form_radio(
										array(
										    'name'		=> 'setting[supplement_deposit]',
										    'value'		=> 'fraction',
										    'checked'	=> set_radio('setting[supplement_deposit]', 'fraction', (setting('supplement_deposit') == 'fraction'))
										    )
										);
					?>
					<div class="input-append">
						<?php  echo form_input(array(
									'name'	=> 'setting[supplement_deposit_percentage]',
									'class'	=> 'span1',
									'value'	=> set_value('setting[supplement_deposit_percentage]', setting('supplement_deposit_percentage'))
									));
						?>
							<span class="add-on">%</span>			
					</div>
					
					 of total supplement price

				</label>

			</div>
			<!-- Dealing with supplements //-->

			<label class="radio">
				<?php echo form_radio(
									array(
									    'name'		=> 'setting[deposit]',
									    'value'		=> 'fraction',
									    'checked'	=> set_radio('setting[deposit]', 'fraction', (setting('deposit') == 'fraction')),
										'onclick'	=> 'depositSelected(this);'
									    )
									);
				?>
				<div class="input-append">
					<?php  echo form_input(array(
								'name'	=> 'setting[deposit_percentage]',
								'class'	=> 'span1',
								'value'	=> set_value('setting[deposit_percentage]', setting('deposit_percentage'))
								));
					?>
						<span class="add-on">%</span>			
				</div>
				
				 of full price

			</label>
		</div>
	</div>

	
</fieldset>

<fieldset>
	<legend>Balance</legend>

	<div class="control-group">
		<label class="control-label">Balance is due</label>
		<div class="controls">
			<?php echo form_dropdown('setting[balance_due]', array('checkin' => 'at check-in', 'checkout' => 'at check-out'), set_value('setting[balance_due]', setting('balance_due')), 'class="span2"'); ?>
		</div>
	</div>

	
</fieldset>

<script type="text/javascript">
<!--
	function depositSelected(elem)
	{
		if($(elem).val() == 'first')
		{
			$('#supplement_deposit').slideDown();
		} else
		{
			$('#supplement_deposit').slideUp();
		}

		if($(elem).val() == 'none')
		{
			$('select[name="setting[payment_gateway]"]').val('NoGateway');
			toggleGateway($('select[name="setting[payment_gateway]"]'));
		}
	}

	function toggleGateway(elem)
	{
		$('div.gateway_block').each(function() {
			$(this).addClass('hide');
		});

		$('div#' + $(elem).val()).removeClass('hide');

		if($(elem).val() == 'NoGateway')
		{
			$('input[name="setting[deposit]"][value="none"]').attr('checked', 'checked');
		} 
	}
-->
</script>

<fieldset id="gateway">
	<legend>Payment Gateway</legend>

	<div class="control-group">
		<label class="control-label">Payment Gateway</label>
		<div class="controls">
			<?php echo form_dropdown('setting[payment_gateway]', 
									$this->config->item('supported_gateways'), 
									set_value('setting[payment_gateway]', setting('payment_gateway')), 
									'class="span3" onchange="toggleGateway(this);"'); ?>
			

		</div>
	</div>

	<?php foreach($this->config->item('supported_gateways') as $key => $val) { 
		echo $template['partials'][$key];
	} ?>

</fieldset>

			<div class="control-group">
				
				<div class="controls">
					<button type="submit" class="btn btn-warning btn-large">Save Changes</button>
				</div>
			</div>

		</form>	
	</div>
</li>