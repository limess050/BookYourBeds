<h2>Reset your BookYourBeds password</h2>

<p>A request has been made to reset the password for the user account with email address <?php echo $email; ?>. If you have not made this request just ignore this email. If you did make the request please visit the following link in your browser: <?php echo anchor('reset_password?auth=' . $auth, null, null, FALSE); ?>. This link will expire in 24 hours.</p>

<p>Thanks!</p>

<p><strong>The BookYourBeds Team</strong></p>