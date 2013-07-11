<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bookings extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('cookie');
	}

	public function index()
	{
		if($this->input->get('timestamp'))
		{
			$data['current_date'] = $this->input->get('timestamp');
		} else if($this->input->get('datetime'))
		{
			$data['current_date'] = human_to_unix($this->input->get('datetime') . ' 00:00:00');
		} else
		{
			$data['current_date'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		}

		if($this->input->get('checkingin'))
		{
			set_cookie(array(
					    'name'   => 'hideOther',
					    'value'  => '1',
					    'expire' => '86500'
					));
			
			redirect(site_url('admin/bookings?timestamp=' . $data['current_date']));
		} else if($this->input->get('all'))
		{	
			delete_cookie('hideOther');
			redirect(site_url('admin/bookings?timestamp=' . $data['current_date']));
		} 
		
		$show_all = ! get_cookie('hideOther');

		$data['resources'] = $this->model('booking')->get_date_by_resource($data['current_date'], $this->account->val('id'), $show_all);
		
		$this->template
			->build('admin/bookings/index', $data);
	}

	public function show($id = null)
	{
		if(empty($id))
		{
			show_404();
		}
		
		$data['booking'] = $this->model('booking')->get($id, $this->account->val('id')); 

		$data['previous'] = ( ! empty($data['booking']->booking_original_id)) ? $this->model('booking')->get_previous($data['booking']->booking_original_id) : array();

		$data['new'] = ( ! empty($data['booking']->booking_transferred_to_id)) ? $this->model('booking')->get($data['booking']->booking_transferred_to_id) : null;
		
		$this->template
			->set_partial('booking_button_group', 'admin/partials/booking_button_group')
			->set_partial('booking_overview', 'admin/partials/booking_overview')
			->set_partial('booking_supplements', 'admin/partials/booking_supplements')
			->build('admin/bookings/show', $data);
	}

	public function email($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
		$this->form_validation->set_rules('message', 'Message', 'trim|xss_clean');

		if($this->form_validation->run() === FALSE)
		{
			$data['booking'] = $this->model('booking')->get($id, $this->account->val('id')); 
			
			$this->template
				->set_partial('booking_button_group', 'admin/partials/booking_button_group')
				->build('admin/bookings/email', $data);
		} else
		{
			$this->booking->email($id, $this->input->post('email'), $this->input->post('subject'), $this->input->post('message'));

			$this->session->set_flashdata('msg', 'Booking details emailed');

			redirect('admin/bookings/show/' . $id);
		}
	}

	public function edit($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('customer_id', 'Customer ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('customer[customer_firstname]', 'First Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('customer[customer_lastname]', 'Last Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('customer[customer_email]', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('customer[customer_phone]', 'Telephone', 'trim|xss_clean');
				
		if($this->form_validation->run() === FALSE)
		{
			$this->load->config('countries');
			$data['booking'] = $this->model('booking')->get($id, $this->account->val('id')); 
			
			$this->template
				->set_partial('booking_button_group', 'admin/partials/booking_button_group')
				->set_partial('booking_overview', 'admin/partials/booking_overview')
				->set_partial('booking_supplements', 'admin/partials/booking_supplements')
				->build('admin/bookings/edit', $data);
		} else
		{
			$this->model('customer')->update($this->input->post('customer_id'), $this->input->post('customer'));

			$this->session->set_flashdata('msg', 'Guest details updated');

			redirect('admin/bookings/show/' . $id);
		}
		
	}

	public function supplements($id = null)
	{
		if(empty($id))
		{
			show_404();
		}

		$data['booking'] = $this->model('booking')->get($id, $this->account->val('id')); 

		$data['supplements'] = $this->model('supplement')->get_for_resources($data['booking']->resources, $this->account->val('id'));

		//$data['supplements'] = $this->model('supplement')->get_for_resource($data['booking']->resources[0]->resource_id, $this->account->val('id'));

		$this->load->library('form_validation');

		if(empty($data['supplements']))
		{
			$this->session->set_flashdata('msg', 'You do not have any supplements on these rooms');
			redirect('admin/bookings/show/' . $id);
		} 

		foreach($data['supplements'] as $supplement)
		{
			foreach($data['supplements'] as $resource)
			{
				
				foreach($resource->supplements as $supplement)
				{
					
					$this->form_validation->set_rules("supplements[{$resource->resource_id}][{$supplement->supplement_id}][qty]", '', 'trim|xss_clean');
					$this->form_validation->set_rules("supplements[{$resource->resource_id}][{$supplement->supplement_id}][price]", '', 'trim|xss_clean');
					$this->form_validation->set_rules("supplements[{$resource->resource_id}][{$supplement->supplement_id}][description]", '', 'trim|xss_clean');
				}
			}
		}

		if($this->form_validation->run() === FALSE)
		{
			//$data['resources'] = $data['booking']->resources;

			$data['_supplements'] = array();

			foreach($data['booking']->resources as $resource)
			{
				foreach($resource->supplements as $supplement)
				{
					$data['_supplements'][$resource->resource_id][$supplement->supplement_id] = $supplement;
				}
			}

			$data['previous'] = ( ! empty($data['booking']->booking_original_id)) ? $this->model('booking')->get_previous($data['booking']->booking_original_id) : array();

			$data['new'] = ( ! empty($data['booking']->booking_transferred_to_id)) ? $this->model('booking')->get($data['booking']->booking_transferred_to_id) : null;
			
			
			$this->load->helper('typography');

			$this->template
				->set_partial('booking_button_group', 'admin/partials/booking_button_group')
				->set_partial('booking_overview', 'admin/partials/booking_overview')
				->build('admin/bookings/supplements', $data);
		} else
		{
			/*echo '<pre>';
			print_r($this->input->post('supplements'));
			echo '</pre>';

			die();*/

			// Start by deleting any existing...
			$this->model('supplement')->clear_from_booking($id);

			$supplement_total = 0;

			// Add the new ones in...
			foreach($this->input->post('supplements') as $rid => $resource)
			{
				foreach($resource as $sid => $supplement)
				{
					if($supplement['qty'] > 0)
					{
						$this->model('supplement')->add_to_booking($sid, $id, $rid, $supplement['qty'], $supplement['price']);
						$supplement_total += ($supplement['price'] * $supplement['qty']);
					}
				}
			}

			// Now update the booking price
			$update = array(
						'booking_price' 			=> $data['booking']->booking_room_price + $supplement_total,
						'booking_supplement_price'	=> $supplement_total
						);

			if($data['booking']->booking_deposit > $update['booking_price'])
			{
				$update['booking_deposit'] = $update['booking_price'];
				$update['booking_refund'] = $data['booking']->booking_deposit - $update['booking_price'];

				$this->session->set_flashdata('msg', 'Supplements updated. The guest will require a refund of &pound;' . as_currency($update['booking_refund']));
			} else
			{
				$this->session->set_flashdata('msg', 'Supplements updated.');
			}

			$this->model('booking')->update($id, $update);

			redirect('admin/bookings/show/' . $id);
		}

	}

	public function refund($id = null)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->model('booking')->update($id, array('booking_refund_refunded' => 1));

		$this->session->set_flashdata('msg', 'Amount refunded');
			
		redirect(site_url('admin/bookings/show/' . $id));
	}

	public function acknowledge($id = null)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->model('booking')->acknowledge($id);

		$this->session->set_flashdata('msg', 'Booking acknowledged');
			
		redirect(site_url('admin/bookings/show/' . $id));
	}

	public function acknowledge_cancellation($id = null)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->booking->acknowledge_cancellation($id);

		$this->session->set_flashdata('msg', 'Cancellation acknowledged');
			
		redirect(site_url('admin/bookings/show/' . $id));
	}

	public function cancel($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->booking->cancel($id);

		$this->session->set_flashdata('msg', 'Booking cancelled');

		redirect('admin/bookings');
	}

	public function uncancel($id)
	{
		if(empty($id))
		{
			show_404();
		}

		if ($this->booking->uncancel($id))
		{
			$this->session->set_flashdata('msg', 'Booking uncancelled');

			redirect('admin/bookings/show/' . $id);
		} else
		{
			$this->session->set_flashdata('msg', 'Unable to uncancel booking');

			redirect('admin/bookings');
		}
	}

	public function remove($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->model('booking')->remove($id);

		$this->session->set_flashdata('msg', 'Booking removed');

		redirect('admin/dashboard');
	}


	public function verify($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->booking->verify(null, $id);

		$this->session->set_flashdata('msg', 'Booking manually verified');

		redirect('admin/bookings/show/' . $id);
	}

	public function checkin($id = null)
	{
		if($this->input->post('booking'))
		{
			foreach($this->input->post('booking') as $key => $val)
			{
				$this->model('booking')->checkin($key, account('id'));
				
				$this->session->set_flashdata('msg', 'Booking checked-in');
			}
		}

		redirect(site_url(($this->input->post('redirect')) ? $this->input->post('redirect') : 'admin'));
	}

	public function search()
	{
		$this->load->library('form_validation');
		$this->load->helper('text');

		$this->form_validation->set_rules('search_terms', 'Search Terms', 'trim|required|xss_clean');

		$data['search'] = $this->input->post('search_terms');

		if($this->form_validation->run() == TRUE)
		{
			$data['results'] = $this->model('booking')->search($this->input->post('search_terms'), account('id'));
		}

		$this->template
			->build('admin/bookings/search', $data);
	}

}