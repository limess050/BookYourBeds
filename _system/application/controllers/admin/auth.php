<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->template->set_layout('default');
	}

	public function forgotten_password()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|callback_do_reset');

		if($this->form_validation->run() == FALSE)
		{
			$this->template->build('admin/auth/forgotten_password');
		} else {
			$this->account->send_password_reset($this->input->post('email'));
			$this->template->build('admin/auth/forgotten_password', array('sent' => TRUE));
		}
	}

	public function do_reset($str)
	{
		$this->form_validation->set_message('do_reset', 'There is no user account with that email address');
		return $this->account->send_password_reset($this->input->post('email'));
	}

	public function reset_password()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('password', 'New Password', 'trim|required|matches[password_conf]');
		$this->form_validation->set_rules('password_conf', 'Confirm New Password', 'trim|required');
		$this->form_validation->set_rules('user_id', 'User', 'required');

		if($this->form_validation->run() == FALSE)
		{
			$data['user'] = $this->model('user')->get_by_auth($this->input->get('auth'));

			$this->template->build('admin/auth/reset_password', $data);
		} else {
			$this->model('user')->reset_password($this->input->post('user_id'), $this->input->post('password'));

			$this->session->set_flashdata('msg', 'Password reset');

			redirect(site_url('signin'));
		}
	}
}