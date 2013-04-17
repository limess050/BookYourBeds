<div class="page-header clearfix">
	<h1 class="pull-left">
		Accounts
	</h1>

	<div class="pull-right">
		<a href="<?php echo site_url('internal/accounts/create'); ?>" class="btn btn-primary btn-large">Create New Account</a>
	</div>
</div>

<table class="table table-condensed table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>Account Name</th>
			<th>Contact Email</th>
			<th>Created</th>
			<th>Confirmed</th>
			<th>Activated</th>
			<th>Capacity</th>
			<th>Last Activity</th>

			<th></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($accounts as $account) { ?>
		<tr>
			<td><?php echo $account->account_id; ?></td>
			<td><?php echo $account->account_name; ?></td>
			<td><?php echo mailto($account->account_email); ?></td>
			<td><?php echo mysql_to_format($account->account_created_at); ?></td>
			<td><?php echo ($account->account_confirmed) ? '<span class="label label-success">CONFIRMED</span>' : '<span class="label label-important">NOT CONFIRMED</span>'; ?></td>
			<td><?php echo ($account->account_activated_at == '0000-00-00 00:00:00') ? '<span class="label label-important">NOT ACTIVATED</span>' : mysql_to_format($account->account_activated_at); ?></td>
			<td><?php echo (int) $account->account_capacity; ?></td>

			<td><?php echo (empty($account->account_last_activity)) ? '<span class="label label-important">NEVER</span>' : nice_time($account->account_last_activity); ?></td>

			<td><a href="<?php echo site_url('internal/accounts/edit/' . $account->account_id); ?>" class="btn btn-primary btn-small">Edit</a></td>
		</tr>


		<?php } ?>


	</tbody>

</table>