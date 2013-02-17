<div class="page-header row">
	<h1>Seasons</h1>
</div>

<div class="row">
	<div class="span4">
		<h2>Edit Season</h2>
		<!--<p>All forms are given default styles to present them in a readable and scalable way.</p>-->

	</div>
	
	<div class="span8">

		<?php echo validation_errors(); ?>

		<?php echo form_open("admin/seasons/edit/{$season->season_id}", 'class="form-horizontal"', array('season_id' => $season->season_id)); ?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="season_title">Season Title</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'season[season_title]',
							'id'	=> 'season_title',
							'class'	=> 'xlarge',
							'value'	=> set_value('season[season_title]', $season->season_title)
							));
						?>
					</div>
				</div> <!-- /clearfix -->

				<div class="control-group">
					<label class="control-label" for="start_datepicker">Season Starts</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'season[season_start_at]',
							'id'	=> 'start_datepicker',
							'class'	=> 'medium',
							'value'	=> set_value('season[season_start_at]', mysql_to_format($season->season_start_at, 'd/m/Y'))
							));
						?>
					</div>
				</div> <!-- /clearfix -->

				<div class="control-group">
					<label class="control-label" for="end_datepicker">Season Ends</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'season[season_end_at]',
							'id'	=> 'end_datepicker',
							'class'	=> 'medium',
							'value'	=> set_value('season[season_end_at]', mysql_to_format($season->season_end_at, 'd/m/Y'))
							));
						?>
					</div>
				</div> <!-- /clearfix -->

				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-primary">Save Changes</button>
					</div>
				</div>
			</fieldset>

		</form>
	</div>
</div>

<!-- start: page-specific javascript -->
<script type="text/javascript">
<!--
	$(function(){
			
		$('#start_datepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			firstDay: 1,
			dateFormat: 'dd/mm/yy',
			showAnim: 'fadeIn',
			showOn: 'button',
			buttonText: '<i class="icon-calendar"></i>',
			onClose: 
			function(dateText, inst) 
			{ 
				var d = dateText.split('/'); 
				var ts = $.datepicker.formatDate('@', new Date(d[2], d[1] - 1, d[0]));
				var ds = 60 * 60 * 24 * 1000;
				
				var min_d = new Date();
				min_d.setTime(Number(ts) + (Number(ds) * 1));
				
				$('#end_datepicker').datepicker('option', 'minDate', min_d);
			}
		});
		
		$('#end_datepicker').datepicker({
			changeMonth: true,
			changeYear: true,
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