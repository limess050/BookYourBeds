<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Test_account extends CI_Migration
{
	
	public function up()
	{
		$account = array(
					'account_name'	=> 'Testing Account',
					'account_slug'	=> 'test',
					'account_email'	=> 'phil@othertribe.com'
					);

		$account_id = $this->model('account')->insert($account);

		$user = array(
					'user_firstname'	=> 'Joe',
					'user_lastname'		=> 'Bloggs',
					'user_username'		=> 'joe',
					'user_password'		=> SHA1('password'),
					'user_is_admin'		=> 1,
					'user_account_id'	=> $account_id
					);

		$this->model('user')->insert($user);
	}

	public function down()
	{
		$tables = array('accounts',
						'bookings',
						'customers',
						'logins',
						'prices',
						'releases',
						'reservations',
						'resources',
						'seasons',
						'sessions',
						'settings',
						'users');
		
		foreach($tables as $table)
		{
			$this->db->truncate($table);
		}
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