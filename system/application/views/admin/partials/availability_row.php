<tr>
	<?php if(empty($hide_title)) { ?>
	<td>
		<?php echo anchor("admin/resources/edit/{$resource->resource_id}", $resource->resource_title); 

		echo form_hidden("resource[{$resource->resource_id}][resource_title]", $resource->resource_title);
		?>

	</td>
	<?php } ?>

	<?php for($i = 1; $i <= 14; $i++) { ?>
	<td class="align_center<?php echo ((strtotime('+' . ($i - 1) . ' day', $start_timestamp)) < $today) ? ' disabled' : ''; echo ((strtotime('+' . ($i - 1) . ' day', $start_timestamp)) == $today) ? ' today' : ''; ?>">
		
		<p><?php echo $resource->availability[$i]->bookings;?></p>
		
		<?php 
		$hidden = array(
			"resource[{$resource->resource_id}][day][{$i}][timestamp]" => strtotime('+' . ($i - 1) . ' day', $start_timestamp),


			"resource[{$resource->resource_id}][day][{$i}][default_release]" => $resource->resource_default_release,

			"resource[{$resource->resource_id}][day][{$i}][release]" => $resource->availability[$i]->release,

			"resource[{$resource->resource_id}][day][{$i}][bookings]" => $resource->availability[$i]->bookings,

			"resource[{$resource->resource_id}][day][{$i}][resource_id]" => $resource->resource_id
			);
		
		$input = array(
			'name'	=> "resource[{$resource->resource_id}][day][{$i}][availability]",
			'class'	=> 'halfspan',
			'value'	=> set_value("resource[{$resource->resource_id}][day][{$i}][availability]",
						($resource->availability[$i]->release - $resource->availability[$i]->bookings))
			);
		
		if((strtotime('+' . ($i - 1) . ' day', $start_timestamp)) < $today)
		{
			$input['disabled'] = 'disabled';

			$hidden["resource[{$resource->resource_id}][day][{$i}][availability]"] = $resource->availability[$i]->release - $resource->availability[$i]->bookings;
		}

		echo form_input($input);
		echo form_hidden($hidden);



		?>
	</td>
	<?php } ?>
</tr>