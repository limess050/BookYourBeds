<li class="well well-small media">
	<a class="pull-left" href="#">
		<img class="media-object" data-src="holder.js/64x64">
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Launch your site!</h4>

		<p>It looks like you're ready to go!  When your own personal site is launched you can access it at the following address:</p>

		<div class="alert">
			<h2><?php echo site_url(); ?></h2>
		</div>

		<?php echo form_open('admin/dashboard/wizard', 'class="form-horizontal" id="launch_form"', array('_form' => 'launch')); ?>

			<button type="submit" class="btn btn-success btn-large">Launch Your Site</button>
		</form>
	</div>
</li>