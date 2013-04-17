<h1 class="page-header">Seasons</h1>

<?php echo $template['partials']['settings_menu']; ?>

<div class="row">
	<div class="span4">
		<h2>Your Seasons</h2>
		<!--<p>All forms are given default styles to present them in a readable and scalable way.</p>-->

		<p><?php echo anchor('admin/seasons/create', 'Create New Season', 'class="btn primary plus"'); ?></p>

		<?php if(count($seasons) > 1) { ?>
		<p>
			<a href="#" onclick="startSort(); return false;" id="start_sort" class="btn sort">Sort Season Order</a>
			<a href="#" onclick="finishSort(); return false;" class="btn btn-success finish_sort tick" style="display: none;">Finish Sorting</a>
			<a href="<?php echo site_url('admin/seasons'); ?>"class="btn finish_sort cross" style="display: none;">Cancel</a>
		</p>
		<?php } ?>
	</div>
	
	<div class="span8">

<?php echo form_open('admin/seasons', array('id' => 'season_form')); ?>
<table id="season_list" class="table table-condensed table-striped table-hover">
	<thead>
		<tr>
			<th>Season Name</th>
			<th>Start</th>
			<th>End</th>
		</tr>
	</thead>
	
	<tbody>
		<?php foreach($seasons as $season) { ?>
		<tr>
			<td><?php 
			echo anchor("admin/seasons/edit/{$season->season_id}", $season->season_title); 

			echo form_hidden("season[{$season->season_id}][season_id]", $season->season_id);		
			?>
			<input type="hidden" name="<?php echo "season[{$season->season_id}][season_sort_order]"; ?>" value="<?php echo $season->season_sort_order; ?>" class="sort_order" />
			</td>
			<td><?php echo mysql_to_format($season->season_start_at); ?></td>
			<td><?php echo mysql_to_format($season->season_end_at); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</form>
</div>
</div>

<script type="text/javascript">
<!--
function startSort()
{
	$('#start_sort').hide();
	$('.finish_sort').show();

	$("#season_list tbody").tableDnD(
		{
		onDragClass: "success"
		});
}

function finishSort()
{
	var i = 0;
	$("#season_list tbody tr input.sort_order").each(function() {
		$(this).val(i);
		i++;
	});

	$('#season_form').submit();
}


-->
</script>