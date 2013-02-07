<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function account_id()
{
	$CI =& get_instance();
	
	return $CI->account->ac('id');
}

function account($key = null)
{
	$CI =& get_instance();
	
	return $CI->account->ac($key);
}