<tr>
	<?php if(empty($hide_title)) { ?>
	<td>


		<div class="responsive-content no-label">
			<?php echo anchor("admin/resources/edit/{$resource->resource_id}", $resource->resource_title); 

			echo form_hidden("resource[{$resource->resource_id}][resource_title]", $resource->resource_title);
			?>
		</div>

	</td>
	<?php } ?>

	<?php for($i = 1; $i <= AVAILABILITY_DAYS; $i++) { ?>
	<td class="align_center<?php echo ((strtotime('+' . ($i - 1) . ' day', $start_timestamp)) < $today) ? ' disabled' : ''; echo (date("w", strtotime('+' . ($i - 1) . ' day', $start_timestamp)) > 4 ) ? ' weekend' : ''; echo ((strtotime('+' . ($i - 1) . ' day', $start_timestamp)) == $today) ? ' today' : ''; ?>">
		

		<div class="responsive-label">
			<?php
			echo date("l", strtotime('+' . ($i - 1) . ' day', $start_timestamp)); ?><br />
			<small>
			<?php 
			echo anchor('admin/bookings?timestamp=' . strtotime('+' . ($i - 1) . ' day', $start_timestamp), 
			date("j F Y", strtotime('+' . ($i - 1). ' day', $start_timestamp))); 
			?></small>
		</div>

		<div class="responsive-content">

			<?php
			if ($resource->availability[$i]->release - $resource->availability[$i]->bookings <= 0)
			{
				$status = 'error';
			} else
			{
				$status = 'success';
			}
			?>
			<div class="control-group <?php echo $status; ?>">

				<div class="input-prepend">
					<span class="add-on"><?php echo $resource->availability[$i]->bookings;?></span>
				
				<?php 
				$hidden = array(
					"resource[{$resource->resource_id}][day][{$i}][timestamp]" => strtotime('+' . ($i - 1) . ' day', $start_timestamp),


					"resource[{$resource->resource_id}][day][{$i}][default_release]" => $resource->resource_default_release,

					"resource[{$resource->resource_id}][day][{$i}][release]" => $resource->availability[$i]->release,

					"resource[{$resource->resource_id}][day][{$i}][bookings]" => $resource->availability[$i]->bookings,

					"resource[{$resource->resource_id}][day][{$i}][resource_id]" => $resource->resource_id,

					"resource[{$resource->resource_id}][day][{$i}][default_price]" => as_currency($resource->availability[$i]->default_price)
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
				?>
				</div> 
			</div>

			<div class="input-prepend">
				<span class="add-on">&pound;</span>
				<?php
				$input = array(
								'name'	=> "resource[{$resource->resource_id}][day][{$i}][price]",
								'class'	=> 'span1',
								'value' => as_currency($resource->availability[$i]->price)
							);

				if((strtotime('+' . ($i - 1) . ' day', $start_timestamp)) < $today)
				{
					$input['disabled'] = 'disabled';

					$hidden["resource[{$resource->resource_id}][day][{$i}][price]"] = as_currency($resource->availability[$i]->price);
				}

				echo form_input($input);

				echo form_hidden($hidden);
				?>
			</div>
		</div>

	</td>
	<?php } ?>
</tr>