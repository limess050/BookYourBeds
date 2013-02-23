<?php echo $template['partials']['cart']; 

?>

	<h1 class="page-header">Optional Supplements</h1>

	<?php echo $template['partials']['form_errors']; ?>

	<?php echo form_open('admin/salesdesk/supplements', array('class' => 'form-horizontal')); ?>

	<table class="table">
	<?php foreach($supplements as $supplement) { ?>
	<tr>
		<td><h4><?php echo $supplement->supplement_short_description; ?> <small>&pound;<?php echo as_currency($supplement->resource_price); ?></small></h4></td>
		<td></td>
		<td></td>
	</tr>


	<?php } ?>
	</table>
	
	<pre>
		<?php print_r($supplements); ?>

	</pre>
	
		
	<div class="control-group">

		<div class="controls">
			<button type="submit" class="btn btn-primary">Continue</button>
		</div>
	</div>

	</form>

