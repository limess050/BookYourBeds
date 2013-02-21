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
		ci()->load->helper('form');

		$output = (ci()->session->flashdata('err')) ? ci()->session->flashdata('err') : '';
		
		// 'a/' . $this->payments->ci->account->access_domain() . '/process'
		$output .= form_open('salesdesk/process', 
								'id="processForm"', 
								array(
									'booking_id' => $params->booking_id
									));
  		
		/*$output .= form_button(array(
								    'name' 		=> 'button',
								    'class' 	=> 'btn primary',
								    'content' 	=> 'Complete Booking',
								    'type'		=> 'submit'
									));
		$output .= '&nbsp;';
		$output .= '<a href="' . site_url('salesdesk/reset') . '" class="btn" onclick="return confirm(\'Are you sure you want to cancel this booking?\');">Cancel Booking</a>';*/

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

		/*$response = array(
						'valid'			=> FALSE,
						'action'		=> 'fail',
						'booking_id'	=> $data['booking_id'],
						'message'		=> 'No reCAPTCHA data'
						);

		$captcha = $this->recaptcha_check_answer( ci()->config->item('recaptcha_private_key') ,
			                            $_SERVER["REMOTE_ADDR"],
		                                $data["recaptcha_challenge_field"],
		                                $data["recaptcha_response_field"]);

		if ( ! $captcha->is_valid) 
		{
		    ci()->session->set_flashdata('err', 'Words don\'t match');
		    
		    $response = array(
		    			'valid'			=> TRUE,
						'action'		=> 'retry',
						'message'		=> 'Words don\'t match',
						'booking_id'	=> $data['booking_id']
						);

		} else {
			// Process the booking...
			$response = array(
						'valid'			=> TRUE,
						'action'		=> 'create',
						'message'		=> 'Booking Successful',
						'booking_id'	=> $data['booking_id']
						);
		}
		*/
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

/**
 * A ReCaptchaResponse is returned from $this->recaptcha_check_answer()
 */
class ReCaptchaResponse {
        var $is_valid;
        var $error;
}