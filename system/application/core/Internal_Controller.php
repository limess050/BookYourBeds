<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Internal_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->check_access();

		$this->template->set_layout('internal');

		$this->template
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

	public function signin()
	{
		if(session('internal_user', 'internal_user_id'))
		{
			redirect(site_url('internal'));
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|sha1|callback_do_signin');

		if($this->form_validation->run() == FALSE)
		{

			$this->template
				->title('Signin')
				->build('internal/auth/signin'); 
		} else
		{
			
			redirect(
				($this->input->get('redirect') && $this->input->get('redirect') != 'internal/signin') ? 
				$this->input->get('redirect') : 
				'internal');
		}
	}

	public function do_signin($str)
	{
		$this->form_validation->set_message('do_signin', 'That username/password combination is incorrect');
		return $this->model('internal_user')->do_signin($this->input->post('username'), $str);
	}
	
	public function signout()
	{
		$this->session->sess_destroy();
		redirect('internal/signin');
	}

	private function check_access()
	{
		//return TRUE;
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
		} else if( ! session('internal_user', 'internal_user_id'))
		{
			redirect(
				site_url('internal/signin?redirect=' .  uri_string())
				);
		}

		return TRUE;
	}
	

}
