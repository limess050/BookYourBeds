<h1 class="page-header">Supplements</h1>


		<!--<p>All forms are given default styles to present them in a readable and scalable way.</p>-->



		<p><?php echo anchor('admin/supplements/create', 'Create New Supplement', 'class="btn btn-primary plus"'); ?></p>


		<table class="table zebra-striped">
			<thead>
				<tr>
					<th>Short Description</th>
					<th>Default Price</th>
					<th></th>
				</tr>
			</thead>

			<tbody>
			<?php foreach($supplements as $supplement) { ?>
			<tr>
				<td><?php echo $supplement->supplement_short_description; ?> <?php echo ( ! $supplement->supplement_active) ? '<span class="label label-important">DISABLED</span>': ''; ?></td>
				<td>&pound;<?php echo as_currency($supplement->supplement_default_price); ?></td>
				<td><?php echo anchor("admin/supplements/edit/{$supplement->supplement_id}", 'Edit'); ?></td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
