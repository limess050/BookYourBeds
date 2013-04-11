<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_m extends MY_Model
{
	public function get_all($account_id = null)
	{
		$this->_set_account_id($account_id);

		return $this->db->where($this->account_id_field, $this->account_id)
				->get($this->_table)->result();
	}

	public function get_setting($key, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$setting = $this->db->where($this->account_id_field, $this->account_id)
							->where('setting_key', $key)
							->get('settings')
							->row();

		return ( ! empty($setting)) ? $setting->setting_value : FALSE;
	}
	
	public function create_or_update($key, $value = 0, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$exists = $this->db->where($this->account_id_field, $this->account_id)
				->where('setting_key', $key)
				->count_all_results('settings');

		if($exists > 0)
		{
			$this->db->where($this->account_id_field, $this->account_id)
				->where('setting_key', $key)
				->update('settings', array('setting_value' => $value));
		} else
		{
			$this->db->insert('settings', array(
				'setting_account_id'	=> $this->account_id,
				'setting_key'			=> $key,
				'setting_value'			=> $value));
		}

	}

	public function create_or_update_many($data, $account_id = null)
	{
		foreach($data as $key => $value)
		{
			$this->create_or_update($key, $value, $account_id);
		}
	}

	public function delete_many($data, $account_id = null)
	{
		foreach($data as $key)
		{
			$this->delete_setting($key, $account_id);
		}
	}

	public function delete_setting($key, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$this->db->where($this->account_id_field, $this->account_id)
				->where('setting_key', $key)
				->delete('settings');
	}

}