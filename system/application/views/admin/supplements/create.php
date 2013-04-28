<div class="page-header row">
	<h1>Create Supplement</h1>
</div>


		<?php echo validation_errors(); ?>

		<?php echo form_open('admin/supplements/create', 'class="form-horizontal"', array('supplement[supplement_account_id]' => account('id'))); ?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="supplement_short_description">Short Description/Name</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'supplement[supplement_short_description]',
							'id'	=> 'resource_title',
							'class'	=> 'span4',
							'value'	=> set_value('supplement[supplement_short_description]')
							));
						?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Long Description</label>
					<div class="controls">
						<?php echo form_textarea(array(
											'name'	=> 'supplement[supplement_long_description]',
											'class'	=> 'span4',
											'rows'	=> 4,
											'value'	=> set_value('supplement[supplement_long_description]')
											));
						?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="resource_default_release">Default Price</label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">&pound;</span>
							<?php
							echo form_input(array(
								'name'	=> 'supplement[supplement_default_price]',
								'class'	=> 'span1',
								'value'	=> set_value('supplement[supplement_default_price]')
								));
							?>
						</div>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="resource_default_release">Priced Per</label>
					<div class="controls">
						<?php
						echo form_dropdown('supplement[supplement_per_guest]', 
											array('0' => 'room', '1' => 'guest'), 
											set_value('supplement[supplement_per_guest]'),
											'class="span2"');	
						?>
						&nbsp;per&nbsp;
						<?php
						echo form_dropdown('supplement[supplement_per_day]', 
											array('0' => 'stay', '1' => 'day'), 
											set_value('supplement[supplement_per_day]'),
											'class="span1"');	
						?>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-warning btn-large">Save Changes</button>
					</div>
				</div>
			</fieldset>
		</form>

