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
	<h1>Room Pricing <small><?php echo $resource->resource_title; ?></small></h1>
</div>

<?php echo $template['partials']['resource_menu']; ?>

<?php echo form_open("admin/resources/price/{$resource->resource_id}", '', array('resource_id' => $resource->resource_id)); ?>
<div class="row">
	<div class="span4 columns">
		<h2>Default Pricing</h2>
		<p>All forms are given default styles to present them in a readable and scalable way.</p>
	</div>
	
	<div class="span8 columns">

<?php echo validation_errors(); ?>	

<table class="table">
	<thead>
		<tr>
			<th>Monday</th>
			<th>Tuesday</th>
			<th>Wednesday</th>
			<th>Thursday</th>
			<th class="weekend">Friday</th>
			<th class="weekend">Saturday</th>
			<th>Sunday</th>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td class="today">
				<?php
				$input = array(
					'name'	=> 'season[0][1][price]',
					'class'	=> 'season_0 first_price span1',
					'value'	=> set_value('season[0][1][price]', 
										as_currency(
											(isset($default[1]->price_price)) ? $default[1]->price_price : ''))
				);
				
				echo form_input($input);
				echo (isset($default[1]->price_price)) ? form_hidden('season[0][1][id]', $default[1]->price_id) : '';

				?>
				<!--<a href="#" onclick="$('.season_0').val($('.season_0.first').val()); return false;" class="link_fields" title="Link">j</a>-->
			</td>
			<?php for($i = 2; $i <= 7; $i++) { ?>
			<td<?php echo ($i == 5 || $i == 6) ? ' class="weekend"' : ''; ?>>
				<?php
				$input = array(
					'name'	=> "season[0][{$i}][price]",
					'class'	=> 'season_0 span1',
					'value'	=> set_value("season[0][{$i}][price]", 
										as_currency(
											(isset($default[$i]->price_price)) ? $default[$i]->price_price : ''))
				);
				
				echo form_input($input);
				echo (isset($default[$i]->price_price)) ? form_hidden("season[0][{$i}][id]", $default[$i]->price_id) : '';
				?>
			</td>
			<?php } ?>
			
		</tr>

		<tr>
			<td colspan="7"><button type="submit" class="btn primary">Save Changes</button></td>
		</tr>
	</tbody>
</table>
</div>
</div>

<div class="row">
	<div class="span4 columns">
		<h2>Seasonal Pricing</h2>
		<p>All forms are given default styles to present them in a readable and scalable way.</p>

		<p><?php echo anchor('admin/seasons/create', 'Create New Season', 'class="btn primary"'); ?></p>
	</div>
	
	<div class="span8 columns">


<?php foreach($seasons as $season) { ?>
<h3><?php echo $season->season_title . ' (' . mysql_to_format($season->season_start_at) . ' - ' . mysql_to_format($season->season_end_at) . ')'; ?></h3>

<table class="table">
	<thead>
		<tr>
			<th>Monday</th>
			<th>Tuesday</th>
			<th>Wednesday</th>
			<th>Thursday</th>
			<th class="weekend">Friday</th>
			<th class="weekend">Saturday</th>
			<th>Sunday</th>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td class="today">
				<?php
				$input = array(
					'name'	=> "season[{$season->season_id}][1][price]",
					'class'	=> "season_{$season->season_id} first_price span1",
					'value'	=> set_value("season[{$season->season_id}][1][price]", 
										(isset($season->prices[1]->price_price)) ? as_currency($season->prices[1]->price_price) : ''
										)
				);
				
				echo form_input($input);
				echo (isset($season->prices[1]->price_id)) ? form_hidden("season[{$season->season_id}][1][id]", $season->prices[1]->price_id) : '';
				?>

				<!--<a href="#" onclick="$('.season_<?php echo $season->season_id; ?>').val($('.season_<?php echo $season->season_id; ?>.first_price').val()); return false;" class="link_fields" title="Link">j</a>-->
			</td>
			<?php for($i = 2; $i <= 7; $i++) { ?>
			<td<?php echo ($i == 5 || $i == 6) ? ' class="weekend"' : ''; ?>>
				<?php
				$input = array(
					'name'	=> "season[{$season->season_id}][{$i}][price]",
					'class'	=> "season_{$season->season_id} span1",
					'value'	=> set_value("season[{$season->season_id}][{$i}][price]", 
										(isset($season->prices[$i]->price_price)) ? as_currency($season->prices[$i]->price_price) : ''
										)
				);
				
				echo form_input($input);
				echo (isset($season->prices[$i]->price_id)) ? form_hidden("season[{$season->season_id}][{$i}][id]", $season->prices[$i]->price_id) : '';
				?>
				
			</td>
			<?php } ?>
			
		</tr>

		<tr>
			<td colspan="7"><button type="submit" class="btn primary">Save Changes</button></td>
		</tr>
	</tbody>
</table>

<?php } ?>

</form>

</div>
</div>