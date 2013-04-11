<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['sagepay_form_gateway_protocol'] = '2.23'; 

$config['sagepay_verification_email'] = '.byb_sp_verify@example.com'; 

$config['gateway_settings'] = array(
	'sagepay_form_vendor_id'		=> array(
											'label'	=> 'Vendor ID',
											'rules'	=> array('trim', 'required')
											),
	'sagepay_form_crypt'			=> array(
											'label'	=> 'Encryption Key',
											'rules'	=> array('trim', 'required')
											), 
	'sagepay_form_encryption_type'	=> array(
											'label'	=> 'Encryption Type',
											'rules'	=> array()
											)
	);

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
		case 'testing':
			$config['api_endpoint'] = "https://test.sagepay.com/Simulator/VSPFormGateway.asp";
		break;
		case 'production':
			$config['api_endpoint'] = "https://live.sagepay.com/gateway/service/vspform-register.vsp";
		break;
		default:
			exit('The application environment is not set correctly.');
	}
}

$config['form_preamble'] = '<p>You will now be taken to our secure payment system provided by <a href="http://www.sagepay.com" target="_blank">Sage Pay</a>. Please do not close your browser until you have been returned to this site, otherwise there may be problems processing your booking. You can cancel this booking at any time by clicking the \'Cancel\' button on the Sage Pay site.</p>';