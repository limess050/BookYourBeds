<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Set mode to test or production.  This determines which endpoints are used.
 * 
 * DEFAULT: test 
 */
$config['payments_mode'] = ENVIRONMENT;

/**
  * Supported Methods
*/
$config['supported_methods'] = array(
	'form', 'validate_settings', 'process', 'receipt'
	);


/**
  * Supported Gateways
*/
$config['supported_gateways'] = array(
	'nogateway'			=> 'No payment gateway',
	'sagepay_form'		=> 'Sage Pay FORM',
	'paypal'			=> 'PayPal Web Payments Standard'
	);