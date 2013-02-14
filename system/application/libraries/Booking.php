<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Booking
{
	public function __construct()
	{
		
	}

	public function create($account_id, 
							$resource_id, 
							$start_timestamp, 
							$duration, 
							$guests, 
							$footprint = null, 
							$price = null, 
							$deposit = null, 
							$user_id = 0)
	{
		// Yes? Let's go!
		$booking = array(
							'booking_account_id'	=> $account_id,
							'booking_guests'		=> $guests,
							'booking_price'			=> $price,
							'booking_deposit'		=> $deposit,
							'booking_session_id'	=> ci()->session->userdata('session_id'),
							'booking_user_id'		=> $user_id,
							'booking_ip_address' 	=> ci()->input->ip_address(),
							'booking_user_agent' 	=> ci()->input->user_agent(),
							'resource_id'			=> $resource_id,
							'footprint'				=> $footprint,
							'duration'				=> $duration,
							'start_at'				=> $start_timestamp
							);

		if( ! session('booking', 'booking_id'))
		{
			$booking_id = $this->model('booking')->insert($booking);
			
			// Create an empty booking for this session...
			ci()->session->set_userdata('booking', $this->model('booking')->get($booking_id));
		} else
		{
			$this->model('booking')->update(session('booking', 'booking_id'), $booking);

			// Update the booking we're using for this session...
			ci()->session->set_userdata('booking', $this->model('booking')->get(session('booking', 'booking_id')));
		}

		return TRUE;
	}

	public function reset()
	{
		$booking = ci()->session->userdata('booking');

		$this->dump();

		ci()->db->where('reservation_booking_id', $booking->booking_id)->delete('reservations');
		ci()->session->unset_userdata('booking');

	}

	public function dump()
	{
		$booking = ci()->session->userdata('booking');

		if($booking)
		{
			$this->model('booking')->update($booking->booking_id, array(
																		'booking_session_dump' => serialize($booking)
																		));
		}
	}

	public function get_session($booking_id)
	{
		$booking = $this->model('booking')->get($booking_id);

		if($booking)
		{
			ci()->session->set_userdata('booking', unserialize($booking->booking_session_dump));
		}
	} 

	public function process($booking_id = null, $gateway_results = null)
	{
		ci()->load->helper('date');
		ci()->load->helper('text');

		if( ! empty($booking_id))
		{
			$_booking = $this->model('booking')->get($booking_id);
			if($_booking->booking_completed)
			{
				ci()->session->unset_userdata('booking');
				return $_booking->booking_id;
			}
			$booking = unserialize(( ! empty($_booking->booking_session_dump)) ? $_booking->booking_session_dump : null);
			unset($_booking);
		} else
		{
			$booking = ci()->session->userdata('booking');
		}
		
		if(empty($booking))
		{
			return FALSE;
		}

		$booking->booking_session_id = NULL;
		
		$booking->booking_reference = $booking->booking_id;
		
		// Billing Address data (for SagePay) ---------------------------------------------------------------------------------
		$booking->booking_billing_data = (isset($booking->billing)) ? serialize($booking->billing) : NULL;
		unset($booking->billing);

		$booking->booking_gateway_data = ( ! empty($gateway_results)) ? serialize($gateway_results) : null;

		// Customer ---------------------------------------------------------------------------------
		$booking->booking_customer_id = $this->model('customer')->insert($booking->customer);
		$lastname = $booking->customer['customer_lastname'];
		unset($booking->customer);

		// Supplements ---------------------------------------------------------------------------------
		// Later	
		
		// Booking notes ---------------------------------------------------------------------------------
		// Later

		// Reservation data...
		$resources = $booking->resources;
		$booking->resource_id = $resources[0]->reservation_resource_id;
		$booking->footprint = $resources[0]->reservation_footprint;
		$booking->duration = $resources[0]->reservation_duration;
		$booking->start_at = $resources[0]->reservation_start_at;

		/*$booking->booking_reference = strtoupper("{$resources[0]->resource_reference}-");
		$booking->booking_reference .= mysql_to_format($resources[0]->reservation_start_at, 'dm-');
		$booking->booking_reference .= url_title(convert_accented_characters($lastname), '');
		$booking->booking_reference .= "-{$booking->booking_guests}-{$booking->booking_id}";
		$booking->booking_reference .= ($booking->booking_user_id > 0) ? '-M' : '';*/

		$booking->booking_reference = $booking->booking_account_id . '-' . $booking->booking_id;

		unset($resources, $booking->resources, $lastname);

		$booking->booking_completed = 1;
		$booking->booking_failed = $booking->booking_aborted = 0;

		// scrape off the accountdata...
		foreach($booking as $key => $value)
		{
			if(substr($key, 0, 4) == 'acco')
			{
				unset($booking->$key);
			}
		}

		// Also performs garbage collection on the booking_session_dump... NOICE!
		$this->model('booking')->update($booking->booking_id, (array) $booking);


		// Confirmations etc ---------------------------------------------------------------------------------

		// Cleanup
		ci()->session->unset_userdata('booking');
		
		$booking = $this->model('booking')->get($booking->booking_id);

		// Notifications
		ci()->load->library('mandrill');

		$this->internal_notification($booking);

		$this->customer_notification($booking);

		return $booking->booking_id;
	}

	private function internal_notification($booking)
	{
		// Send email
		$message = array(
				'html'		=> ci()->load->view('messages/internal_booking_confirmation', array('booking' => $booking), TRUE),
				'subject'	=> 'You have a new booking',
				'from_email'	=> 'robot@bookyourbeds.com',
				'from_name'		=> 'BookYourBeds.com',
				'to'			=> array(
										array(
											'email'	=> $booking->account_email
											)
										),
				'auto_text'		=> TRUE,
				'url_strip_qs'	=> TRUE
				);

		ci()->mandrill->call('messages/send', array('message' => $message));


		// Send to webhook
	}

	private function customer_notification($booking)
	{
		// Send email
		$message = array(
				'html'		=> ci()->load->view('messages/customer_booking_confirmation', array('booking' => $booking), TRUE),
				'subject'	=> 'Your Booking with ' . account('name'),
				'from_email'	=> 'robot@bookyourbeds.com',
				'from_name'		=> $booking->account_name,
				'to'			=> array(
										array(
											'email'	=> $booking->customer->customer_email
											)
										),
				'auto_text'		=> TRUE,
				'url_strip_qs'	=> TRUE
				);

		ci()->mandrill->call('messages/send', array('message' => $message));
		
	}

	public function aborted($booking_id)
	{
		$this->model('booking')->update($booking_id, array('booking_aborted' => 1, 'booking_session_id' => null));
	}

	public function failed($booking_id)
	{
		$this->model('booking')->update($booking_id, array('booking_failed' => 1));
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

if ( ! function_exists('ci'))
{
	/**
	 * Returns the CI object.
	 *
	 * Example: ci()->db->get('table');
	 *
	 * @staticvar	object	$ci
	 * @return		object
	 */
	function ci()
	{
		return get_instance();
	}
}