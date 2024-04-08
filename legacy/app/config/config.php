<?php

global $sysConfig;

$sysConfig = [
	// general settings
	'static' => false,
	'timezone' => 'Europe/Rome',
	'logged-redirect' => '/profile',
	'domain' => 'shippingdog.com',

	// e-email
	'email-host' => 'smtp.titan.email',
	'email-username' => 'noreply@shippingdog.com',
	'email-passsword' => 'REDACTED_SMTP_PASSWORD_COMPOSE',

	// name
	'name' => 'Shipping Dog',
	'name-full' => 'Shipping Dog SNC',
	'desc-it' => 'Capi di abbigliamento personalizzati con stampa di alta qualità, spedizioni internazionali, consegna veloce. Scegli una grafica o caricane una tua. Nuove promozioni ogni settimana.',
	'desc-shr-it' => 'T-shirt, Abbigliamento e Accessori Personalizzati',
	'desc-en' => '',
	'desc-shr-en' => '',
	'desc-pt' => '',
	'desc-shr-pt' => '',

	// language
	'languages' => [
		'it' => 'Italiano',
		// 'en' => 'English',
		// 'de' => 'Deutsch',
		// 'pt' => 'Português',
		// 'es' => 'Español',
	],
	'default-language' => 'it',

	// logo
	'logo' => '/app/assets/logo/logo.png',

	// business data
	'VAT' => '02397230224',

	// contacts
	'contact-facebook' => '@shippingdog',
	'contact-facebook-link' => 'https://www.facebook.com/shippingdog',
	'contact-instagram' => '@shipping.dog',
	'contact-instagram-link' => 'https://www.instagram.com/shipping.dog/',
	'contact-yt' => '',
	'contact-sp' => '',
	'contact-twit' => '',
	'contact-twit-link' => '',
	'contact-email' => 'giebapromotion@gmail.com',
	'contact-email-link' => 'emailto:giebapromotion@gmail.com',
	'contact-tel' => '0462 230218',
	'contact-tel-link' => 'tel:0462230218',
	'contact-whatsapp' => '',
	'contact-whatsapp-link' => 'https://wa.me/3517419263',
	'contact-tele' => '',
	'contact-pin' => '',
	'contact-pin-link' => '',
	'contact-tiktok' => '',
	'contact-tiktok-link' => '',

	// third party integrations
	'google-analytics' => true,
	'facebook-pixel' => false,

	'color-scheme' => 'dark'
];

//local server settings
if (strpos($_SERVER['REMOTE_ADDR'], '172') === 0) {
	$sysConfig['hostname'] = env('DB_HOST');
	$sysConfig['database'] = env('DB_DATABASE');
	$sysConfig['username'] = env('DB_USERNAME');
	$sysConfig['password'] = env('DB_PASSWORD');
} else {
	// live server settings
	$sysConfig['hostname'] = 'localhost';
	$sysConfig['database'] = 'u944138369_shippingdog';
	$sysConfig['username'] = 'u944138369_shippingdog';
	$sysConfig['password'] = 'V7*;ca8*';
}
