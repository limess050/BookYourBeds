<?php if( ! $resource->resource_active) { ?>
<div class="alert alert-success clearfix">
	<?php echo anchor('admin/resources/enable/' . $resource->resource_id,
							'<i class="icon-ok icon-white"></i> Enable now</a>',
							'class="btn btn-success pull-right" onclick="return confirm(\'Are you sure you want to enable this room?\')"'
							); ?>
	<strong>This room is currently disabled.</strong><br />It cannot be booked and will not appear on your diary or availability screens.
</div>
<?php } ?>

<div class="page-header row">
	<h1>Room Availability <small><?php echo $resource->resource_title; ?></small></h1>
</div>

<?php echo $template['partials']['resource_menu']; ?>

<?php echo $template['partials']['form_errors']; ?>

<p class="align_center">
<?php echo anchor('admin/availability/resource/' . $resource->resource_id . '?timestamp=' . strtotime('-14 day', $start_timestamp), '&laquo; Previous 14 days', 'class="btn" id="prev_link"'); ?>&nbsp;
<?php echo anchor('admin/availability/resource/' . $resource->resource_id, 'TODAY', 'class="btn btn-primary" id="today_link"'); ?>&nbsp; 
<input type="hidden" value="<?php echo date("Y-m-d", $start_timestamp); ?>" id="datepicker" />&nbsp;

<?php echo anchor('admin/availability/resource/' . $resource->resource_id . '?timestamp=' . strtotime('+14 day', $start_timestamp), 'Next 14 days &raquo;', 'class="btn" id="next_link"'); ?>
</p>


<?php echo form_open(safe_get_env()); ?>
	
	<table class="table table-condensed table-hover table-striped table-bordered">
		<thead>
			<tr>
				<?php for($i = 0; $i < 14; $i++) { ?>
				<th class="align_center<?php echo (date("w", strtotime('+' . $i . ' day', $start_timestamp)) > 4 ) ? ' weekend' : ''; echo ((strtotime('+' . ($i) . ' day', $start_timestamp)) == $today) ? ' today' : ''; ?>">
				<?php
				echo date("D", strtotime('+' . $i . ' day', $start_timestamp)); ?><br />
				<small>
				<?php 
				echo anchor('admin/bookings?timestamp=' . strtotime('+' . $i . ' day', $start_timestamp), 
				date("d/m", strtotime('+' . $i . ' day', $start_timestamp))); 
				?></small>
				</th>
				<?php } ?>
			
			</tr>
		</thead>
	
		<tbody id="availability"></tbody>
	</table>
</form>

<!-- start: page-specific javascript -->
<script type="text/javascript">
<!--
	$(function() {
		$('#datepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			firstDay: 1,
			dateFormat: 'yy-mm-dd',
			showAnim: 'fadeIn',
			showOn: 'button',
			buttonText: '<i class="icon-calendar"></i>',
			onSelect: function(dateText, inst) { location.href='<?php echo site_url("admin/availability/resource/{$resource->resource_id}"); ?>' + '?datetime=' + dateText; }

		});

		$(document).keydown(function(e){
			//alert(e.keyCode);
			if(e.keyCode === 37) {
				// Left
				e.preventDefault();
				location.href = $("#prev_link").attr('href');
			} else if(e.keyCode === 39) {
				e.preventDefault();
				location.href = $("#next_link").attr('href');
			} else if(e.keyCode === 38) {
				e.preventDefault();
				location.href = $("#today_link").attr('href');
			}
		});

		$('#availability').spin();

		$('#availability').load('<?php echo site_url('admin/availability/availability_data?' . $request_string); ?>');
	});

-->
</script>
<!-- end: page-specific javascript -->

