<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Adding_refunds extends CI_Migration
{

	public function up()
	{
		$this->load->dbforge();

		$fields = array(
		                'booking_refund'	=> array(
		                										'type' 			=> 'FLOAT',
										                        'default' 			=> 0),
		                'booking_refund_refunded'	=> array(
		                									'type'	=> 'TINYINT',
		                									'contraint'	=> 1,
		                									'default'	=> 0
		                										)
		);
		
		$this->dbforge->add_column('bookings', $fields);

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