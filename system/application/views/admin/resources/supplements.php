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

		<?php echo form_open('admin/resources/supplements/' . $resource->resource_id); ?>
		<table class="table zebra-striped">
			<thead>
				<tr>
					<th class="span1"></th>
					<th>Short Description</th>
					<th class="span3">Price for this <?php echo $resource->resource_priced_per; ?></th>
				</tr>
			</thead>

			<tbody>
			<?php foreach($supplements as $supplement) { ?>
			<tr<?php echo ($supplement->resource_id == $resource->resource_id) ? ' class="success"' : ''; ?> id="row<?php echo $supplement->supplement_id; ?>">
				<td>
					<?php
					echo form_checkbox(array(
											'name'	=> "supplement[$supplement->supplement_id][str_resource_id]",
											'value'	=> $resource->resource_id,
											'checked'	=> set_checkbox("supplement[$supplement->supplement_id][str_resource_id]", $supplement->resource_id, ($supplement->resource_id == $resource->resource_id)),
											'onclick'	=> "$('#row{$supplement->supplement_id}').toggleClass('success');"
										));
					?>
					
				</td>
				<td><strong><?php echo $supplement->supplement_short_description; ?></strong></td>
				<td>
					<div class="input-prepend input-append">
						<span class="add-on">&pound;</span>
						<?php
						echo form_input(array(
											'name'	=> "supplement[$supplement->supplement_id][str_price]",
											'class'	=> 'span1',
											'value' => as_currency($supplement->resource_price)
										));
						
						echo form_hidden(array(
											"supplement[$supplement->supplement_id][supplement_default_price]" 	=> $supplement->supplement_default_price,
											"supplement[$supplement->supplement_id][str_supplement_id]" 		=> $supplement->supplement_id
											));
						?>

						<span class="add-on">per <?php echo ($supplement->supplement_per_guest) ? 'guest' : 'room'; ?>
						per <?php echo ($supplement->supplement_per_day) ? 'day' : 'stay'; ?></span>
					</div>
				</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>

		<button type="submit" class="btn btn-warning btn-large">Save Changes</button>

		</form>
	</div>
</div>