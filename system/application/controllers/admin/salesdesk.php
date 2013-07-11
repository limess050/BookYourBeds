<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
		$this->form_validation->set_rules('start_at', 'Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('duration', 'Duration', 'callback_max_duration|xss_clean');
		$this->form_validation->set_rules('guests', 'Guests', 'callback_max_guests|xss_clean');

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
		$this->form_validation->set_message('max_duration', 'Please enter a whole number of nights greater than 0.');
		return ($str != 0 && ctype_digit((string) $str));
	}

	public function max_guests($str)
	{
		$this->form_validation->set_message('max_guests', 'Please enter a whole number of guests greater than 0.');
		return ($str != 0 && ctype_digit((string) $str));
	}

	public function reset()
	{
		$this->booking->reset();

		redirect(site_url('admin/salesdesk'));
	}

	public function new_booking()
	{

		if( $this->booking->create(account('id'),
									$this->input->post('start_timestamp'),
									$this->input->post('duration'),
									$this->input->post('resource'),
									session('user', 'user_id')
									)
									)
		{
			redirect(site_url('admin/salesdesk/details'));
		} else
		{
			redirect(site_url('admin/salesdesk'));
		}
	}

	public function details()
	{
		if( ! booking('booking_id'))
		{
			redirect(site_url('admin/salesdesk'));
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('customer[customer_firstname]', 'First Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('customer[customer_lastname]', 'Last Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('customer[customer_email]', 'Email Address', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('customer[customer_phone]', 'Contact Telephone', 'trim|xss_clean');

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

	public function basic_supplements()
	{
		if( ! booking('customer'))
		{
			redirect(site_url('admin/salesdesk/details'));
		}
 
		$resources = booking('resources');
		$data['supplements'] = $this->model('supplement')->get_for_resource($resources[0]->resource_id, $this->account->val('id'));

		if(empty($data['supplements']))
		{
			$this->booking->update_session(array('supplements' => array()));

			redirect(site_url('admin/salesdesk/confirm'));
		} else
		{
			$this->load->library('form_validation');

			foreach($data['supplements'] as $supplement)
			{
				$this->form_validation->set_rules("supplements[{$supplement->supplement_id}][qty]", '', 'trim|xss_clean');
				$this->form_validation->set_rules("supplements[{$supplement->supplement_id}][price]", '', 'trim|xss_clean');
				$this->form_validation->set_rules("supplements[{$supplement->supplement_id}][description]", '', 'trim|xss_clean');
			}
		}

		if($this->form_validation->run() === FALSE)
		{
			$this->load->helper('typography');
			$this->template->build('admin/salesdesk/supplements', $data);
		} else
		{
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

			$this->booking->update_session(array('booking_price' => booking('booking_room_price') + $total_price, 'booking_supplement_price' => $total_price, 'supplements' => ((empty($supplements)) ? array() : $supplements)));

			redirect(site_url('admin/salesdesk/confirm'));
		}
	}

	public function supplements()
	{
		if(! $this->booking->has_customer())
		{
			redirect(site_url('admin/salesdesk/details'));
		}
		
		$data['booking'] = booking();
		$data['supplements'] = $this->model('supplement')->get_for_resources(booking('resources'), $this->account->val('id'));

		if(empty($data['supplements']))
		{
			$this->booking->update_session(array('supplements' => array()));

			redirect(site_url('admin/salesdesk/confirm'));
		} else
		{
			$this->load->library('form_validation');

			foreach($data['supplements'] as $resource)
			{
				
				foreach($resource->supplements as $supplement)
				{
					
					$this->form_validation->set_rules("supplements[{$resource->resource_id}][{$supplement->supplement_id}][qty]", '', 'trim');
					$this->form_validation->set_rules("supplements[{$resource->resource_id}][{$supplement->supplement_id}][price]", '', 'trim');
					$this->form_validation->set_rules("supplements[{$resource->resource_id}][{$supplement->supplement_id}][description]", '', 'trim');
				}
			}
		}

		if($this->form_validation->run() === FALSE)
		{
			$this->load->helper('typography');
			$this->template->build('admin/salesdesk/supplements', $data);
		} else
		{
			$supplements = $this->input->post('supplements');

			$total_price = 0;

			foreach($supplements as $rid => $resource)
			{
				foreach($resource as $sid => $supplement)
				{
					if(empty($supplement['qty']))
					{
						unset($supplements[$rid][$sid]);
					} else
					{
						$total_price += ($supplement['qty'] * $supplement['price']);
					}
				}
			}
			
			$this->booking->update_session(array('booking_price' => booking('booking_room_price') + $total_price, 'booking_supplement_price' => $total_price, 'supplements' => ((empty($supplements)) ? array() : $supplements)));

			redirect(site_url('admin/salesdesk/confirm'));
		}
	}

	public function confirm()
	{
		if( ! $this->booking->has_supplements() || ! $this->booking->has_customer())
		{
			redirect(site_url('admin/salesdesk/supplements'));
		}
		
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
			$this->booking->sent_for_payment();
			
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