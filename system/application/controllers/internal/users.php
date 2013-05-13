<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Internal_Controller {

	
	public function me()
	{
		$data['user'] = $this->model('internal_user')->get(session('internal_user', 'internal_user_id'));

		$this->template->build('internal/users/edit', $data);
	}

	public function edit($id)
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('user[internal_user_firstname]', 'First Name', 'trim');
		$this->form_validation->set_rules('user[internal_user_lastname]', 'Last Name', 'trim');
		$this->form_validation->set_rules('user[internal_user_username]', 'Username', 'trim|callback_check_username');
		$this->form_validation->set_rules('user[internal_user_email]', 'Email Address', 'trim|valid_email');

		if( ! empty($_POST['password']))
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]|sha1');
			$this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required');
		}

		if($this->form_validation->run() === FALSE)
		{
			$data['user'] = $this->model('internal_user')->get($id);

			$this->template
					->build('internal/users/edit', $data);
		} else
		{
			$user = $this->input->post('user');

			if($this->input->post('password'))
			{
				$user['internal_user_password'] = $this->input->post('password');
			}

			$this->model('internal_user')->update($this->input->post('user_id'), $user);

			$this->session->set_flashdata('msg', 'User updated');

			redirect(site_url('internal/users/edit/' . $this->input->post('user_id')));
		}
	}

	public function check_username($str)
	{
		$this->form_validation->set_message('check_username', 'That Username has already been taken.');
		return (empty($str)) ? TRUE : $this->model('internal_user')->check_unique('username', $str, $this->input->post('user_id'));
	}


}