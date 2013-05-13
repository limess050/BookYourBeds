<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Internal_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo 'foo';
	}
}