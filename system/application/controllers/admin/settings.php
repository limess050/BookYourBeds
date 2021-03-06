<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->template->set_partial('settings_menu', 'admin/partials/settings_menu');
	}

	public function account()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('account[account_name]', 'Account Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('account[account_slug]', 'Account URL', 'trim|required|xss_clean|callback_check_account_slug');
		$this->form_validation->set_rules('account[account_email]', 'Account Email', 'trim|required|valid_email|xss_clean|callback_check_account_email');
		
		$this->form_validation->set_rules('account[account_address1]', 'Address 1', 'trim|required|xss_clean');
		$this->form_validation->set_rules('account[account_address2]', 'Address 2', 'trim|xss_clean');
		$this->form_validation->set_rules('account[account_city]', 'Town/City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('account[account_postcode]', 'Postcode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('account[account_country]', 'Country', 'trim|required|xss_clean');
		//$this->form_validation->set_rules('account[account_contact_email]', 'Contact Email', 'trim|valid_email');
		$this->form_validation->set_rules('account[account_phone]', 'Contact Telephone', 'trim|xss_clean');
		
		$this->form_validation->set_rules('account[account_description]', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('account[account_website]', 'Website', 'trim|xss_clean');
 
		if($this->form_validation->run() == FALSE)
		{
			$this->template->build('admin/settings/account');
		} else
		{
			if( ! empty($_FILES['account_logo']))
			{
				$this->account->upload_logo();
			}

			if( ! empty($_FILES['account_bg']))
			{ 
				$this->account->upload_bg();
			}

			$this->model('account')->update(account('id'), $this->input->post('account'));
			
			$this->account->ac = $this->model('account')->get(account('id'));

			$this->session->set_flashdata('msg', 'Account updated');
			redirect(site_url('admin/settings/account'));
		}
	}

	public function check_account_email($str)
	{
		$this->form_validation->set_message('check_account_email', 'That email address is already in use with another account');
		return $this->model('account')->check_unique('email', $str, account('id'));
	}

	public function check_account_slug($str)
	{
		$this->form_validation->set_message('check_account_slug', 'That URL is already in use with another account');
		return $this->model('account')->check_unique('slug', $str, account('id'));
	}

	public function bookings()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('setting[max_duration_public]', 'Maximum duration', 'trim|required|is_numeric|xss_clean');
		$this->form_validation->set_rules('setting[max_guests_public]', 'Maximum guests', 'trim|required|is_numeric|xss_clean');
		$this->form_validation->set_rules('setting[terms_and_conditions]', 'Terms and Conditions', 'trim|xss_clean');
		$this->form_validation->set_rules('setting[booking_instructions]', 'Additional Booking Information', 'trim|xss_clean');

		if($this->form_validation->run() == FALSE)
		{
			$this->template->build('admin/settings/bookings');
		} else
		{
			$this->model('setting')->create_or_update_many($this->input->post('setting'));

			$this->session->set_flashdata('msg', 'Booking settings updated');
			redirect(site_url('admin/settings/bookings'));
		}

	}

	public function invoice()
	{
		$this->template->build('admin/settings/invoice');
	}

	public function payments()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('setting[deposit]', 'Deposit', 'trim|required|callback_gateway_needed');
		$this->form_validation->set_rules('setting[balance_due]', 'Balance Due', 'trim|required|xss_clean');
		$this->form_validation->set_rules('setting[payment_gateway]', 'Payment Gateway', 'trim|required|xss_clean');

		if($this->input->post('setting'))
		{
			if($_POST['setting']['deposit'] != 'none')
			{
				if($_POST['setting']['payment_gateway'] == 'PayPal')
				{
					$this->form_validation->set_rules('setting[paypal_email]', 'PayPal email', 'trim|required|valid_email|xss_clean');
					unset($_POST['setting']['sagepay_form_vendor_id'], $_POST['setting']['sagepay_form_crypt'], $_POST['setting']['sagepay_form_encryption_type']);
				} else if($_POST['setting']['payment_gateway'] == 'SagePay_Form')
				{
					$this->form_validation->set_rules('setting[sagepay_form_vendor_id]', 'SagePay Vendor ID', 'trim|required|xss_clean');
					$this->form_validation->set_rules('setting[sagepay_form_crypt]', 'SagePay Crypt', 'trim|required|xss_clean');
					unset($_POST['setting']['paypal_email']);
				}


				if($_POST['setting']['deposit'] == 'fraction')
				{
					$this->form_validation->set_rules('setting[deposit_percentage]', 'Percentage', 'trim|required|greater_than[0]|xss_clean');
				}
			} else
			{
				unset($_POST['setting']['paypal_email'], $_POST['setting']['sagepay_form_vendor_id'], $_POST['setting']['sagepay_form_crypt'], $_POST['setting']['sagepay_form_encryption_type']);
			}
		}

		if($this->form_validation->run() == FALSE)
		{
			$this->load->config('payment');

			//$this->template->set_partial('NoGateway', 'admin/partials/gateways/nogateway');
			//$this->template->set_partial('PayPal', 'admin/partials/gateways/paypal');
			//$this->template->set_partial('SagePay_Form', 'admin/partials/gateways/sagepay_form');
			
			foreach($this->config->item('supported_gateways') as $key => $val) { 
				$this->template->set_partial($key, 'admin/partials/gateways/' . strtolower($key));
			}

			$this->template->build('admin/settings/payments');
		} else
		{
			$settings = $this->input->post('setting');

			if($settings['deposit'] != 'fraction')
			{
				unset($settings['deposit_percentage']);
			}

			$this->model('setting')->create_or_update_many($settings);

			$this->session->set_flashdata('msg', 'Payment options added');
			redirect(site_url('admin/settings/payments'));
		}
	}

	public function gateway_needed($str)
	{
		$this->form_validation->set_message('gateway_needed', 'You need to select a payment gateway if you are taking a deposit.');
		return ($str != 'none' && $_POST['setting']['payment_gateway'] == 'NoGateway') ? FALSE : TRUE;
	}

	
}