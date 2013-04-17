<h1 class="page-header">Settings</h1>

<?php echo $template['partials']['settings_menu']; ?>

		<h2>Create Season</h2>
	

		<?php echo validation_errors(); ?>

		<?php echo form_open("admin/seasons/create", 'class="form-horizontal"', array('season[season_account_id]' => account('id'))); ?>
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="season_title">Season Title</label>
					<div class="controls">
						<?php
						echo form_input(array(
							'name'	=> 'season[season_title]',
							'id'	=> 'season_title',
							'class'	=> 'xlarge',
							'value'	=> set_value('season[season_title]')
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
							'value'	=> set_value('season[season_start_at]')
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
							'value'	=> set_value('season[season_end_at]')
							));
						?>
					</div>
				</div> <!-- /clearfix -->

				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-primary">Create Season</button>
					</div>
				</div>
			</fieldset>

		</form>

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