<h1 class="page-header">Rooms</h1>


		<p><?php echo anchor('admin/resources/create', 'Create New Room', 'class="btn btn-primary plus"'); ?></p>


		<table class="table zebra-striped">
			<thead>
				<tr>
					<th>Room Name</th>
					<th></th>
				</tr>
			</thead>

			<tbody>
			<?php foreach($resources as $resource) { ?>
			<tr>
				<td><?php echo $resource->resource_title; ?> <?php echo ( ! $resource->resource_active) ? '<span class="label label-important">DISABLED</span>': ''; ?></td>
				<td><?php echo anchor("admin/resources/edit/{$resource->resource_id}", 'Edit'); ?></td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
