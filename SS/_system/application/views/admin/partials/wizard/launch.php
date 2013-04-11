<li class="well well-small media">
	<a class="pull-left" href="#">
		<img class="media-object" data-src="holder.js/64x64">
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Launch your site!</h4>

		<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p>

		<a href="#" onclick="$('#launch_form').slideToggle(); return false;">Do this...</a>

		<?php echo form_open('admin/dashboard/wizard', 'class="form-horizontal hide" id="launch_form"', array('_form' => 'launch')); ?>

			<button type="submit" class="btn btn-success btn-large">Launch Your Site</button>
		</form>
	</div>
</li>