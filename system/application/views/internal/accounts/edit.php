<h1 class="page-header">Edit Account</h1>

<div class="row">

	<div class="span8">
		<?php echo form_open_multipart('internal/accounts/edit/' . $account->account_id, 'class="form-horizontal"', array('account_id' => $account->account_id)); ?>
			<?php echo $template['partials']['form_errors']; ?>

			<fieldset>
				<legend>The Essentials</legend>

				<div class="control-group">
					<label class="control-label">Account Name</label>
					<div class="controls">
						<?php echo form_input(array(
											'name'	=> 'account[account_name]',
											'class'	=> 'span4',
											'value'	=> set_value('account[account_name]', $account->account_name)
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
											'value'	=> set_value('account[account_slug]', $account->account_slug)
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
													'value'	=> set_value('account[account_email]', $account->account_email)
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
													'value'	=> set_value('account[account_phone]', $account->account_phone)
													));
								?>
							</div>
						</div>

					</fieldset>

					<fieldset>
						<legend>About</legend>

						<div class="control-group">
							<label class="control-label">Description</label>
							<div class="controls">
								<?php echo form_textarea(array(
													'name'	=> 'account[account_description]',
													'class'	=> 'span4',
													'rows'	=> 4,
													'value'	=> set_value('account[account_description]', $account->account_description)
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
													'value'	=> set_value('account[account_website]', $account->account_website)
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
							<div class="image_logo_holder" style="background-image: url(<?php echo ( ! empty($account_logo)) ?  $account_logo : site_url('assets/img/default/style_logo_200.jpg', FALSE); ; ?>);"></div>
							<input type="file" name="account_logo" />
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Account Background</label>
						<div class="controls">
							<div class="image_bg_holder" style="background-image: url(<?php echo ( ! empty($account_bg)) ?  $account_bg : site_url('assets/img/default/style_bg.jpg', FALSE); ; ?>);"></div>
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

	<div class="span4">
		<div class="alert alert-info">
			Current Capacity: <strong><?php echo (int) $account->account_capacity; ?></strong>
		</div>

		<a href="<?php echo site_url('internal/accounts/remove/' . $account->account_id); ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to remove this account?');">Remove this account</a>
	</div>
</div>