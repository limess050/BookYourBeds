<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Booking
{
	public function __construct()
	{
		
	}
 
	public function create(
							$account_id,
							$start_timestamp,
							$duration,
							$resources,
							$user_id = 0)
	{
		// Need to do some parsing for calculations
		$_guests = 0;
		$_first_night = 0;
		$_room_price = 0;

		foreach($resources as $key => $resource)
		{
			if($resource['guests'] == 0)
			{
				unset($resources[$key]);
			} else
			{
				$_guests += $resource['guests'];
				$_first_night += $resource['resource_first_night'];
				$_room_price += ($resource['resource_single_price'] * ceil($resource['guests'] / $resource['resource_booking_footprint']));
			}
		}

		if($_guests > 0)
		{
			$booking = array(
							'booking_account_id'		=> $account_id,
							'booking_guests'			=> $_guests,
							'booking_price'				=> $_room_price,
							'booking_room_price'		=> $_room_price,
							'booking_first_night_price'	=> $_first_night,
							'booking_session_id'		=> ci()->session->userdata('session_id'),
							'booking_user_id'			=> $user_id,
							'booking_ip_address' 		=> ci()->input->ip_address(),
							'booking_user_agent' 		=> ci()->input->user_agent(),
							'duration'					=> $duration,
							'start_at'					=> $start_timestamp,
							'resources'					=> $resources
							);

			if( ! booking('booking_id'))
			{
				$booking_id = $this->model('booking')->insert($booking);
				
				// Create an empty booking for this session...
				$this->update_session($this->model('booking')->get($booking_id));
			} else
			{
				$this->model('booking')->update(booking('booking_id'), $booking);

				// Update the booking we're using for this session...
				$this->update_session($this->model('booking')->get(booking('booking_id')));
			}

			return TRUE;
		} else
		{
			return FALSE;
		}
	}

	public function session()
	{
		return session('booking', ( ! account('id')) ? '0' : account('id'));
	}

	public function update_session($data, $force_reset = FALSE)
	{
		$session = session('booking');

		if(empty($session[account('id')]) || $force_reset)
		{
			$session[account('id')] = $data;

			ci()->session->set_userdata('booking', $session);
		} else
		{
			$session[account('id')] = (object) array_merge((array) $session[account('id')], (array) $data);
		}

		ci()->session->set_userdata('booking', $session);
	}

	public function clear_session()
	{
		$session = session('booking');

		unset($session[account('id')]);

		ci()->session->set_userdata('booking', $session);
	}



	public function reset()
	{
		$booking = $this->session();

		if($booking)
		{
			$this->dump();

			ci()->db->where('reservation_booking_id', $booking->booking_id)->delete('reservations');
			$this->clear_session();
		}
	}

	public function dump()
	{
		$booking = $this->session();

		if($booking)
		{
			$this->model('booking')->update($booking->booking_id, array(
																		'booking_session_dump' => serialize($booking)
																		));
		}
	}

	public function sent_for_payment()
	{
		$booking = $this->session();

		ci()->db->where('booking_id', $booking->booking_id)
				->set('booking_sent_for_payment', 1)
				->update('bookings');
	}

	public function verify($auth = null, $id = null, $account_id = null)
	{
		if($id = $this->model('booking')->verify($auth, $id, $account_id))
		{
			$data['booking'] = $this->model('booking')->get($id);

			$this->email($id, $data['booking']->customer->customer_email, 'Your confirmed booking with ' . account('name'), 'Thank you for verifiying your booking with ' . account('name') . '.');
			$this->internal_notification($data['booking']);

			return $id;
		}

		return FALSE;
	}

	public function get_session($booking_id)
	{
		$booking = $this->model('booking')->get($booking_id);

		if($booking)
		{
			$this->update_session(unserialize($booking->booking_session_dump), TRUE);
		}
	} 

	public function has_customer()
	{
		$booking = $this->session();

		return (empty($booking->customer)) ? FALSE : TRUE;
	}

	public function has_supplements()
	{
		$booking = $this->session();

		return ( ! isset($booking->supplements)) ? FALSE : TRUE;
	}

	public function process($booking_id = null, $gateway_results = null)
	{
		ci()->load->helper('date');
		ci()->load->helper('text');
		ci()->load->helper('typography');

		if( ! empty($booking_id))
		{
			$_booking = $this->model('booking')->get($booking_id);
			if($_booking->booking_completed)
			{
				$this->clear_session();
				return $_booking->booking_id;
			}
			$booking = unserialize(( ! empty($_booking->booking_session_dump)) ? $_booking->booking_session_dump : null);
			unset($_booking);
		} else
		{
			$booking = $this->session();
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
		foreach($booking->supplements as $rid => $resource)
		{
			foreach($resource as $sid => $supplement)
			{
				$this->model('supplement')->add_to_booking($sid, $booking->booking_id, $rid, $supplement['qty'], $supplement['price']);
			}	
		}
	
		unset($booking->supplements);
		
		// Booking notes ---------------------------------------------------------------------------------
		// Later

		// Reservation data...
		$resources = $booking->resources;
		
		$booking->booking_reference = $booking->booking_account_id . '-' . $booking->booking_id;

		$booking->booking_completed = 1;
		$booking->booking_failed = $booking->booking_aborted = 0;

		// Is this a booking that requires customer verification?
		$verification_only = FALSE;

		$method = ci()->db->where('setting_account_id', $booking->account_id)
							->where('setting_key', 'payment_gateway')
							->get('settings')
							->row();

		if(in_array($method->setting_value, array('NoGateway')) && $booking->booking_user_id == 0)
		{
			$verification_only = TRUE;

			$booking->booking_completed = 0;
			$booking->booking_confirmation_code = sha1($booking->booking_reference . $lastname . time());
			$booking->booking_confirmation_sent_at = unix_to_human(time(), TRUE, 'eu');
		}

		unset($resources, $booking->resources, $lastname, $booking->emailconf, $booking->has_supplements);

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
		$this->clear_session();
		//ci()->session->unset_userdata('booking');
		
		$booking = $this->model('booking')->get($booking->booking_id);

		// Notifications
		ci()->load->library('mandrill');

		if($verification_only)
		{
			if (SEND_EMAILS) $this->customer_verification($booking, 'Please confirm your booking with ' . account('name'));
		} else
		{
			if (SEND_EMAILS) $this->customer_notification($booking, 'Your Booking with ' . account('name'));

			if (SEND_EMAILS) $this->internal_notification($booking);
		}

		return $booking->booking_id;
	}

	public function transfer($data)
	{
		$original = $this->model('booking')->get($data['booking_original_id']);

		// Create the booking...
		$booking = array(
							'booking_original_id'		=> $original->booking_id,
							'booking_account_id'		=> $original->booking_account_id,
							'booking_reference'			=> $original->booking_reference,
							'booking_guests'			=> $data['booking_guests'],
							'booking_price'				=> $data['booking_price'],
							'booking_room_price'		=> $data['booking_room_price'],
							'booking_supplement_price'	=> $data['booking_supplement_price'],
							'booking_first_night_price'	=> $data['booking_first_night_price'],

							'booking_user_id'			=> $data['booking_user_id'],
							
							'booking_ip_address'		=> $data['booking_ip_address'],
							'booking_user_agent'		=> $data['booking_user_agent'],
							
							'booking_billing_data'		=> $original->booking_billing_data,
							'booking_gateway_data'		=> $original->booking_gateway_data,
							'duration'					=> $data['duration'],
							'start_at'					=> $data['start_at'],
							'resources'					=> $data['resources']
							);

	
		$new = $this->model('booking')->insert($booking);

		// Customer
		$data['customer']['customer_account_id'] = $original->booking_account_id;
		$customer_id = $this->model('customer')->insert($data['customer']);

		// Supplements
		foreach($data['supplements'] as $rid => $resource)
		{
			foreach($resource as $sid => $supplement)
			{
				$this->model('supplement')->add_to_booking($sid, $new, $rid, $supplement['qty'], $supplement['price']);
			}
		}

		// Update the booking (customer ID)
		$booking = array(
						'booking_customer_id' 	=> $customer_id,
						'booking_completed'		=> 1
						);

		$this->model('booking')->update($new, $booking);

		// Update the original booking
		$update = array(
						'booking_transferred_to_id'	=> $new
						);

		$this->model('booking')->update($original->booking_id, $update);
		$this->cancel($original->booking_id);

		// Clear any session data
		ci()->session->unset_userdata('transfer_booking');

		// Send new notifications...
		if(ENVIRONMENT == 'production')
		{
			ci()->load->library('mandrill');

			$booking = $this->model('booking')->get($new);

			$this->customer_notification($booking, 'Your rearranged booking with ' . $booking->account_name);

			$this->internal_notification($booking, 'You have a rearranged booking');
		}
		

		return $new;
	}

	private function internal_notification($booking, $subject = 'You have a new booking')
	{
		// Send email
		$message = array(
				'html'		=> ci()->template->set_layout('email', '')->build('messages/internal_booking_confirmation', array('booking' => $booking), TRUE),
				'subject'	=> $subject,
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


		// Send to webhook (eventually)
	}

	private function customer_notification($booking, $subject = 'Your booking')
	{
		ci()->load->helper('typography');
		ci()->load->config('countries');

		$data['booking'] = $booking;
		$data['countries'] = ci()->config->item('iso_countries');
		$data['instructions'] = $this->model('setting')->get_setting('booking_instructions', $booking->account_id);
		$data['terms'] = $this->model('setting')->get_setting('terms_and_conditions', $data['booking']->account_id);


		// Send email
		$message = array(
				'html'		=> ci()->template->set_layout('email', '')->build('messages/customer_booking_confirmation', $data, TRUE),
				'subject'	=> $subject,
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

	private function customer_verification($booking, $subject = 'Confirm your booking')
	{
		ci()->load->config('countries');

		// Send email
		$message = array(
				'html'		=> ci()->template->set_layout('email', '')->build('messages/customer_booking_verification', array('booking' => $booking, 'countries' => ci()->config->item('iso_countries')), TRUE),
				'subject'	=> $subject,
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

	public function email($booking_id, $email, $subject, $message = null)
	{
		ci()->load->config('countries');

		$data['booking'] = $this->model('booking')->get($booking_id);
		$data['message'] = $message;
		$data['countries'] = ci()->config->item('iso_countries');
		$data['instructions'] = $this->model('setting')->get_setting('booking_instructions', $data['booking']->account_id);
		$data['terms'] = $this->model('setting')->get_setting('terms_and_conditions', $data['booking']->account_id);

		ci()->load->library('mandrill');
		ci()->load->helper('typography');

		$message = array(
				'html'		=> ci()->template->set_layout('email', '')->build('messages/customer_booking_confirmation', $data, TRUE),
				'subject'	=> $subject,
				'from_email'	=> $data['booking']->account_email,
				'from_name'		=> $data['booking']->account_name,
				'auto_text'		=> TRUE,
				'url_strip_qs'	=> TRUE
				);

		$to = explode(',', $email);
			
		foreach($to as $e)
		{
			$message['to'][] = array('email' => $e);
		}

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

	public function cancel($booking_id)
	{
		$this->model('booking')->delete($booking_id);
	}

	public function uncancel($booking_id)
	{
		return $this->model('booking')->undelete($booking_id);
	}

	public function acknowledge_cancellation($booking_id)
	{
		$this->model('booking')->acknowledge_cancellation($booking_id);
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