<div class="page-header row">
	<h1>Edit Supplement <small><?php echo $supplement->supplement_short_description; ?></small></h1>
</div>

<div class="row">
	<div class="span4">

		<?php if($supplement->supplement_active) { ?>
		<div class="alert alert-danger">
			<strong>Disable this supplement</strong>

			<p>It is possible to disable this room - this will stop it appearing on diary and availability pages, and prevent it from being booked.</p>
			
			<?php echo anchor('admin/resources/disable/' . $supplement->supplement_id,
							'<i class="icon-remove icon-white"></i> Disable this room now</a>',
							'class="btn btn-danger" onclick="return confirm(\'Are you sure you want to disable this room?\')"'
							); ?>
		</div>
		<?php } ?>
	</div>
	
	<div class="span8">
		<?php echo validation_errors(); ?>

		<?php echo form_open("admin/supplements/edit/{$supplement->supplement_id}", 'class="form-horizontal"', array('supplement_id' => $supplement->supplement_id)); ?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="supplement_short_description">Short Description/Name</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'supplement[supplement_short_description]',
							'id'	=> 'resource_title',
							'class'	=> 'span4',
							'value'	=> set_value('supplement[supplement_short_description]', $supplement->supplement_short_description)));
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
											'value'	=> set_value('supplement[supplement_long_description]', $supplement->supplement_long_description)
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
								'value'	=> set_value('supplement[supplement_default_price]', as_currency($supplement->supplement_default_price))));
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
											set_value('supplement[supplement_per_guest]', $supplement->supplement_per_guest),
											'class="span2"');	
						?>
						&nbsp;per&nbsp;
						<?php
						echo form_dropdown('supplement[supplement_per_day]', 
											array('0' => 'stay', '1' => 'day'), 
											set_value('supplement[supplement_per_day]', $supplement->supplement_per_day),
											'class="span1"');	
						?>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-primary">Save Changes</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
