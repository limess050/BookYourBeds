<h2>Welcome to BookYourBeds</h2>

<p>Thank you for creating your new account - <strong><?php echo $account->account_name; ?></strong>.</p>

<p>You can sign in to your new account and get started at <?php echo anchor('signin'); ?> using the details you used when creating your account:<br />
Email Address: <strong><?php echo $email; ?></strong><br />
Password: <strong><?php echo $password; ?></strong></p>

<p>You will also need to confirm your account by visiting the following link in your browser: <?php echo anchor('confirm_account?auth=' . $account->account_confirmation_code, null, null, FALSE); ?></p>

<p>Thanks!</p>

<p><strong>The BookYourBeds Team</strong></p>