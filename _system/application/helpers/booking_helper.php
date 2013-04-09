<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function booking($key = null, $subkey = null)
{
	$CI =& get_instance();
	
	$booking = $CI->booking->session();

	if( ! empty($booking))
	{
		if(empty($key))
		{
			return $booking;
		} else if (empty($subkey))
		{
			return (isset($booking->$key)) ? $booking->$key : FALSE;
		} else
		{
			$item = (isset($booking->$key)) ? $booking->$key : null;
			
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

	return FALSE;
}

function is_cancelled($booking)
{
	return ($booking->booking_deleted_at != '0000-00-00 00:00:00');
}

function is_verified($booking)
{
	return $booking->booking_completed;
}