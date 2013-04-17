<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends Internal_Controller {

	public function index()
	{
		$data['accounts'] = $this->model('account')->get_all_internal();

		$this->template
					->build('internal/accounts/index', $data);
		
	}

	public function create()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|callback_check_account_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('name', 'Account Name', 'trim|required');
		$this->form_validation->set_rules('email_details', '', 'trim');

		if($this->form_validation->run() == FALSE)
		{
			$this->template
					->build('internal/accounts/create');
		} else
		{
			$account_id = $this->account->create($this->input->post('name'), $this->input->post('email'), $this->input->post('password'), $this->input->post('email_details'), TRUE);

			//$this->session->set_userdata('user', $this->model('user')->get($user_id));
			//$this->account->ac = $this->model('account')->get( session('user', 'user_account_id') );
			$this->session->set_flashdata('msg', 'Account created');

			redirect(site_url('internal/accounts/edit/' . $account_id));
		}

		
	}

	public function edit($id)
	{
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('account[account_name]', 'Account Name', 'trim|required');
		$this->form_validation->set_rules('account[account_slug]', 'Account URL', 'trim|required|callback_check_account_slug');
		$this->form_validation->set_rules('account[account_email]', 'Account Email', 'trim|required|valid_email|callback_check_account_email');
		$this->form_validation->set_rules('account[account_phone]', 'Contact Telephone', 'trim');
		$this->form_validation->set_rules('account[account_description]', 'Description', 'trim');
		$this->form_validation->set_rules('account[account_website]', 'Website', 'trim');
 
		if($this->form_validation->run() == FALSE)
		{
			$data['account'] = $this->model('account')->get_internal($id);
			$data['account_logo'] = $this->model('setting')->get_setting('account_logo', $id);
			$data['account_bg'] = $this->model('setting')->get_setting('account_bg', $id);

			$this->template
						->build('internal/accounts/edit', $data);
		} else
		{
			if( ! empty($_FILES['account_logo']))
			{
				$this->account->upload_logo($this->input->post('account_id'));
			}

			if( ! empty($_FILES['account_bg']))
			{ 
				$this->account->upload_bg($this->input->post('account_id'));
			}

			$this->model('account')->update($this->input->post('account_id'), $this->input->post('account'));		
			
			$this->session->set_flashdata('msg', 'Account updated');
			redirect(site_url('internal/accounts/edit/' . $this->input->post('account_id')));
		}
	}

	public function check_account_email($str)
	{
		$this->form_validation->set_message('check_account_email', 'That email address is already in use with another account');
		return $this->model('account')->check_unique('email', $str, $this->input->post('account_id'));
	}

	public function check_account_slug($str)
	{
		$this->form_validation->set_message('check_account_slug', 'That URL is already in use with another account');
		return $this->model('account')->check_unique('slug', $str, $this->input->post('account_id'));
	}

	public function remove($id)
	{
		$this->model('account')->delete($id);

		$this->session->set_flashdata('msg', 'Account removed');
		redirect(site_url('internal/accounts'));
	}


}