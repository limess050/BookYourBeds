Welcome to BookYourBeds<br /><br />

Thank you for creating your new account - <?php echo $account->account_name; ?>.<br /><br />

You can sign in to your new account and get started at <?php echo anchor('signin'); ?> using the details you used when creating your account:<br />
Email Address: <?php echo $email; ?><br />
Password: <?php echo $password; ?><br /><br />

You will also need to confirm your account by visiting the following link in your browser: <?php echo anchor('confirm_account?auth=' . $account->account_confirmation_code, null, null, FALSE); ?><br /><br />

Thanks!<br /><br />

The BookYourBeds Team