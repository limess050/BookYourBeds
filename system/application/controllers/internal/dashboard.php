<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Internal_Controller {

	public function index()
	{
		redirect(site_url('internal/accounts'));
		
	}

}