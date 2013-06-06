<?php echo $template['partials']['inactive_room_alert']; ?>

<h1 class="page-header">Edit Room <small><?php echo $resource->resource_title; ?></small></h1>

<?php echo $template['partials']['resource_menu']; ?>

		<h2>General Settings</h2>

		<?php echo validation_errors(); ?>

		<?php echo form_open("admin/resources/edit/{$resource->resource_id}", 'class="form-horizontal"', array('resource_id' => $resource->resource_id)); ?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="resource_title">Room Name</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'resource[resource_title]',
							'id'	=> 'resource_title',
							'class'	=> 'span4',
							'value'	=> set_value('resource[resource_title]', $resource->resource_title)));
						?>
					</div>
				</div>

				<!--<div class="control-group">
					<label class="control-label" for="resource_reference">Your Reference</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'resource[resource_reference]',
							'id'	=> 'resource_reference',
							'class'	=> 'span2',
							'value'	=> set_value('resource[resource_reference]', $resource->resource_reference)));
						?>
					</div>
				</div>-->

				<div class="control-group">
					<label class="control-label" for="resource_priced_per">Priced per</label>
					<div class="controls">
						<?php
						echo form_dropdown('resource[resource_priced_per]', 
											array('room' => 'room', 'bed' => 'bed'), 
											set_value('resource[resource_priced_per]', $resource->resource_priced_per),
											'class="span2"');	
						?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="resource_booking_footprint">Occupancy</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'resource[resource_booking_footprint]',
							'id'	=> 'resource_booking_footprint',
							'class'	=> 'span1',
							'value'	=> set_value('resource[resource_booking_footprint]', $resource->resource_booking_footprint)));
						?>
						<span class="help-block">This is the number of guests each one of these resources can accommodate.</span>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="resource_default_release">Default Release</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'resource[resource_default_release]',
							'id'	=> 'resource_default_release',
							'class'	=> 'span1',
							'value'	=> set_value('resource[resource_default_release]', $resource->resource_default_release)));
						?>
						<span class="help-block">This is the number of resources of this type, not the number of guests you can accommodate.</span>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-warning btn-large">Save Changes</button>
					</div>
				</div>
			</fieldset>
		</form>

	<?php if($resource->resource_active) { ?>
		<div class="alert alert-info">
			<strong>Disable this room</strong>

			<p>It is possible to disable this room - this will stop it appearing on diary and availability pages, and prevent it from being booked.</p>
			
			<?php echo anchor('admin/resources/disable/' . $resource->resource_id,
							'<i class="icon-remove icon-white"></i> Disable this room now</a>',
							'class="btn btn-primary" onclick="return confirm(\'Are you sure you want to disable this room?\')"'
							); ?>
		</div>
		<?php } ?>

		<div class="alert alert-danger">
			<strong>Delete this room</strong>

			<p>If you delete this room, it will be permanently removed. Any bookings made for this room will also be deleted. <strong>This cannot be undone!</strong></p>
			
			<?php echo anchor('admin/resources/delete/' . $resource->resource_id,
							'<i class="icon-remove icon-white"></i> Permanently delete this room</a>',
							'class="btn btn-danger" onclick="return confirm(\'Are you sure you want to permanently delete this room?\')"'
							); ?>
		</div>