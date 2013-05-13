<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seasons extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->template->set_partial('settings_menu', 'admin/partials/settings_menu');
	}

	public function index()
	{
		$this->load->library('form_validation');

		if($this->input->post('season'))
		{
			$seasons = $this->input->post('season');
			foreach($seasons as $key => $season)
			{
				$this->form_validation->set_rules("season[{$key}][season_sort_order]", '', 'trim');
			}
		}

		if($this->form_validation->run() == FALSE)
		{		
			$data['seasons'] = $this->model('season')->get_all();
			
			$this->template
				->append_metadata( js('jquery.tablednd.js') )
				->build('admin/seasons/index', $data);	
		} else
		{
			foreach($seasons as $season)
			{
				$this->model('season')->update($season['season_id'], $season);
			}

			$this->session->set_flashdata('msg', 'Season sort order successfully updated');

			redirect(site_url('admin/seasons'));
		}
	}

	public function create()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('season[season_title]', 'Season Title', 'trim|required');
		$this->form_validation->set_rules('season[season_start_at]', 'Season Start', 'trim|required');
		$this->form_validation->set_rules('season[season_end_at]', 'Season End', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->template
				->build('admin/seasons/create');
		} else
		{
			$id = $this->model('season')->insert($this->input->post('season'));

			$this->session->set_flashdata('msg', 'New season succesfully created');

			redirect("admin/seasons");
		}
		
	}
	
	public function edit($id)
	{
		if(empty($id))
		{
			show_404();
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('season[season_title]', 'Season Title', 'trim|required');
		$this->form_validation->set_rules('season[season_start_at]', 'Season Start', 'trim|required');
		$this->form_validation->set_rules('season[season_end_at]', 'Season End', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['season'] = $this->model('season')->get($id, account('id'));
			
			if(empty($data['season']))
			{
				show_404();
			}
			
			$this->template
				->build('admin/seasons/edit', $data);
		} else
		{
			$this->model('season')->update($this->input->post('season_id'), $this->input->post('season'));

			$this->session->set_flashdata('msg', 'Season successfully updated');

			redirect("admin/seasons/edit/{$this->input->post('season_id')}");
		}
		
	}
 
}