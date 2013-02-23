<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Test_account extends CI_Migration
{
	public $account_id;

	public $firstname = array('Philip', 'Douglas', 'David', 'Jon', 'John', 'Alex', 'Colin', 'Christopher', 'Michael', 'Peter', 'Jack', 'Anthony', 'Ben', 'Daniel', 'Alan', 'Jeff', 'Aaron', 'Billy', 'Evan', 'Ewan', 'Gavin', 'Graeme', 'Ian', 'Jamie', 'James', 'Luke', 'Robert', 'Tom', 'Justin', 'Neil', 'Christian', 'Russell', 'Cameron', 'Curtis', 'Adrian', 'Sean', 'Leo', 'Oliver', 'Cole', 'Dennis', 'Derek', 'George', 'Clive', 'Graham', 'Shaun', 'Eric', 'Brian', 'Gary', 'William', 'Patrick', 'Martin', 'Jeremy', 'Ray', 'Tyler', 'Jake', 'Scott', 'Dustin', 'Greg', 'Andrew', 'Kevin', 'Lee', 'Kate', 'Ashley', 'Melissa', 'Lorna', 'Sarah', 'Louise', 'Karen', 'Rebecca', 'Amelia', 'Heidi', 'Sophie', 'Jennifer', 'Amanda', 'Anna', 'Chloe', 'Caroline', 'Donna', 'Fiona', 'Hayley', 'Hannah', 'Imogen', 'Lindsay', 'Kristen', 'Krista', 'Christina', 'Stacey', 'Emily', 'Maria', 'Marie', 'Fiona', 'Shauna', 'Phoebe', 'Paige', 'Piper', 'Cheryl', 'Natasha', 'Natalie', 'Tina', 'Anita', 'Melanie', 'Mary', 'Erin', 'Sheila', 'Nicole', 'Michelle', 'Annabel', 'Kate', 'Patricia', 'Holly', 'Keira', 'Danielle', 'Prue', 'Vicky', 'Elizabeth', 'Rhiannon', 'Claire', 'Gemma', 'Nina', 'Kim');
	public $lastname = array('Anderson', 'Atkins', 'Atkinson', 'Aitken', 'Black', 'White', 'Baldwin', 'Clarke', 'MacMillan', 'MacGregor', 'Stephens', 'Smith', 'Jones', 'Carson', 'Baxter', 'Robinson', 'Roberts', 'Woods', 'Thomas', 'Parker', 'Bauer', 'King', 'Simpson', 'Womack', 'Saunders', 'Sowden', 'Butler', 'Stewart', 'Burke', 'Patterson', 'Pattinson', 'Sheen', 'Bell', 'Greene', 'Welsh', 'Reed', 'Mitchell', 'Webb', 'Lloyd', 'Webber', 'Lambert', 'Halliwell', 'Wyatt', 'Cole', 'Crowley', 'Richardson', 'Palmer', 'Porter', 'Turner', 'Shepherd', 'Owen', 'Murray', 'Rigby', 'Rose', 'Wilkins', 'Brown', 'Dane', 'McCormack', 'Thomas', 'Millington', 'Gordon', 'Cusack', 'Livingston', 'Watson', 'Holmes', 'Doyle', 'Brady');
	public $email = array('gmail', 'hotmail', 'live', 'yahoo', 'me', 'ymail', 'rocketmail', 'mac');
	public $tld = array('.com', '.co.uk', '.au.com');

	public $start = '2013-01-01 00:00:00';

	public $rooms = array(
						array(
							'resource_title'				=> 'Double Room',
							'resource_booking_footprint'	=> 2,
							'resource_default_release'		=> 4,
							'resource_priced_per'			=> 'room',
							'price'							=> '60'
							),

						array(
							'resource_title'				=> 'Quad Room',
							'resource_booking_footprint'	=> 4,
							'resource_default_release'		=> 2,
							'resource_priced_per'			=> 'room',
							'price'							=> '130'
							),

						array(
							'resource_title'				=> '12 Bed Dormitory',
							'resource_booking_footprint'	=> 1,
							'resource_default_release'		=> 24,
							'resource_priced_per'			=> 'bed',
							'price'							=> '10'
							)
						);

	public function up()
	{
		$account = array(
					'account_name'		=> 'The Edinburgh Hostel',
					'account_slug'		=> 'the-edinburgh-hostel',
					'account_email'		=> (ENVIRONMENT == 'development') ? 'phil@othertribe.com' : 'test@bookyourbeds.com',
					'account_confirmed'	=> 1,
					'account_personalised'	=> 1,
					'account_active'		=> 1
					);

		$this->account_id = $this->model('account')->insert($account);

		$user = array(
					'user_firstname'	=> 'Joe',
					'user_lastname'		=> 'Bloggs',
					'user_username'		=> 'test',
					'user_email'		=> (ENVIRONMENT == 'development') ? 'phil@othertribe.com' : 'test@bookyourbeds.com',
					'user_password'		=> SHA1('password'),
					'user_is_admin'		=> 1,
					'user_account_id'	=> $this->account_id
					);

		$this->model('user')->insert($user);

		$settings = array(
					'deposit'	=> 'full',
					'payment_gateway'	=> 'SagePay_Form',
					'sagepay_form_vendor_id'	=> 'applecart',
					'sagepay_form_crypt'		=> 'oG1PDrzXanmXe5JE',
					'sagepay_form_encryption_type'	=> 'AES',
					'balance_due'	=> 'checkin',
					'account_bg'	=> site_url('assets/img/default/style_edinburgh_bg.jpg', FALSE)
					);

		$_settings = array(
					'deposit'	=> 'none',
					'payment_gateway'	=> 'NoGateway',
					'balance_due'	=> 'checkin',
					'account_bg'	=> site_url('assets/img/default/style_edinburgh_bg.jpg', FALSE)
					);

		$this->model('setting')->create_or_update_many($settings, $this->account_id);

		$this->supplements();
		$this->rooms();
		$this->bookings();
	}

	private function supplements()
	{
		$supplements = array(
							array(
								'supplement_account_id'			=> $this->account_id,
								'supplement_short_description'	=> 'Early check-in',
								'supplement_default_price'		=> 20,
								'supplement_active'				=> 1
								),
							array(
								'supplement_account_id'			=> $this->account_id,
								'supplement_short_description'	=> 'Breakfast',
								'supplement_default_price'		=> 10,
								'supplement_per_guest'			=> 1,
								'supplement_per_day'			=> 1,
								'supplement_active'				=> 1
								)
							);

		$this->model('supplement')->insert_many($supplements);
	}

	private function rooms()
	{
		foreach($this->rooms as $room)
		{
			$resource = array(
							'resource_account_id'			=> $this->account_id,
							'resource_title'				=> $room['resource_title'],
							'resource_booking_footprint'	=> $room['resource_booking_footprint'],
							'resource_default_release'		=> $room['resource_default_release'],
							'resource_priced_per'			=> $room['resource_priced_per'],
							'resource_active'				=> 1
							);

			$resource_id = $this->model('resource')->insert($resource);

			for($i = 1; $i <= 7; $i++)
			{
				$this->model('price')->insert(array(
													'price_resource_id'		=> $resource_id,
													'price_season_id'		=> 0,
													'price_day_id'			=> $i,
													'price_price'			=> $room['price']
													));
			}

			for($s = 1; $s <= 2; $s++)
			{
				$this->db->insert('supplement_to_resource', array(
																'str_supplement_id'	=> $s,
																'str_resource_id'	=> $resource_id
																));
			}
		}
	}

	private function bookings()
	{
		for($b = 0; $b < 500; $b++)
		{
			// Random date
			//$arrive = strtotime(human_to_unix($this->start), '+' . rand(0, 365) . ' days');
			$arrive = unix_to_human(strtotime('+' . rand(0, 180) . ' days', human_to_unix($this->start)), TRUE, 'eu');

			// Number of nights
			$duration = rand(1, 7);

			// Guests
			$guests = rand(1, 6);

			// Pick a random room
			$room = $this->db->order_by('resource_id', 'random')
							->where('resource_account_id', $this->account_id)
							->get('resources')
							->row();

			$a = $this->model('resource')->resource_availability($room->resource_id, 
																human_to_unix($arrive), 
																strtotime('+' . ($duration - 1) . ' days', human_to_unix($arrive)), 
																$this->account_id);
			
			$booking_footprint = ceil($guests/$room->resource_booking_footprint);

			$available = TRUE;

			foreach($a->availability as $day)
			{
				if(($day->release - $day->bookings) < $booking_footprint)
				{
					$available = FALSE;
				}
			}
			
			if($available)
			{
				$day_price = $this->db->where('price_resource_id', $room->resource_id)
								->where('price_day_id', 1)
								->get('prices')
								->row();

				

				$price = (($booking_footprint * $day_price->price_price) * $duration);

				$firstname = $this->firstname[rand(0,count($this->firstname) - 1)];
				$lastname = $this->lastname[rand(0,count($this->lastname) - 1)];
				$email = strtolower("{$firstname}.{$lastname}@");
				$email .= $this->email[rand(0,count($this->email) - 1)];
				$email .= $this->tld[rand(0,count($this->tld) - 1)];

				$phone = null;

				$customer_id = $this->model('customer')->insert(array(
																	'customer_account_id'	=> $this->account_id,
																	'customer_firstname'	=> $firstname,
																	'customer_lastname'		=> $lastname,
																	'customer_email'		=> $email,
																	'customer_phone'		=> $phone,
																	'customer_accepts_marketing'	=> rand(0,1)
																	));

				$acknowledged = rand(0,100);

				$booking_id = $this->model('booking')->insert(array(
																'booking_account_id'	=> $this->account_id,
																'booking_guests'		=> $guests,
																'booking_price'			=> $price,
																'booking_deposit'		=> ($booking_footprint * $day_price->price_price),
																'booking_customer_id'	=> $customer_id,
																'booking_completed'		=> 1,
																'booking_acknowledged'	=> ((human_to_unix($arrive) > time()) && ($acknowledged < 3)) ? 0 : 1
																));

				$this->model('booking')->update($booking_id, array('booking_reference' => $this->account_id . '-' . $booking_id));
			
				$this->model('reservation')->insert(array(
														'reservation_booking_id'	=> $booking_id,
														'reservation_resource_id'	=> $room->resource_id,
														'reservation_footprint'		=> $booking_footprint,
														'reservation_start_at'		=> $arrive,
														'reservation_duration'		=> $duration,
														'reservation_checked_in'	=> (strtotime('+1 days', human_to_unix($arrive)) < time()) ? 1 : 0
														));
			}

		}
	}

	public function down()
	{
		$tables = array('accounts',
						'bookings',
						'customers',
						'logins',
						'prices',
						'releases',
						'reservations',
						'resources',
						'seasons',
						'sessions',
						'settings',
						'users');
		
		foreach($tables as $table)
		{
			$this->db->truncate($table);
		}
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