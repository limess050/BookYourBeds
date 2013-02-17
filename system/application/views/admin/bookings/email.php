<div class="page-header row">
	<div class="pull-left">
		<h1>Booking <small><?php echo $booking->booking_reference; ?></small></h1>
	</div>


	<?php echo $template['partials']['booking_button_group']; ?>
</div>

<?php echo form_open('admin/bookings/email/' . $booking->booking_id, 'class="form-horizontal" method="POST"'); ?>

<?php echo $template['partials']['form_errors']; ?>

<fieldset>
	<legend>Email Booking Details</legend>

    <div class="control-group">
    	<label class="control-label">Email Address(es)</label>
    	<div class="controls">
    		<input type="text" class="span4" name="email" value="<?php echo set_value('email', $booking->customer->customer_email); ?>" /> *
			<span class="help-block">Separate multiple email addresses with commas</span>

    	</div>

    </div>

    <div class="control-group">
    	<label class="control-label">Subject</label>
    	<div class="controls">
    		<input type="text" class="span4" name="subject" value="<?php echo set_value('subject', 'Your booking with ' . $booking->account_name); ?>" /> *
    	</div>

    </div>

    <div class="control-group">
    	<label class="control-label">Additional Message</label>
    	<div class="controls">
    		<?php echo form_textarea(array(
											'name'	=> 'message',
											'class'	=> 'span4',
											'rows'	=> 4,
											'value'	=> set_value('message')
											));
						?>
    	</div>

    </div>


	<div class="control-group">

		<div class="controls">
			<button type="submit" class="btn btn-primary">Send Booking Details</button>
		</div>
	</div>
</fieldset>

</form>