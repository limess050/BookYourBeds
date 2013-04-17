<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Internal_user_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function do_signin($username, $password)
	{
		$this->is_paranoid();
		
		$user = $this->db->where('internal_user_username', $username)
						->where('internal_user_password', $password)
						->get('internal_users')
						->row();


		if( ! empty($user))
		{
			$this->session->set_userdata('internal_user', $user);
			
			return TRUE;
		}

		return FALSE;
	}

	public function check_unique($element, $attempt, $internal_user_id = null)
	{
		if( ! empty($internal_user_id))
		{
			$this->db->where('internal_user_id !=', $internal_user_id);
		}

		$exists = $this->db->where('internal_user_' . $element, $attempt)
						->count_all_results('internal_users');

		return ($exists > 0) ? FALSE : TRUE;
	}

}