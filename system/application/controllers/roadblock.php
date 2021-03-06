<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roadblock extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->template
			->set_layout('default')
			->enable_parser(FALSE)

			->set_partial('form_errors', 'partials/form_errors')
			
			->append_metadata( js('jquery.js') )
			->append_metadata( js('bootstrap-dropdown.js') )

			->append_metadata( css('admin.css') )
			->append_metadata( js('application.js') )
			->append_metadata( js('spin.js') )
			->append_metadata( js('holder.js') )

			->append_metadata( css('jquery.ui.css') ) 
			->append_metadata( js('jquery.ui.js') );
	}

	public function index()
	{
		$this->template->build('roadblock/index');
	}

	public function coming_soon()
	{
		$this->template->build('roadblock/coming_soon');
	}

	public function signup()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|xss_clean|callback_check_account_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Account Name', 'trim|required|xss_clean');

		if($this->form_validation->run() == FALSE)
		{
			$this->load->helper('typography');

			$this->template
					->append_metadata( js('bootstrap-modal.js') )
					->build('roadblock/signup');
		} else
		{
			$user_id = $this->account->create($this->input->post('name'), $this->input->post('email'), $this->input->post('password'));

			$this->session->set_userdata('user', $this->model('user')->get($user_id));
			$this->account->ac = $this->model('account')->get( session('user', 'user_account_id') );

			redirect(site_url('admin'));
		}
	}

	public function check_account_email($str)
	{
		$this->form_validation->set_message('check_account_email', 'That email address is already in use with another account');
		return $this->model('account')->check_unique('email', $str);
	}

	public function confirm_account()
	{
		$data['account'] = $this->account->confirm($this->input->get('auth'));
		
		$this->template->build('roadblock/confirm_account', $data);
	}
}