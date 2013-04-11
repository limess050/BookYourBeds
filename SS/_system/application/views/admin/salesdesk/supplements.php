<?php echo $template['partials']['cart']; 

?>

	<h1 class="page-header">Optional Supplements</h1>

	<?php echo $template['partials']['form_errors']; ?>

	<?php echo form_open('admin/salesdesk/supplements', array('class' => 'form-horizontal')); ?>

	<table class="table">
	<?php 
	$resources = booking('resources');
	$_supplements = booking('supplements');

	foreach($supplements as $supplement) { ?>
	<tr>
		<td><h4><?php echo $supplement->supplement_short_description; ?> <small>&pound;<?php echo as_currency($supplement->resource_price) . ' ' .
																								(($supplement->supplement_per_guest) ? 'per person' : 'per ' . $resources[0]->resource_priced_per) . ' ' .
																								(($supplement->supplement_per_day) ? 'per night' : 'per stay') ; ?></small></h4>

			<?php echo auto_typography($supplement->supplement_long_description); ?>
		</td>
		
		<td class="span4">
			<?php
			// The total number of options
			$opt_count = ($supplement->supplement_per_guest) ? booking('booking_guests') : $resources[0]->reservation_footprint;
			$multiply = ($supplement->supplement_per_day) ? $resources[0]->reservation_duration : 1;

			$options = array(
							0	=> '0'
							);

			for($i = 1; $i <= $opt_count; $i++)
			{
				$options[$i] = $i . ' ' . (($supplement->supplement_per_guest) ? 'person' : $resources[0]->resource_priced_per) . (($i > 1) ? 's' : '') . ' - &pound;' . as_currency($supplement->resource_price * $multiply * $i);
			}

			echo form_dropdown("supplements[{$supplement->supplement_id}][qty]", 
								$options, 
								set_value("supplements[{$supplement->supplement_id}][qty]", ( ! empty($_supplements[$supplement->supplement_id])) ? $_supplements[$supplement->supplement_id]['qty'] : 0), 
								'class="span3"');
			
			echo form_hidden(array(
								"supplements[{$supplement->supplement_id}][price]" => ($supplement->resource_price * $multiply),
								"supplements[{$supplement->supplement_id}][description]" => $supplement->supplement_short_description
								));
			?>

		</td>
	</tr>


	<?php } ?>
	</table>

	
		
	<div class="control-group">

		<div class="controls">
			<button type="submit" class="btn btn-primary">Continue</button>
		</div>
	</div>

	</form>

