<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Season_m extends MY_Model
{
	protected $before_create = array('human_to_mysql');
	protected $before_update = array('human_to_mysql');

	public function __construct()
	{
		parent::__construct();
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

}