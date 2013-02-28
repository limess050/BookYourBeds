<?php 
$_r = booking('resources');
if( ! empty($_r)) { ?>
<div class="alert">
	<h3>Currently Booking <small><?php echo anchor('admin/salesdesk/index', 'Change') . ' | ' . anchor('admin/salesdesk/confirm', 'Continue'); ?></small></h3>

	<table class="table table-condensed">
		<thead>	
			<tr>
				<th></th>
				<th>Arriving</th>
				<th>Duration</th>
				<th>Guests</th>
				<th class="span1"></th>
			</tr>

		</thead>

		<tbody>
			<?php foreach($_r as $resource) { ?>
			<tr>
				<td><strong><?php echo $resource->resource_title; ?></strong></td>
				<td><?php echo mysql_to_format($resource->reservation_start_at); ?></td>
				<td><?php echo duration($resource->reservation_duration); ?></td>
				<td><?php echo booking('booking_guests'); ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)</td>
				<td><a href="<?php echo site_url('admin/salesdesk/reset'); ?>" class="btn btn-danger btn-small">Cancel</a></td>
			</tr>
			<?php } ?>
		</tbody>

	</table>
</div>
<?php } ?>