<h1 class="page-header">Create New Account</h1>

<?php echo form_open('internal/accounts/create', array('class' => 'form-horizontal')); ?>

<fieldset>

				<?php echo $template['partials']['form_errors']; ?>

				<div class="control-group">
		        	<label class="control-label">User Name</label>
		        	<div class="controls">
		            	<?php
		        		echo form_input(array(
		        						'name'	=> 'user_name',
		        						'class'	=> 'span3',
		        						'value'	=> set_value('user_name')
		        						));
		        		?>
		            </div>
		        </div>

				<div class="control-group">
		        	<label class="control-label">Property Name</label>
		        	<div class="controls">
		            	<?php
		        		echo form_input(array(
		        						'name'	=> 'name',
		        						'class'	=> 'span3',
		        						'value'	=> set_value('name')
		        						));
		        		?>
		            </div>
		        </div>

				<div class="control-group">
		        	<label class="control-label">Email address</label>
		        	<div class="controls">
		        		<?php
		        		echo form_input(array(
		        						'name'	=> 'email',
		        						'class'	=> 'span4',
		        						'value'	=> set_value('email')
		        						));
		        		?>
		            </div>
		        </div>

		        <div class="control-group">
		        	<label class="control-label">Password</label>
		        	<div class="controls">
		            	<?php
		        		echo form_password(array(
		        						'name'	=> 'password',
		        						'class'	=> 'span4',
		        						'value'	=> set_value('password')
		        						));
		        		?>
		            </div>
		        </div>

		        <div class="control-group">
		        	<div class="controls">
		            	<label><?php
		        		echo form_checkbox(array(
		        						'name'	=> 'email_details',
		        						'value'	=> 1,
		        						'checked'	=> set_checkbox('email_details', 1, TRUE)
		        						));
		        		?> Send account details to customer</label>
		            </div>
		        </div>

			</fieldset>

			<div class="form-actions">
		        <button type="submit" class="btn btn-warning btn-large">Continue</button>
		    </div>


</form>