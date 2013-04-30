<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class NoGateway
{
	/**
	 *	The payments object
	*/
	public $payments;
	
	/**
	 *	The gateway settings
	*/
	public $settings;

	public function __construct($payments)
	{
		$this->payments = $payments;
		$this->settings = ci()->config->item('gateway_settings');
	}

	public function nogateway_form($params)
	{
		ci()->load->helper('url');

		redirect(site_url('salesdesk/force_process?id=' . base64_encode($params->booking_id)));


		// Old method...

		ci()->load->helper('form');
		 
		$output = 'Processing...';

		$output .= form_open('salesdesk/process', 
								'id="processForm"', 
								array(
									'booking_id' => $params->booking_id
									));
  		
		$output .= '</form>';

		$output .= '<script type="text/javascript">
					<!--
					    $(function(){
					        $.doTimeout( 100, function(){
					                                    $("#processForm").submit();
					                                    return true;
					                                    });
					    });
					-->
					</script>';

		return $output;
	}

	public function nogateway_process($data)
	{
		$response = array(
						'valid'			=> TRUE,
						'action'		=> 'create',
						'message'		=> 'Booking Successful',
						'booking_id'	=> $data['booking_id'],
						'results'		=> null,
						'extra_action'	=> ''
						);

		return $response;
	}



	protected function model($name)
	{
		$name = $name . MODEL_SUFFIX;
		
		// is there a module involved
		$model_name = explode('/', $name);
		
		if ( ! isset(ci()->{end($model_name)}) )
		{
			ci()->load->model($name, '', TRUE);
		}

		return ci()->{end($model_name)};
	}
}

if( ! function_exists('ci'))
{
	function ci()
	{
		return get_instance();
	}	
}
