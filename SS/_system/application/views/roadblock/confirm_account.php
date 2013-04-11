<h1 class="page-header">Confirm Account</h1>

<?php if(! empty($account)) { ?>
<p>Thank you for confirming the account - <?php echo $account->account_name; ?>.</p>

<p><a href="<?php echo site_url($account->account_slug . '/admin'); ?>" class="btn btn-primary">Go to your account</a></p>

<?php } else { ?>
<p>Account not found!</p>

<?php } ?>
