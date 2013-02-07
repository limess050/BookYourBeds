<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Session Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Sessions
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/sessions.html
 */
class MY_Session extends CI_Session {

	/**
	 * Fetch a specific item from the session array
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function userdata($key, $subkey = null)
	{
		if(empty($subkey))
		{
			return ( ! isset($this->userdata[$key])) ? FALSE : $this->userdata[$key];
		} else
		{
			$item = $this->userdata($key);
			
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
		
		return ( ! isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
	}


}
// END Session Class