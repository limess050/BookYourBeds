<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Supplement_m extends MY_Model
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

		// Needed?
		//$this->_get();

		return $this->db->where($this->primary_key, $primary_value)
			->get($this->_table)
			->row();
	}

	public function _get()
	{
		$this->db->join('accounts', 'account_id = supplement_account_id');
	}

	public function get_all($account_id = null)
	{
		$this->_set_account_id($account_id);

		return $this->db->where($this->account_id_field, $this->account_id)
				->get($this->_table)->result();
	}

	public function get_for_resource($resource_id, $account_id = null)
	{
		$_supplements = $this->get_all($account_id);

		$supplements = array();

		foreach($_supplements as $supplement)
		{
			$supplements[$supplement->supplement_id] = $supplement;
		}

		// Now get all for the resource
		$resource = $this->db->where('str_resource_id', $resource_id)
							->get('supplement_to_resource')
							->result();

		foreach($resource as $r)
		{
			$supplements[$r->str_supplement_id]->resource_id = $resource_id;
			$supplements[$r->str_supplement_id]->resource_price = $r->str_price;
		}

		return $supplements;
	}

}