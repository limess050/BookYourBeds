<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	public function account()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('account[account_name]', 'Account Name', 'trim|required');
		$this->form_validation->set_rules('account[account_slug]', 'Account URL', 'trim|required');
		$this->form_validation->set_rules('account[account_email]', 'Account Email', 'trim|required|valid_email');

		if($this->form_validation->run() == FALSE)
		{
			$this->template->build('admin/settings/account');
		} else
		{
			$this->model('account')->update(account('id'), $this->input->post('account'));
			
			$this->account->ac = $this->model('account')->get(account('id'));

			$this->session->set_flashdata('msg', 'Account updated');
			redirect(site_url('admin/settings/account'));
		}

		
	}

	public function payments()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('setting[deposit]', 'Deposit', 'trim|required');
		if($this->input->post('setting'))
		{
			if($_POST['setting']['deposit'] != 'none')
			{
				$this->form_validation->set_rules('setting[paypal_email]', 'PayPal email', 'trim|required|valid_email');

				if($_POST['setting']['deposit'] == 'fraction')
				{
					$this->form_validation->set_rules('setting[deposit_percentage]', 'Percentage', 'trim|required|greater_than[0]');
				}
			}
		}

		if($this->form_validation->run() == FALSE)
		{
			$this->template->build('admin/settings/payments');
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
			redirect(site_url('admin/settings/payments'));
		}
	}

	
}