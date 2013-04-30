<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('mysql_to_format'))
{
	function mysql_to_format($datetime, $format = 'j M Y')
	{
		$timestamp = human_to_unix($datetime);
		
		return date($format, $timestamp);
	}
}
 
if ( ! function_exists('unix_to_mysql'))
{
	function unix_to_mysql($timestamp)
	{
		return date('Y-m-d H:i:s', $timestamp);
	}
}

if ( ! function_exists('human_to_mysql'))
{
	function human_to_mysql($date, $time = null)
	{
		$parts = explode('/', $date);
		
		if( ! empty($time))
		{
			if(is_array($time))
			{
				$t = $time['hours'] . ':' . $time['minutes'];
			} else
			{
				$t = $time;
			}
		} else
		{
			$t = $time;
		}
		
		return trim($parts[2] . '-' . $parts[1] . '-' . $parts[0] . ' ' . $t);
	}
}

if ( ! function_exists('day_of_week'))
{
	function day_of_week($i)
	{
		$days = array(
			1	=> 'Monday',
			2	=> 'Tuesday',
			3 	=> 'Wednesday',
			4 	=> 'Thursday',
			5 	=> 'Friday',
			6 	=> 'Saturday',
			7 	=> 'Sunday'
			);
		
		return $days[$i];	
	}
}

if ( ! function_exists('dropdown_time'))
{
	function dropdown_time($label, $default = '0000-00-00 08:00:00')
	{
		$parts = explode(' ', $default);
		$time = explode(':', $parts[1]);
		
		$output = "<select name=\"{$label}[hour]\" class=\"span1\">\n";
				
		for($i = 0; $i < 24; $i++)
		{
			$n = sprintf("%02d", $i);
			$output .= "\t<option";
			$output .= ($n == $time[0]) ? ' selected="selected"' : '';
			$output .= ">{$n}</option>\n";
		}
		
		
		$output .= "</select>\n";
		
		$output .= ":\n";
		
		$output .= "<select name=\"{$label}[minutes]\" class=\"span1\">\n";
		for($i = 0; $i < 60; $i++)
		{
			$n = sprintf("%02d", $i);
			$output .= "\t<option";
			$output .= ($n == $time[1]) ? ' selected="selected"' : '';
			$output .= ">{$n}</option>\n";
		}
		$output .= "</select>\n";
		
		return $output;
	}
}

if ( ! function_exists('duration'))
{
	function duration($amount, $qty = null, $unit = null)
	{
		$ci =& get_instance();

		$qty = (empty($qty)) ? 1 : $qty;
		$unit = (empty($unit)) ? 'night' : $unit;

		$qty = $qty * $amount;

		$unit = ($qty > 1) ? $unit . 's' : $unit;

		return $qty . ' ' . $unit; 
	}
}

if ( ! function_exists('nice_time'))
{
	function nice_time($date)
	{
	    if(empty($date)) {
	        return "No date provided";
	    }

	    $periods    = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	    $lengths    = array("60","60","24","7","4.35","12","10");

	    $now        = now();
	    $unix_date	= strtotime($date);
		
	    // check validity of date
	    if(empty($unix_date)) {   
	        return "Bad date";
	    }

	    // is it future date or past date
	    if($now > $unix_date) {   
	        $difference     = $now - $unix_date;
	        $tense         = "ago";

	    } else {
	        $difference     = $unix_date - $now;
	        $tense         = "from now";
	    }

	    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	        $difference /= $lengths[$j];
	    }

	    $difference = round($difference);

	    if($difference != 1) {
	        $periods[$j].= "s";
	    }

	    return "$difference $periods[$j] {$tense}";
	}
}

function time_remaining($start, $duration)
{
	if( ! is_numeric($start))
	{
		$start = human_to_unix($start);
	}

	//return $start;

	$end = $start + $duration;

	return timespan(time(), $end);
}