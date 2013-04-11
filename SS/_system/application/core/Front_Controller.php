<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Front_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->template
			->set_layout('front')
			->enable_parser(FALSE)

			->set_partial('form_errors', 'partials/form_errors')
			->set_partial('cart', 'partials/cart')

			->append_metadata( js('jquery.js') )
			->append_metadata( js('bootstrap-dropdown.js') )

			->append_metadata( css('front.css') )
			->append_metadata( js('application.js') )
			->append_metadata( js('spin.js') )

			->append_metadata( css('jquery.ui.css') ) 
			->append_metadata( js('jquery.ui.js') );

		if( ! $this->account->val('active'))
		{
			redirect('roadblock/coming_soon');
		}

		$this->load->helper('typography');
	}
}
