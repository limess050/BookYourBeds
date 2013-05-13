<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

function session($key, $subkey = null)
{
	$CI =& get_instance();
	
	$item = $CI->session->userdata($key);

	if(empty($subkey) && $subkey !== '0') 
	{
		return $item;
	} else
	{
		if(is_array($item))
		{
			return (isset($item[$subkey])) ? $item[$subkey] : FALSE;
		} else if(is_object($item))
		{
			return (isset($item->$subkey)) ? $item->$subkey : FALSE;
		} else
		{
			return FALSE;
		}
	}
}

function select_if_current($controller)
{
	$CI =& get_instance();
	
	return ($CI->uri->rsegment(1) == $controller) ? 'selected' : ''; 
}