<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_all_internal()
	{	
		$this->is_paranoid();

		return $this->db->select('accounts.*')
						->select('(SELECT SUM(resource_default_release * resource_booking_footprint) FROM resources WHERE resource_account_id = account_id AND resource_active = 1) as account_capacity')
						->select('(SELECT login_at FROM logins JOIN users ON user_id = login_user_id WHERE user_account_id = account_id ORDER BY login_at DESC LIMIT 1) as account_last_activity')
						->get($this->_table)->result();
	}

	public function get_internal($primary_value)
	{
		$this->is_paranoid();

		return $this->db
			->select('accounts.*')
			->select('(SELECT SUM(resource_default_release * resource_booking_footprint) FROM resources WHERE resource_account_id = account_id AND resource_active = 1) as account_capacity')
			->select('(SELECT login_at FROM logins JOIN users ON user_id = login_user_id WHERE user_account_id = account_id ORDER BY login_at DESC LIMIT 1) as account_last_activity')
						
			->where($this->primary_key, $primary_value)
			->get($this->_table)
			->row();
	}

	public function check_unique($element, $attempt, $account_id = null)
	{
		if( ! empty($account_id))
		{
			$this->db->where('account_id !=', $account_id);
		}

		$exists = $this->db->where('account_' . $element, $attempt)
						->count_all_results('accounts');

		return ($exists > 0) ? FALSE : TRUE;
	}

	public function launch($account_id)
	{
		return $this->db->where('account_id', $account_id)
						->set('account_activated_at', 'NOW()', FALSE)
						->set('account_active', 1)
						->update('accounts');
	}


}