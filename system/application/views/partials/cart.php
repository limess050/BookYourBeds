<?php 
$_r = booking('resources');
if( ! empty($_r)) { ?>
<div class="alert">
	<h3>Currently Booking <small><?php echo anchor('salesdesk/index', 'Change'); ?> | <?php echo anchor('salesdesk/confirm', 'Continue'); ?> | </small><a href="<?php echo site_url('salesdesk/reset'); ?>" onclick="return confirm('Are you sure you want to cancel this booking?');" class="btn btn-danger btn-small">Cancel</a></h3>

	<table class="table table-condensed">
		<thead>	
			<tr>
				<th></th>
				<th>Arriving</th>
				<th>Duration</th>
				<th>Guests</th>
				<th>Price</th>
			</tr>

		</thead>

		<tbody>
			<?php foreach($_r as $resource) { ?>
			<tr>
				<td><strong><?php echo $resource->resource_title; ?></strong></td>
				<td><?php echo mysql_to_format($resource->reservation_start_at); ?></td>
				<td><?php echo duration($resource->reservation_duration); ?></td>
				<td><?php echo $resource->reservation_guests; ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)</td>
				<td>&pound;<?php echo as_currency($resource->reservation_price); ?></td>
			</tr>
			<?php } ?>
		</tbody>

	</table>
</div>
<?php } ?>