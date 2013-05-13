<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Season_m extends MY_Model
{
	protected $before_create = array('human_to_mysql', 'get_latest_sort');
	protected $before_update = array('human_to_mysql');

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

		return $this->db->where($this->primary_key, $primary_value)
			->get($this->_table)
			->row();

	}

	public function get_all($account_id = null)
	{
		$this->_set_account_id($account_id);

		return $this->db->where($this->account_id_field, $this->account_id)
				->order_by('season_sort_order')
				->get($this->_table)->result();
	}

	public function get_valid($account_id = null)
	{
		$this->_set_account_id($account_id);

		return $this->db
					->where('season_end_at >= CURDATE()')
					->where($this->account_id_field, $this->account_id)
					->order_by('season_sort_order')
					->get('seasons')
					->result();
	}

	public function human_to_mysql($data)
	{
		if( ! empty($data['season_start_at']))
		{
			$data['season_start_at'] = human_to_mysql($data['season_start_at']);
		}

		if( ! empty($data['season_end_at']))
		{
			$data['season_end_at'] = human_to_mysql($data['season_end_at']);
		}
		
		return $data;
	}

	public function get_latest_sort($data)
	{
		if(empty($data['season_account_id']))
		{
			$data['season_account_id'] = $this->account->val('id');
		} 

		$max = $this->db->where('season_account_id', $data['season_account_id'])
						->select_max('season_sort_order')
						->get('seasons')
						->row();

		$data['season_sort_order'] = ($max->season_sort_order + 1);
		
		return $data;
	}

}