<h2>New BookYourBeds Account</h2>

<p>You have a new account signup:</p>

<p>Account Name: <strong><?php echo $account->account_name; ?></strong><br />
Email Address: <strong><?php echo $email; ?></strong><br />
Account ID: <strong><?php echo $account->account_id; ?></strong></p>

<p>More Details at <?php echo site_url('internal/accounts/edit/' . $account->account_id, FALSE); ?>.</p>