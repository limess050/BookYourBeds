<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
|--------------------------------------------------------------------------
| Use SSL
|--------------------------------------------------------------------------
|
| Run this over HTTP or HTTPS. HTTPS (SSL) is more secure but can cause problems
| on incorrectly configured servers.
|
*/

$config['use_ssl'] = TRUE;

/*
|--------------------------------------------------------------------------
| Access Key
|--------------------------------------------------------------------------
|
| Your Amazon S3 access key.
|
*/

$config['access_key'] = 'AKIAJYACA3YHUWVSYW5A';

/*
|--------------------------------------------------------------------------
| Parser Enabled
|--------------------------------------------------------------------------
|
| Your Amazon S3 Secret Key.
|
*/

$config['secret_key'] = 'FTBQiK9XCLwPyi1lCKScCJrXs8TbPmH7ooyerWpo';

$config['s3_bucket_name'] = 'bookyourbeds';
$config['s3_bucket_location'] = 'https://s3-eu-west-1.amazonaws.com';

