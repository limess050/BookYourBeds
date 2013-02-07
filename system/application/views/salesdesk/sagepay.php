
<form action="<?php echo SAGEPAY_ENDPOINT; ?>" method="POST" id="SagePayForm" name="SagePayForm" class="form-horizontal">
	<?php

	echo form_hidden('VPSProtocol', '2.23');
	echo form_hidden('TxType', 'PAYMENT');
	echo form_hidden('Vendor', SAGEPAY_VENDOR);
	echo form_hidden('Crypt', $crypt);
	?>

	<button type="submit" class="btn btn-success">If you are not automatically redirected to SagePay click here...</button>
</form>