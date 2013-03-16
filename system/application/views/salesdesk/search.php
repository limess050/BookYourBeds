<?php echo form_open('salesdesk/new_booking', array('method' => 'POST'), array('guests' => $guests)); ?>

<strong>Prices shown are for <?php echo $guests . ' ' . (($guests > 1) ? 'guests' : 'guest'); ?></strong>

<?php foreach($resources as $resource) { 
			
$availability =& $resource->availability; ?>
<div>
	<h4><?php echo $resource->resource_title; ?> <small><?php
				$footprint = ceil($guests / $resource->resource_booking_footprint);
				echo "<strong>{$footprint} {$resource->resource_priced_per}" . (($footprint > 1) ? 's' : '') . "</strong>";
				?></small></h4>

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
			$total_price = 0;

			for($i = 1; $i <= $duration; $i++) { ?>

			<td class="align_center<?php echo ((strtotime('+' . ($i - 1) . ' day', $start_timestamp)) < $today) ? ' disabled' : ''; ?>">
				
				<?php
				$b = ( ! empty($availability[$i]->bookings_pending)) ? $availability[$i]->bookings + $availability[$i]->bookings_pending + $availability[$i]->bookings_unverified  : $availability[$i]->bookings + $availability[$i]->bookings_unverified;
				
				if($footprint <= ($availability[$i]->release - $b))
				{
					

					echo '<i class="icon-ok"></i><br />&nbsp;';

					$day_total = $footprint * $availability[$i]->price;
					$total_price += $day_total;					

					echo '&pound;' . as_currency($day_total);
					$js = 'onClick="radioClicked(this);"';
					
					$js .= ' style="display: none;"';

					echo form_radio('day[' . $i . ']', $resource->resource_id, false, $js);
					//echo form_hidden('day[' . $i . ']', $resource->resource_id, false, $js);
					
				} else
				{
					$day_total = 0;
					echo '<i class="icon-remove"></i><br />&nbsp;';
					$available = FALSE;
				}
				
				echo form_hidden("resource[{$resource->resource_id}][day][{$i}][resource_id]", $resource->resource_id);
				echo form_hidden("resource[{$resource->resource_id}][day][{$i}][timestamp]", strtotime('+' . ($i - 1) . ' day', $start_timestamp));
				echo form_hidden("resource[{$resource->resource_id}][day][{$i}][footprint]", $footprint);
				echo form_hidden("resource[{$resource->resource_id}][day][{$i}][priced_per]", $resource->resource_priced_per);			
				?>

				<input type="hidden" name="<?php echo "resource[{$resource->resource_id}][day][{$i}][price]"; ?>" value="<?php echo $day_total; ?>" class="this_price" />			
			</td>
			<?php } ?>
			
			<td>
				<?php if($available) { ?>
				<a href="#" class="btn btn-large btn-success" onclick="fillRow(<?php echo $resource->resource_id; ?>); return false;">&pound;<?php echo as_currency($total_price); ?></a>
				<?php } ?>
			</td>
		</tr>
	
	</tbody>
</table>

</div>
<?php } ?>

<div id="price_total" style="display: none;">
	<h2 class="page-header">Your Booking</h2>

	<table class="table">
		<thead>
			<tr>
				<th>Room Type Chosen</th>
				<th class="pricing_subtotal">Nights</th>
				<th class="pricing_subtotal">Total</th>
			</tr>
		</thead>
	
		<tbody id="price_breakdown">
		
		</tbody>
	</table>


	<div class="actions" style="text-align: right;">
		<input type="hidden" name="price_total" id="price_total_field" />
		<input type="hidden" name="price_deposit" id="price_deposit_field" />
		<input type="hidden" name="price_first_night" id="price_first_night_field" />
		
		<?php /*switch (setting('deposit')) {
			case 'full':
				echo "Full payment required: <strong id=\"deposit_amount\" style=\"text-decoration: underline;\"></strong>&nbsp;&nbsp;&nbsp;";
				break;

			case 'first':
				echo "First Night as Deposit/Downpayment: <strong id=\"deposit_amount\" style=\"text-decoration: underline;\"></strong>&nbsp;&nbsp;&nbsp;";
				break;

			case 'fraction':
				echo setting('deposit_percentage') . "% total cost as Deposit/Downpayment: <strong id=\"deposit_amount\" style=\"text-decoration: underline;\"></strong>&nbsp;&nbsp;&nbsp;";
				break; 
		} */ ?>
		
		<button type="submit" class="btn btn-primary">Book Now</button>
	</div>

	
</div>
</form>

