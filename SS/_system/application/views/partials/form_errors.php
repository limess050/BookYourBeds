<?php 
$errors = validation_errors();
if (!empty($errors) || !empty($error)) { ?>
<div class="alert alert-error">
	<ul>
		<?php if (!empty($errors)) { echo validation_errors(); } ?>
		<?php echo (!empty($error)) ? '<li>' . $error . '</li>' : '' ; ?>
	</ul>
</div>
<?php } ?>