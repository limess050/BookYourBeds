	<?php 
if( ! empty($resources))
{
	foreach($resources as $resource) {
		$this->load->view('admin/partials/availability_row', array('resource' => $resource));
	}
} else
{
	$this->load->view('admin/partials/availability_row', array('resource' => $resource, 'hide_title' => TRUE));
}
?>