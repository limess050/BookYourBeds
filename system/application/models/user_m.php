<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class User_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get($primary_value, $account_id = null)
	{
		if( ! empty($account_id))
		{
			$this->db->where($this->account_id_field, $account_id);
		}

		$this->is_paranoid();

		return $this->db->where($this->primary_key, $primary_value)
			->get($this->_table)
			->row();
	}

	public function _get_all($account_id = null)
	{
		/*$this->_set_account_id($account_id);

		$this->is_paranoid();

		return $this->db->where($this->account_id_field, $this->account_id)
				->get($this->_table)->result();*/

		$this->is_paranoid();


	}
	
	public function do_signin($username, $password)
	{
		$this->load->library('PasswordHash', array(8, FALSE));

		$this->is_paranoid();
		
		/*$user = $this->db->where('user_username', $username)
						->or_where('user_email', $username)
						->get('users')
						->row();*/

		$user = $this->db->where('user_email', $username)
						->get('users')
						->row();

		if( ! empty($user) && $this->passwordhash->CheckPassword($password, $user->user_password) && $account = $this->model('account')->get($user->user_account_id))
		{
			$this->session->set_userdata('user', $user);
			$this->session->set_userdata('account', $account);
			
			$this->account->ac = $account;

			// Set login
			$this->db->insert('logins', array('login_user_id' => $user->user_id));

			return TRUE;
		}

		return FALSE;
	}

	public function check_unique($element, $attempt, $user_id = null)
	{
		if( ! empty($user_id))
		{
			$this->db->where('user_id !=', $user_id);
		}

		$exists = $this->db->where('user_' . $element, $attempt)
						->count_all_results('users');

		return ($exists > 0) ? FALSE : TRUE;
	}

	public function get_by_auth($auth)
	{
		$this->is_paranoid();
		
		return $this->db->where('user_password_reset', $auth)
						->where('user_password_reset_expires >', 'NOW()', FALSE)
						->get('users')
						->row();
	}

	public function reset_password($id, $password)
	{
		$this->load->library('PasswordHash', array(8, FALSE));

		$this->db->where('user_id', $id)
				->update('users', array(
										'user_password'					=> $this->passwordhash->HashPassword($password),
										'user_password_reset'			=> null,
										'user_password_reset_expires'	=> null
										));
	}

}