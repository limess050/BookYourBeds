<li class="well well-small media">
	<a class="pull-left" href="#">
		<img class="media-object" data-src="holder.js/64x64">
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Confirm Account Email Address</h4>

		<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p>

		<?php echo form_open('admin/dashboard/wizard', 'class="form-horizontal"', array('_form' => 'confirm_email')); ?>
			<div class="input-append">
			  <?php echo form_input(array(
			  					'name'	=> 'confirm_email',
			  					'class'	=> 'span3',
			  					'value'	=> set_value('confirm_email', account('email'))
			  					)); ?>
			  <button class="btn" type="submit">Send confirmation email again</button>
			</div>
		</form>
	</div>
</li>