<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Price_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_resource($resource_id = null, $season_id = 0)
	{
		$temp = $this->db
						->where('price_resource_id', $resource_id)
						->where('price_season_id', $season_id)
						->order_by('price_day_id')
						->get('prices')
						->result();
						
		foreach($temp as $price)
		{
			$prices[$price->price_day_id] = $price;
		}
		
		return (!empty($prices)) ? $prices : null;
	}
	
	public function get_resource_seasons($resource_id = null, $account_id = null)
	{
		$this->_set_account_id($account_id);

		$seasons = $this->model('season')->get_valid($this->account_id);
		
		foreach($seasons as $key => $season)
		{
			$seasons[$key]->prices = $this->get_resource($resource_id, $season->season_id);
		}
		
		return $seasons;
	}
	
	public function create_or_update($resource_id, $price_data)
	{
		//echo '<pre>';
		foreach($price_data as $season_id => $season)
		{
			foreach($season as $day_id => $price)
			{
				//print_r($price);
				
				// Is there an ID?
				if( ! empty($price['id']))
				{
					$this->db->where('price_id', $price['id']);
					
					// Is the price empty?
					if(strlen($price['price']) > 0  || $season_id == 0)
					{
						// Update it
						$this->db->update('prices', array('price_price' => $price['price']));
					} else
					{
						// Delete the price field
						$this->db->delete('prices');
					}
					
				} else
				{
					// Delete any prices with the same resource, season and day IDs to avoid conflicts
					$this->db->where('price_resource_id', $resource_id)
							->where('price_season_id', $season_id)
							->where('price_day_id', $day_id)
							->delete('prices');
					
					if(strlen($price['price']) > 0 || $season_id == 0)
					{
						$data = array(
							'price_resource_id'	=> $resource_id,
							'price_season_id'	=> $season_id,
							'price_day_id'		=> $day_id,
							'price_price'		=> $price['price']
						);
						
						$this->db->insert('prices', $data);
					}
				}
			}
		}
		//echo '</pre>';
	}
}