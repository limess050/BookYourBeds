<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function index()
	{
		$data['bookings'] = $this->model('booking')->checking_in();
		$data['new'] = $this->model('booking')->unacknowledged();
		
		$this->template->build('admin/dashboard/index', $data);
	}

}