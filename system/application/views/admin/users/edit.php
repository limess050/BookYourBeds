<h1 class="page-header">Users</h1>

<div class="row">
	<div class="span4">
		<h2>Edit User</h2>
		<p>All forms are given default styles to present them in a readable and scalable way.</p>
	</div>
	
	<div class="span8">

		<?php echo $template['partials']['form_errors']; ?>

		<?php echo form_open("admin/users/edit/{$user->user_id}", 'class="form-horizontal"', array('user_id' => $user->user_id)); ?>
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="user_firstname">First Name</label>
				<div class="controls">
					<?php
					echo form_input(array(
						'name'	=> 'user[user_firstname]',
						'id'	=> 'user_firstname',
						'class'	=> 'span3',
						'value'	=> set_value('user[user_firstname]', $user->user_firstname)
						));
					?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="user_lastname">Last Name</label>
				<div class="controls">
					<?php
					echo form_input(array(
						'name'	=> 'user[user_lastname]',
						'id'	=> 'user_lastname',
						'class'	=> 'span3',
						'value'	=> set_value('user[user_lastname]', $user->user_lastname)
						));
					?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="user_username">Username</label>
				<div class="controls">
					<?php
					echo form_input(array(
						'name'	=> 'user[user_username]',
						'id'	=> 'user_username',
						'class'	=> 'span2',
						'value'	=> set_value('user[user_username]', $user->user_username)
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
</div>
</div>