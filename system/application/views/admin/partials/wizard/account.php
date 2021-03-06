<li class="well well-small media">
	<a class="pull-left" href="#">
		<img src="/assets/img/wizard/personalise-icon.png" />
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Personalise your account</h4>

		<p>Personalise the look and feel of your account by writing a description about your B&amp;B, adding a logo and a background image! You can either complete this now or once your account is live.</p>

		<a href="#" onclick="$('#account_form').slideToggle(); return false;">Click here...</a>

		<?php echo form_open_multipart('admin/dashboard/wizard', 'class="form-horizontal' . ((empty($_account_open)) ? ' hide' : '') . '" id="account_form"', array('_form' => 'account', 'account[account_personalised]' => 1)); ?>
			<?php echo $template['partials']['form_errors']; ?>

			<fieldset>
				<legend>The Essentials</legend>

				<div class="control-group">
					<label class="control-label">Property Name</label>
					<div class="controls">
						<?php echo form_input(array(
											'name'	=> 'account[account_name]',
											'class'	=> 'span4',
											'value'	=> set_value('account[account_name]', account('name'))
											));
						?> *
					</div>
				</div>

				<!--<div class="control-group">
					<label class="control-label">Account URL</label>
					<div class="controls">
						<?php echo site_url('', FALSE); ?>
							<?php echo form_input(array(
											'name'	=> 'account[account_slug]',
											'class'	=> 'span2',
											'value'	=> set_value('account[account_slug]', account('slug'))
											));
							?> *
					</div>
				</div>-->

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
				echo country_dropdown('country', 'GB', 'class="span4" disabled="disabled"');
				echo form_hidden('account[account_country]', 'GB');
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
				</div>
			</div>


	</fieldset>

		<div class="control-group">
		
		<div class="controls">
			<button type="submit" class="btn btn-warning btn-large">Save Changes</button>
			&nbsp;&nbsp;* Required fields
		</div>
	</div>

		</form>	
	</div>
</li>