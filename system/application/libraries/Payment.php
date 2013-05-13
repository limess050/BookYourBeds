<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment {

	/**
	 * The version
	*/	
	public $mode;

	/**
	 * The payment module to use
	*/	
	public $payment_module; 

	/**
	 * The payment type to make
	*/		
	public $payment_type;	

	/**
	 * The params to use
	*/		
	private	$_params;


	public function __construct()
	{
		ci()->load->config('payment');

		$this->mode = ci()->config->item('payments_mode');
	}

	/**
	 * Make a call to a gateway. Uses other helper methods to make the request.
	 *
	 * @param	string	The payment method to use
	 * @param	array	$params[0] is the gateway, $params[1] are the params for the request
	 * @return	object	Should return a success or failure, along with a response.
	 */		
	public function __call($method, $params)
	{
		$supported = $this->_check_method_supported($method);
		
		if($supported)
		{
			$response = $this->_make_gateway_call($params[0], $method, $params[1]);
		}
		else
		{
			/*$response = $this->return_response(
				'failure', 
				'not_a_method', 
				'local_response'
			);*/
		}

		return $response;
	}

	/**
	 * Checks to ensure that a method is actually supported by cf_payments before continuing
	 *
	 * @param	string	The payment method to use
	 * @return	object	Should return a success or failure, along with a response.
	 */	
	private function _check_method_supported($method)
	{
		$supported_methods = ci()->config->item('supported_methods');
		return in_array($method, $supported_methods);
	}
 
	/**
	 * Make a call to a gateway. Uses other helper methods to make the request.
	 *
	 * @param	string	The payment module to use
	 * @param	string	The type of method being used.
	 * @param	array	Params to submit to the payment module
	 * @return	object	Should return a success or failure, along with a response.
	 */	
	private function _make_gateway_call($payment_module, $payment_type, $params)
	{	
		$module_exists = $this->_load_module($payment_module);

		if($module_exists === FALSE)
		{
			/*return $this->return_response(
				'failure', 
				'not_a_module', 
				'local_response'
			);*/
		}
		else
		{
			ci()->load->config(strtolower('gateways/'.$payment_module));

			if($this->_check_settings() === FALSE)
			{
				return FALSE;
			}

			$this->payment_type = $payment_type;	
			
			//$valid_inputs = $this->_check_inputs($payment_module, $params);
			$valid_inputs = TRUE;

			if($valid_inputs === TRUE)
			{
				$this->_params = $params;	
				$response = $this->_do_method($payment_module);
				return $response;		
			}
			else
			{
				return $valid_inputs;	
			}
		}	
	}

	private function _check_settings()
	{
		$settings = ci()->config->item('gateway_settings');

		foreach($settings as $setting => $validation_rules)
		{
			if(ci()->account->setting($setting) === FALSE)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	public function validate_settings($payment_module)
	{
		ci()->load->library('form_validation');

		ci()->load->config(strtolower('gateways/'.$payment_module));

		$settings = ci()->config->item('gateway_settings');

		foreach($settings as $setting => $validation_rules)
		{
			ci()->form_validation->set_rules("setting[{$setting}]", $validation_rules['label'], implode('|', $validation_rules['rules']));
		}
	}

	/**
	 * Try to load a payment module
	 *
	 * @param	string	The payment module to load
	 * @return	mixed	Will return bool if file is not found.  Will return file as object if found.
	 */		
	private function _load_module($payment_module)
	{
		$module = dirname(__FILE__).'/Gateways/'.$payment_module.'.php';
		
		if (!is_file($module))
		{
			return FALSE;
		}

		ob_start();
		
		include_once($module);
		
		return ob_get_clean();
	}

	/**
	 * Try use a method from a particular gateway
	 *
	 * @param	string	The payment module to use
	 * @param	string	The type of method being used.  Can be for making payments or getting statuses / profiles.
	 * @param	array	Params to submit to the payment module
	 * @return	object	Should return a success or failure, along with a response
	 */		
	private function _do_method($payment_module)
	{		
		$object = new $payment_module($this);
		
		$method = $payment_module.'_'.$this->payment_type;
		
		if( ! method_exists($payment_module, $method))
		{
			/*return $this->return_response(
				'failure', 
				'not_a_method', 
				'local_response'
			);*/
		}
		else
		{
			ci()->load->config('payment_types/'.$this->payment_type);
			
			$this->_default_params = ci()->config->item('payment_' . $this->payment_type);
			
			/*return $object->$method(
				array_merge(
					$this->_default_params, 
					$this->_params
				)
			);*/

			return $object->$method(
				
					$this->_params
				
			);						
		}
	}

	protected function model($name)
	{
		$name = $name . MODEL_SUFFIX;
		
		// is there a module involved
		$model_name = explode('/', $name);
		
		if ( ! isset(ci()->{end($model_name)}) )
		{
			ci()->load->model($name, '', TRUE);
		}

		return ci()->{end($model_name)};
	}

}

if ( ! function_exists('ci'))
{
	/**
	 * Returns the CI object.
	 *
	 * Example: ci()->db->get('table');
	 *
	 * @staticvar	object	$ci
	 * @return		object
	 */
	function ci()
	{
		return get_instance();
	}
}