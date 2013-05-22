<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Reservation_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_by()
	{
		$where = func_get_args();
		$this->_set_where($where);
		
		$this->db->join('resources', 'resource_id = reservation_resource_id')
				->join('accounts', 'account_id = resource_account_id');
		
		return $this->db->get($this->_table)
			->row();
	}

	public function get_many_by()
	{
		$where = func_get_args();
		$this->_set_where($where);

		$this->db->join('resources', 'resource_id = reservation_resource_id')
				->join('accounts', 'account_id = resource_account_id')
				->order_by('reservation_start_at');

		return $this->get_all();
	}

	public function get_for_booking($booking_id)
	{
		$reservations = $this->get_many_by('reservation_booking_id', $booking_id);

		foreach($reservations as $key => $reservation)
		{
			$reservations[$key]->supplements = $this->model('supplement')->get_for_reservation($booking_id, $reservation->reservation_resource_id);
		}

		return $reservations;
	}
}