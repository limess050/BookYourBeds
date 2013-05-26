<?php echo $template['partials']['cart']; 

?>


	<h1 class="page-header">Optional Supplements</h1>

	<?php echo $template['partials']['form_errors']; ?>

	<?php echo form_open('admin/salesdesk/supplements', array('class' => 'form-horizontal')); ?>

	<?php 
	$_supplements = booking('supplements');

	foreach($supplements as $rid => $resource) { ?>
		<h3><?php echo $resource->resource_title; ?></h3>

		<table class="table">
			<tbody>
			<?php foreach($resource->supplements as $supplement) { ?>
				<tr>
					<td><h4><?php echo $supplement->supplement_short_description; ?> <small>&pound;<?php echo as_currency($supplement->resource_price) . ' ' .
																								(($supplement->supplement_per_guest) ? 'per person' : 'per ' . $resource->resource_priced_per) . ' ' .
																								(($supplement->supplement_per_day) ? 'per night' : 'per stay') ; ?></small></h4>

						<?php echo auto_typography($supplement->supplement_long_description); ?>
					</td>

					<td class="span2">
						<?php
						// The total number of options
						$opt_count = ($supplement->supplement_per_guest) ? $booking->resources[$rid]->reservation_guests : $booking->resources[$rid]->reservation_footprint;
						$multiply = ($supplement->supplement_per_day) ? $booking->resources[$rid]->reservation_duration : 1;

						$options = array(
										0	=> '0'
										);

						for($i = 1; $i <= $opt_count; $i++)
						{
							$options[$i] = $i . ' ' . (($supplement->supplement_per_guest) ? 'person' : $resource->resource_priced_per) . (($i > 1) ? 's' : '') . ' - &pound;' . as_currency($supplement->resource_price * $multiply * $i);
						}

						echo form_dropdown("supplements[{$resource->resource_id}][{$supplement->supplement_id}][qty]", 
											$options, 
											set_value("supplements[{$resource->resource_id}][{$supplement->supplement_id}][qty]", ( ! empty($_supplements[$resource->resource_id][$supplement->supplement_id])) ? $_supplements[$resource->resource_id][$supplement->supplement_id]['qty'] : 0), 
											'class="span2"');
						
						echo form_hidden(array(
											"supplements[{$resource->resource_id}][{$supplement->supplement_id}][price]" => ($supplement->resource_price * $multiply),
											"supplements[{$resource->resource_id}][{$supplement->supplement_id}][description]" => $supplement->supplement_short_description . ' (' . $resource->resource_title . ')'
											));
						?>

					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>

	<?php } ?>

	
		
	<div class="control-group">

		<div class="controls">
			<button type="submit" class="btn btn-warning">Continue</button>
		</div>
	</div>

	</form>

