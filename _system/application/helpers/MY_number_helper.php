<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('as_currency'))
{
	function as_currency($n, $full = TRUE, $return_zero = TRUE)
	{
		if ( ! empty($n))
		{
			$number = number_format($n, 2, '.', '');
			
			return ($full) ? number_format($number, 2) : $number;
		} else
		{
			return ($return_zero) ? '0.00' : null;
		}
	}
}


/* End of file MY_number_helper.php */
/* Location: ./system/application/helpers/MY_number_helper.php */