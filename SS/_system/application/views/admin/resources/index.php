<div class="page-header row">
	<h1>Rooms</h1>
</div>

<div class="row">
	<div class="span4">
		<h2>Your Rooms</h2>
		<!--<p>All forms are given default styles to present them in a readable and scalable way.</p>-->



		<p><?php echo anchor('admin/resources/create', 'Create New Room', 'class="btn btn-primary plus"'); ?></p>
	</div>
	
	<div class="span8">

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

	</div>
</div>