<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function launch($account_id)
	{
		return $this->db->where('account_id', $account_id)
						->set('account_activated_at', 'NOW()', FALSE)
						->set('account_active', 1)
						->update('accounts');
	}

}