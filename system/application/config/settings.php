<?php 
/*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/

defined('BASEPATH') OR exit('No direct script access allowed');

$config['default_settings'] = array(
									'max_duration_public' => 7,
									'max_guests_public' => 6
									);


if(ENVIRONMENT == 'development') {
	$config['new_account_notifications'] = array(
												array(
													'email'	=> 'phil@othertribe.com'
													)
											);
} else
{
	$config['new_account_notifications'] = array(
												array(
													'email'	=> 'mail@bookyourbeds.com'
													)
											);
}
