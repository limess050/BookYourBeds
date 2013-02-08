<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Config extends CI_Config
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Set a config file item
	 *
	 * @access	public
	 * @param	string	the config item key
	 * @param	string	the config item value
	 * @return	void
	 */
	public function set_item($item, $value, $index = '')
	{
		if ($index == '')
		{
			$this->config[$item] = $value;
		}
		else
		{
			$this->config[$index][$item] = $value;
		}
	}

	/**
	 * Site URL
	 *
	 * @access	public
	 * @param	string	the URI string
	 * @return	string
	 */
	function site_url($uri = '', $include_account = TRUE)
	{
		$pre = '';

		if(class_exists('account') && $include_account)
		{
			$ci =& get_instance();
			
			$pre = ( empty($ci->account->ac)) ? '' : $ci->account->val('slug') . '/';
		}

		if ($uri == '')
		{
			return $this->slash_item('base_url').$pre.$this->item('index_page');
		}

		if ($this->item('enable_query_strings') == FALSE)
		{
			if (is_array($uri))
			{
				$uri = implode('/', $uri);
			}

			$index = $this->item('index_page') == '' ? '' : $this->slash_item('index_page');
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			

			return $this->slash_item('base_url').$index.$pre.trim($uri, '/').$suffix;
		}
		else
		{
			// This part is kind of irrelevant to this implementation...
			if (is_array($uri))
			{
				$i = 0;
				$str = '';
				foreach ($uri as $key => $val)
				{
					$prefix = ($i == 0) ? '' : '&';
					$str .= $prefix.$key.'='.$val;
					$i++;
				}

				$uri = $str;
			}

			return $this->slash_item('base_url').$this->item('index_page').'?'.$uri;
		}
	}
}

