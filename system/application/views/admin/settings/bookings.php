<h1 class="page-header">Settings</h1>

<?php echo $template['partials']['settings_menu']; ?>

<h2>Booking Settings</h2>

<?php echo form_open('admin/settings/bookings', 'class="form-horizontal"'); ?>
	<?php echo $template['partials']['form_errors']; ?>

	<fieldset>
		<legend>Release Limits</legend>

		<div class="control-group">
			<label class="control-label">Only release beds after</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'setting[availability_limit_start_at]',
									'class'	=> 'span2',
									'value'	=> set_value('setting[availability_limit_start_at]', setting('availability_limit_start_at')),
									'placeholder'	=> 'dd/mm/yyyy',
									'id'	=> 'start_datepicker'
									));
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Release beds until</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'setting[availability_limit_end_at]',
									'class'	=> 'span2',
									'value'	=> set_value('setting[availability_limit_end_at]', setting('availability_limit_end_at')),
									'placeholder'	=> 'dd/mm/yyyy',
									'id'	=> 'end_datepicker'
									));
				?>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>Customer Information</legend>
				<div class="control-group">
					<label class="control-label">Terms and Conditions</label>
					<div class="controls">
						<?php echo form_textarea(array(
											'name'	=> 'setting[terms_and_conditions]',
											'class'	=> 'span4',
											'rows'	=> 4,
											'value'	=> set_value('setting[terms_and_conditions]', setting('terms_and_conditions'))
											));
						?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Additional Booking Information</label>
					<div class="controls">
						<?php echo form_textarea(array(
											'name'	=> 'setting[booking_instructions]',
											'class'	=> 'span4',
											'rows'	=> 4,
											'value'	=> set_value('setting[booking_instructions]', setting('booking_instructions'))
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

<!-- start: page-specific javascript -->
<script type="text/javascript">
<!--
	$(function(){
			
		$('#start_datepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			firstDay: 1,
			dateFormat: 'dd/mm/yy',
			showAnim: 'fadeIn',
			showOn: 'button',
			buttonText: '<i class="icon-calendar"></i>',
			onClose: 
			function(dateText, inst) 
			{ 
				var d = dateText.split('/'); 
				var ts = $.datepicker.formatDate('@', new Date(d[2], d[1] - 1, d[0]));
				var ds = 60 * 60 * 24 * 1000;
				
				var min_d = new Date();
				min_d.setTime(Number(ts) + (Number(ds) * 1));
				
				$('#end_datepicker').datepicker('option', 'minDate', min_d);
			}
		});
		
		$('#end_datepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			firstDay: 1,
			dateFormat: 'dd/mm/yy',
			showAnim: 'fadeIn',
			showOn: 'button',
			buttonText: '<i class="icon-calendar"></i>'
		});
		
		
	});
	

-->
</script>
<!-- end: page-specific javascript -->
