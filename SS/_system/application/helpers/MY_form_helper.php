<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('country_dropdown'))
{
	function country_dropdown($name, $selected = null, $attr = null)
	{
		$ci =& get_instance();

		$ci->config->load('countries');

		return form_dropdown($name, 
								array_merge(array('0' => 'Please select...'), $ci->config->item('iso_countries')),
								$selected,
								$attr);
	}
}

if ( ! function_exists('us_state_dropdown'))
{
	function us_state_dropdown($name, $selected = null, $attr = null)
	{
		$ci =& get_instance();

		$ci->config->load('countries');

		return form_dropdown($name, 
								array_merge(array('0' => 'Please select...'), $ci->config->item('us_states')),
								$selected,
								$attr);
	}
}

if ( ! function_exists('sth_accounts_dropdown'))
{
	function sth_accounts_dropdown($name, $selected = null, $attr = null)
	{
		$ci =& get_instance();

		$ci->config->load('sth');

		return form_dropdown($name, 
								array_merge(array('0' => 'Please select...'), $ci->config->item('sth_accounts')),
								$selected,
								$attr);
	}
}

