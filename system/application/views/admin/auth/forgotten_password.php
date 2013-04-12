<?php if(empty($sent)) { ?>
<div class="row">
	<div class="span8 offset2">
		<?php echo form_open('forgotten_password', array('class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Forgotten Password</legend>

				<?php echo $template['partials']['form_errors']; ?>

				<div class="control-group">
		        	<label class="control-label">Email address</label>
		        	<div class="controls">
		            	<input type="text" class="span4" name="email" />
		            </div>
		        </div>
			</fieldset>

			<div class="form-actions">
		        <button type="submit" class="btn  btn-warning btn-large">Send reset code</button>
		    </div>
		</form>
	</div>
</div>
<?php } else { ?>
<p>An email has been sent to <?php echo $this->input->post('email'); ?>.</p>

<?php } ?>
