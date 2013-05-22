<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Advanced_booking_engine extends CI_Migration
{

	public function up()
	{
		$this->load->dbforge();

		$fields = array(
		                'reservation_guests'	=> array(
                										'type' 			=> 'INT',
                										'constraint'	=> 6,
								                        'default' 		=> 1),
		                'reservation_price'		=> array(
		                								'type'		=> 'FLOAT',
		                								'default'	=> 0
		                								)
		);
		
		$this->dbforge->add_column('reservations', $fields);

		//$this->db->query('ALTER TABLE `reservations` ADD `reservation_id` INT(11) UNSIGNED  NOT NULL  AUTO_INCREMENT  PRIMARY KEY  FIRST;');

		// Go through and populate the test columns...
		$reservations = $this->db->join('bookings', 'booking_id = reservation_booking_id')
								->get('reservations')
								->result();

		foreach($reservations as $reservation)
		{
			$this->db->where('reservation_booking_id', $reservation->reservation_booking_id)
						->where('reservation_resource_id', $reservation->reservation_resource_id)
						->update('reservations', array(
														'reservation_guests'	=> $reservation->booking_guests,
														'reservation_price'		=> $reservation->booking_room_price
														));
		}


		$this->db->query('ALTER TABLE `supplement_to_booking` ADD `stb_resource_id` INT(11)  NOT NULL  DEFAULT \'0\'  AFTER `stb_booking_id`;');
		$this->db->query('ALTER TABLE `supplement_to_booking` ADD INDEX (`stb_resource_id`);');

	}

	public function down()
	{
		
	}

	protected function model($name)
	{
		$name = $name . MODEL_SUFFIX;
		
		// is there a module involved
		$model_name = explode('/', $name);
		
		if ( ! isset($this->{end($model_name)}) )
		{
			$this->load->model($name, '', TRUE);
		}

		return $this->{end($model_name)};
	}

}