<h1 class="page-header">Users</h1>

<div class="row">
	<div class="span4">
		<h2>Your Users</h2>
		<p>All forms are given default styles to present them in a readable and scalable way.</p>



		<p><?php echo anchor('admin/users/create', 'Create New User', 'class="btn primary plus"'); ?></p>
	</div>
	
	<div class="span8">

		<table class="table table-condensed table-striped table-hover">
			<thead>
		<tr>
			<th>Name</th>
			<th>Username</th>
			<th class="span1"></th>
			<th class="span1"></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($users as $user) { ?>
		<tr>
			<td><?php 

			echo $user->user_firstname . ' ' . $user->user_lastname; 

			echo ($user->user_id == session('user', 'user_id')) ? ' (YOU)' : '';
			?>
				
			</td>
			<td><?php echo $user->user_username; ?></td>
			<td><?php echo ($user->user_id == session('user', 'user_id')) ? anchor('admin/users/me', 'Edit') : anchor("admin/users/edit/{$user->user_id}", 'Edit'); ?></td>
			<td><?php echo ($user->user_id != session('user', 'user_id')) ? anchor("admin/users/delete/{$user->user_id}", 'Delete', 'onclick="return confirm(\'Are you sure you want to delete this user?\')"') : ''; ?></td>
		</tr>
		<?php } ?>

	</tbody>
		</table>

	</div>
</div>