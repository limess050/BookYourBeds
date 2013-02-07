<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roadblock extends MY_Controller {

	public function index()
	{
		show_404();
	}

}