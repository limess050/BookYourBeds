<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Init extends CI_Migration
{
	public function up()
	{
		$this->load->helper('file');
		
		$init = file_get_contents('system/sql/bootstrap.sql');
		
		$init = explode('-- COMMAND BREAK --', $init);

		foreach($init as $sql)
		{
			$this->db->query($sql);
		}
				
		unset($init);
	}

	public function down()
	{
		$this->load->dbforge();
		
		$tables = array('accounts',
						'bookings',
						'customers',
						'logins',
						'prices',
						'releases',
						'reservations',
						'resources',
						'seasons',
						'settings',
						'users');
		
		foreach($tables as $table)
		{
			$this->dbforge->drop_table($table);
		}
		
		
	}

}