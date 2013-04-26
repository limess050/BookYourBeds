<?php 
if( ! empty($resources))
{
	foreach($resources as $resource) {
		$this->load->view('admin/partials/availability_row', array('resource' => $resource));
	}
} else
{
	if( ! empty($resource))
	{
		$this->load->view('admin/partials/availability_row', array('resource' => $resource, 'hide_title' => TRUE));
	} else
	{
		
	}
}
?>