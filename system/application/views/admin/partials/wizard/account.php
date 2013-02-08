<li class="well well-small media">
	<a class="pull-left" href="#">
		<img class="media-object" data-src="holder.js/64x64">
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Personalise your account</h4>

		<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p>

		<a href="#" onclick="$('#account_form').slideToggle(); return false;">Do this...</a>

		<?php echo form_open('admin/dashboard/wizard', 'class="form-horizontal' . ((empty($_account_open)) ? ' hide' : '') . '" id="account_form"', array('_form' => 'account', 'account[account_personalised]' => 1)); ?>
			<?php echo $template['partials']['form_errors']; ?>

			<fieldset>
				<legend>The Essentials</legend>

				<div class="control-group">
					<label class="control-label">Account Name</label>
					<div class="controls">
						<?php echo form_input(array(
											'name'	=> 'account[account_name]',
											'class'	=> 'span4',
											'value'	=> account('name')
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
											'value'	=> account('slug')
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
											'value'	=> account('email')
											));
						?>
					</div>
				</div>


				<div class="control-group">
					<label class="control-label">Contact Telephone</label>
					<div class="controls">
						<?php echo form_input(array(
											'name'	=> 'account_phone',
											'class'	=> 'span4',
											'value'	=> account('telephone')
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