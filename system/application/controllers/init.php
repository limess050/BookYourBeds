<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Init extends CI_Controller {

	public function migrate()
	{
		if(in_array($_SERVER['SERVER_NAME'], array('secure.bookyourbeds.dev', 'bybbckdr.bookyourbeds.com')))
		{
			$this->load->library('migration');

			if ( ! $this->migration->current())
			{
				show_error($this->migration->error_string());
			}

			echo anchor('init/back?v=0', 'Back');
		}
	}

	public function back()
	{
		if(in_array($_SERVER['SERVER_NAME'], array('secure.bookyourbeds.dev', 'bybbckdr.bookyourbeds.com')))
		{
			$this->load->library('migration');

			$this->migration->version($this->input->get('v'));

			echo anchor('init/migrate', 'Forwards');
		}
	}

	public function host()
	{
		echo $_SERVER['HTTP_HOST'];
	}
}