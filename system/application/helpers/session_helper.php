<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function session($key, $subkey = null)
{
	$CI =& get_instance();
	
	$item = $CI->session->userdata($key);

	if(empty($subkey))
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