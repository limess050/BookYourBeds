<div class="row">
	<div class="span8 offset2">
		<?php echo form_open('signup', array('class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Create Your New Account</legend>

				<?php echo $template['partials']['form_errors']; ?>

				<div class="control-group">
		        	<label class="control-label">Your Name</label>
		        	<div class="controls">
		            	<?php
		        		echo form_input(array(
		        						'name'	=> 'name',
		        						'class'	=> 'span3',
		        						'value'	=> set_value('name')
		        						));
		        		?>
		            </div>
		        </div>

				<div class="control-group">
		        	<label class="control-label">Email address</label>
		        	<div class="controls">
		        		<?php
		        		echo form_input(array(
		        						'name'	=> 'email',
		        						'class'	=> 'span4',
		        						'value'	=> set_value('email')
		        						));
		        		?>
		            </div>
		        </div>

		        <div class="control-group">
		        	<label class="control-label">Password</label>
		        	<div class="controls">
		            	<?php
		        		echo form_password(array(
		        						'name'	=> 'password',
		        						'class'	=> 'span4',
		        						'value'	=> set_value('password')
		        						));
		        		?>
		            </div>
		        </div>

		        <div class="control-group">
		        	<div class="controls">
		            	<div class="alert alert-warning">
		            		By clicking 'Continue' below you are agreeing to the <strong>Book Your Beds</strong> <a href="/terms.html" data-target="#myModal" data-toggle="modal">Terms &amp; Conditions</a>.
						</div>

						<div class="alert alert-success">
							Need help getting started? Follow our <?php echo anchor('getting_started_guide', 'Getting Started', 'style="color: #468847; font-weight: bold;"'); ?> guide <?php echo anchor('getting_started_guide', 'here', 'style="color: #468847; font-weight: bold;"'); ?>!
						</div>
		            </div>
		        </div>
			</fieldset>

			<div class="form-actions">
		        <button type="submit" class="btn btn-warning btn-large">Continue</button>
		    </div>
		</form>
	</div>
</div>

<div class="modal hide" id="myModal"> 
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
    	<h3>Book Your Beds Terms and Conditions</h3>
  	</div>
  
  	<div class="modal-body">
    	
  	</div>
  
  	<div class="modal-footer">
    	<a href="#" class="btn btn-warning" data-dismiss="modal">OK</a>
  	</div>
</div>
