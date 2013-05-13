<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class PayPal
{
	/**
	 *	The payments object
	*/
	public $payments;
	
	/**
	 *	The gateway settings
	*/
	public $settings;

	public function __construct($payments)
	{
		$this->payments = $payments;
		$this->settings = ci()->config->item('gateway_settings');
	}

	public function paypal_form($params)
	{
		ci()->load->helper('form');

		$output = ci()->config->item('form_preamble');

		$output .= '<div class="spinner"></div>';
		
		$output .= '<form action="' . ci()->config->item('api_endpoint') . '" method="POST" id="processForm">';
		$output .= form_hidden('business', ci()->account->setting('paypal_email'));
		$output .= form_hidden('cmd', '_xclick');
		$output .= form_hidden('amount', $params->booking_deposit);
		$output .= form_hidden('currency_code', 'GBP');
		$output .= form_hidden('item_name', 'Booking with ' . ci()->account->val('name'));
		$output .= form_hidden('return', site_url('salesdesk/process'));
		$output .= form_hidden('notify_url', site_url('salesdesk/process/ipn'));
		$output .= form_hidden('rm', 2);
		$output .= form_hidden('custom', $params->booking_id);
		
		$output .= form_button(array(
								    'name' 		=> 'button',
								    'class' 	=> 'btn primary',
								    'content' 	=> 'Make Payment &amp; Complete Booking',
								    'type'		=> 'submit'
									));
		$output .= '&nbsp;';
		$output .= '<a href="' . site_url('salesdesk/reset') . '" class="btn" onclick="return confirm(\'Are you sure you want to cancel this booking?\');">Cancel Booking</a>';

		$output .= '</form>';

		$output .= '<p class="alert">For testing use email address <code>buyer_1360309574_per@othertribe.com</code> and password <code>bookyourbeds</code> when paying.</p>';

		$output .= '<script type="text/javascript">
					<!--
					    $(function(){
					    	$(".spinner").spin();
					        $.doTimeout( 5000, function(){
					                                    $("#processForm").submit();
					                                    return true;
					                                    });
					    });
					-->
					</script>';
		return $output;
	}

	public function paypal_process($data)
	{
		// PayPal only sends successful payments back...
		return array(
					'valid'			=> TRUE,
					'action'		=> 'create',
					'message'		=> 'Booking Successful',
					'booking_id'	=> $data['custom'],
					'results'		=> $data
					);
	}

	public function paypal_receipt($data)
	{
		

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

if( ! function_exists('ci'))
{
	function ci()
	{
		return get_instance();
	}	
}