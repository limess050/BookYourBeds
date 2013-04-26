<?php if( ! $supplement->supplement_active) { ?>
<div class="alert alert-success clearfix">
	<?php echo anchor('admin/supplements/enable/' . $supplement->supplement_id,
							'<i class="icon-ok icon-white"></i> Enable now</a>',
							'class="btn btn-success pull-right" onclick="return confirm(\'Are you sure you want to enable this supplement?\')"'
							); ?>
	<strong>This supplement is currently disabled.</strong><br />It cannot be booked and will not appear on your rooms.
</div>
<?php } ?>

<div class="page-header row">
	<h1>Edit Supplement <small><?php echo $supplement->supplement_short_description; ?></small></h1>
</div>


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
						<button type="submit" class="btn btn-warning">Save Changes</button>
					</div>
				</div>
			</fieldset>
		</form>

<?php if($supplement->supplement_active) { ?>
<div class="alert alert-info">
	<strong>Disable this supplement</strong>

	<p>It is possible to disable this supplement - this will stop it appearing on rooms, and prevent it from being booked.</p>
	
	<?php echo anchor('admin/supplements/disable/' . $supplement->supplement_id,
					'<i class="icon-remove icon-white"></i> Disable this supplement now</a>',
					'class="btn btn-primary" onclick="return confirm(\'Are you sure you want to disable this supplement?\')"'
					); ?>
</div>
<?php } ?>

<div class="alert alert-danger">
	<strong>Delete this supplement</strong>

	<p>If you delete this supplement, it will be permanently removed from all rooms and bookings. <strong>This cannot be undone!</strong></p>
	
	<?php echo anchor('admin/supplements/delete/' . $supplement->supplement_id,
					'<i class="icon-remove icon-white"></i> Permanently delete this supplement</a>',
					'class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this supplement? It cannot be undone.\')"'
					); ?>
</div>