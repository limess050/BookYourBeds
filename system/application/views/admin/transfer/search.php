<?php echo form_open('admin/transfer/new_transfer/' . $booking->booking_id, 
					array(
						'method' => 'POST',
						'onsubmit' => 'return checkGuests(' . $guests . ');'
						), 
					array(
						'guests' 			=> $guests,
						'start_timestamp'	=> $start_timestamp,
						'duration'			=> $duration
						)); ?>
<?php foreach($resources as $resource) { 
			
$availability =& $resource->availability; ?>

<!--<pre>
<?php print_r($resource); ?>

</pre>-->

<div>
	<h4><?php echo $resource->resource_title; ?></h4>

	<table class="table table-condensed table-striped table-hover">
	
	<thead>
		<tr>
			<?php for($i = 0; $i < $duration; $i++) { ?>
			<th class="align_center<?php echo (date("w", strtotime('+' . $i . ' day', $start_timestamp)) > 4 ) ? ' weekend' : ''; ?>">
			<?php
			echo date("D", strtotime('+' . $i . ' day', $start_timestamp)); ?> <small><?php echo date("d/m", strtotime('+' . $i . ' day', $start_timestamp)); ?></small>
			</th>
			<?php } ?>
			<th class="span1"></th>
		</tr>
	</thead>

	<tbody id="availability_results">
		<tr id="row_<?php echo $resource->resource_id; ?>">
			
			<?php 
			$available = TRUE;
			$max = $guests;
			$single_total = 0;

			for($i = 1; $i <= $duration; $i++) { ?>

			<td class="align_center">
				
				<?php
				$b = ( ! empty($availability[$i]->bookings_pending)) ? $availability[$i]->bookings + $availability[$i]->bookings_pending + $availability[$i]->bookings_unverified  : $availability[$i]->bookings + $availability[$i]->bookings_unverified;
				
				$a = ($availability[$i]->release - $b) * $resource->resource_booking_footprint;

				$e = $availability[$i]->release - $b;

				if($e > 0)
				{
					

					echo '<i class="icon-ok"></i><br />';

					echo $e . ' ' . $resource->resource_priced_per . ' available<br />';

					$single_total += $availability[$i]->price;			

					echo '&pound;' . as_currency($availability[$i]->price) . ' per ' . $resource->resource_priced_per;

					$max = ($a < $max) ? $a : $max;
				} else
				{
					echo '<i class="icon-remove"></i><br />&nbsp;';
					$available = FALSE;
					$max = 0;
				}
				?>		
			</td>
			<?php } ?>
			
			<td>
				<?php if($available) { ?>
				
				<?php
				$dropdown[0] = 'Select...';

				for($d = 1; $d <= $max; $d++)
				{
					$dropdown[$d] = $d . ' guests (' . ceil($d / $resource->resource_booking_footprint) . ' ' . $resource->resource_priced_per . ')';
				}

				$js = 'class="span2" onchange="updateRow(' . $resource->resource_id . ');"';

				echo form_dropdown("resource[{$resource->resource_id}][guests]", $dropdown, 0, $js);
				
				echo form_hidden("resource[{$resource->resource_id}][resource_title]", $resource->resource_title);
				echo form_hidden("resource[{$resource->resource_id}][resource_single_price]", $single_total);
				echo form_hidden("resource[{$resource->resource_id}][resource_first_night]", $availability[1]->price);
				echo form_hidden("resource[{$resource->resource_id}][resource_booking_footprint]", $resource->resource_booking_footprint);
				echo form_hidden("resource[{$resource->resource_id}][resource_priced_per]", $resource->resource_priced_per);


				} else { ?>
				No availability
				<?php } ?>
			</td>
		</tr>
	
	</tbody>
</table>

</div>
<?php } ?>

<div id="booking_total" style="display: none;">
	<h2 class="page-header">New Booking</h2>

	<table class="table">
		<thead>
			<tr>
				<th>Room Type</th>
				<th>Quantity</th>
				<th>Guests</th>
				<th>Total</th>
			</tr>
		</thead>
	
		<tbody id="booking_breakdown">
		
		</tbody>
	</table>

	<button type="submit" class="btn btn-primary" id="submit_btn" style="display: none;">BOOK NOW</button>
</div>

</form>

<!-- Page specific Javascript -->
<script type="text/javascript">
<!--
	var guest_array = new Array();
	var price_array = new Array();

	var total_guests = 0;
	var total_price = 0;

	function updateRow(id)
	{
		// Let's get some values...
		var guests = $('select[name="resource[' + id + '][guests]"]').val();

		if(guests == 0)
		{
			$('#total_row_' + id).remove();
			guest_array[id] = price_array[id] = 0;
		} else {
			var title = $('input[name="resource[' + id + '][resource_title]"]').val();
			var single_total = $('input[name="resource[' + id + '][resource_single_price]"]').val();
			var footprint = $('input[name="resource[' + id + '][resource_booking_footprint]"]').val();
			var priced_per = $('input[name="resource[' + id + '][resource_priced_per]"]').val();

			// These can probably be done on the fly...
			var resources = Math.ceil(guests / footprint);
			var resource_total = resources * single_total;

			//alert(resource_total);
			guest_array[id] = guests;
			price_array[id] = resource_total;


			var table_row = '<tr id="total_row_' + id + '">';
			table_row += '<td>' + title + '</td>';
			table_row += '<td>' + resources + '</td>';
			table_row += '<td>' + guests + '</td>';
			table_row += '<td>&pound;' + resource_total.toFixed(2) + '</td>';
			table_row += '</tr>';

			if($('#total_row_' + id).length == 0)
			{
				$('#booking_breakdown').prepend(table_row);
			} else
			{
				$('#total_row_' + id).replaceWith(table_row);
			}

			$('#booking_total').show();

			// Scroll down...
			$('html, body').animate({
		         scrollTop: $("#booking_total").offset().top
		     }, 1000);

		}

		// Add up the totals
		var index;
		total_guests = 0;
		
		for (index in guest_array) {
		    total_guests += Number(guest_array[index]);
		}

		if(total_guests > 0)
		{
			

			var index;
			total_price = 0;
			
			for (index in price_array) {
			    total_price += Number(price_array[index]);
			}

			var table_row = '<tr id="grand_total_row">';
			table_row += '<td><strong>Grand Total</strong></td>';
			table_row += '<td></td>';
			table_row += '<td><strong>' + total_guests + '</strong></td>';
			table_row += '<td><strong>&pound;' + total_price.toFixed(2) + '</strong></td>';
			table_row += '</tr>';
		} else
		{
			var table_row = '<tr id="grand_total_row">';
			table_row += '<td colspan="4">Please select a room...</td>';
			table_row += '</tr>';
		}

		if($('#grand_total_row').length == 0)
		{
			$('#booking_breakdown').append(table_row);
		} else
		{
			$('#grand_total_row').replaceWith(table_row);
		}

		if(total_guests > 0)
		{
			$('#submit_btn').show();
		} else
		{
			$('#submit_btn').hide();
		}
	}

	function checkGuests(guests)
	{
		if(guests == total_guests)
		{
			return true;
		} else
		{
			return confirm('You are making a booking for ' + total_guests + ' guests (you originally requested ' + guests + '). Is this correct?');
		}
	}
-->
</script>
<!-- // -->