<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

function account_id()
{
	$CI =& get_instance();
	
	return $CI->account->val('id');
}

function account($key = null)
{
	$CI =& get_instance();
	
	return $CI->account->val($key);
}

function setting($key = null)
{
	$CI =& get_instance();
	
	return $CI->account->setting($key);
}