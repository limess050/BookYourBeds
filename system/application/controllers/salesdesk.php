<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesdesk extends Front_Controller {

	public function index()
	{
		$this->search();
	}

	public function search()
	{
		$data = array();

		$this->load->library('form_validation');
		$this->form_validation->set_rules('start_at', 'Date', 'trim|required');
		$this->form_validation->set_rules('duration', 'Duration', 'callback_max_duration');
		$this->form_validation->set_rules('guests', 'Guests', 'callback_max_guests');

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
 
		$this->template->build('salesdesk/index', $data);	
	}

	public function max_duration($str)
	{
		$this->form_validation->set_message('max_duration', 'For stays longer than 7 days please contact the hostel directly.');
		return $str < 8;
	}

	public function max_guests($str)
	{
		$this->form_validation->set_message('max_guests', 'For more than 6 guests please contact the hostel directly.');
		return $str < 7;
	}

	public function reset()
	{
		$this->booking->reset();

		redirect(site_url('salesdesk'));
	}

	public function new_booking()
	{
		// guests
		$guests = $this->input->post('guests');
		// duration
		$_days = $this->input->post('day');
		$duration = count($_days);
		// resource_id
		$resource_id = $_days[1];
		// start date
		$_resources = $this->input->post('resource');
		$start_timestamp = $_resources[$resource_id]['day'][1]['timestamp'];
		$footprint = $_resources[$resource_id]['day'][1]['footprint'];
		// total price
		$price = $this->input->post('price_total');
		// deposit
		// $deposit = $this->input->post('price_deposit');
		// first night
		$first_night = $this->input->post('price_first_night');

		if( $this->booking->create(account('id'), $resource_id, $start_timestamp, $duration, $guests, $footprint, $price, $first_night))
		{
			redirect(site_url('salesdesk/details'));
		} else
		{
			redirect(site_url());
		}
	}

	public function details()
	{
		if( ! booking('booking_id'))
		{
			redirect(site_url('salesdesk'));
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('customer[customer_firstname]', 'First Name', 'trim|required');
		$this->form_validation->set_rules('customer[customer_lastname]', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('customer[customer_email]', 'Email Address', 'trim|required|valid_email|matches[emailconf]');
		$this->form_validation->set_rules('emailconf', 'Confirm Email Address', 'trim|required');
		$this->form_validation->set_rules('customer[customer_phone]', 'Contact Telephone', 'trim');

		if($this->form_validation->run() === FALSE)
		{
			
			$this->template->build('salesdesk/details');
		} else
		{
			$this->booking->update_session($this->input->post());
			//$this->session->set_userdata('booking', (object) array_merge((array) session('booking'), (array) $this->input->post()));

			redirect(site_url('salesdesk/supplements'));
		}
	}

	public function supplements()
	{
		if( ! booking('customer'))
		{
			redirect(site_url('salesdesk/details'));
		}

		$resources = booking('resources');
		$data['supplements'] = $this->model('supplement')->get_for_resource($resources[0]->resource_id, $this->account->val('id'));

		if(empty($data['supplements']))
		{
			$this->booking->update_session(array('supplements' => array()));

			redirect(site_url('salesdesk/confirm'));
		} else
		{
			$this->load->library('form_validation');

			foreach($data['supplements'] as $supplement)
			{
				$this->form_validation->set_rules("supplements[{$supplement->supplement_id}][qty]", '', 'trim');
				$this->form_validation->set_rules("supplements[{$supplement->supplement_id}][price]", '', 'trim');
				$this->form_validation->set_rules("supplements[{$supplement->supplement_id}][description]", '', 'trim');
			}
		}

		if($this->form_validation->run() === FALSE)
		{
			$this->load->helper('typography');
			$this->template->build('salesdesk/supplements', $data);
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

			redirect(site_url('salesdesk/confirm'));
		}
	}

	public function confirm()
	{
		if( ! booking('supplements') && ! is_array(booking('supplements')))
		{
			redirect(site_url('salesdesk/supplements'));
		}
		// Merge any data that might have come from a failed submission
		$this->booking->update_session($this->input->post());
		//$this->session->set_userdata('booking', (object) array_merge((array) session('booking'), (array) $this->input->post()));


		$this->load->library('form_validation');

		if($this->input->post('booking_price') !== '0')
		{
			$this->form_validation->set_rules('billing[firstname]', 'Billing First Name', 'trim|required');
			$this->form_validation->set_rules('billing[lastname]', 'Billing Last Name', 'trim|required');
			$this->form_validation->set_rules('billing[email]', 'Billing Email Address', 'trim|required|valid_email');
			$this->form_validation->set_rules('billing[address1]', 'Billing Address 1', 'trim|required');
			$this->form_validation->set_rules('billing[address2]', 'Billing Address 2', 'trim');
			$this->form_validation->set_rules('billing[city]', 'Billing Town/City', 'trim|required');
			$this->form_validation->set_rules('billing[postcode]', 'Billing Postal/ZIP Code', 'trim|required');
			$this->form_validation->set_rules('billing[country]', 'Billing Country', 'trim|required_no_zero');
			
			$b = $this->input->post('billing');
			if( ! empty($b['country']) && $b['country'] == 'US')
			{
				$this->form_validation->set_rules('billing[state]', 'Billing State', 'trim|required_no_zero');
			}
		}

		/*$this->form_validation->set_rules('booking_id', '', 'required');
		$this->form_validation->set_rules('booking_booking_referrer_id', 'Referral source', 'trim|required_no_zero');
		$this->form_validation->set_rules('note[note_note]', '', 'trim');
		$this->form_validation->set_rules('over18', '', 'callback_check_over18');*/

		if($this->form_validation->run() === FALSE)
		{
			$this->load->helper('typography');

			$data['booking'] = $this->booking->session();
			$data['resources'] = booking('resources');
			$data['customer'] = booking('customer');

			$this->template
					->append_metadata( js('bootstrap-modal.js'))
					->build('salesdesk/confirm', $data);
		} else
		{
			$this->booking->update_session($this->input->post());
			//$this->session->set_userdata('booking', (object) array_merge((array) session('booking'), (array) $this->input->post()));

			//redirect(site_url('salesdesk/sagepay'));
			redirect(site_url('salesdesk/payment'));
		}
	}


	public function payment()
	{
		$this->booking->sent_for_payment();
		$this->booking->dump();

		$this->load->library('payment');

		

		$data['form'] = $this->payment->form(setting('payment_gateway'), $this->booking->session());
		
		$this->template->append_metadata( js('jquery.dotimeout.js'))
					->build('salesdesk/payment', $data); 
	}



	public function process($submission_type = null)
	{
		// $submission_type can also be 'ipn' - won't redirect after completion

		$this->load->library('payment');

		$results = $this->payment->process(setting('payment_gateway'), array_merge($_POST, $_GET));

		switch($results['action'])
		{
			case 'abort':
				// Aborted - die!
				$this->booking->aborted($results['booking_id']);

				$this->session->unset_userdata('booking');

				$this->session->set_flashdata('reason', $results['message']);
				redirect(site_url('salesdesk/aborted'));
				break;

			case 'create':
				 // Has it already been processed by an IPN call or something?
				
				$this->session->set_flashdata('booking_id', $this->booking->process($results['booking_id'], $results['results']));



				redirect(site_url('salesdesk/complete'));
				break;

			case 'fail':
				// Try again?
				$this->booking->failed($results['booking_id']);

				$this->session->set_flashdata('reason', $results['message']);
				$this->session->set_flashdata('booking_id', $results['booking_id']);

				redirect(site_url('salesdesk/try_again'));
				break;

			default:

				break;
		}
	}

	public function force_process()
	{
		if( ! $this->input->get('id'))
		{
			show_404();
		}

		$id = $this->input->get('id');

		$this->session->set_flashdata('booking_id', $this->booking->process(base64_decode($id), null));

		redirect(site_url('salesdesk/complete'));
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

		$this->load->library('payment');

		$gateway_data = ( ! empty($data['booking']->booking_gateway_data)) ? unserialize($data['booking']->booking_gateway_data) : null;
		
		$data['billing_info'] = ( ! empty($gateway_data)) ? $this->payment->receipt(setting('payment_gateway'), $gateway_data) : null;

		$this->template->build('salesdesk/complete', $data);
	}

	public function aborted()
	{
		$this->template->build('salesdesk/aborted');
	}

	public function try_again()
	{
		// Get the booking
		$this->booking->get_session($this->session->flashdata('booking_id'));

		$this->template->build('salesdesk/try_again');

	}

	public function verify()
	{
		$data = array();

		if($this->input->get('auth'))
		{
			if($id = $this->booking->verify($this->input->get('auth')))
			{
				$data['booking'] = $this->model('booking')->get($id);
			}
		}

		$this->template->build('salesdesk/verify', $data);
	}


	/* Loads of SagePay-specific encryption stuff */

	private function _encrypt_and_encode($str)
	{
		return $this->_base_64_encode($this->_simple_Xor($str, SAGEPAY_CRYPT));
		/*if ($this->payments->ci->account->setting('sagepay_form_encryption_type') == "XOR") 
		{
			//** XOR encryption with Base64 encoding **
			return $this->_base_64_encode($this->_simple_Xor($str, $this->payments->ci->account->setting('sagepay_form_crypt')));
		} 
		else 
		{
			//** AES encryption, CBC blocking with PKCS5 padding then HEX encoding - DEFAULT **

			//** use initialization vector (IV) set from $strEncryptionPassword
	    	$strIV = $this->payments->ci->account->setting('sagepay_form_crypt');
	    	
	    	//** add PKCS5 padding to the text to be encypted
	    	$str = $this->_add_PKCS5_padding($str);

	    	//** perform encryption with PHP's MCRYPT module
			$str_crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->payments->ci->account->setting('sagepay_form_crypt'), $str, MCRYPT_MODE_CBC, $strIV);
			
			//** perform hex encoding and return
			return "@" . bin2hex($str_crypt);
		}*/
	}

	private function _simple_Xor($str, $key)
	{
		// Initialise key array
		$key_list = array();
		// Initialise out variable
		$output = "";

		// Convert $Key into array of ASCII values
		for($i = 0; $i < strlen($key); $i++){
			$key_list[$i] = ord(substr($key, $i, 1));
		}

		// Step through string a character at a time
		for($i = 0; $i < strlen($str); $i++) {
			// Get ASCII code from string, get ASCII code from key (loop through with MOD), XOR the two, get the character from the result
			// % is MOD (modulus), ^ is XOR
			$output .= chr(ord(substr($str, $i, 1)) ^ ($key_list[$i % strlen($key)]));
		}

		// Return the result
		return $output;
	}

	private function _base_64_encode($plain)
	{
		// Initialise output variable
		$output = "";

		// Do encoding
		$output = base64_encode($plain);

		// Return the result
		return $output;
	}

	//** PHP's mcrypt does not have built in PKCS5 Padding, so we use this
	private function _add_PKCS5_padding($input)
	{
		$blocksize = 16;
		
		$padding = "";

		// Pad input to an even block size boundary
		$padlength = $blocksize - (strlen($input) % $blocksize);
		
		for($i = 1; $i <= $padlength; $i++) {
			$padding .= chr($padlength);
		}

		return $input . $padding;
	}

	//** Wrapper function do decode then decrypt based on header of the encrypted field **
	private function _decode_and_decrypt($strIn) 
	{

		if (substr($strIn,0,1)=="@") 
		{
			//** HEX decoding then AES decryption, CBC blocking with PKCS5 padding - DEFAULT **

			//** use initialization vector (IV) set from $strEncryptionPassword
			$strIV = SAGEPAY_CRYPT;

			//** remove the first char which is @ to flag this is AES encrypted
			$strIn = substr($strIn,1); 

			//** HEX decoding
			$strIn = pack('H*', $strIn);

			//** perform decryption with PHP's MCRYPT module
			return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, SAGEPAY_CRYPT, $strIn, MCRYPT_MODE_CBC, $strIV); 
		} else {
			//** Base 64 decoding plus XOR decryption **
			return $this->_simple_Xor($this->_base_64_decode($strIn), SAGEPAY_CRYPT);
		}
	}

	/* Base 64 decoding function **
	** PHP does it natively but just for consistency and ease of maintenance, let's declare our own function **/
	private function _base_64_decode($scrambled) 
	{
		// Initialise output variable
		$output = "";

		// Fix plus to space conversion issue
		$scrambled = str_replace(" ","+",$scrambled);

		// Do encoding
		$output = base64_decode($scrambled);

		// Return the result
		return $output;
	}

	private function _get_tokens($thisString) 
	{

		// List the possible tokens
		$tokens = array(
						"Status",
						"StatusDetail",
						"VendorTxCode",
						"VPSTxId",
						"TxAuthNo",
						"Amount",
						"AVSCV2", 
						"AddressResult", 
						"PostCodeResult", 
						"CV2Result", 
						"GiftAid", 
						"3DSecureStatus", 
						"CAVV",
						"AddressStatus",
						"CardType",
						"Last4Digits",
						"PayerStatus");

		// Initialise arrays
		$output = array();
		$resultArray = array();

		// Get the next token in the sequence
		for ($i = count($tokens)-1; $i >= 0 ; $i--)
		{
			// Find the position in the string
			$start = strpos($thisString, $tokens[$i]);
			// If it's present
			if ($start !== false)
			{
				$resultArray[$i] = new StdClass;
				// Record position and token name
				$resultArray[$i]->start = $start;
				$resultArray[$i]->token = $tokens[$i];
			}
		}

		// Sort in order of position
		sort($resultArray);
		
		// Go through the result array, getting the token values
		for ($i = 0; $i<count($resultArray); $i++)
		{
			// Get the start point of the value
			$valueStart = $resultArray[$i]->start + strlen($resultArray[$i]->token) + 1;
			
			// Get the length of the value
			if ($i==(count($resultArray)-1)) 
			{
				$output[$resultArray[$i]->token] = substr($thisString, $valueStart);
			} else {
				$valueLength = $resultArray[$i+1]->start - $resultArray[$i]->start - strlen($resultArray[$i]->token) - 2;
				$output[$resultArray[$i]->token] = substr($thisString, $valueStart, $valueLength);
			}      

		}

		// Return the ouput array
		return $output;
	}

}