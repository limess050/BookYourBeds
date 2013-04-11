<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function site_url($uri = '', $include_account = TRUE)
{
	$CI =& get_instance();
	return $CI->config->site_url($uri, $include_account);
}

function anchor($uri = '', $title = '', $attributes = '', $include_account = TRUE)
{
	$title = (string) $title;

	if ( ! is_array($uri))
	{
		$site_url = preg_match('#^(\w+:)?//#i', $uri) ? $uri : site_url($uri, $include_account);
	}
	else
	{
		$site_url = site_url($uri, $include_account);
	}

	if ($title === '')
	{
		$title = $site_url;
	}

	if ($attributes !== '')
	{
		$attributes = _stringify_attributes($attributes);
	}

	return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
}

function safe_get_env()
{
	$CI =& get_instance();
	return str_replace($CI->account->val('slug'), '', getenv('REQUEST_URI'));
}