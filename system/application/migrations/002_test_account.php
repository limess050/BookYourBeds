<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Test_account extends CI_Migration
{
	public $account_id;

	public $sup;

	public $firstname = array('Philip', 'Douglas', 'David', 'Jon', 'John', 'Alex', 'Colin', 'Christopher', 'Michael', 'Peter', 'Jack', 'Anthony', 'Ben', 'Daniel', 'Alan', 'Jeff', 'Aaron', 'Billy', 'Evan', 'Ewan', 'Gavin', 'Graeme', 'Ian', 'Jamie', 'James', 'Luke', 'Robert', 'Tom', 'Justin', 'Neil', 'Christian', 'Russell', 'Cameron', 'Curtis', 'Adrian', 'Sean', 'Leo', 'Oliver', 'Cole', 'Dennis', 'Derek', 'George', 'Clive', 'Graham', 'Shaun', 'Eric', 'Brian', 'Gary', 'William', 'Patrick', 'Martin', 'Jeremy', 'Ray', 'Tyler', 'Jake', 'Scott', 'Dustin', 'Greg', 'Andrew', 'Kevin', 'Lee', 'Kate', 'Ashley', 'Melissa', 'Lorna', 'Sarah', 'Louise', 'Karen', 'Rebecca', 'Amelia', 'Heidi', 'Sophie', 'Jennifer', 'Amanda', 'Anna', 'Chloe', 'Caroline', 'Donna', 'Fiona', 'Hayley', 'Hannah', 'Imogen', 'Lindsay', 'Kristen', 'Krista', 'Christina', 'Stacey', 'Emily', 'Maria', 'Marie', 'Fiona', 'Shauna', 'Phoebe', 'Paige', 'Piper', 'Cheryl', 'Natasha', 'Natalie', 'Tina', 'Anita', 'Melanie', 'Mary', 'Erin', 'Sheila', 'Nicole', 'Michelle', 'Annabel', 'Kate', 'Patricia', 'Holly', 'Keira', 'Danielle', 'Prue', 'Vicky', 'Elizabeth', 'Rhiannon', 'Claire', 'Gemma', 'Nina', 'Kim');
	public $lastname = array('Anderson', 'Atkins', 'Atkinson', 'Aitken', 'Black', 'White', 'Baldwin', 'Clarke', 'MacMillan', 'MacGregor', 'Stephens', 'Smith', 'Jones', 'Carson', 'Baxter', 'Robinson', 'Roberts', 'Woods', 'Thomas', 'Parker', 'Bauer', 'King', 'Simpson', 'Womack', 'Saunders', 'Sowden', 'Butler', 'Stewart', 'Burke', 'Patterson', 'Pattinson', 'Sheen', 'Bell', 'Greene', 'Welsh', 'Reed', 'Mitchell', 'Webb', 'Lloyd', 'Webber', 'Lambert', 'Halliwell', 'Wyatt', 'Cole', 'Crowley', 'Richardson', 'Palmer', 'Porter', 'Turner', 'Shepherd', 'Owen', 'Murray', 'Rigby', 'Rose', 'Wilkins', 'Brown', 'Dane', 'McCormack', 'Thomas', 'Millington', 'Gordon', 'Cusack', 'Livingston', 'Watson', 'Holmes', 'Doyle', 'Brady');
	public $email = array('gmail', 'hotmail', 'live', 'yahoo', 'me', 'ymail', 'rocketmail', 'mac');
	public $tld = array('.com', '.co.uk', '.au.com');

	public $start = '2013-04-27 00:00:00';

	public $season_id;

	public $rooms = array(
						array(
							'resource_title'				=> 'Single Room',
							'resource_booking_footprint'	=> 1,
							'resource_default_release'		=> 3,
							'resource_priced_per'			=> 'room',
							'price'							=> array(
																	'std'	=> array(
																					'weekday'	=> '40',
																					'weekend'	=> '50'
																					),
																	'wtr'	=> array(
																					'weekday'	=> '30',
																					'weekend'	=> '35'
																					)
																	)
							),

						array(
							'resource_title'				=> 'Double Room',
							'resource_booking_footprint'	=> 2,
							'resource_default_release'		=> 4,
							'resource_priced_per'			=> 'room',
							'price'							=> array(
																	'std'	=> array(
																					'weekday'	=> '50',
																					'weekend'	=> '60'
																					),
																	'wtr'	=> array(
																					'weekday'	=> '40',
																					'weekend'	=> '45'
																					)
																	)
							)
						);

	public function up()
	{
		$account = array(
					'account_name'		=> 'The Reivers Rest',
					'account_slug'		=> 'the-reivers-rest',
					'account_email'		=> (ENVIRONMENT == 'development') ? 'phil@othertribe.com' : 'mail@thebedbooker.com',
					'account_confirmed'	=> 1,
					'account_personalised'	=> 1,
					'account_phone'			=> '01573 123456',
					'account_description'	=> 'A fantastic B&B in the heart of the Scottish Borders, where you will always find a warm welcome, a comfy bed and a hearty breakfast to set you up for the day!'
					);

		$this->account_id = $this->model('account')->insert($account);


		$this->model('account')->launch($this->account_id);

		$user = array(
					'user_firstname'	=> 'Joe',
					'user_lastname'		=> 'Bloggs',
					'user_username'		=> 'user1',
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
					'account_logo'	=> 'https://s3-eu-west-1.amazonaws.com/bookyourbeds/0b9cd56c2868788acec9bf70bbde3a3e.jpg',
					'account_bg'	=> 'https://s3-eu-west-1.amazonaws.com/bookyourbeds/fdc655d9dd8f01ffd995db0480146194.jpg',
					'availability_limit_start_at'	=> '27/04/2013',
					'availability_limit_end_at'		=> '31/10/2014',
					'terms_and_conditions'			=> 'These are my terms and conditions for your stay!',
					'booking_instructions'			=> 'We can accommodate dogs and have a couple of small comfortable warm kennels, where your dog can get as good a nights rest as you will!'
					);

		$_settings = array(
					'deposit'	=> 'none',
					'payment_gateway'	=> 'NoGateway',
					'balance_due'	=> 'checkout',
					'account_logo'	=> 'https://s3-eu-west-1.amazonaws.com/bookyourbeds/0b9cd56c2868788acec9bf70bbde3a3e.jpg',
					'account_bg'	=> 'https://s3-eu-west-1.amazonaws.com/bookyourbeds/fdc655d9dd8f01ffd995db0480146194.jpg',
					'availability_limit_start_at'	=> '27/04/2013',
					'availability_limit_end_at'		=> '31/10/2014',
					'terms_and_conditions'			=> 'These are my terms and conditions for your stay!',
					'booking_instructions'			=> 'We can accommodate dogs and have a couple of small comfortable warm kennels, where your dog can get as good a nights rest as you will!'
					);

		$this->model('setting')->create_or_update_many($_settings, $this->account_id);


		$season = array(
						'season_account_id'	=> $this->account_id,
						'season_sort_order'	=> 1,
						'season_title'		=> 'Winter Season',
						'season_start_at'	=> '01/10/2013',
						'season_end_at'		=> '31/03/2014'
						);

		$this->season_id = $this->model('season')->insert($season);

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
								'supplement_default_price'		=> '7.5',
								'supplement_active'				=> 1
								),
							array(
								'supplement_account_id'			=> $this->account_id,
								'supplement_short_description'	=> 'Cot',
								'supplement_default_price'		=> '0',
								'supplement_active'				=> 1
								)
							);

		foreach($supplements as $supplement)
		{
			$this->sup[] = $this->model('supplement')->insert($supplement);
		}
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
													'price_price'			=> ($i == 5 || $i == 6) ? $room['price']['std']['weekend'] : $room['price']['std']['weekday']
													));
			}

			for($i = 1; $i <= 7; $i++)
			{
				$this->model('price')->insert(array(
													'price_resource_id'		=> $resource_id,
													'price_season_id'		=> $this->season_id,
													'price_day_id'			=> $i,
													'price_price'			=> ($i == 5 || $i == 6) ? $room['price']['std']['weekend'] : $room['price']['std']['weekday']
													));
			}



			foreach($this->sup as $s)
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
		for($b = 0; $b < 200; $b++)
		{
			// have a couple arriving today...
			if($b < 10)
			{
				$arrive = date('Y-m-d 00:00:00');
			} else
			{
				// Random date
				//$arrive = strtotime(human_to_unix($this->start), '+' . rand(0, 365) . ' days');
				$arrive = unix_to_human(strtotime('+' . rand(0, 180) . ' days', human_to_unix($this->start)), TRUE, 'eu');
			}

			// Number of nights
			$duration = rand(1, 4);

			// Guests
			$guests = rand(1, 3);

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
																'booking_room_price'	=> $price,
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