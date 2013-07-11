<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function index()
	{

		if(account('active'))
		{
			$this->dash();
		} else
		{
			redirect(site_url('admin/dashboard/wizard'));
		}
	}

	public function dash()
	{
		if( ! account('active'))
		{
			$this->index();
		}

		$data['tabs'] = array(
							'today'			=> $this->model('booking')->checking_in(),
							'new'			=> $this->model('booking')->unacknowledged(),
							'unverified'	=> (setting('payment_gateway') == 'NoGateway') ? $this->model('booking')->unverified() : null,
							'cancelled'		=> $this->model('booking')->cancelled()
							);

		$data['today'] = $this->model('booking')->checking_in();
		$data['new'] = $this->model('booking')->unacknowledged();
		$data['unverified'] = (setting('payment_gateway') == 'NoGateway') ? $this->model('booking')->unverified() : null;
		$data['cancelled'] = $this->model('booking')->cancelled();
		
		$this->template
					->append_metadata( js('bootstrap-tab.js'))
					->build('admin/dashboard/index', $data);
	}

	public function wizard()
	{
		$this->load->library('form_validation');

		$this->load->config('payment');

		/*$this->template->set_partial('NoGateway', 'admin/partials/gateways/nogateway');
		$this->template->set_partial('PayPal', 'admin/partials/gateways/paypal');
		$this->template->set_partial('SagePay_Form', 'admin/partials/gateways/sagepay_form');*/
		
		foreach($this->config->item('supported_gateways') as $key => $val) { 

			$this->template->set_partial($key, 'admin/partials/gateways/' . strtolower($key));
		}

		$data['steps'] = $this->account->wizard_steps();

		foreach($data['steps'] as $step)
		{
			$this->template->set_partial($step, 'admin/partials/wizard/' . strtolower($step));
		}

		if($fn = $this->input->post('_form'))
		{
			$fn = 'wizard_' . $fn;
			$data = array_merge($data, $this->$fn());
		}

		$this->template->build('admin/dashboard/wizard', $data);
	}

	private function wizard_account()
	{
		$this->form_validation->set_rules('account[account_name]', 'Account Name', 'trim|required|xss_clean');
		//$this->form_validation->set_rules('account[account_slug]', 'Account URL', 'trim|required');
		$this->form_validation->set_rules('account[account_email]', 'Account Email', 'trim|required|valid_email|xss_clean');
		
		$this->form_validation->set_rules('account[account_address1]', 'Address 1', 'trim|required|xss_clean');
		$this->form_validation->set_rules('account[account_address2	]', 'Address 2', 'trim|xss_clean');
		$this->form_validation->set_rules('account[account_city]', 'Town/City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('account[account_postcode]', 'Postcode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('account[account_country]', 'Country', 'trim|required|xss_clean');
		//$this->form_validation->set_rules('account[account_contact_email]', 'Contact Email', 'trim|valid_email');
		$this->form_validation->set_rules('account[account_phone]', 'Contact Telephone', 'trim|xss_clean');

		$this->form_validation->set_rules('account[account_description]', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('account[account_website]', 'Website', 'trim|xss_clean');

		$data = array();
		if($this->form_validation->run() == FALSE)
		{
			$data['_account_open'] = TRUE;

			return $data;
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
			
			$account = $this->input->post('account');

			$account['account_slug'] = $this->account->generate_slug($account['account_name']);

			$this->model('account')->update(account('id'), $account);
			
			$this->account->ac = $this->model('account')->get(account('id'));

			$this->session->set_flashdata('msg', 'Account updated');
			redirect(site_url('admin/dashboard/wizard'));
		}
	}

	private function wizard_add_room()
	{
		$this->form_validation->set_rules('resource[resource_title]', 'Resource Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('resource[resource_default_release]', 'Default Release', 'trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('resource[resource_booking_footprint]', 'Booking Footprint', 'trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('resource[resource_priced_per]', 'Resource Priced Per', 'trim|xss_clean');
		
		$dow =  array('', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

		for($i = 1; $i <= 7; $i++)
		{
			$this->form_validation->set_rules("price[{$i}]", $dow[$i] . ' Price', 'trim|required|is_numeric|as_currency|xss_clean');
		}

		$data = array();
		if($this->form_validation->run() == FALSE)
		{
			$data['_add_room_open'] = TRUE;

			return $data;
		} else
		{
			$id = $this->model('resource')->insert($this->input->post('resource'));

			$prices = $this->input->post('price');

			foreach($prices as $key => $value)
			{
				$this->model('price')->insert(array(
												'price_resource_id'	=> $id,
												'price_day_id'		=> $key,
												'price_price'		=> $value
												));
			}

			$this->session->set_flashdata('msg', 'Room added');
			redirect(site_url('admin/dashboard/wizard'));
		}
	}

	private function wizard_payment_options()
	{
		
		$this->form_validation->set_rules('setting[deposit]', 'Deposit', 'trim|required|callback_gateway_needed|xss_clean');
		$this->form_validation->set_rules('setting[balance_due]', 'Balance Due', 'trim|required|xss_clean');

		if($this->input->post('setting'))
		{
			if($_POST['setting']['deposit'] != 'none')
			{
				$this->form_validation->set_rules('setting[payment_gateway]', 'Payment Gateway', 'required|xss_clean');

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
			
			$data['_payment_options_open'] = TRUE;

			return $data;
		} else
		{
			$settings = $this->input->post('setting');

			if($settings['deposit'] != 'fraction')
			{
				unset($settings['deposit_percentage']);
			}

			if($settings['deposit'] == 'none')
			{
				unset($settings['payment_gateway'], $setting['paypal_email']);
			}

			$this->model('setting')->create_or_update_many($settings);

			$this->session->set_flashdata('msg', 'Payment options added');
			redirect(site_url('admin/dashboard/wizard'));
		}
	}

	public function gateway_needed($str)
	{
		$this->form_validation->set_message('gateway_needed', 'You need to select a payment gateway if you are taking a deposit.');
		return ($str != 'none' && $_POST['setting']['payment_gateway'] == 'NoGateway') ? FALSE : TRUE;
	}

	private function wizard_launch()
	{
		$this->account->launch();

		$this->session->set_flashdata('msg', 'Your site has been launched!');

		redirect(site_url('admin/dashboard'));
	}

	public function wizard_confirm_email()
	{
		$this->form_validation->set_rules('confirm_email', 'Account Email', 'trim|required|valid_email|xss_clean');

		if($this->form_validation->run() == FALSE)
		{
			return;
		} else
		{
			$this->account->send_confirmation_email($this->input->post('confirm_email'));

			$this->session->set_flashdata('msg', 'Confirmation email sent');
			redirect(site_url('admin/dashboard/wizard'));
		}
	}

}