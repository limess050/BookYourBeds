<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Availability extends Admin_Controller {

	public function index()
	{
		$this->load->library('form_validation');
		
		if($this->input->post('resource'))
		{
			$resources = $this->input->post('resource');
			
			foreach($resources as $key => $resource)
			{
				for($i = 1; $i <= count($resource['day']); $i++)
				{
					$this->form_validation->set_rules("resource[{$key}][day][{$i}][availability]",
						"{$resource['resource_title']} - " . date('j M Y', $resource['day'][$i]['timestamp']) . " - ",
						'trim|required|is_natural'
						);

					$this->form_validation->set_rules("resource[{$key}][day][{$i}][price]",
						"{$resource['resource_title']} - " . date('j M Y', $resource['day'][$i]['timestamp']) . " - ",
						'trim|required|is_numeric'
						);
				}
			}
		}

		if($this->form_validation->run() == FALSE)
		{
			if($this->input->get('timestamp'))
			{
				$start = $data['start_timestamp'] = $this->input->get('timestamp');
			} else if($this->input->get('datetime'))
			{
				$start = $data['start_timestamp'] = human_to_unix($this->input->get('datetime') . ' 00:00:00');
			} else
			{
				$start = $data['start_timestamp'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
			}
			
			$end = $data['end_timestamp'] = strtotime('+ ' . (AVAILABILITY_DAYS - 1) . ' days', $start);
			
			$data['today'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
			
			$data['request_string'] = 'start_timestamp=' . $start;

			$this->template
				->build('admin/availability/index', $data);	
		} else
		{
			foreach($resources as $key => $resource)
			{
				$this->model('release')->update_many_by_resource($key, $resource);
			}
			
			$this->session->set_flashdata('msg', 'Availability successfully updated');

			redirect(site_url(safe_get_env()));
		}
	}

	public function availability_data()
	{
		//$request = $this->uri->uri_to_assoc(5);



		$start = $data['start_timestamp'] = $this->input->get('start_timestamp');

		$end = strtotime('+' . (AVAILABILITY_DAYS - 1) . ' days', $start);
		
		$data['today'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		
		if($this->input->get('resource_id'))
		{
			$data['resource'] = $this->model('resource')->resource_availability($this->input->get('resource_id'), $start, $end);

		} else
		{ 
			$data['resources'] = $this->model('resource')->all_availability($start, $end);
		}

		$this->load->view('admin/partials/availability_tbody', $data);
	}

	public function resource($id = null)
	{
		if(empty($id))
		{
			show_404();
		}
		
		$this->template->set_partial('resource_menu', 'admin/partials/resource_menu');

		$this->load->library('form_validation');
		
		if($this->input->post('resource'))
		{
			$resources = $this->input->post('resource');
			
			foreach($resources as $key => $resource)
			{
				for($i = 1; $i <= count($resource['day']); $i++)
				{
					$this->form_validation->set_rules("resource[{$key}][day][{$i}][availability]",
						date('j M Y', $resource['day'][$i]['timestamp']),
						'trim|required|is_natural|xss_clean'
						);

					$this->form_validation->set_rules("resource[{$key}][day][{$i}][price]",
						date('j M Y', $resource['day'][$i]['timestamp']),
						'trim|required|is_numeric|xss_clean'
						);
				}
			}
		}

		if($this->form_validation->run() == FALSE)
		{
			if($this->input->get('timestamp'))
			{
				$start = $data['start_timestamp'] = $this->input->get('timestamp');
			} else if($this->input->get('datetime'))
			{
				$start = $data['start_timestamp'] = human_to_unix($this->input->get('datetime') . ' 00:00:00');
			} else
			{
				$start = $data['start_timestamp'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
			}
			
			$end = strtotime('+' . (AVAILABILITY_DAYS - 1) . ' days', $start);
			
			$data['today'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
			
			$data['resource'] = $this->model('resource')->get($id);
			
			$data['request_string'] = 'start_timestamp=' . $start . '&resource_id=' . $id;
					
			$this->template
				->set_partial('inactive_room_alert', 'admin/partials/inactive_room_alert')
				->build('admin/availability/resource', $data);
		} else
		{
			foreach($resources as $key => $resource)
			{
				$this->model('release')->update_many_by_resource($key, $resource);
			}

			$this->session->set_flashdata('msg', 'Resource availability successfully updated');
			
			redirect(site_url(safe_get_env()));
		}
	}
	

}