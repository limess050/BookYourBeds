<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function site_url($uri = '', $include_account = TRUE)
{
	$CI =& get_instance();
	return $CI->config->site_url($uri, $include_account);
}

function safe_get_env()
{
	$CI =& get_instance();
	return str_replace($CI->account->val('slug'), '', getenv('REQUEST_URI'));
}