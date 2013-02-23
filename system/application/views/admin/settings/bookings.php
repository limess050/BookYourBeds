<h1 class="page-header">Booking Settings</h1>

<?php echo form_open('admin/settings/bookings', 'class="form-horizontal"'); ?>
	<?php echo $template['partials']['form_errors']; ?>

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


	
		<div class="control-group">
		
		<div class="controls">
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

</form>
