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
		$this->is_paranoid();

		$this->_set_account_id($account_id);

		return $this->db->where($this->account_id_field, $this->account_id)
				->get($this->_table)->result();
	}

	public function get_for_resource($resource_id, $account_id = null, $return_all = FALSE)
	{
		$this->_set_account_id($account_id);

		$this->db->select('supplements.*');

		if(! $return_all)
		{
			$this->db->join('supplement_to_resource', 'str_supplement_id = supplement_id AND str_resource_id = ' . $resource_id);
		}

		$this->db->select("(
							IF(
							(SELECT str_price FROM supplement_to_resource WHERE str_supplement_id = supplement_id AND str_resource_id = '{$resource_id}') IS NOT NULL,
							(SELECT str_price FROM supplement_to_resource WHERE str_supplement_id = supplement_id AND str_resource_id = '{$resource_id}'),
							(SELECT supplement_default_price)
							)
							) as 'resource_price'");

		$this->db->select("(
							IF(
							(SELECT str_resource_id FROM supplement_to_resource WHERE str_supplement_id = supplement_id AND str_resource_id = '{$resource_id}') IS NOT NULL,
							(SELECT str_resource_id FROM supplement_to_resource WHERE str_supplement_id = supplement_id AND str_resource_id = '{$resource_id}'),
							'0'
							)
							) as 'resource_id'");

		return $this->db->where('supplement_active', 1)
						->where($this->account_id_field, $this->account_id)
									->get($this->_table)->result();
	}

	public function update_resource($resource_id, $supplements)
	{
		// Clear the decks
		$this->db->where('str_resource_id', $resource_id)
					->delete('supplement_to_resource');

		// Reinsert
		foreach($supplements as $supplement)
		{
			if( ! empty($supplement['str_resource_id']))
			{
				if($supplement['str_price'] == $supplement['supplement_default_price'])
				{
					unset($supplement['str_price']);
				}

				unset($supplement['supplement_default_price']);

				$this->db->insert('supplement_to_resource', $supplement);
			}
		}
	}

	public function add_to_booking($supplement_id, $booking_id, $qty, $price = 0)
	{
		$this->db->insert('supplement_to_booking', array(
														'stb_supplement_id'	=> $supplement_id,
														'stb_booking_id'	=> $booking_id,
														'stb_quantity'		=> $qty,
														'stb_price'			=> $qty * $price
														));
	}

	public function clear_from_booking($booking_id, $supplement_id = null)
	{
		if(! empty($supplement_id))
		{
			$this->db->where('stb_supplement_id', $supplement_id);
		}

		$this->db->where('stb_booking_id', $booking_id)
				->delete('supplement_to_booking');
	}

	public function get_for_booking($booking_id)
	{
		return $this->db->join('supplements', 'supplement_id = stb_supplement_id')
						->where('stb_booking_id', $booking_id)
						->get('supplement_to_booking')
						->result();
	}

	public function disable($id, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$this->db->where('supplement_id', $id)
					->update($this->_table, array('supplement_active' => 0));
	}

	public function enable($id, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$this->db->where('supplement_id', $id)
					->update($this->_table, array('supplement_active' => 1));
	}
}