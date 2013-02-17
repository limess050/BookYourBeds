<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_m extends MY_Model
{
	protected $reservation = array();

	protected $before_create = array('extract_reservation');
	protected $before_update = array('extract_reservation');

	protected $after_create = array('update_reservation');
	protected $after_update = array('update_reservation');

	public function __construct()
	{
		parent::__construct();
	}

	public function extract_reservation($data)
	{
		if( isset($data['resource_id']))
		{
			$this->reservation = array(
				'reservation_resource_id'	=> $data['resource_id'],
				'reservation_footprint'		=> $data['footprint'],
				'reservation_duration'		=> $data['duration'],
				'reservation_start_at'		=> (is_numeric($data['start_at'])) ? unix_to_mysql($data['start_at']) : $data['start_at'] 
				);

			unset($data['resource_id'], $data['footprint'], $data['duration'], $data['start_at']);
		}

		return $data;
	}

	public function update_reservation($data, $id)
	{
		if( ! empty($this->reservation))
		{
			// Clear the decks
			$this->db->where('reservation_booking_id', $id)
					->delete('reservations');

			$this->db->insert('reservations', array_merge(
													array('reservation_booking_id' => $id),
													$this->reservation
													));
		}
	}

	public function get($primary_value, $account_id = null)
	{
		if( ! empty($account_id))
		{
			$this->db->where('booking_account_id', $account_id);
		}

		$booking = $this->db->where($this->primary_key, $primary_value)
			
			->join('accounts', 'account_id = booking_account_id')
			->get($this->_table)
			->row();

		// ->join('customers', 'customer_id = booking_customer_id')
		if( ! empty($booking))
		{
			$booking->customer = $this->model('customer')->get($booking->booking_customer_id);	
			$booking->resources = $this->model('reservation')->get_many_by('reservation_booking_id', $primary_value);
		}

		return $booking;
	}

	public function acknowledge($id, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$this->db->where('booking_id', $id)
				->update($this->_table, array('booking_acknowledged' => 1));
	}

	public function unacknowledged($account_id = null, $count = FALSE)
	{
		$this->_set_account_id($account_id);

		$this->is_paranoid();

		$this->db
				->select('bookings.*, customers.*, resources.*')

				->select('(SELECT SUM(reservation_duration) FROM reservations WHERE reservation_booking_id = booking_id) AS reservation_duration')
				->select('(SELECT MIN(reservation_start_at) FROM reservations WHERE reservation_booking_id = booking_id) AS reservation_start_at')
				->join('reservations', 'reservation_booking_id = booking_id')
				->join('resources', 'resource_id = reservation_resource_id')
				->join('customers', 'customer_id = booking_customer_id')
				->where('reservation_start_at >=', date("Y-m-d", mktime(0, 0, 0, date('m'), date('d'), date('Y'))))
				->where($this->account_id_field, $this->account_id)
				->where('booking_acknowledged', '0')
				->where('booking_completed', '1')
				->group_by('booking_id');

		return ($count) ? count($this->db->get('bookings')->result()) : $this->db->get('bookings')->result();
	}

	// Gets the bookings that START on a given date
	public function checking_in($datetime = null, $resource_id = null, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$this->is_paranoid();

		if(empty($datetime))
		{
			$datetime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		}
		
		if( ! empty($resource_id))
		{
			$this->db->where('resource_id', $resource_id);
		}

		return $this->db
				->join('reservations', 'reservation_booking_id = booking_id')
				->join('resources', 'resource_id = reservation_resource_id')
				->join('customers', 'customer_id = booking_customer_id')
				->where('reservation_start_at', date("Y-m-d", $datetime))
				->where('reservation_checked_in', '0')
				->where($this->account_id_field, $this->account_id)
				->where('booking_completed', '1')
				->order_by('customer_lastname')
				->get('bookings')
				->result();
				
	}

	public function get_date_by_resource($datetime = null, $account_id = null, $show_all = TRUE)
	{
		$this->_set_account_id($account_id);

		if(empty($datetime))
		{
			$datetime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		}
		
		// First get the resources
		$this->db->where('resource_active', 1);
		$resources = $this->model('resource')->get_all($this->account_id);
		
		foreach($resources as $key => $resource)
		{
			$resources[$key]->bookings = $this->get_date($datetime, $resource->resource_id, $this->account_id, $show_all);
			$resources[$key]->availability = $this->model('resource')->availability($resource->resource_id, $datetime, $datetime, $this->account_id);
		}
		
		return $resources;
	}
	
	// Gets all bookings that are in progress on a given date
	public function get_date($datetime = null, $resource_id = null, $account_id = null, $show_all = TRUE)
	{
		$this->_set_account_id($account_id);

		$this->is_paranoid();

		if(empty($datetime))
		{
			$datetime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		}
		
		if( ! empty($resource_id))
		{
			$this->db->where('resource_id', $resource_id);
		}

		if($show_all)
		{
			$this->db->where('reservation_start_at <=', date("Y-m-d", $datetime))
					->where('(reservation_start_at + INTERVAL (reservation_duration - 1) DAY) >=', date("Y-m-d", $datetime));
		} else
		{
			$this->db->where('reservation_start_at', date("Y-m-d", $datetime));
		}
		
		return $this->db
				->select('bookings.*, resources.*, reservations.*, customers.*')
				->join('reservations', 'reservation_booking_id = booking_id')
				->join('resources', 'resource_id = reservation_resource_id')
				->join('customers', 'customer_id = booking_customer_id')
				->select("DATEDIFF('" . date("Y-m-d", $datetime) . "', reservation_start_at) as stage", FALSE)
				->where($this->account_id_field, $this->account_id)
				->where('booking_completed', '1')
				->order_by('booking_created_at')
				->get('bookings')
				->result();
	}

	public function search($search_terms, $account_id = null)
	{
		$this->_set_account_id($account_id);
		
		return $this->db
				->select('bookings.*, customers.*, resources.*')

				->select('(SELECT SUM(reservation_duration) FROM reservations WHERE reservation_booking_id = booking_id) AS reservation_duration')
				->select('(SELECT MIN(reservation_start_at) FROM reservations WHERE reservation_booking_id = booking_id) AS reservation_start_at')
				->join('reservations', 'reservation_booking_id = booking_id')
				->join('resources', 'resource_id = reservation_resource_id')
				->join('customers', 'customer_id = booking_customer_id')
				
				->where($this->account_id_field, $this->account_id)
				->where("(`customer_firstname` LIKE '%{$search_terms}%' 
							OR `customer_lastname` LIKE '%{$search_terms}%'
							OR `customer_email` LIKE '%{$search_terms}%'
							OR `booking_reference` LIKE '%{$search_terms}%'
							OR CONCAT(`customer_firstname`, ' ', `customer_lastname`) LIKE '%{$search_terms}%'
							OR CONCAT(`customer_lastname`, ' ', `customer_firstname`) LIKE '%{$search_terms}%')")
				
				->group_by('booking_id')
				->order_by('reservation_start_at', 'desc')
				->get('bookings')
				->result();
	}
}