<!-- start: page-specific javascript -->
<script type="text/javascript">
<!--
	var resources = new Array();
	<?php foreach($resources as $resource) { ?>
	resources[<?php echo $resource->resource_id; ?>] = '<?php echo $resource->resource_title; ?>';	
	<?php } ?>
	
	var duration = <?php echo $duration; ?>;
	
	function fillRow(id)
	{
		
		$('#availability_results input[type="radio"]').each(function()
		{
			
			var the_name = $(this).attr('name');
			
			
			$('input[name="' + the_name + '"]').each(function()
			{
				
				$(this).removeClass('on');
				$(this).prop("checked", false);
			
				if($(this).val() == id)
				{
					
					$(this).prop("checked", true);
					$(this).addClass('on');
				}
			});
		}
		);

		$('#availability_results tr').each(function()
		{
			$(this).removeClass('success');
		});

		$('#availability_results tr#row_' + id).addClass('success');

		calculatePrice();
	}
	
	function radioClicked(elem)
	{
		if($(elem).hasClass('on'))
		{
			$(elem).removeClass('on');
			$(elem).attr('checked', '');
		} else
		{
			var the_name = $(elem).attr('name');
			$('input[name="' + the_name + '"]').each(function()
			{
				$(this).removeClass('on');
			});
			
			$(elem).addClass('on');
		}
		
		
		calculatePrice();
	}
	
	function calculatePrice()
	{

		var nights = new Array();
		var prices = new Array();
		
		var total_price = 0;
				
		var first_night = '';
		
		for(i = 1; i <= duration; i++)
		{			
			$('#availability_results input[name="day[' + i + ']"]').each(function()
			{
				if($(this).is(':checked'))
				{
					var this_price = $('input[type="hidden"].this_price', $(this).parent()).val();

					total_price += Number(this_price);

					if(nights[$(this).val()] == undefined)
					{
						nights[$(this).val()] = 0;
						prices[$(this).val()] = 0;
					}

					nights[$(this).val()]++;
					prices[$(this).val()] += Number(this_price);

					var the_name = $(this).attr('name');

					if(first_night == '')
					{
						first_night = Number(this_price);
					}
				}
			});
		}
		/*
		$('#availability_results input[type="radio"]').each(function()
		{
			if($(this).is(':checked'))
			{
				var this_price = $('input[type="hidden"].this_price', $(this).parent()).val();
				
				total_price += Number(this_price);
				
				if(nights[$(this).val()] == undefined)
				{
					nights[$(this).val()] = 0;
					prices[$(this).val()] = 0;
				}
				
				nights[$(this).val()]++;
				prices[$(this).val()] += Number(this_price);
				
				var the_name = $(this).attr('name');
								
				if(n == 1)
				{
					first_night = Number(this_price);
				}
				
				n++;
			}
		}
		);*/
		
		//console.log(nights);
		//console.log(prices);
		
		//console.log(total_price);
		//console.log(first_night);
		
		$('#price_total').fadeIn();
		
		var table_body = '';
		
		for(var room in nights)
		{
			//console.log(nights[room]);
			table_body += '<tr><td>' + resources[room] + '</td><td>' + nights[room] + '</td><td>&pound;' + prices[room].toFixed(2) + '</td></tr>';
		}
		
		//table_body += '<tr class="total_row"><td colspan="2" class="align_right">Total:</td><td><strong>&pound;' + total_price.toFixed(2) + '</strong></td></tr>';
		
		$('#price_total_field').val(total_price);
		$('#price_breakdown').html(table_body);
		
		<?php switch (setting('deposit')) {
			case 'none': ?>
		$('#price_deposit_field').val('0');
		$('#deposit_amount').html('0');
		<?php break; ?>
		<?php case 'full': ?>
		$('#price_deposit_field').val(total_price);
		$('#deposit_amount').html(total_price.toFixed(2));
		<?php break; ?>
		<?php case 'first': ?>
		$('#price_deposit_field').val(first_night);
		$('#deposit_amount').html(first_night.toFixed(2));
		<?php break; ?>
		<?php case 'fraction': ?>
		var deposit = total_price * <?php echo setting('deposit_percentage') / 100; ?>;
		$('#price_deposit_field').val(deposit);
		$('#deposit_amount').html('&pound;' + deposit.toFixed(2));
		<?php break; ?>
		<?php } ?>
		
		$('input[name="price_first_night"]').val(first_night);

		//$('#price_deposit_field').val(first_night);
		//$('#deposit_amount').html(first_night.toFixed(2));
		//var deposit = total_price * 0.1;
		//$('#price_deposit_field').val(deposit);
		//$('#deposit_amount').html('&pound;' + deposit.toFixed(2));
	}

	/*function submitForm(elem)
	{
		
		$('input[type="radio"]', elem).each(function() {
			$(this).attr('disabled', ! this.checked);
		});

		//$(elem).submit();
		return true;
	}*/
	
	
-->
</script>
<!-- end: page-specific javascript -->