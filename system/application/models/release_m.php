<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Release_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function update_many_by_resource($resource_id, $release_data)
	{
		foreach($release_data['day'] as $day)
		{
			$this->db->where('release_resource_id', $resource_id)
					->where('release_date', unix_to_mysql($day['timestamp']))
					->delete('releases');

			if($day['availability'] + $day['bookings'] != $day['default_release'] || $day['price'] != $day['default_price'])
			{
				$data = array(
					'release_resource_id'	=> $resource_id,
					'release_date'			=> unix_to_mysql($day['timestamp']),
					'release_amount'		=> $day['availability'] + $day['bookings'] != $day['default_release'] ? ($day['availability'] + $day['bookings']) : null,
					'release_price'			=> ($day['price'] != $day['default_price']) ? $day['price'] : null
					);

				$this->db->insert('releases', $data);
			}
		}
	}
}