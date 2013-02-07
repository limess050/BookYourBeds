<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Admin_Controller {

	public function index()
	{
		$this->admin_access();

		$data['users'] = $this->model('user')->get_all(account('id'));

		$this->template->build('admin/users/index', $data);
	}

	public function create()
	{
		$this->admin_access();

		$this->load->library('form_validation');

		$this->form_validation->set_rules('user[user_firstname]', 'First Name', 'trim|required');
		$this->form_validation->set_rules('user[user_lastname]', 'Last Name', 'trim');
		$this->form_validation->set_rules('user[user_username]', 'Username', 'trim|required|callback_check_username');
		$this->form_validation->set_rules('user[user_password]', 'Password', 'trim|required|sha1');

		if($this->form_validation->run() == FALSE)
		{
			$this->template->build('admin/users/create');
		} else
		{
			$this->model('user')->insert($this->input->post('user'));

			$this->session->set_flashdata('msg', 'New user successfully created');

			redirect(site_url('admin/users'));
		}
		
	}

	public function me()
	{
		$data['user'] = $this->model('user')->get(session('user', 'user_id'));

		$this->template->build('admin/users/edit', $data);
	}

	public function edit($id)
	{
		if($id != session('user', 'user_id'))
		{
			$this->admin_access();
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('user[user_firstname]', 'First Name', 'trim|required');
		$this->form_validation->set_rules('user[user_lastname]', 'Last Name', 'trim');
		$this->form_validation->set_rules('user[user_username]', 'Username', 'trim|required|callback_check_username');
		//$this->form_validation->set_rules('user[user_email]', 'Email Address', 'trim|valid_email');
		//$this->form_validation->set_rules('user[user_agent_admin]', '', 'trim|callback_set_to_zero');

		if( ! empty($_POST['password']))
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]|sha1');
			$this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required');
		}

		if($this->form_validation->run() === FALSE)
		{
			$data['user'] = $this->model('user')->get($id);

			$this->template
					->build('admin/users/edit', $data);
		} else
		{
			$user = $this->input->post('user');

			if($this->input->post('password'))
			{
				$user['user_password'] = $this->input->post('password');
			}

			$this->model('user')->update($this->input->post('user_id'), $user);

			$this->session->set_flashdata('msg', 'User updated');

			redirect(site_url('admin/users/edit/' . $this->input->post('user_id')));
		}
	}

	public function check_username($str)
	{
		$this->form_validation->set_message('check_username', 'That Username has already been taken.');
		return $this->model('user')->check_username($str, $this->input->post('user_id'));
	}

	public function delete($id = null)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->admin_access();

		$this->db->where('user_account_id', account('id'));
		
		if($this->model('user')->delete($id))
		{
			$this->session->set_flashdata('msg', 'User successfully removed');	
		} else
		{
			$this->session->set_flashdata('msg', 'Unable to remove user');
		}

		redirect(site_url('admin/users'));
	}

}