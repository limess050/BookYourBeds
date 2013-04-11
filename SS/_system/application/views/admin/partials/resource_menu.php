<ul class="nav nav-tabs">
	<li<?php echo ($this->uri->rsegment(2) == 'edit') ? ' class="active"' : ''; ?>><?php echo anchor("admin/resources/edit/{$resource->resource_id}", 'General Settings'); ?></li>
  	<li<?php echo ($this->uri->rsegment(2) == 'price') ? ' class="active"' : ''; ?>><?php echo anchor("admin/resources/price/{$resource->resource_id}", 'Pricing'); ?></li>
  	<li<?php echo ($this->uri->rsegment(2) == 'supplements') ? ' class="active"' : ''; ?>><?php echo anchor("admin/resources/supplements/{$resource->resource_id}", 'Supplements'); ?></li>
  	<li<?php echo ($this->uri->rsegment(2) == 'resource') ? ' class="active"' : ''; ?>><?php echo anchor("admin/availability/resource/{$resource->resource_id}", 'Availability'); ?></li>
</ul>
