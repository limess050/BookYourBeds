<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplements extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['supplements'] = $this->model('supplement')->get_all($this->account->val('id'));
		
		$this->template
			->build('admin/supplements/index', $data);
	}

	public function create()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('supplement[supplement_short_description]', 'Short Description/Title', 'trim|required');
		$this->form_validation->set_rules('supplement[supplement_long_description]', 'Long Description', 'trim');
		$this->form_validation->set_rules('supplement[supplement_default_price]', 'Default Price', 'trim|numeric_or_empty');
		$this->form_validation->set_rules('supplement[supplement_per_guest]', 'Per Guest', 'trim');
		$this->form_validation->set_rules('supplement[supplement_per_day]', 'Per Day', 'trim');

		if($this->form_validation->run() == FALSE)
		{	
			$this->template
				->build('admin/supplements/create');
		} else
		{
			$id = $this->model('supplement')->insert($this->input->post('supplement'));

			$this->session->set_flashdata('msg', 'Supplement created');

			redirect(site_url('admin/supplements/edit/' . $id));
		}

	}

	public function edit($id = null)
	{
		if(empty($id))
		{
			show_404();
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('supplement[supplement_short_description]', 'Short Description/Title', 'trim|required');
		$this->form_validation->set_rules('supplement[supplement_long_description]', 'Long Description', 'trim');
		$this->form_validation->set_rules('supplement[supplement_default_price]', 'Default Price', 'trim|numeric_or_empty');
		$this->form_validation->set_rules('supplement[supplement_per_guest]', 'Per Guest', 'trim');
		$this->form_validation->set_rules('supplement[supplement_per_day]', 'Per Day', 'trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['supplement'] = $this->model('supplement')->get($id);
			
			$this->template
				->build('admin/supplements/edit', $data);
		} else
		{
			$this->model('supplement')->update($id, $this->input->post('supplement'));

			$this->session->set_flashdata('msg', 'Supplement updated');

			redirect(site_url('admin/supplements/edit/' . $id));
		}

	}


}