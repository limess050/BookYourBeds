<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Init extends CI_Controller {

	public function migrate()
	{
		$this->load->library('migration');

		if ( ! $this->migration->current())
		{
			show_error($this->migration->error_string());
		}
	}

	public function back()
	{
		$this->load->library('migration');

		if ( ! $this->migration->version($this->input->get('v')))
		{
			show_error($this->migration->error_string());
		}
	}
}