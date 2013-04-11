<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['gateway_settings'] = array(
	'paypal_email'	=> array(
							'label'	=> 'Account Email',
							'rules'	=> array('trim', 'required', 'valid_email')
							)
	);

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
		case 'testing':
			$config['api_endpoint'] = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		break;
		case 'production':
			$config['api_endpoint'] = "https://www.paypal.com/cgi-bin/webscr";
		break;
		default:
			exit('The application environment is not set correctly.');
	}
}

$config['form_preamble'] = '<p>You will now be taken to our secure payment system provided by <a href="http://www.paypal.com" target="_blank">PayPal</a>.</p>';