<div class="sheet">
	<div class="row">

		<div class="span7">
			<div class="alert diary diary-bar">
				<strong><?php echo date('l', $current_date); ?></strong>
				<h1><?php echo date('j F Y', $current_date); ?></h1>
			</div>
		</div>

		<div class="span5">
			<div class="alert diary-bar">
				<p style="text-align: center;">
				<?php echo anchor('admin/bookings?timestamp=' . strtotime('-1 day', $current_date), '&laquo; ' . date('j M Y', strtotime('-1 day', $current_date)), 'id="prev_link" class="btn"'); ?>
				<?php echo anchor('admin/bookings', 'TODAY', 'id="today_link" class="btn btn-primary"'); ?>
				<input type="hidden" value="<?php echo date("Y-m-d", $current_date); ?>" id="datepicker" />
				<?php echo anchor('admin/bookings?timestamp=' . strtotime('+1 day', $current_date), date('j M Y', strtotime('+1 day', $current_date)) . ' &raquo;', 'id="next_link" class="btn"'); ?></p>

				<p style="text-align: center;">
					<a href="<?php echo site_url('admin/bookings?timestamp=' . $current_date . '&' . (( ! get_cookie('hideOther')) ? 'checkingin=1' : 'all=1')); ?>" class="btn">
						<span<?php echo ( ! get_cookie('hideOther')) ? ' style="display: none;"' : ''; ?>>Show</span><span<?php echo (get_cookie('hideOther')) ? ' style="display: none;"' : ''; ?>>Hide</span> guests NOT arriving on this date
					</a>
				</p>
			</div>
		</div>

	</div>

	<div>
		<hr />
		<?php echo form_open('admin/bookings/checkin'); ?>
		<?php foreach($resources as $resource) { ?>

		<div class="row">
			<h2 class="pull-left"><?php echo $resource->resource_title; ?></h2>

			<div class="btn-group pull-right" style="margin-top: 5px;">
				<a href="<?php echo site_url('admin/availability/resource/' . $resource->resource_id . '?timestamp=' . $current_date); ?>" class="btn btn-<?php echo ($resource->availability[1]->release - $resource->availability[1]->bookings <= 0) ? 'danger' : 'success'; ?>"><?php echo $resource->availability[1]->release - $resource->availability[1]->bookings; ?></a>
				<!--<a href="#" onclick="return false;" class="btn btn-<?php echo ($resource->availability[1]->release - $resource->availability[1]->bookings <= 0) ? 'danger' : 'success'; ?>"><i class="icon-plus icon-white"></i><i class="icon-user icon-white"></i></a>-->
				<a href="<?php echo site_url('admin/availability/resource/' . $resource->resource_id . '?timestamp=' . $current_date); ?>" class="btn">&pound;<?php echo as_currency($resource->availability[1]->price); ?></a>
			</div>

		</div>

		<?php if(empty($resource->bookings)) { ?>
		<p><span class="label label-important">NO BOOKINGS</span></p>
		<?php } else { ?>
		<table class="resource table table-striped table-condensed">
			<thead>
				<tr>
					<th></th>
					<th>Customer Name</th>
					<th>Booking Reference</th>
					<th>Guests</th>
					<th><?php echo ucfirst($resource->resource_priced_per); ?>s</th>
					<th>Duration</th>
					<th>Deposit Paid</th>
					<th>Bill</th>
					<?php if(now() > $current_date) { ?>
					<th class="checkin_col"></th>
					<?php } ?>
				</tr>
			<thead>
			  
			<tbody>
				<?php 
				$n = 1;
				$g = 0;
				foreach($resource->bookings as $booking) { ?>
				<tr<?php if ($booking->stage > 0) {
					echo ' class="checked_in"';
					} ?>>
					<td><?php echo $n; ?></td>
					<td><?php echo $booking->customer_firstname . ' ' . $booking->customer_lastname; ?></td>
					<td><?php echo anchor("admin/bookings/show/{$booking->booking_id}", $booking->booking_reference); ?></td>
					<td><?php echo $booking->booking_guests; 
					$g += $booking->booking_guests;
					?></td>
					<td><?php echo $booking->reservation_footprint; ?></td>
					<td>
						<?php
						echo ($booking->stage > 0) ? anchor('admin/bookings?timestamp=' . human_to_unix($booking->reservation_start_at), duration($booking->reservation_duration), 'title="Arrived ' . mysql_to_format($booking->reservation_start_at) . '"') : duration($booking->reservation_duration);

						?>

					</td>

					<td><?php echo '&pound;' . as_currency($booking->booking_deposit); ?></td>
					
					<td><?php echo '&pound;' . as_currency($booking->booking_price - $booking->booking_deposit); ?></td>
					<?php if(now() > $current_date) { ?>
					<td>
						<?php if($booking->stage == 0) { 
						if($booking->reservation_checked_in) {
						echo '<span class="label ">CHECKED IN</span>';
						} else { ?>
						<input type="submit" value="CHECK-IN" name="booking[<?php echo $booking->booking_id; ?>]" class="btn btn-mini btn-success" />
						<?php 
						}
						} ?>
					</td>
					<?php } ?>
				</tr>
				<?php 

					$n++;
					} ?>
				<!--<tr>
					<td colspan="3"></td>
					<td><strong><?php echo $g; ?></strong></td>
					<td colspan="3"></td>
				</tr>-->
			</tbody>
		</table>
		<?php } ?>
		<hr />
		<?php } ?>
		<?php echo form_hidden(array('redirect' => safe_get_env())); ?>
		</form>
	</div>
</div>

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
			onSelect: function(dateText, inst) { location.href='<?php echo site_url('admin/bookings'); ?>' + '?datetime=' + dateText; }

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
	});

-->
</script>
<!-- end: page-specific javascript -->
