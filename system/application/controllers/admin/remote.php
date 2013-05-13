<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remote extends Admin_Controller {

	public function help()
	{
		$this->load->config('help_uris');

		$help_uri = $this->config->item('help_uri');

		$route = ( ! empty($help_uri[$this->input->get('curi')])) ? $help_uri[$this->input->get('curi')] : '';

		$remote = 'http://support.bookyourbeds.com/' . $route . '/?source=remote';

		$this->load->library('curl');

		$source = $this->curl->simple_get($remote);

		echo $source;
	}
}