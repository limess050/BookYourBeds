<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rehash_passwords extends CI_Migration
{

	public function up()
	{
		echo 'Up';
		$this->load->library('PasswordHash', array(8, FALSE));
		$this->load->library('mandrill');

		$this->load->helper('string');


		ci()->template
			->set_layout('email', '')
			->enable_parser(FALSE);

		$users = $this->model('user')->get_all();


		foreach($users as $user)
		{
			$new_password = random_string('alpha', 8);

			$hashed_password = $this->passwordhash->HashPassword($new_password);

			$this->model('user')->update($user->user_id, array('user_password' => $hashed_password));

			echo $new_password . '<br />';

			// inform user
			if( ! empty($user->user_email) && ENVIRONMENT != 'development')
			{
				$data = array(
							'user_firstname' 	=> $user->user_firstname,
							'password'			=> $new_password
							);

				$message = array(
					'html'		=> ci()->template->set_layout('email', '')->build('messages/new_hashed_password', $data, TRUE),
					'subject'	=> 'Your Updated Password for BookYourBeds',
					'from_email'	=> 'mail@bookyourbeds.com',
					'from_name'		=> 'BookYourBeds.com',
					'to'			=> array(
											array(
												'email'	=> $user->user_email
												)
											),
					'auto_text'		=> TRUE,
					'url_strip_qs'	=> TRUE
					);

				$this->mandrill->call('messages/send', array('message' => $message));
			}
		}


		// Now rehash the internal accounts
		$this->model('internal_user')->update(5, array('internal_user_email' => 'doug.atkinson@thetourbooker.com'));

		$users = $this->model('internal_user')->get_all();

		foreach($users as $user)
		{
			$new_password = random_string('alpha', 8);

			$hashed_password = $this->passwordhash->HashPassword($new_password);

			$this->model('internal_user')->update($user->internal_user_id, array('internal_user_password' => $hashed_password));

			echo $user->internal_user_username . ' => ' . $new_password . '<br />';

			if( ! empty($user->internal_user_email) && ENVIRONMENT != 'development')
			{
				// inform user
				$data = array(
							'user_firstname' 	=> $user->internal_user_firstname,
							'password'			=> $new_password
							);

				$message = array(
					'html'		=> ci()->template->set_layout('email', '')->build('messages/new_hashed_password', $data, TRUE),
					'subject'	=> 'Your Updated Password for BookYourBeds',
					'from_email'	=> 'mail@bookyourbeds.com',
					'from_name'		=> 'BookYourBeds.com',
					'to'			=> array(
											array(
												'email'	=> $user->internal_user_email
												)
											),
					'auto_text'		=> TRUE,
					'url_strip_qs'	=> TRUE
					);

				$this->mandrill->call('messages/send', array('message' => $message));
			}
		}
	}

	public function down()
	{
		
	}

	protected function model($name)
	{
		$name = $name . MODEL_SUFFIX;
		
		// is there a module involved
		$model_name = explode('/', $name);
		
		if ( ! isset($this->{end($model_name)}) )
		{
			$this->load->model($name, '', TRUE);
		}

		return $this->{end($model_name)};
	}

}