<ul class="nav nav-pills hidden-phone">
	<li<?php echo ($this->uri->rsegment(2) == 'account') ? ' class="active"' : ''; ?>><?php echo anchor("admin/settings/account", 'Account Settings'); ?></li>
	<li<?php echo ($this->uri->rsegment(2) == 'payments') ? ' class="active"' : ''; ?>><?php echo anchor("admin/settings/payments", 'Payment Options'); ?></li>
  	<li<?php echo ($this->uri->rsegment(2) == 'bookings') ? ' class="active"' : ''; ?>><?php echo anchor("admin/settings/bookings", 'Booking Settings'); ?></li>
  	<li<?php echo ($this->uri->rsegment(1) == 'seasons') ? ' class="active"' : ''; ?>><?php echo anchor("admin/seasons", 'Seasons'); ?></li>
  	<li<?php echo ($this->uri->rsegment(1) == 'users') ? ' class="active"' : ''; ?>><?php echo anchor("admin/users/me", 'Personal Settings'); ?></li>
</ul>

<ul class="nav nav-pills nav-stacked hidden-desktop hidden-tablet">
	<li<?php echo ($this->uri->rsegment(2) == 'account') ? ' class="active"' : ''; ?>><?php echo anchor("admin/settings/account", 'Account Settings'); ?></li>
	<li<?php echo ($this->uri->rsegment(2) == 'payments') ? ' class="active"' : ''; ?>><?php echo anchor("admin/settings/payments", 'Payment Options'); ?></li>
  	<li<?php echo ($this->uri->rsegment(2) == 'bookings') ? ' class="active"' : ''; ?>><?php echo anchor("admin/settings/bookings", 'Booking Settings'); ?></li>
  	<li<?php echo ($this->uri->rsegment(1) == 'seasons') ? ' class="active"' : ''; ?>><?php echo anchor("admin/seasons", 'Seasons'); ?></li>
  	<li<?php echo ($this->uri->rsegment(1) == 'users') ? ' class="active"' : ''; ?>><?php echo anchor("admin/users/me", 'Personal Settings'); ?></li>
</ul>