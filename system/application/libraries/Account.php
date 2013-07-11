<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Account
{
	public $ac;
	public $bookings = null;
	public $unverified = null;
	public $cancelled = null;
	private $settings;

	public function __construct()
	{
		if( ! in_array(ci()->uri->segment(1), array('roadblock', 'init', 'endpoint', 'internal')) && ci()->uri->rsegment(2) != 'auth')
		{
			$this->ac = (ci()->uri->segment(1)) ? $this->model('account')->get_by('account_slug', ci()->uri->segment(1)) : null;

			if(empty($this->ac) && ci()->uri->total_segments() != 1)
			{
				redirect(site_url('roadblock')); 
			}
		}
	}

	public function new_bookings($account_id = null)
	{
		$account_id = ( ! empty($account_id)) ? $account_id : $this->ac->account_id;

		if( ! empty($account_id))
		{
			$this->bookings = $this->model('booking')->unacknowledged($account_id, TRUE);
		}

		if($this->bookings == '0')
		{
			$this->bookings = null;
		}
	}

	public function bookings_awaiting_verification($account_id = null)
	{
		$account_id = ( ! empty($account_id)) ? $account_id : $this->ac->account_id;

		if( ! empty($account_id))
		{
			if($this->model('setting')->get_setting('payment_gateway', $account_id) == 'NoGateway')
			{
				$this->unverified = $this->model('booking')->unverified($account_id, TRUE);
			}

			$this->unverified = $this->model('booking')->unverified($account_id, TRUE);
		}

		if($this->unverified == '0')
		{
			$this->unverified = null;
		}
	}

	public function new_cancellations($account_id = null)
	{
		$account_id = ( ! empty($account_id)) ? $account_id : $this->ac->account_id;

		if( ! empty($account_id))
		{
			$this->cancelled = $this->model('booking')->cancelled($account_id, TRUE);
		}

		if($this->cancelled == '0')
		{
			$this->cancelled = null;
		}
	}

	public function val($key)
	{
		$key = 'account_' . $key;

		return (isset($this->ac->$key)) ? $this->ac->$key : FALSE;
	}

	public function create($name, $email, $password, $send_notification = TRUE, $internal = FALSE, $property_name = '')
	{
		
		// Create the account

		// ERROR CHECKING ON SLUG
		$account = array(
						'account_name'	=> $property_name,
						'account_slug'	=> $this->generate_slug($property_name),
						'account_email'	=> $email,
						'account_confirmation_code'	=> SHA1($email . time())
						);

		$account_id = $this->model('account')->insert($account);

		// Create the user
		$name_parts = explode(' ', $name);

		$user = array(
					'user_firstname'	=> array_shift($name_parts),
					'user_lastname' 	=> implode(' ', $name_parts),
					'user_account_id'	=> $account_id,
					'user_email'		=> $email,
					'user_password'		=> SHA1($password),
					'user_is_admin'		=> 1
					);

		$user_id = $this->model('user')->insert($user);

		if( ! $internal && $send_notification)
		{
			ci()->load->library('mandrill');

			$data['account'] = $this->model('account')->get($account_id);
			$data['user'] = $this->model('user')->get($user_id);
			$data['email'] = $email;
			$data['password'] = $password;

			$message = array(
				'html'		=> ci()->template->set_layout('email', '')->build('messages/new_account', $data, TRUE),
				'subject'	=> 'Welcome to BookYourBeds.com',
				'from_email'	=> 'bookyourbeds@othertribe.com',
				'from_name'		=> 'BookYourBeds.com',
				'to'			=> array(
										array(
											'email'	=> $email
											)
										),
				'auto_text'		=> TRUE,
				'url_strip_qs'	=> TRUE
				);

			ci()->mandrill->call('messages/send', array('message' => $message));
		} else
		{
			// Automatically authorise
			$this->model('account')->update($account_id, array('account_confirmed' => 1));

			// Send the welcome email (non-authorisation)


		}

		if($send_notification)
		{
			// Add them to the MailChimp mailing list (they will still need to opt-in)
			ci()->load->library('MCAPI');
			ci()->mcapi->listSubscribe(ci()->config->item('mcapi_signup_list'), $email);
		}

		$internal_notifications = ci()->config->item('new_account_notifications');

		if( ! $internal && ! empty($internal_notifications) && ENVIRONMENT != 'development')
		{
			ci()->load->library('mandrill');

			$message = array(
				'html'		=> ci()->template->set_layout('email', '')->build('messages/internal_new_account', $data, TRUE),
				'subject'	=> 'New account sign up for BookYourBeds',
				'from_email'	=> 'bookyourbeds@othertribe.com',
				'from_name'		=> 'BookYourBeds.com',
				'to'			=> $internal_notifications,
				'auto_text'		=> TRUE,
				'url_strip_qs'	=> TRUE
				);

			ci()->mandrill->call('messages/send', array('message' => $message));
		}

		return ($internal) ? $account_id : $user_id;
	}

	public function generate_slug($name = '')
	{
		ci()->load->helper('text');
		ci()->load->helper('string');

		$slug = ( ! empty($name)) ? url_title(convert_accented_characters($name), '-', TRUE) : random_string('alnum', 8);
		
		$ac = $this->model('account')->get_by('account_slug', $slug);

		while( ! empty($ac))
		{
			$slug = increment_string($slug, '-');
			$ac = $this->model('account')->get_by('account_slug', $slug);
		}

		return $slug;
	}

	public function send_confirmation_email($email)
	{
		if($this->val('email') != $email)
		{
			$this->model('account')->update($this->val('id'), array('account_email' => $email));
			$this->ac = $this->model('account')->get($this->val('id'));
		}

		$data = array(
					'account'	=> $this->ac,
					'email'		=> $email
					);

		ci()->load->library('mandrill');

		// Send confirmation mail
		$message = array(
				'html'		=> ci()->template->set_layout('email', '')->build('messages/confirm_account', $data, TRUE),
				'subject'	=> 'Confirm you BookYourBeds.com account',
				'from_email'	=> 'bookyourbeds@othertribe.com',
				'from_name'		=> 'BookYourBeds.com',
				'to'			=> array(
										array(
											'email'	=> $email
											)
										),
				'auto_text'		=> TRUE,
				'url_strip_qs'	=> TRUE
				);

			ci()->mandrill->call('messages/send', array('message' => $message));
	}

	public function confirm($auth)
	{
		if($this->model('account')->update_by('account_confirmation_code', $auth, array('account_confirmed' => 1)))
		{
			return $this->model('account')->get_by('account_confirmation_code', $auth);
		}

		return;
	}

	public function send_password_reset($email)
	{
		if($user = $this->model('user')->get_by('user_email', $email))
		{
			$auth = SHA1(time() . $email);
			ci()->db->set('user_password_reset_expires', '(NOW() + INTERVAL 1 DAY)', FALSE);

			$this->model('user')->update($user->user_id, array(
																'user_password_reset'	=> $auth
																));


			ci()->load->library('mandrill');

			// Send confirmation mail
			$message = array(
				'html'		=> ci()->template->set_layout('email', '')->build('messages/reset_password', array('email' => $email, 'auth' => $auth), TRUE),
				'subject'	=> 'Reset your BookYourBeds.com password',
				'from_email'	=> 'bookyourbeds@othertribe.com',
				'from_name'		=> 'BookYourBeds.com',
				'to'			=> array(
										array(
											'email'	=> $email
											)
										),
				'auto_text'		=> TRUE,
				'url_strip_qs'	=> TRUE
				);

			ci()->mandrill->call('messages/send', array('message' => $message));

			return TRUE;
		}

		return FALSE;
	}

	public function wizard_steps()
	{
		// Things to check...
		$steps = array();
		$append = array();
		$can_launch = TRUE;

		// Has the account email been confirmed?
		if( ! $this->ac->account_confirmed)
		{
			$steps[] = 'confirm_email';
			$can_launch = FALSE;
		}

		// Has the account been a wee bit personalised?
		if( ! $this->ac->account_personalised)
		{
			$steps[] = 'account';
			$can_launch = FALSE;
		}

		// How many rooms does the account have?
		
		$this->rooms = $this->model('resource')->count_by('resource_account_id', $this->ac->account_id);
		if($this->rooms == 0)
		{
			$steps[] = 'add_room';
			$can_launch = FALSE;
		} else
		{
			$append[] = 'add_room';
		}

		// Are there any payment options set?
		if( ! $this->setting('deposit'))
		{
			$steps[] = 'payment_options';
			$can_launch = FALSE;
		}

		// Any Terms and Conditions?
		// $steps[] = 'terms';

		// Let's Activate!
		if($can_launch && ! $this->val('active'))
		{
			$steps[] = 'launch';
		}

		return array_merge($steps, $append);
	}

	public function launch()
	{
		$steps = $this->wizard_steps();

		if(in_array('launch', $steps))
		{
			return $this->model('account')->launch($this->ac->account_id);
		}

		return FALSE;
	}

	public function upload_logo($id = null)
	{
		ci()->load->library('upload');
		
		//$config['upload_path'] = BASEPATH . '../../uploads/';
		$config['upload_path'] = 'temp_uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '500';
		$config['encrypt_name'] = TRUE;
		
		ci()->upload->initialize($config);

		if(ci()->upload->do_upload('account_logo'))
		{
			$img_data = ci()->upload->data();
			
			$config['image_library'] = 'gd2';
			//$config['source_image']	= BASEPATH . '../../uploads/' . $img_data['file_name'];
			$config['source_image']	= 'temp_uploads/' . $img_data['file_name'];
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = TRUE;
			//$config['width'] = 200;
			$config['height'] = 200;
			
			ci()->load->library('image_lib', $config); 

			ci()->image_lib->resize();
			
			
			ci()->load->config('s3');
			ci()->load->library('s3');

			ci()->s3->putObject(ci()->s3->inputFile('temp_uploads/' . $img_data['file_name'], false), ci()->config->item('s3_bucket_name'), $img_data['file_name']);


			$url = array(
						ci()->config->item('s3_bucket_location'),
						ci()->config->item('s3_bucket_name'),
						$img_data['file_name']
						);


			$this->model('setting')->create_or_update('account_logo', implode('/', $url), ( ! empty($id)) ? $id : $this->val('id'));
			
			unlink('temp_uploads/' . $img_data['file_name']);

			//$this->session->set_flashdata('msg', 'New logo successfully uploaded');
			//redirect(site_url('admin/settings/account'));

			return TRUE;
		}

		return FALSE;
	}

	public function upload_bg($id = null)
	{
		ci()->load->library('upload');
		
		//$config['upload_path'] = BASEPATH . '../../uploads/';
		$config['upload_path'] = 'temp_uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1000';
		$config['encrypt_name'] = TRUE;
		
		ci()->upload->initialize($config);

		if(ci()->upload->do_upload('account_bg'))
		{
			$img_data = ci()->upload->data();
			
			$config['image_library'] = 'gd2';
			//$config['source_image']	= BASEPATH . '../../uploads/' . $img_data['file_name'];
			$config['source_image']	= 'temp_uploads/' . $img_data['file_name'];
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = TRUE;
			//$config['width'] = 200;
			$config['height'] = 1000;
			
			ci()->load->library('image_lib', $config); 

			ci()->image_lib->resize();
			
			ci()->load->config('s3');
			ci()->load->library('s3');

			ci()->s3->putObject(ci()->s3->inputFile('temp_uploads/' . $img_data['file_name'], false), ci()->config->item('s3_bucket_name'), $img_data['file_name']);

			$url = array(
						ci()->config->item('s3_bucket_location'),
						ci()->config->item('s3_bucket_name'),
						$img_data['file_name']
						);


			$this->model('setting')->create_or_update('account_bg', implode('/', $url),  ( ! empty($id)) ? $id : $this->val('id'));

			unlink('temp_uploads/' . $img_data['file_name']);
			
			return TRUE;
		}

		return FALSE;
	}

	public function setting($key = null)
	{
		if(empty($key))
		{
			return FALSE;
		}

		$this->set_settings();

		return (isset($this->settings[$key])) ? $this->settings[$key] : FALSE;
	}

	private function set_settings()
	{
		if(empty($this->settings))
		{
			$this->settings = ci()->config->item('default_settings');
			//$this->settings = array();

			$custom = $this->model('setting')->get_all();

			foreach($custom as $setting)
			{
				$this->settings[$setting->setting_key] = $setting->setting_value;
			}
		}
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