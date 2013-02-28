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
																								(($supplement->supplement_per_day) ? 'per day' : 'per stay') ; ?></small></h4></td>
		<td>
			<?php
			$amount = ($supplement->supplement_per_guest) ? booking('booking_guests') : $resources[0]->reservation_footprint;
			$multiply = ($supplement->supplement_per_day) ? $resources[0]->reservation_duration : 1;

			echo '&pound;' . as_currency($supplement->resource_price * $amount * $multiply);
			?>
		</td>
		<td>
			<?php
			echo form_checkbox("supplements[{$supplement->supplement_id}][price]", ($supplement->resource_price * $amount * $multiply), set_checkbox("supplements[{$supplement->supplement_id}][price]", ($supplement->resource_price * $amount * $multiply), ! empty($_supplements[$supplement->supplement_id])));
			echo form_hidden("supplements[{$supplement->supplement_id}][description]", $supplement->supplement_short_description);
			?>

		</td>
	</tr>


	<?php } ?>
	</table>


	
	<pre>
		<?php //print_r(booking('resources')); ?>
		<?php //print_r(booking('booking_guests')); ?>
		<?php //print_r($supplements); ?>

	</pre>
	
		
	<div class="control-group">

		<div class="controls">
			<button type="submit" class="btn btn-primary">Continue</button>
		</div>
	</div>

	</form>

