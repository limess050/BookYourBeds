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
	'NoGateway'			=> 'No payment gateway',
	'SagePay_Form'		=> 'Sage Pay FORM',
	'PayPal'			=> 'PayPal Web Payments Standard'
	);