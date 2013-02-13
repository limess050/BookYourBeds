<li class="well well-small media">
	<a class="pull-left" href="#">
		<img class="media-object" data-src="holder.js/64x64">
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Personalise your account</h4>

		<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p>

		<a href="#" onclick="$('#account_form').slideToggle(); return false;">Do this...</a>

		<?php echo form_open_multipart('admin/dashboard/wizard', 'class="form-horizontal' . ((empty($_account_open)) ? ' hide' : '') . '" id="account_form"', array('_form' => 'account', 'account[account_personalised]' => 1)); ?>
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
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

		</form>	
	</div>
</li>