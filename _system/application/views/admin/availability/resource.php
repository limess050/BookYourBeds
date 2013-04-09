<?php echo $template['partials']['inactive_room_alert']; ?>

<h1 class="page-header">Room Availability <small><?php echo $resource->resource_title; ?></small></h1>

<?php echo $template['partials']['resource_menu']; ?>

<?php echo $template['partials']['form_errors']; ?>

<p class="align_center">
<?php echo anchor('admin/availability/resource/' . $resource->resource_id . '?timestamp=' . strtotime('-' . AVAILABILITY_DAYS . ' day', $start_timestamp), '&laquo; Previous ' . AVAILABILITY_DAYS . ' days', 'class="btn" id="prev_link"'); ?>&nbsp;
<?php echo anchor('admin/availability/resource/' . $resource->resource_id, 'TODAY', 'class="btn btn-primary" id="today_link"'); ?>&nbsp; 
<input type="hidden" value="<?php echo date("Y-m-d", $start_timestamp); ?>" id="datepicker" />&nbsp;

<?php echo anchor('admin/availability/resource/' . $resource->resource_id . '?timestamp=' . strtotime('+' . AVAILABILITY_DAYS . ' day', $start_timestamp), 'Next ' . AVAILABILITY_DAYS . ' days &raquo;', 'class="btn" id="next_link"'); ?>
</p>


<?php echo form_open(safe_get_env()); ?>
	
	<table class="table table-condensed table-hover table-striped table-bordered">
		<thead>
			<tr>
				<?php for($i = 0; $i < AVAILABILITY_DAYS; $i++) { ?>
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

