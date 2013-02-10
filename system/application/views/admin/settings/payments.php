<h1 class="page-header">Payment Options</h1>

<?php echo form_open('admin/settings/payments', 'class="form-horizontal"'); ?>
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
									    'checked'	=> set_radio('setting[deposit]', 'none', (setting('deposit') == 'none'))
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
									    'checked'	=> set_radio('setting[deposit]', 'full', (setting('deposit') == 'full'))
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
									    'checked'	=> set_radio('setting[deposit]', 'first', (setting('deposit') == 'first'))
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
									    'checked'	=> set_radio('setting[deposit]', 'fraction', (setting('deposit') == 'fraction'))
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

<script type="text/javascript">
<!--
	function toggleGateway(elem)
	{
		$('div.gateway_block').each(function() {
			$(this).addClass('hide');
		});

		$('div#' + $(elem).val()).removeClass('hide');
	}
-->
</script>

<fieldset id="gateway" class="<?php echo (set_value('setting[deposit]', setting('deposit')) == null || set_value('setting[deposit]', setting('deposit')) == 'none') ? 'hide' : ''; ?>">
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
		//echo $template['partials'][$key];
		$this->load->view('admin/partials/gateways/' . strtolower($key));
	} ?>

</fieldset>

<div class="control-group">
	
	<div class="controls">
		<button type="submit" class="btn btn-primary">Save Changes</button>
	</div>
</div>

</form>	