<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfer extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function start($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$data['booking'] = $this->model('booking')->get($id, $this->account->val('id')); 
			
		$this->template
			->set_partial('booking_button_group', 'admin/partials/booking_button_group')
			->build('admin/transfer/start', $data);
	}

	public function search($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$data['booking'] = $this->model('booking')->get($id, $this->account->val('id')); 
			
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
			$data['resources'] = $this->model('resource')->all_availability($start, $end, account('id'), TRUE, $id);
			$data['guests'] = (int) $this->input->post('guests');
			$data['duration'] = $this->input->post('duration');
			$data['account_id'] = account('id');
			$data['resource_id'] = $this->input->post('resource_id');
		}

		$this->template
			->set_partial('booking_button_group', 'admin/partials/booking_button_group')
			->build('admin/transfer/start', $data);

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
	
	public function new_transfer($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$data['booking'] = $this->model('booking')->get($id, $this->account->val('id')); 

		// Create the temporary booking...
		// duration
		$_days = $this->input->post('day');
		$duration = count($_days);
		// resource_id
		$resource_id = $_days[1];
		// start date
		$_resources = $this->input->post('resource');
		$start_timestamp = $_resources[$resource_id]['day'][1]['timestamp'];
		$footprint = $_resources[$resource_id]['day'][1]['footprint'];

		$booking = array(
							'booking_account_id'		=> $data['booking']->account_id,
							'booking_original_id'		=> $id,
							'booking_guests'			=> $this->input->post('guests'),
							'booking_price'				=> $this->input->post('price_total'),
							'booking_room_price'		=> $this->input->post('price_total'),
							'booking_first_night_price'	=> $this->input->post('price_first_night'),
							'booking_user_id'			=> session('user', 'user_id'),
							'booking_ip_address' 		=> ci()->input->ip_address(),
							'booking_user_agent' 		=> ci()->input->user_agent(),
							'resource_id'				=> $resource_id,
							'footprint'					=> $footprint,
							'duration'					=> $duration,
							'start_at'					=> $start_timestamp
							);

		$this->session->set_userdata('transfer_booking', $booking);
			
		redirect(site_url('admin/transfer/details/' . $id));
	}

	public function details($id)
	{
		if(empty($id))
		{
			show_404();
		}

		if(session('transfer_booking', 'booking_original_id') != $id)
		{
			$this->session->unset_userdata('transfer_booking');
			redirect(site_url('admin/transfer/start/' . $id));
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('customer[customer_firstname]', 'First Name', 'trim|required');
		$this->form_validation->set_rules('customer[customer_lastname]', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('customer[customer_email]', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('customer[customer_phone]', 'Contact Telephone', 'trim');

		if($this->form_validation->run() === FALSE)
		{

			$data['booking'] = $this->model('booking')->get($id, $this->account->val('id'));
			$data['new_resource'] = $this->model('resource')->get(session('transfer_booking', 'resource_id')); 
				
			$this->template
				->set_partial('booking_button_group', 'admin/partials/booking_button_group')
				->build('admin/transfer/details', $data);
		} else
		{
			$this->session->set_userdata('transfer_booking', array_merge((array) session('transfer_booking'), $this->input->post()));

			redirect(site_url('admin/transfer/supplements/' . $id));
		}
	}

	public function supplements($id)
	{
		if(empty($id))
		{
			show_404();
		}

		if(session('transfer_booking', 'booking_original_id') != $id)
		{
			$this->session->unset_userdata('transfer_booking');
			redirect(site_url('admin/transfer/start/' . $id));
		}

		$data['booking'] = $this->model('booking')->get($id, $this->account->val('id'));
		$data['new_resource'] = $this->model('resource')->get(session('transfer_booking', 'resource_id')); 
		$data['supplements'] = $this->model('supplement')->get_for_resource(session('transfer_booking', 'resource_id'), $data['booking']->account_id);

		if(empty($data['supplements']))
		{
			$this->session->set_userdata(array_merge(
													(array) session('transfer_booking'),
													array('supplements' => array())
													));

			redirect(site_url('admin/transfer/confirm/' . $id));
		} else
		{
			$this->load->library('form_validation');

			foreach($data['supplements'] as $supplement)
			{
				$this->form_validation->set_rules("supplements[{$supplement->supplement_id}][qty]", '', 'trim');
				$this->form_validation->set_rules("supplements[{$supplement->supplement_id}][price]", '', 'trim');
				$this->form_validation->set_rules("supplements[{$supplement->supplement_id}][description]", '', 'trim');
			}
		}

		if($this->form_validation->run() === FALSE)
		{
			$this->load->helper('typography');

			foreach($data['booking']->supplements as $supplement)
			{
				$data['_supplements'][$supplement->supplement_id]['qty'] = $supplement->stb_quantity;
			}

			$this->template
				->set_partial('booking_button_group', 'admin/partials/booking_button_group')
				->build('admin/transfer/supplements', $data);
		} else
		{
			echo '<pre>';
			print_r($this->input->post('supplements'));
			echo '</pre>';

			$supplements = $this->input->post('supplements');

			$total_price = 0;

			foreach($supplements as $key => $supplement)
			{
				if(empty($supplement['qty']))
				{
					unset($supplements[$key]);
				} else
				{
					$total_price += ($supplement['qty'] * $supplement['price']);
				}
			}

			// Calculate new price etc
			$data['transfer'] = $this->session->userdata('transfer_booking');

			// Calculate the deposit
			$room_deposit = 0;
			$supplement_deposit = 0;
			
			switch(setting('deposit'))
			{
				case 'full':
					$room_deposit = session('transfer_booking', 'booking_room_price');
					$supplement_deposit = $total_price;
					break;

				case 'first':
					$room_deposit = session('transfer_booking', 'booking_first_night_price');
					switch(setting('supplement_deposit'))
					{
						case 'fraction':
							$supplement_deposit = (setting('supplement_deposit_percentage') / 100) * $total_price;
							break;

						default:
							$supplement_deposit = $total_price;
							break;
					}
					break;

				case 'fraction':
					$room_deposit = (setting('deposit_percentage') / 100) * session('transfer_booking', 'booking_room_price');
					$supplement_deposit = (setting('deposit_percentage') / 100) * $total_price;
					break;
			}

			$this->session->set_userdata('transfer_booking', array_merge(
													(array) session('transfer_booking'),
													array('booking_price' => session('transfer_booking', 'booking_room_price') + $total_price, 'booking_supplement_price' => $total_price, 'booking_deposit' => $room_deposit + $supplement_deposit, 'supplements' => ((empty($supplements)) ? array() : $supplements))
													)
													);

			redirect(site_url('admin/transfer/confirm/' . $id));
		}
	}

	public function confirm($id)
	{
		if(empty($id))
		{
			show_404();
		}

		if(session('transfer_booking', 'booking_original_id') != $id)
		{
			$this->session->unset_userdata('transfer_booking');
			redirect(site_url('admin/transfer/start/' . $id));
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules("original_deposit", '', 'trim|required');
		$this->form_validation->set_rules("email_customer", '', 'trim');
		$this->form_validation->set_rules("booking_deposit", '', 'trim');
		$this->form_validation->set_rules("booking_refund", '', 'trim');

		if($this->form_validation->run() === FALSE)
		{

			$data['booking'] = $this->model('booking')->get($id, $this->account->val('id'));
			$data['new_resource'] = $this->model('resource')->get(session('transfer_booking', 'resource_id'));
			$data['transfer'] = session('transfer_booking');
			
			$this->template
					->set_partial('booking_button_group', 'admin/partials/booking_button_group')
				->build('admin/transfer/confirm', $data);
		} else
		{
			

		
			$this->session->set_flashdata('msg', 'Booking transferred');

			redirect(site_url('admin/bookings/show/' . $this->booking->transfer(array_merge(
																						(array) session('transfer_booking'),
																						(array) $this->input->post()
																						))));
		}
	}

}