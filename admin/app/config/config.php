<?php

global $sysConfig;

$sysConfig = [
	// e-email
	'email-host' => 'smtp.titan.email',
	'email-username' => 'noreply@shippingdog.com',
	'email-passsword' => 'REDACTED_SMTP_PASSWORD_COMPOSE',

	'name' => 'Shipping Dog',
	'name_full' => 'Shipping Dog SNC',
	'desc_it' => '',
	'desc_shr_it' => 'Amministrazione',

	// avilable languages, in order
	'default-language' => 'it',
	'languages' => [
		'it' => 'Italiano',
		// 'en' => 'English',
		// 'de' => 'Deutsch',
		// 'pt' => 'Português',
		// 'es' => 'Español',
	],

	'logo' => '/app/assets/logo/logo.jpg',

	'google_analytics' => false,

	'timezone' => 'Europe/Rome',

	'logged-redirect' => '/',

	// theme
	'theme-color' => 'dark',

];

$sysConfig['static'] = false;

//local server settings
if (strpos($_SERVER['REMOTE_ADDR'], '172') === 0) {
	$sysConfig['hostname'] = $_ENV['DB_HOST'];
	$sysConfig['database'] = $_ENV['DB_DATABASE'];
	$sysConfig['username'] = $_ENV['DB_USERNAME'];
	$sysConfig['password'] = $_ENV['DB_PASSWORD'];
} else {
	// live server settings
	$sysConfig['hostname'] = 'localhost';
	$sysConfig['database'] = 'u944138369_shippingdog';
	$sysConfig['username'] = 'u944138369_shippingdog';
	$sysConfig['password'] = 'V7*;ca8*';
}
