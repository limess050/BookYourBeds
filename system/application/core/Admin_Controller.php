<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Admin_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->check_access();

		if(account('id'))
		{
			$this->account->new_bookings();
			$this->account->bookings_awaiting_verification();
			$this->account->new_cancellations();
			$this->template->set_layout('admin');
		} else
		{
			$this->template->set_layout('default');
		}

		$this->template
			->enable_parser(FALSE)

			->set_partial('form_errors', 'partials/form_errors')
			
			->append_metadata( js('jquery.js') )
			->append_metadata( js('bootstrap-modal.js') )
			->append_metadata( js('bootstrap-dropdown.js') )

			->append_metadata( css('style.css') )
			->append_metadata( js('application.js') )
			->append_metadata( js('spin.js') )
			->append_metadata( js('holder.js') )

			->append_metadata( css('jquery.ui.css') ) 
			->append_metadata( js('jquery.ui.js') );
	}

	public function signin()
	{
		if(session('user', 'user_id'))
		{
			ci()->account->ac = $this->model('account')->get( session('user', 'user_account_id') );
			redirect(site_url('admin'));
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Email Address', 'trim|required|xss_clean|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_do_signin');

		if($this->form_validation->run() == FALSE)
		{

			$this->template
				->title('Signin')
				->build('admin/auth/signin'); 
		} else
		{
			
			redirect(
				($this->input->get('redirect') && $this->input->get('redirect') != 'signin') ? 
				$this->input->get('redirect') : 
				'admin');
		}
	}

	public function new_valid_email($str)
	{
		$this->form_validation->set_message('new_valid_email', 'The email address is not valid');
		return filter_var($str, FILTER_VALIDATE_EMAIL);
	}

	public function do_signin($str)
	{
		$this->form_validation->set_message('do_signin', 'That username/password combination is incorrect');
		return $this->model('user')->do_signin($this->input->post('username'), $str);
	}
	
	public function signout()
	{
		$this->session->sess_destroy();
		redirect('signin');
	}

	private function check_access()
	{
		
		// These pages get past permission checks
	    $ignored_methods = array('signin', 'signout', 'forgotten_password', 'reset_password');

	    if(ci()->uri->total_segments() == 1 && in_array($this->uri->segment(1), $ignored_methods))
		{
			return TRUE;
		}

	    // Check if the current page is to be ignored
	    $current_method = $this->uri->rsegment(2);

	    // Dont need to log in, this is an open page
		if(in_array($current_method, $ignored_methods))
		{
			return TRUE;
		} else if( ! session('user', 'user_id'))
		{
			redirect(
				site_url('signin?redirect=' . 
							str_replace(ci()->account->val('slug') . '/', '', uri_string()))
						);
		} else if( session('user', 'user_id') && session('user', 'user_account_id') != ci()->account->val('id') )
		{
			ci()->account->ac = $this->model('account')->get( session('user', 'user_account_id') );

			redirect(site_url('admin'));
		}

		return TRUE;
	}
 
	protected function admin_access()
	{
		if( ! session('user', 'user_is_admin'))
		{
			show_404();
		}
	}
	

}
