<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account
{
	public $ac;
	public $bookings = null;

	public function __construct()
	{
		if( ! in_array(ci()->uri->segment(1), array('roadblock', 'init')) && ci()->uri->rsegment(2) != 'auth')
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

	public function val($key)
	{
		$key = 'account_' . $key;

		return (isset($this->ac->$key)) ? $this->ac->$key : FALSE;
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