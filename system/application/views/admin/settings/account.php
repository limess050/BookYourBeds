<h1 class="page-header">Settings</h1>

<?php echo $template['partials']['settings_menu']; ?>

<h2>Account Settings</h2>

<p>* Required information</p>

<?php echo form_open_multipart('admin/settings/account', 'class="form-horizontal"'); ?>
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
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Account URL</label>
			<div class="controls">
				<div class="input-prepend">
  					<span class="add-on"><?php echo site_url('', FALSE); ?></span>
					<?php echo form_input(array(
									'name'	=> 'account[account_slug]',
									'class'	=> 'span3',
									'value'	=> set_value('account[account_slug]', account('slug'))
									));
					?> *
				</div>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Account Email</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'account[account_email]',
									'class'	=> 'span4',
									'value'	=> set_value('account[account_email]', account('email'))
									));
				?> *
				<span class="help-block"><i class="icon-info-sign"></i> This is the email that BookYourBeds will contact you on and send your booking confirmations to. This is also the email that your customers will contact you on.</span>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>Contact Details</legend>

		<div class="control-group">
			<label class="control-label">Address 1</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'account[account_address1]',
									'class'	=> 'span4',
									'value'	=> set_value('account[account_address1]', account('address1'))
									));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Address 2</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'account[account_address2]',
									'class'	=> 'span4',
									'value'	=> set_value('account[account_address2]', account('address2'))
									));
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Town/City</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'account[account_city]',
									'class'	=> 'span3',
									'value'	=> set_value('account[account_city]', account('city'))
									));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Postcode</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'account[account_postcode]',
									'class'	=> 'span2',
									'value'	=> set_value('account[account_postcode]', account('postcode'))
									));
				?> *
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Country</label>
			<div class="controls">
				<?php 
				echo country_dropdown('country', account('country'), 'class="span4" disabled="disabled"');
				echo form_hidden('account[account_country]', account('country'));
				?>
				<span class="help-block"><i class="icon-info-sign"></i> BookYourBeds is currently only available to customers in the United Kingdom.</span>

			</div>
		</div>

		<!--<div class="control-group">
			<label class="control-label">Contact Email</label>
			<div class="controls">
				<?php echo form_input(array(
									'name'	=> 'account[account_contact_email]',
									'class'	=> 'span4',
									'value'	=> set_value('account[account_contact_email]', account('contact_email'))
									));
				?>
			</div>
		</div>-->

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

			<fieldset>
				<legend>A Bit About You</legend>

				<div class="control-group">
					<label class="control-label">Description</label>
					<div class="controls">
						<?php echo form_textarea(array(
											'name'	=> 'account[account_description]',
											'class'	=> 'span4',
											'rows'	=> 4,
											'value'	=> set_value('account[account_description]', account('description'))
											));
						?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Website</label>
					<div class="controls">
						http://
						<?php echo form_input(array(
											'name'	=> 'account[account_website]',
											'class'	=> 'span3',
											'value'	=> set_value('account[account_website]', account('website'))
											));
						?>
					</div>
				</div>

			</fieldset>

	<fieldset>
			<legend>Appearance</legend>

			<div class="control-group">
				<label class="control-label">Account Logo</label>
				<div class="controls">
					<div class="image_logo_holder" style="background-image: url(<?php echo (setting('account_logo')) ?  setting('account_logo') : site_url('assets/img/default/style_logo_200.jpg', FALSE); ; ?>);"></div>
					<input type="file" name="account_logo" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Account Background</label>
				<div class="controls">
					<div class="image_bg_holder" style="background-image: url(<?php echo (setting('account_bg')) ?  setting('account_bg') : site_url('assets/img/default/style_bg.jpg', FALSE); ; ?>);"></div>
					<input type="file" name="account_bg" />
					<span class="help-block"><i class="icon-info-sign"></i> This image will appear across the top of your sales page. For best results use an image that is at least 1024 pixels wide.</span>
				</div>
			</div>


	</fieldset>

		<div class="control-group">
		
		<div class="controls">
			<button type="submit" class="btn btn-warning btn-large">Save Changes</button>
		</div>
	</div>

</form>
