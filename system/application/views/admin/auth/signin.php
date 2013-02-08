<div class="row">
	<div class="span8">
		<?php echo form_open('signin?redirect=' . $this->input->get('redirect'), array('class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Please Signin</legend>

				<?php echo $template['partials']['form_errors']; ?>

				<div class="control-group">
		        	<label class="control-label">Email address or username</label>
		        	<div class="controls">
		            	<input type="text" class="span4" name="username" />
		            </div>
		        </div>

		        <div class="control-group">
		        	<label class="control-label">Password</label>
		        	<div class="controls">
		            	<input type="password" class="span4" name="password" />
		            </div>
		        </div>
			</fieldset>

			<div class="form-actions">
		        <button type="submit" class="btn btn-primary">Signin</button>&nbsp;&nbsp;<?php echo anchor('forgotten_password', 'Forgotten Password?'); ?>
		    </div>
		</form>
	</div>
</div>
