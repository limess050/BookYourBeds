<li class="well well-small media">
	<a class="pull-left" href="#">
		<img class="media-object" data-src="holder.js/64x64">
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Add your first room</h4>

		<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p>

		<a href="#" onclick="$('#room_form').slideToggle(); return false;">Do this...</a>

		<?php echo form_open('admin/dashboard/wizard', 'class="form-horizontal hide" id="room_form"'); ?>
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
							));
						?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="resource_booking_footprint">Booking Footprint</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'resource[resource_booking_footprint]',
							'id'	=> 'resource_booking_footprint',
							'class'	=> 'span1'
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
							'class'	=> 'span1'
							));
						?>
						<span class="help-block">This is the number of rooms/beds of this type, not the number of guests you can accommodate.</span>
					</div>
				</div>


				<div class="control-group">
					<label class="control-label" for="resource_priced_per">Resource priced per</label>
					<div class="controls">
						<?php
						echo form_dropdown('resource[resource_priced_per]', 
											array('guest' => 'guest', 'room' => 'room'), 
											null,
											'class="span2"');	
						?>
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
												'name'	=> 'season[0][1][price]',
												'class'	=> 'season_0 span1'
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
												'name'	=> "season[0][{$i}][price]",
												'class'	=> 'season_0 span1'
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