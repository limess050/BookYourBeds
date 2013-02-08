<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Test_account extends CI_Migration
{
	
	public function up()
	{
		$account = array(
					'account_name'		=> 'The Bunkhouse',
					'account_slug'		=> 'the-bunkhouse',
					'account_email'		=> 'phil@othertribe.com',
					'account_confirmed'	=> 1
					);

		$account_id = $this->model('account')->insert($account);

		$user = array(
					'user_firstname'	=> 'Phil',
					'user_lastname'		=> 'Stephens',
					'user_username'		=> 'phil',
					'user_email'		=> 'phil@othertribe.com',
					'user_password'		=> SHA1('pcr34t366'),
					'user_is_admin'		=> 1,
					'user_account_id'	=> $account_id
					);

		$this->model('user')->insert($user);

		$settings = array(
					'deposit'	=> 'full',
					'payment_gateway'	=> 'SagePay_Form',
					'sagepay_form_vendor_id'	=> 'applecart',
					'sagepay_form_crypt'		=> 'oG1PDrzXanmXe5JE',
					'sagepay_form_encryption_type'	=> 'AES'

					);

		$this->model('setting')->create_or_update_many($settings, $account_id);
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