<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Resource_m extends MY_Model
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

		$this->_get();

		return $this->db->where($this->primary_key, $primary_value)
			->get($this->_table)
			->row();
	}

	public function _get()
	{
		$this->db->join('accounts', 'account_id = resource_account_id');
	}

	public function disable($id, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$this->db->where('resource_id', $id)
					->update($this->_table, array('resource_active' => 0));
	}

	public function enable($id, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$this->db->where('resource_id', $id)
					->update($this->_table, array('resource_active' => 1));
	}

	public function get_all($account_id = null)
	{
		$this->is_paranoid();
		
		$this->_set_account_id($account_id);

		return $this->db->where($this->account_id_field, $this->account_id)
				->get($this->_table)->result();
	}
	
	public function all_availability($start, $end, $account_id = null, $include_sessions = FALSE)
	{
		$this->_set_account_id($account_id);

		$this->db->where('resource_active', 1);
		$resources = $this->get_all($this->account_id);
		
		foreach($resources as $key => $value)
		{
			$resources[$key]->availability = $this->availability($resources[$key]->resource_id, $start, $end, $this->account_id, $include_sessions);
		}
		
		return $resources;
	}

	public function resource_availability($resource_id, $start, $end, $account_id = null, $include_sessions = FALSE)
	{
		$this->_set_account_id($account_id);

		$resource = $this->get($resource_id);
		
		$resource->availability = $this->availability($resource_id, $start, $end, $this->account_id, $include_sessions);
		
		return $resource;
	}
	
	public function availability($id, $start, $end, $account_id = null, $include_sessions = FALSE, $ignore_booking_id = null)
	{
		$this->_set_account_id($account_id);

		$resource = '';
		$current_date = $start;
		$i = 1;
		
		// Max/Min dates

		$lower = $this->model('setting')->get_setting('availability_limit_start_at', $this->account_id);
		$upper = $this->model('setting')->get_setting('availability_limit_end_at', $this->account_id);


		do
		{
			if( (! empty($lower) && human_to_unix(human_to_mysql($lower, '00:00:00')) > $current_date) || ( ! empty($upper) && human_to_unix(human_to_mysql($upper, '00:00:00')) < $current_date))
			{
				$resource[$i] = new stdClass();
				$resource[$i]->release = 0;
				$resource[$i]->bookings = 0;
				$resource[$i]->bookings_pending = 0;
				$resource[$i]->bookings_unverified = 0;
				$resource[$i]->resource_default_release = 0;
				$resource[$i]->default_price = 0;
				$resource[$i]->price = 0;
			} else
			{
				$this->db->select("(
								IF(
								(SELECT release_amount FROM releases WHERE release_resource_id = '{$id}' AND release_date = '" . date("Y-m-d", $current_date) . "') IS NOT NULL,
								(SELECT release_amount FROM releases WHERE release_resource_id = '{$id}' AND release_date = '" . date("Y-m-d", $current_date) . "'),
								(SELECT resource_default_release FROM resources WHERE resource_id = '{$id}')
								)
								) as 'release'");
				
				$this->db->select("(SELECT SUM(reservation_footprint) FROM reservations JOIN bookings ON booking_id = reservation_booking_id WHERE reservation_resource_id = '{$id}' AND reservation_start_at <= '" . date("Y-m-d", $current_date) . "' AND (reservation_start_at + INTERVAL (reservation_duration - 1) DAY) >= '" . date("Y-m-d", $current_date) . "' AND booking_completed = 1 AND booking_deleted_at = 0) as bookings");
							
				// Need to take account of sessions...
				if(  $include_sessions)
				{
					$this->db->select("(SELECT SUM(reservation_footprint) FROM reservations 
															JOIN bookings ON booking_id = reservation_booking_id 
															JOIN sessions ON session_id = booking_session_id
															WHERE reservation_resource_id = '{$id}' 
															AND booking_completed = 0
															AND booking_aborted = 0
															AND reservation_start_at <= '" . date("Y-m-d", $current_date) . "' 
															AND (reservation_start_at + INTERVAL (reservation_duration - 1) DAY) >= '" . date("Y-m-d", $current_date) . "') 
										
										
										as bookings_pending");
				}

				$this->db->select("(SELECT SUM(reservation_footprint) FROM reservations 
												JOIN bookings ON booking_id = reservation_booking_id 
												WHERE reservation_resource_id = '{$id}' 
												AND reservation_start_at <= '" . date("Y-m-d", $current_date) . "' 
												AND (reservation_start_at + INTERVAL (reservation_duration - 1) DAY) >= '" . date("Y-m-d", $current_date) . "' 
												AND booking_completed = 0 
												AND booking_confirmation_sent_at >= '" . unix_to_mysql(strtotime('-24 hour', now()), TRUE, 'eu') . "')
											as bookings_unverified");

				$this->db->select('resource_default_release');
				
				$this->db->select("(
								IF(
								(SELECT `price_price` FROM (`prices`) JOIN `seasons` ON `season_id` = `price_season_id` WHERE `price_resource_id` = '{$id}' AND `price_day_id` = '" . date("N", $current_date) . "' AND `season_start_at` <= '" . date("Y-m-d", $current_date) . "' AND `season_end_at` >= '" . date("Y-m-d", $current_date) . "' ORDER BY `season_sort_order` asc, `season_id` asc LIMIT 1) IS NOT NULL,
								
								(SELECT `price_price` FROM (`prices`) JOIN `seasons` ON `season_id` = `price_season_id` WHERE `price_resource_id` = '{$id}' AND `price_day_id` = '" . date("N", $current_date) . "' AND `season_start_at` <= '" . date("Y-m-d", $current_date) . "' AND `season_end_at` >= '" . date("Y-m-d", $current_date) . "' ORDER BY `season_sort_order` asc, `season_id` asc LIMIT 1),
								(SELECT `price_price` FROM (`prices`) WHERE `price_resource_id` = '{$id}' AND `price_season_id` = 0 AND `price_day_id` = '" . date("N", $current_date) . "')
								)
								) as default_price", FALSE);

				$this->db->select("(
								IF(
								(SELECT release_price FROM releases WHERE release_resource_id = '{$id}' AND release_date = '" . date("Y-m-d", $current_date) . "') IS NOT NULL,

								(SELECT release_price FROM releases WHERE release_resource_id = '{$id}' AND release_date = '" . date("Y-m-d", $current_date) . "'),
								(
									IF(
									(SELECT `price_price` FROM (`prices`) JOIN `seasons` ON `season_id` = `price_season_id` WHERE `price_resource_id` = '{$id}' AND `price_day_id` = '" . date("N", $current_date) . "' AND `season_start_at` <= '" . date("Y-m-d", $current_date) . "' AND `season_end_at` >= '" . date("Y-m-d", $current_date) . "' ORDER BY `season_sort_order` asc, `season_id` asc LIMIT 1) IS NOT NULL,
									
									(SELECT `price_price` FROM (`prices`) JOIN `seasons` ON `season_id` = `price_season_id` WHERE `price_resource_id` = '{$id}' AND `price_day_id` = '" . date("N", $current_date) . "' AND `season_start_at` <= '" . date("Y-m-d", $current_date) . "' AND `season_end_at` >= '" . date("Y-m-d", $current_date) . "' ORDER BY `season_sort_order` asc, `season_id` asc LIMIT 1),
									(SELECT `price_price` FROM (`prices`) WHERE `price_resource_id` = '{$id}' AND `price_season_id` = 0 AND `price_day_id` = '" . date("N", $current_date) . "')
									)
								)

								)

								
								) as price", FALSE);
				 
				$resource[$i] = $this->db->where('resource_id', $id)
											->where($this->account_id_field, $this->account_id)
											->get('resources')
											->row();
			}
			
			$resource[$i]->timestamp = $current_date;
			$resource[$i]->bookings = ( ! empty($resource[$i]->bookings)) ? $resource[$i]->bookings : 0;
			
			// Advance
			$current_date = strtotime('+1 day', $current_date);		
			$i++;
		} while($current_date <= $end);
		
		return $resource;
	}

}