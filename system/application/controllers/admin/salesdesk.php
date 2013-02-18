<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesdesk extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->template->set_partial('cart', 'admin/partials/cart');
	}

	public function index()
	{
		$this->search();
	}

	public function search()
	{
		$data = array();

		$this->load->library('form_validation');
		$this->form_validation->set_rules('start_at', 'Date', 'trim|required');
		$this->form_validation->set_rules('duration', 'Duration', 'callback_max_duration');
		$this->form_validation->set_rules('guests', 'Guests', 'callback_max_guests');

		$this->form_validation->set_message('is_natural_no_zero', 'Please select a hostel.');
		$this->form_validation->set_message('required', 'Please enter an arrival date.');

		if($this->form_validation->run() == TRUE)
		{
			$start = $data['start_timestamp'] = human_to_unix(human_to_mysql($this->input->post('start_at')) . ' 00:00:00');
			$end = strtotime("+{$this->input->post('duration')} days", $start);
			
			$data['today'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));		
			$data['resources'] = $this->model('resource')->all_availability($start, $end, account('id'), TRUE);
			$data['guests'] = (int) $this->input->post('guests');
			$data['duration'] = $this->input->post('duration');
			$data['account_id'] = account('id');
		}

		$this->template->build('admin/salesdesk/index', $data);	
	}

	public function max_duration($str)
	{
		$this->form_validation->set_message('max_duration', 'For stays longer than 7 days please contact the hostel directly.');
		return $str < 8;
	}

	public function max_guests($str)
	{
		$this->form_validation->set_message('max_guests', 'For more than 6 guests please contact the hostel directly.');
		return $str < 7;
	}

	public function reset()
	{
		$this->booking->reset();

		redirect(site_url('admin/salesdesk'));
	}

	public function new_booking()
	{
		// guests
		$guests = $this->input->post('guests');
		// duration
		$_days = $this->input->post('day');
		$duration = count($_days);
		// resource_id
		$resource_id = $_days[1];
		// start date
		$_resources = $this->input->post('resource');
		$start_timestamp = $_resources[$resource_id]['day'][1]['timestamp'];
		$footprint = $_resources[$resource_id]['day'][1]['footprint'];
		// total price
		$price = $this->input->post('price_total');
		// deposit
		$deposit = $this->input->post('price_deposit');

		if( $this->booking->create(account('id'), $resource_id, $start_timestamp, $duration, $guests, $footprint, $price, $deposit, session('user', 'user_id')))
		{
			redirect(site_url('admin/salesdesk/details'));
		} else
		{
			redirect(site_url('admin/salesdesk'));
		}
	}

	public function details()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('customer[customer_firstname]', 'First Name', 'trim|required');
		$this->form_validation->set_rules('customer[customer_lastname]', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('customer[customer_email]', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('customer[customer_phone]', 'Contact Telephone', 'trim');

		if($this->form_validation->run() === FALSE)
		{
			
			$this->template->build('admin/salesdesk/details');
		} else
		{
			$this->booking->update_session($this->input->post());
			//$this->session->set_userdata('booking', (object) array_merge((array) session('booking'), (array) $this->input->post()));

			redirect(site_url('admin/salesdesk/supplements'));
		}
	}

	public function supplements()
	{
		redirect(site_url('admin/salesdesk/confirm'));
	}

	public function confirm()
	{
		// Merge any data that might have come from a failed submission
		$this->booking->update_session($this->input->post());
		//$this->session->set_userdata('booking', (object) array_merge((array) session('booking'), (array) $this->input->post()));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('booking_id', '', 'required');

		if($this->form_validation->run() === FALSE)
		{
			$data['booking'] = $this->booking->session();
			$data['resources'] = booking('resources');
			$data['customer'] = booking('customer');

			$this->template
					->append_metadata( js('bootstrap-modal.js'))
					->build('admin/salesdesk/confirm', $data);
		} else
		{
			//$this->session->set_userdata('booking', (object) array_merge((array) session('booking'), (array) $this->input->post()));

			$this->session->set_flashdata('msg', 'Booking Complete');

			redirect(site_url('admin/bookings/show/' . $this->booking->process()));
		}
	}


	public function complete()
	{
		if( ! $this->session->flashdata('booking_id'))
		{
			if(ENVIRONMENT == 'development' && $this->input->get('id'))
			{
				$data['booking'] = $this->model('booking')->get($this->input->get('id'));
			} else
			{
				redirect(site_url());
			}
		} else
		{
			$data['booking'] = $this->model('booking')->get($this->session->flashdata('booking_id'));
		}

		$data['gateway_data'] = ( ! empty($data['booking']->booking_gateway_data)) ? unserialize($data['booking']->booking_gateway_data) : null;

		$this->template->build('salesdesk/complete', $data);
	}



}