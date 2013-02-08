<li class="well well-small media">
	<a class="pull-left" href="#">
		<img class="media-object" data-src="holder.js/64x64">
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Add payment options</h4>

		<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p>

		<a href="#" onclick="$('#payment_form').slideToggle(); return false;">Do this...</a>

		<?php echo form_open('admin/dashboard/wizard', 'class="form-horizontal hide" id="payment_form"'); ?>
			<fieldset>
				<legend>Deposit</legend>

				<div class="control-group">
					<label class="control-label">Deposit</label>
					<div class="controls">
						
						<label class="radio">
							<input type="radio" name="payment" onchange="toggleGateway();" value="none" checked>
							No payment up front
						</label>
						
						<label class="radio">
							<input type="radio" name="payment" onchange="toggleGateway();" value="full">
							Payment in full
						</label>

						<label class="radio">
							<input type="radio" name="payment" onchange="toggleGateway();" value="first">
							First night
						</label>

						<label class="radio">
							<input type="radio" name="payment" onchange="toggleGateway();" value="percent">
							<div class="input-append">
  								<input type="text" class="span1" />
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

			<fieldset id="gateway" class="hide">
				<legend>Payment Gateway</legend>

				<div class="control-group">
					<label class="control-label">PayPal Email Address</label>
					<div class="controls">
						<?php echo form_input(array(
											'name'	=> 'account[account_email]',
											'class'	=> 'span4'
											));
						?>
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