<?php echo $template['partials']['cart']; 

$_r = booking('resources');

$_arrive = ( ! empty($_r)) ? mysql_to_format($_r[0]->reservation_start_at, 'd/m/Y') : null;
$_duration = ( ! empty($_r)) ? $_r[0]->reservation_duration : 1;
$_guests = (booking('booking_guests')) ? booking('booking_guests') : 1;

?>

	<h1 class="page-header">Search for Availability</h1>

	<?php echo $template['partials']['form_errors']; ?>

	<?php echo form_open('admin/salesdesk/search#results', array('class' => 'form-horizontal')); ?>

	<fieldset>
		

		<div class="control-group">
			<label class="control-label" for="start_datepicker">Arrival Date</label>
			<div class="controls">
				<?php
				echo form_input(array(
					'name'	=> 'start_at',
					'id'	=> 'start_datepicker',
					'class'	=> 'span2',
					'value'	=> set_value('start_at', $_arrive),
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
			
				echo form_dropdown('duration', $nights, set_value('duration', $_duration), 'class="span1"');
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
			
				echo form_dropdown('guests', $g, set_value('guests', $_guests), 'class="span1"');
				?>
			</div>
		</div> <!-- /clearfix -->

		<div class="control-group">

			<div class="controls">
				<button type="submit" class="btn btn-primary">Search</button>
			</div>
		</div>
	</fieldset>

	</form>

	<!-- start: sales desk results -->
	<?php if( ! empty($resources)) { ?>
	<div id="results">

		<h2 class="page-header">Search Results</h2>


			<?php $this->load->view('admin/salesdesk/search'); ?>




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