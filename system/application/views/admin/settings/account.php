<h1 class="page-header">Account Settings</h1>

<?php echo form_open('admin/settings/account', 'class="form-horizontal"'); ?>
	<?php echo $template['partials']['form_errors']; ?>

	<fieldset>
		<legend>The Essentials</legend>

		<div class="control-group">
			<label class="control-label">Account Name</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'account[account_name]',
									'class'	=> 'span4',
									'value'	=> set_value('account[account_name]', account('name'))
									));
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Account URL</label>
			<div class="controls">
				<?php echo site_url('', FALSE); ?>
					<?php echo form_input(array(
									'name'	=> 'account[account_slug]',
									'class'	=> 'span2',
									'value'	=> set_value('account[account_slug]', account('slug'))
									));
					?>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>Contact Details</legend>

		<div class="control-group">
			<label class="control-label">Account Email</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'account[account_email]',
									'class'	=> 'span4',
									'value'	=> set_value('account[account_email]', account('email'))
									));
				?>
			</div>
		</div>


		<div class="control-group">
			<label class="control-label">Contact Telephone</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'account[account_phone]',
									'class'	=> 'span4',
									'value'	=> set_value('account[account_phone]', account('phone'))
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