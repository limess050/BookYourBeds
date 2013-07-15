<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Test_migration extends CI_Migration
{

	public function up()
	{
		echo 'This is a test';
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