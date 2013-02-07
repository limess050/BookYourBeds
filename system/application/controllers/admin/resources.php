<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->template->set_partial('resource_menu', 'admin/partials/resource_menu');
	}

	public function index()
	{
		$data['resources'] = $this->model('resource')->get_all($this->account->val('id'));
		
		$this->template
			->build('admin/resources/index', $data);
	}

	public function create()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('resource[resource_title]', 'Resource Title', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->template
				->build('admin/resources/create');
		} else
		{
			$id = $this->model('resource')->insert($this->input->post('resource'));
			
			$this->session->set_flashdata('msg', 'New resource successfully created');

			redirect(site_url("admin/resources/edit/{$id}"));
		}
	}

	public function edit($id = null)
	{
		if(empty($id))
		{
			show_404();
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('resource[resource_title]', 'Resource Title', 'trim|required');
		$this->form_validation->set_rules('resource[resource_reference]', 'Resource Reference', 'trim');
		$this->form_validation->set_rules('resource[resource_default_release]', 'Default Release', 'trim|is_natural_no_zero');
		$this->form_validation->set_rules('resource[resource_booking_footprint]', 'Booking Footprint', 'trim|is_natural_no_zero');
		$this->form_validation->set_rules('resource[resource_priced_per]', 'Resource Priced Per', 'trim');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['resource'] = $this->model('resource')->get($id, $this->account->val('id'));

			if(empty($data['resource']))
			{
				show_404();
			}

			$this->template
				->build('admin/resources/edit', $data);
		} else
		{
			$this->model('resource')->update($this->input->post('resource_id'), $this->input->post('resource'));

			$this->session->set_flashdata('msg', 'Resource settings successfully updated');
			
			redirect(site_url('admin/resources/edit/' . $this->input->post('resource_id')));
		}
	}

	public function price($id = null)
	{
		if(empty($id))
		{
			show_404();
		}
		
		$this->load->library('form_validation');
		
		if($this->input->post('season'))
		{
			$seasons = $this->input->post('season');
			
			foreach($seasons as $key => $season)
			{
				for($i = 1; $i <= 7; $i++)
				{
					$this->form_validation->set_rules("season[{$key}][{$i}][price]", 'Price', 'trim|' . (($key == 0) ? 'required|' : '') . 'numeric_or_empty');
				}
			}
		}
		
		if($this->form_validation->run() == FALSE)
		{
		
			$data['resource'] = $this->model('resource')->get($id, $this->account->val('id'));
			$data['default'] = $this->model('price')->get_resource($id);
			$data['seasons'] = $this->model('price')->get_resource_seasons($id, $this->account->val('id'));
		
		
			$this->template
				->build('admin/resources/price', $data);
		} else
		{
			$this->model('price')->create_or_update($this->input->post('resource_id'), $this->input->post('season'));

			$this->session->set_flashdata('msg', 'Resource pricing successfully updated');

			redirect(site_url("admin/resources/price/{$this->input->post('resource_id')}"));
		}
	}

	public function disable($id = null)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->model('resource')->disable($id);

		$this->session->set_flashdata('msg', 'Resource disabled');
			
		redirect(site_url('admin/resources/edit/' . $id));
	}

	public function enable($id = null)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->model('resource')->enable($id);

		$this->session->set_flashdata('msg', 'Resource enabled');
			
		redirect(site_url('admin/resources/edit/' . $id));
	}

}