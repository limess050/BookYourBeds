

<div class="page-header row">
	<div class="pull-left">
		<h1>Transfer <?php echo (! is_verified($booking)) ? 'Unverified ' : ''; ?>Booking <small><?php echo $booking->booking_reference; ?></small></h1>
	</div>


	<?php echo $template['partials']['booking_button_group']; ?>

</div>

<div class="row">
	<div class="span7">
		<div class="heavy-border">
			<h3>Current Booking Overview</h3>

			<?php echo (is_cancelled($booking)) ? '<span class="label label-important">BOOKING CANCELLED ON ' . strtoupper(mysql_to_format($booking->booking_deleted_at, 'l, j F Y')) . '</span>' : ''; ?>

			<dl class="dl-horizontal">
				<dt>Booking Reference</dt>
				<dd><?php echo $booking->booking_reference; ?></dd>

				<?php foreach($booking->resources as $resource) { ?>
				<dt>Room</dt>
				<dd><?php echo $resource->resource_title; ?></dd>
				
				<dt>Arrival</dt>
				<dd>
					<?php 
					echo anchor('admin/bookings?timestamp=' . human_to_unix($resource->reservation_start_at), mysql_to_format($resource->reservation_start_at, 'l, j F Y')); 
					echo ($resource->reservation_start_at == date('Y-m-d 00:00:00') && $resource->reservation_checked_in) ? ' <span class="label label-success">CHECKED IN</span>' : '';
					?>
				</dd>

				<dt>Duration</dt>
				<dd><?php echo duration($resource->reservation_duration); ?></dd>

				<dt>Total Guests</dt>
				<dd><?php echo "{$booking->booking_guests} guest" . (($booking->booking_guests > 1) ? 's' : ''); ?> (<?php echo "{$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : ''); ?>)</dd>				
				<?php } ?>
			
				<dt>Total Cost</dt>
				<dd>&pound;<?php echo as_currency($booking->booking_price); ?></dd>

				<dt>Deposit Paid</dt>
				<dd>&pound;<?php echo as_currency($booking->booking_deposit); ?></dd>
			</dl>
		</div>		
	</div>

	<div class="span5">
	    <h3>New Booking Details</h3>

	    <?php echo $template['partials']['form_errors']; ?>

  		<?php echo form_open('admin/transfer/search/' . $booking->booking_id, array('class' => 'form-horizontal'), array('resource_id' => $booking->resources[0]->resource_id)); ?>

		<fieldset>
			

			<div class="control-group">
				<label class="control-label" for="start_datepicker">Arrival Date</label>
				<div class="controls">
					<?php
					echo form_input(array(
						'name'	=> 'start_at',
						'id'	=> 'start_datepicker',
						'class'	=> 'span2',
						'value'	=> set_value('start_at', mysql_to_format($booking->resources[0]->reservation_start_at, 'd/m/Y')),
						'placeholder'	=> 'dd/mm/yyyy'
						));
					?>
				</div>
			</div> <!-- /clearfix -->

			<div class="control-group">
				<label class="control-label" for="duration">Duration</label>
				<div class="controls">
					<?php
					for($i = 1; $i <= 7; $i++)
					{
						$nights[$i] = $i;
					}
					$nights[$i] = $i . '+';
				
					echo form_dropdown('duration', $nights, set_value('duration', $booking->resources[0]->reservation_duration), 'class="span1"');
					?> night(s)
				</div>
			</div> <!-- /clearfix -->
			
			<div class="control-group">
				<label class="control-label" for="guests">Number of Guests</label>
				<div class="controls">
					<?php
					for($i = 1; $i < 7; $i++)
					{
						$g[$i] = $i;
					}
					$g[7] = '+7';
				
					echo form_dropdown('guests', $g, set_value('guests', $booking->booking_guests), 'class="span1"');
					?>
				</div>
			</div> <!-- /clearfix -->

			<div class="control-group">

				<div class="controls">
					<button type="submit" class="btn btn-warning btn-large">Search</button>
				</div>
			</div>
		</fieldset>

		</form>
	</div>

</div>


	<!-- start: sales desk results -->
	<?php if( ! empty($resources)) { ?>
	<div id="results">

		<h2 class="page-header">Search Results</h2>


		<?php $this->load->view('admin/transfer/search'); ?>




		<div id="search_results">
			
		</div>
	</div>
	<?php } ?>


<!-- end: sales desk results -->

	
<!-- start: page-specific javascript -->
<script type="text/javascript">
<!--
	$(function(){
			
		$('#start_datepicker').datepicker({
			numberOfMonths: 3,
			minDate: 0,
			<?php /*switch(setting('availability_limit')) { 
				case 'date':
					echo 'maxDate: \'' . setting('availability_limit_date') . '\',';
					break;
				
				case 'fixed':
					echo 'maxDate: \'+' . setting('availability_fixed_value') . substr(setting('availability_fixed_unit'), 0) . '\',';
					break;

			}*/ ?>
			firstDay: 1,
			dateFormat: 'dd/mm/yy',
			showAnim: 'fadeIn',
			showOn: 'button',
			buttonText: '<i class="icon-calendar"></i>'
		});

		
		
	});
	

-->
</script>
<!-- end: page-specific javascript -->
