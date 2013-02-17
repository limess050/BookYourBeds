<?php if( ! $resource->resource_active) { ?>
<div class="alert alert-success clearfix">
	<?php echo anchor('admin/resources/enable/' . $resource->resource_id,
							'<i class="icon-ok icon-white"></i> Enable now</a>',
							'class="btn btn-success pull-right" onclick="return confirm(\'Are you sure you want to enable this room?\')"'
							); ?>
	<strong>This room is currently disabled.</strong><br />It cannot be booked and will not appear on your diary or availability screens.
</div>
<?php } ?>