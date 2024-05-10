<?php

return [

	/*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

	'mailgun' => [
		'domain' => env('MAILGUN_DOMAIN'),
		'secret' => env('MAILGUN_SECRET'),
		'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
		'scheme' => 'https',
	],

	'postmark' => [
		'token' => env('POSTMARK_TOKEN'),
	],

	'ses' => [
		'key' => env('AWS_ACCESS_KEY_ID'),
		'secret' => env('AWS_SECRET_ACCESS_KEY'),
		'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
	],

	'paypal' => [
		'client_id' => 'AWW2ARJTiBxj8VLzrfBxc5WrWMYUBFJzG8pBOp6vk5Jl5fTo4bn_J_9GvnEhuWJGnKom3DYWe-yX0AHc',
		'client_secret' => 'REMOVED_SECRET',

		'sandbox' => [
			'client_id' => 'AcB6CifmIAzH7ASX8AavVjP3-2KQlnU55cGfmjH1IwhoWPYbqwxlZgImZ-vxv0ZLak0rbnMjzrYeir7z',
			'client_secret' => 'REMOVED_SECRET'
		]
	],

	'gls' => [
		'client_id' => 200162,
		'client_password' => 2001621122,
		'contract_code' => 7841,
		'headquarters' => 'TT',
	]
];
