<?php echo $template['partials']['inactive_room_alert']; ?>

<h1 class="page-header">Room Supplements <small><?php echo $resource->resource_title; ?></small></h1>

<?php echo $template['partials']['resource_menu']; ?>

<div class="row">
	<div class="span4 columns">
		<h2>Supplements</h2>
		<!--<p>All forms are given default styles to present them in a readable and scalable way.</p>-->

		<p><?php echo anchor('admin/supplements/create', 'Create New Supplement', 'class="btn primary"'); ?></p>
	</div>
	
	<div class="span8 columns">

		<table class="table zebra-striped">
			<thead>
				<tr>
					<th class="span1"></th>
					<th>Short Description</th>
					<th>Price for this <?php echo $resource->resource_priced_per; ?></th>
				</tr>
			</thead>

			<tbody>
			<?php foreach($supplements as $supplement) { ?>
			<tr>
				<td>
					<input type="checkbox" <?php echo set_checkbox('checkbox', 1, (! empty($supplement->resource_id))); ?> />
				</td>
				<td><strong><?php echo $supplement->supplement_short_description; ?></strong></td>
				<td>
					<div class="input-prepend">
						<span class="add-on">&pound;</span>
						<?php
						echo form_input(array(
											'name'	=> "",
											'class'	=> 'span1',
											'value' => as_currency($supplement->supplement_default_price)
										));
						?>


					</div>

					per <?php echo ($supplement->supplement_per_guest) ? 'guest' : 'room'; ?>
					per <?php echo ($supplement->supplement_per_day) ? 'day' : 'stay'; ?>
				</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>



	</div>
</div>