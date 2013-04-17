<h1 class="page-header">Create Room</h1>

		<!--<p>All forms are given default styles to present them in a readable and scalable way.</p>-->
		<?php echo validation_errors(); ?>

		<?php echo form_open("admin/resources/create", 'class="form-horizontal"', array('resource[resource_account_id]' => account('id'))); ?>
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="resource_title">Room Name</label>
				<div class="controls">
					<?php
					echo form_input(array(
						'name'	=> 'resource[resource_title]',
						'id'	=> 'resource_title',
						'class'	=> 'span4',
						'value'	=> set_value('resource[resource_title]')));
					?>
				</div>
			</div>

			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn primary">Create Resource</button>
				</div>
			</div>

		</fieldset>
		</form>
