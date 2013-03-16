<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SagePay_Form
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

	public function sagepay_form_form($params)
	{
		ci()->load->helper('form');

		$crypt[] = "VendorTxCode={$params->booking_id}-" . time();
		
		// Basket
		$row = array();

		foreach($params->resources as $resource)
		{
			$row[] = str_replace(':', '-', $resource->resource_title) 
				. " ({$resource->reservation_footprint} {$resource->resource_priced_per}" . (($resource->reservation_footprint > 1) ? 's' : '') . '):'
			 	. $resource->reservation_footprint . ':'
			 	. $params->booking_price 
			 	. ':0.00:' 
			 	. $params->booking_price . ':' 
			 	. $params->booking_price;
		}

		$crypt[] = "Basket=" . count($row) . ":" . implode(':', $row);

		// The rest
		$crypt[] = "Amount={$params->booking_deposit}";
		$crypt[] = "Currency=GBP";
		$crypt[] = "Description=Booking with " . ci()->account->val('name');
		$crypt[] = "SuccessURL=" . site_url('salesdesk/process');
		$crypt[] = "FailureURL=" . site_url('salesdesk/process');

		if(ENVIRONMENT == 'production') 
		{
			$crypt[] = "VendorEMail=" . ci()->account->val('email') . ':' . booking('booking_id') . ci()->config->item('sagepay_verification_email');
		} else
		{
			$crypt[] = "VendorEMail=" . ci()->account->val('email');
		}

		// Customer
		$crypt[] = "CustomerName={$params->billing['firstname']} {$params->billing['lastname']}";
		$crypt[] = "CustomerEMail={$params->billing['email']}";

		// Billing
		$crypt[] = "BillingFirstnames={$params->billing['firstname']}";
		$crypt[] = "BillingSurname={$params->billing['lastname']}";
		//$crypt[] = "BillingPhone=";
		$crypt[] = "BillingAddress1={$params->billing['address1']}";
		$crypt[] = "BillingAddress2={$params->billing['address2']}";
		$crypt[] = "BillingCity={$params->billing['city']}"; 
		$crypt[] = "BillingPostCode={$params->billing['postcode']}";
		$crypt[] = "BillingCountry={$params->billing['country']}";

		// Delivery
		$crypt[] = "DeliveryFirstnames={$params->billing['firstname']}";
		$crypt[] = "DeliverySurname={$params->billing['lastname']}";
		//$crypt[] = "DeliveryPhone=";
		$crypt[] = "DeliveryAddress1={$params->billing['address1']}";
		$crypt[] = "DeliveryAddress2={$params->billing['address2']}";
		$crypt[] = "DeliveryCity={$params->billing['city']}";
		$crypt[] = "DeliveryPostCode={$params->billing['postcode']}";
		$crypt[] = "DeliveryCountry={$params->billing['country']}";

		if($params->billing['country'] == 'US')
		{
			$crypt[] = "BillingState={$params->billing['state']}";
			$crypt[] = "DeliveryState={$params->billing['state']}";
		}





		//$output = '<pre>' . print_r($crypt, TRUE) . '</pre>';

		$output = ci()->config->item('form_preamble');

		$output .= '<div class="spinner"></div>';

		$output .= '<form action="' . ci()->config->item('api_endpoint') . '" method="POST" id="processForm">';
		$output .= form_hidden('VPSProtocol', ci()->config->item('sagepay_form_gateway_protocol'));
		$output .= form_hidden('TxType', 'PAYMENT');
		$output .= form_hidden('Vendor', ci()->account->setting('sagepay_form_vendor_id'));
		$output .= form_hidden('Crypt', $this->_encrypt_and_encode(implode('&', $crypt)));
		$output .= form_button(array(
								    'name' 		=> 'button',
								    'class' 	=> 'btn primary',
								    'content' 	=> 'Make Payment &amp; Complete Booking',
								    'type'		=> 'submit'
									));
		$output .= '&nbsp;';
		$output .= '<a href="' . site_url('salesdesk/reset') . '" class="btn" onclick="return confirm(\'Are you sure you want to cancel this booking?\');">Cancel Booking</a>';
		
		$output .= '</form>';

		$output .= '<script type="text/javascript">
					<!--
					    $(function(){
					        $(".spinner").spin();
					        $.doTimeout( 5000, function(){
					                                    $("#processForm").submit();
					                                    return true;
					                                    });
					    });
					-->
					</script>';


		return $output;
	}

	public function sagepay_form_process($data)
	{
		$response = array(
						'valid'		=> FALSE,
						'action'	=> 'fail',
						'message'	=> 'No Sage Pay gateway data'
						);

		if( ! empty($data['crypt']))
		{
			$crypt = $this->_get_tokens( $this->_decode_and_decrypt($data['crypt']) );

			$tx = explode('_', $crypt['VendorTxCode']);
			if( ! empty($crypt['Status']))
			{
				switch($crypt['Status'])
				{
					case 'ABORT':
						// Reset the booking...
						$response = array(
							'valid'			=> TRUE,
							'action'		=> 'abort',
							'message'		=> 'You have aborted the booking',
							'booking_id'	=> $tx[0],
							'results'		=> $crypt
							);
						break;

					case 'OK':
						// Process the booking...
						$response = array(
							'valid'			=> TRUE,
							'action'		=> 'create',
							'message'		=> 'Booking Successful',
							'booking_id'	=> $tx[0],
							'results'		=> $crypt
							);
						break;

					case 'NOTAUTHED':
					case 'REJECTED':
						// Try again?
						$response = array(
							'valid'			=> TRUE,
							'action'		=> 'fail',
							'message'		=> 'Payment not authorised',
							'booking_id'	=> $tx[0],
							'results'		=> $crypt
							);
						break;

					case 'MALFORMED':
					case 'INVALID':
					case 'ERROR':
						// Try again?
						$response = array(
							'valid'			=> TRUE,
							'action'		=> 'fail',
							'message'		=> 'There was a problem with the Sage Pay gateway',
							'booking_id'	=> $tx[0],
							'results'		=> $crypt
							);
						break;
				}


			}

			/* Possible responses:
			OK
			NOTAUTHED

			MALFORMED - problem at this end - log error
			INVALID - needs to ensure all encoding is done correctly
			ABORT - aborted by customer
			REJECTED - banks authorised the payment but the AVS, CV2 or 3D-Secure rulebases you have set up caused the System to automatically cancel that authorisation
			ERROR = problem at Sage Pay

			Array ( [Status] => OK [StatusDetail] => Successfully Authorised Transaction [VendorTxCode] => e76852916b4122c722a986c87272048f [VPSTxId] => {2E51B76E-519D-476E-8C1E-426EACDE7681} [TxAuthNo] => 7349 [Amount] => 22 [AVSCV2] => ALL MATCH [AddressResult] => MATCHED [PostCodeResult] => MATCHED [CV2Result] => MATCHED [GiftAid] => 0 [3DSecureStatus] => OK [CAVV] => MNG5KJSTSCBJW6LPPE77AG [CardType] => VISA [Last4Digits] => 7479 )
			*/
		}

		return $response;
	}

	public function sagepay_form_receipt($data)
	{
		return array(
					'Card Number'			=> '**** **** **** ' . $data['CardType'],
					'Card Type'				=> $data['CardType'],
					'Amount'				=> '&pound;' . as_currency($data['Amount']),
					'Authorisation Code'	=> $data['TxAuthNo']
					);

	}
	
	/* Loads of SagePay-specific encryption stuff */

	private function _encrypt_and_encode($str)
	{
		return $this->_base_64_encode($this->_simple_Xor($str, ci()->account->setting('sagepay_form_crypt')));

		if (ci()->account->setting('sagepay_form_encryption_type') == "XOR") 
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
		}
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
			$strIV = ci()->account->setting('sagepay_form_crypt');

			//** remove the first char which is @ to flag this is AES encrypted
			$strIn = substr($strIn,1); 

			//** HEX decoding
			$strIn = pack('H*', $strIn);

			//** perform decryption with PHP's MCRYPT module
			return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, ci()->account->setting('sagepay_form_crypt'), $strIn, MCRYPT_MODE_CBC, $strIV); 
		} else {
			//** Base 64 decoding plus XOR decryption **
			return $this->_simple_Xor($this->_base_64_decode($strIn), ci()->account->setting('sagepay_form_crypt'));
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