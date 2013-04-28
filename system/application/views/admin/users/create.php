<div class="page-header row">
	<h1>Users</h1>
</div>

<div class="row">
	<div class="span4">
		<h2>Create User</h2>
		<!--<p>All forms are given default styles to present them in a readable and scalable way.</p>-->

	</div>
	
	<div class="span8">

		<?php echo $template['partials']['form_errors']; ?>

		<?php echo form_open('admin/users/create', 'class="form-horizontal"', array('user[user_account_id]' => account('id'))); ?>
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="user_firstname">First Name</label>
				<div class="controls">
					<?php
					echo form_input(array(
						'name'	=> 'user[user_firstname]',
						'id'	=> 'user_firstname',
						'class'	=> 'span3',
						'value'	=> set_value('user[user_firstname]')
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
						'value'	=> set_value('user[user_lastname]')
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
						'value'	=> set_value('user[user_username]')
						));
					?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="user_password">Password</label>
				<div class="controls">
					<?php
					echo form_password(array(
						'name'	=> 'user[user_password]',
						'id'	=> 'user_password',
						'class'	=> 'span3'
						));
					?>
				</div>
			</div>

			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-warning btn-large">Create User</button>
				</div>
			</div>
		</fieldset>

		</form>
	</div>
</div>