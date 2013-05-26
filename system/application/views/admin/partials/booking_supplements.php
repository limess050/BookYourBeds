<?php if($booking->has_supplements) { ?>
<h3>Supplements <small><?php echo anchor('admin/bookings/supplements/' . $booking->booking_id, 'Edit'); ?></small></h3>

<table class="table table-condensed table-striped">
	<thead>
		<tr>
			<th>Supplement</th>
			<th></th>
			<th class="span1">Qty</th>
			<th class="span1">Price</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($booking->resources as $resource) { ?>
		<?php foreach($resource->supplements as $supplement) { ?>
		<tr>
			<td><?php echo $supplement->supplement_short_description; ?></td>
			<td><?php echo $resource->resource_title ?></td>
			<td><?php echo $supplement->stb_quantity; ?></td>
			<td>&pound;<?php echo as_currency($supplement->stb_price); ?></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</tbody>


</table>


<?php } ?>