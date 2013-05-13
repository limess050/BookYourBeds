<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Will need to integrate this into the payment library

class Endpoint extends MY_Controller {

	public function sagepay()
	{
		if( ! $this->input->post('mandrill_events'))
		{
			die;
		}

		$data = $this->input->post('mandrill_events');

		$data = json_decode($data);

		// Get the booking ID
		// Check the 'to' array - should be second element, but just in case...
		foreach($data[0]->msg->to as $to)
		{
			$address = explode('.', $to[0]);

			if(strpos($address[1], 'byb_sp_verify') !== FALSE)
			{
				$booking_id = $address[0];
			}
		}

		if(empty($booking_id))
		{
			die;
		}

		$this->load->library('booking');

		// Now lets do something with it!
		// Does the subject have SUCCESS in it?
		if(strpos(strtolower($data[0]->msg->subject), 'success') !== FALSE)
		{
			// Success
			// Extract the transaction code from the subject
			$subject = explode('Tx Number:', $data[0]->msg->subject);

			$this->booking->process($booking_id, array('VendorTxCode' => trim($subject[1])));
		} else
		{
			// Some sort of search for aborted and failed
			// Aborted
			if(strpos(strtolower($mandrill[0]->msg->raw_msg), 'abort') !== FALSE)
			{
				// Aborted
				$this->booking->aborted($booking_id);
			} else
			{
				// Failed in some way
				$this->booking->failed($booking_id);
			}
		}
	}
}