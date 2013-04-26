<li class="well well-small media" id="add_room">
	<a class="pull-left" href="#">
		<img src="/assets/img/wizard/room-icon.png" />
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Add <?php echo ($this->account->rooms == 0) ? 'your first' : 'another' ;?> room</h4>

		<p>Your must create at least one room to enable you to launch your site, however you are not required to enter all room information at this time. You can enter additional rooms later.</p>

		<a href="#" onclick="$('#room_form').slideToggle(); return false;">Click here...</a>

		<?php echo form_open('admin/dashboard/wizard', 'class="form-horizontal' . ((empty($_add_room_open)) ? ' hide' : '') . '" id="room_form"', array('_form' => 'add_room', 'resource[resource_account_id]' => account('id'))); ?>
			<?php echo $template['partials']['form_errors']; ?>

			<fieldset>
				<legend>About the Room</legend>

				<div class="control-group">
					<label class="control-label" for="resource_title">Room Name</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'resource[resource_title]',
							'id'	=> 'resource_title',
							'class'	=> 'span4',
							'value'	=> set_value('resource[resource_title]')
							));
						?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="resource_priced_per">Priced per</label>
					<div class="controls">
						<?php
						echo form_dropdown('resource[resource_priced_per]', 
											array('bed' => 'bed', 'room' => 'room'), 
											set_value('resource[resource_priced_per]'),
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
							'value'	=> set_value('resource[resource_booking_footprint]')
							));
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
							'value'	=> set_value('resource[resource_default_release]')
							));
						?>
						<span class="help-block">This is the number of rooms/beds of this type, not the number of guests you can accommodate.</span>
					</div>
				</div>

			</fieldset>

			<fieldset>
				<legend>Pricing</legend>

				
						<table class="table">
							<thead>
								<tr>
									<th>Monday</th>
									<th>Tuesday</th>
									<th>Wednesday</th>
									<th>Thursday</th>
									<th class="weekend">Friday</th>
									<th class="weekend">Saturday</th>
									<th>Sunday</th>
								</tr>
							</thead>
							
							<tbody>
								<tr>
									<td>
										<div class="input-prepend">
  											<span class="add-on">&pound;</span>
  											<?php
											$input = array(
												'name'	=> 'price[1]',
												'class'	=> 'span1',
												'value'	=> set_value('price[1]')
											);
											
											echo form_input($input);
											
											?>
										</div>
										
										<!--<a href="#" onclick="$('.season_0').val($('.season_0.first').val()); return false;" class="link_fields" title="Link">j</a>-->
									</td>
									<?php for($i = 2; $i <= 7; $i++) { ?>
									<td<?php echo ($i == 5 || $i == 6) ? ' class="weekend"' : ''; ?>>
										<div class="input-prepend">
  											<span class="add-on">&pound;</span>
											<?php
											$input = array(
												'name'	=> "price[{$i}]",
												'class'	=> 'span1',
												'value'	=> set_value("price[{$i}]")
											);
											
											echo form_input($input);
											?>
										</div>
									</td>
									<?php } ?>
									
								</tr>
							</tbody>
						</table>

			</fieldset>

			<div class="control-group">
				
				<div class="controls">
					<button type="submit" class="btn btn-primary">Add Room</button>
				</div>
			</div>

		</form>	
	</div>
</li>