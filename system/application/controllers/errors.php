<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function error_404()
	{
		if($this->uri->total_segments() == 1)
		{
			if($account = $this->model('account')->get_by('account_slug', $this->uri->segment(1)))
			{
				redirect(site_url('home'));
			} else
			{
				redirect(site_url());
			}
		}

		show_404();
	}

}

/* End of file errors.php */
/* Location: ./application/controllers/errors.php */