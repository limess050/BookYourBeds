<li class="well well-small media">
	<a class="pull-left" href="#">
		<img src="/assets/img/wizard/email-icon.png" />
	</a>
	
	<div class="media-body">
		<h4 class="media-heading">Confirm Account Email Address</h4>

		<p>Please check the email address below is correct; if so go to your email inbox, and click on the account confirmation link within the welcome email.  Once you’ve confirmed your account, close this browser tab/window, and continue your account set up.</p>

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