<?php if( ! empty($user)) { ?>
<div class="row">
	<div class="span8 offset2">
		<?php echo form_open('reset_password?auth=' . $this->input->get('auth'), array('class' => 'form-horizontal'), array('user_id' => $user->user_id)); ?>
			<fieldset>
				<legend>Reset Password</legend>

				<?php echo $template['partials']['form_errors']; ?>

				<div class="control-group">
		        	<label class="control-label">New Password</label>
		        	<div class="controls">
		            	<input type="password" class="span4" name="password" />
		            </div>
		        </div>

		        <div class="control-group">
		        	<label class="control-label">Confirm New Password</label>
		        	<div class="controls">
		            	<input type="password" class="span4" name="password_conf" />
		            </div>
		        </div>
			</fieldset>

			<div class="form-actions">
		        <button type="submit" class="btn  btn-warning btn-large">Reset Password</button>
		    </div>
		</form>
	</div>
</div>
<?php } else { ?>
<p>No user account was found. This link may have expired - to send another password reset link <?php echo anchor('forgotten_password', 'click here'); ?>.</p>

<?php } ?>