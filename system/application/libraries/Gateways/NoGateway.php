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
		$output .= form_open('payments/process', 
								null, 
								array(
									'booking_id'			=> $params['booking_id'],
									'booking_reference' 	=> $params['booking_reference']
									));
  		
  		$output .= $this->recaptcha_get_html( ci()->config->item('recaptcha_public_key') );

		$output .= form_button(array(
								    'name' 		=> 'button',
								    'class' 	=> 'btn primary',
								    'content' 	=> 'Complete Booking',
								    'type'		=> 'submit'
									));
		$output .= '&nbsp;';
		$output .= '<a href="' . site_url('salesdesk/reset') . '" class="btn" onclick="return confirm(\'Are you sure you want to cancel this booking?\');">Cancel Booking</a>';

		$output .= '</form>';

		return $output;
	}

	public function nogateway_process($data)
	{
		$response = array(
						'valid'			=> FALSE,
						'action'		=> 'fail',
						'booking_id'	=> $data['booking_id'],
						'booking_ref'	=> $data['booking_reference'],
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
						'booking_id'	=> $data['booking_id'],
						'booking_ref'	=> $data['booking_reference']
						);

		} else {
			// Process the booking...
			$response = array(
						'valid'			=> TRUE,
						'action'		=> 'create',
						'message'		=> 'Booking Successful',
						'booking_id'	=> $data['booking_id'],
						'booking_ref'	=> $data['booking_reference']
						);
		}

		return $response;
	}

	/* RECAPTCHA CODE */

	/**
	 * Encodes the given data into a query string format
	 * @param $data - array of string elements to be encoded
	 * @return string - encoded request
	 */
	private function recaptcha_qsencode ($data) 
	{
        $req = "";
        foreach ( $data as $key => $value )
                $req .= $key . '=' . urlencode( stripslashes($value) ) . '&';

        // Cut the last '&'
        $req=substr($req,0,strlen($req)-1);
        return $req;
	}



	/**
	 * Submits an HTTP POST to a reCAPTCHA server
	 * @param string $host
	 * @param string $path
	 * @param array $data
	 * @param int port
	 * @return array response
	 */
	private function recaptcha_http_post($host, $path, $data, $port = 80)
	{
        $req = $this->recaptcha_qsencode($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = '';
        if( false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) 
        {
            die ('Could not open socket');
        }

        fwrite($fs, $http_request);

        while ( !feof($fs) )
                $response .= fgets($fs, 1160); // One TCP-IP packet
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response;
	}



	/**
	 * Gets the challenge HTML (javascript and non-javascript version).
	 * This is called from the browser, and the resulting reCAPTCHA HTML widget
	 * is embedded within the HTML form it was called from.
	 * @param string $pubkey A public key for reCAPTCHA
	 * @param string $error The error given by reCAPTCHA (optional, default is null)
	 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)
	 * @return string - The HTML to be embedded in the user's form.
	 */
	private function recaptcha_get_html($pubkey, $error = null, $use_ssl = false)
	{
		if ($pubkey == null || $pubkey == '') 
		{
			die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
		}
		
		if ($use_ssl) 
		{
            $server = RECAPTCHA_API_SECURE_SERVER;
        } else {
            $server = RECAPTCHA_API_SERVER;
        }

        $errorpart = "";
        if ($error)
        {
           $errorpart = "&amp;error=" . $error;
        }
        
        return '<script type="text/javascript" src="'. $server . '/challenge?k=' . $pubkey . $errorpart . '"></script>

		<noscript>
	  		<iframe src="'. $server . '/noscript?k=' . $pubkey . $errorpart . '" height="300" width="500" frameborder="0"></iframe><br/>
	  		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
	  		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
		</noscript>';
	}




	/**
	  * Calls an HTTP POST function to verify if the user's guess was correct
	  * @param string $privkey
	  * @param string $remoteip
	  * @param string $challenge
	  * @param string $response
	  * @param array $extra_params an array of extra variables to post to the server
	  * @return ReCaptchaResponse
	  */
	private function recaptcha_check_answer ($privkey, $remoteip, $challenge, $response, $extra_params = array())
	{
		if ($privkey == null || $privkey == '') 
		{
			die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
		}

		if ($remoteip == null || $remoteip == '') 
		{
			die ("For security reasons, you must pass the remote ip to reCAPTCHA");
		}
		
        //discard spam submissions
        if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) 
        {
            $recaptcha_response = new ReCaptchaResponse();
            $recaptcha_response->is_valid = false;
            $recaptcha_response->error = 'incorrect-captcha-sol';
            return $recaptcha_response;
        }

        $response = $this->recaptcha_http_post(RECAPTCHA_VERIFY_SERVER, "/recaptcha/api/verify",
                                          array (
                                                 'privatekey' => $privkey,
                                                 'remoteip' => $remoteip,
                                                 'challenge' => $challenge,
                                                 'response' => $response
                                                 ) + $extra_params
                                          );

        $answers = explode ("\n", $response [1]);
        $recaptcha_response = new ReCaptchaResponse();

        if (trim ($answers [0]) == 'true') 
        {
            $recaptcha_response->is_valid = true;
        } else {
            $recaptcha_response->is_valid = false;
            $recaptcha_response->error = $answers [1];
        }

        return $recaptcha_response;
	}

	private function recaptcha_aes_pad($val) 
	{
		$block_size = 16;
		$numpad = $block_size - (strlen ($val) % $block_size);
		return str_pad($val, strlen ($val) + $numpad, chr($numpad));
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