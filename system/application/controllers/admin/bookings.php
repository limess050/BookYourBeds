<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
				
		$data['resources'] = $this->model('booking')->get_date_by_resource($data['current_date'], $this->account->val('id'));
		
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
		
		$this->template
			->build('admin/bookings/show', $data);
	}

	public function edit($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('customer_id', 'Customer ID', 'trim|required');
		$this->form_validation->set_rules('customer[customer_firstname]', 'First Name', 'trim|required');
		$this->form_validation->set_rules('customer[customer_lastname]', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('customer[customer_email]', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('customer[customer_phone]', 'Telephone', 'trim');
				
		if($this->form_validation->run() === FALSE)
		{
			$this->load->config('countries');
			$data['booking'] = $this->model('booking')->get($id, $this->account->val('id')); 
			
			$this->template
				->build('admin/bookings/edit', $data);
		} else
		{
			$this->model('customer')->update($this->input->post('customer_id'), $this->input->post('customer'));

			$this->session->set_flashdata('msg', 'Guest details updated');

			redirect('admin/bookings/show/' . $id);
		}
		
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

	public function cancel($id)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->model('booking')->delete($id);

		$this->session->set_flashdata('msg', 'Booking cancelled');

		redirect('admin/bookings');
	}

	public function uncancel($id)
	{
		if(empty($id))
		{
			show_404();
		}

		if ($this->model('booking')->undelete($id))
		{
			$this->session->set_flashdata('msg', 'Booking uncancelled');

			redirect('admin/bookings/show/' . $id);
		} else
		{
			$this->session->set_flashdata('msg', 'Unable to uncancel booking');

			redirect('admin/bookings');
		}
	}

	public function search()
	{
		$this->load->library('form_validation');
		$this->load->helper('text');

		$this->form_validation->set_rules('search_terms', 'Search Terms', 'trim|required');

		$data['search'] = $this->input->post('search_terms');

		if($this->form_validation->run() == TRUE)
		{
			$data['results'] = $this->model('booking')->search($this->input->post('search_terms'), account('id'));
		}

		$this->template
			->build('admin/bookings/search', $data);
	}

}