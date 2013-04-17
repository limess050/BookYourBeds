<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Internal_users extends CI_Migration
{

	public function up()
	{
		$users = array(
					array(
						'internal_user_username'	=> 'phil',
						'internal_user_firstname'	=> 'Phil',
						'internal_user_lastname'	=> 'Stephens',
						'internal_user_password'	=> SHA1('pcr34t366'),
						'internal_user_email'		=> 'phil@othertribe.com'
						),
					array(
						'internal_user_username'	=> 'dave',
						'internal_user_firstname'	=> 'David',
						'internal_user_lastname'	=> 'Keith',
						'internal_user_password'	=> SHA1('allthegags'),
						'internal_user_email'		=> 'mail@thebedbooker.com'
						),
					array(
						'internal_user_username'	=> 'fraser',
						'internal_user_firstname'	=> 'Fraser',
						'internal_user_lastname'	=> 'Christie',
						'internal_user_password'	=> SHA1('fchristie'),
						'internal_user_email'		=> 'fraserchristie@hotmail.co.uk'
						),
					array(
						'internal_user_username'	=> 'gibson',
						'internal_user_firstname'	=> 'Gibson',
						'internal_user_lastname'	=> 'Lowry',
						'internal_user_password'	=> SHA1('glowry'),
						'internal_user_email'		=> 'giblowry@hotmail.com'
						),
					array(
						'internal_user_username'	=> 'doug',
						'internal_user_firstname'	=> 'Doug',
						'internal_user_lastname'	=> 'Atkinson',
						'internal_user_password'	=> SHA1('appleskins')
						),
					array(
						'internal_user_username'	=> 'test',
						'internal_user_firstname'	=> 'Test',
						'internal_user_lastname'	=> 'Account',
						'internal_user_password'	=> SHA1('password')
						)
					);

		$this->model('internal_user')->insert_many($users);
	}

	public function down()
	{
		$tables = array('internal_users');
		
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