<h1 class="page-header">Edit User</h1>

<?php echo $template['partials']['form_errors']; ?>

<?php echo form_open("internal/users/edit/{$user->internal_user_id}", 'class="form-horizontal"', array('user_id' => $user->internal_user_id)); ?>
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="internal_user_firstname">First Name</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'user[internal_user_firstname]',
					'id'	=> 'internal_user_firstname',
					'class'	=> 'span3',
					'value'	=> set_value('user[internal_user_firstname]', $user->internal_user_firstname)
					));
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="internal_user_lastname">Last Name</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'user[internal_user_lastname]',
					'id'	=> 'internal_user_lastname',
					'class'	=> 'span3',
					'value'	=> set_value('user[internal_user_lastname]', $user->internal_user_lastname)
					));
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="internal_user_email">Email Address</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'user[internal_user_email]',
					'id'	=> 'internal_user_email',
					'class'	=> 'span4',
					'value'	=> set_value('user[internal_user_email]', $user->internal_user_email)
					));
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="internal_user_username">Username</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'user[internal_user_username]',
					'id'	=> 'internal_user_username',
					'class'	=> 'span2',
					'value'	=> set_value('user[internal_user_username]', $user->internal_user_username)
					));
				?>
			</div>
		</div>

	</fieldset>

	<fieldset>
		<legend>Change Password</legend>
		
		<div class="control-group">
	        	<label class="control-label">New Password</label>
	        	<div class="controls">
	            	<input type="password" class="span3" name="password" />
	            </div>
	        </div>

	        <div class="control-group">
	        	<label class="control-label">Confirm Password</label>
	        	<div class="controls">
	            	<input type="password" class="span3" name="passconf" />
	            </div>
	        </div>

	</fieldset>

	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</div>
	</div>
	
</form>