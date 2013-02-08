<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function index()
	{

		if(account('active'))
		{
			$this->dash();
		} else
		{
			$this->wizard();
		}
	}

	public function dash()
	{
		if( ! account('active'))
		{
			$this->index();
		}

		$data['bookings'] = $this->model('booking')->checking_in();
		$data['new'] = $this->model('booking')->unacknowledged();
		
		$this->template->build('admin/dashboard/index', $data);
	}

	public function wizard()
	{
		
		
		$data['steps'] = $this->account->wizard_steps();

		foreach($data['steps'] as $step)
		{
			$this->template->set_partial($step, 'admin/partials/wizard/' . $step);
		}

		$this->template->build('admin/dashboard/wizard', $data);
	}

}