<?php

return [

	/*
    |--------------------------------------------------------------------------
    | Meta Data
    |--------------------------------------------------------------------------
    |
    |
    */

	'meta_data' => [
		'full_name' => env('APP_FULL_NAME', ''),
		'description' => env('APP_DESCRIPTION', ''),
		'short_description' => env('APP_SHORT_DESCRIPTION', ''),
		'default_color_scheme' => env('APP_COLOR_SCHEME', 'light'),
	],

	/*
    |--------------------------------------------------------------------------
    | Navigation Links
    |--------------------------------------------------------------------------
    |
    |
    */

	'header_navigation_links' => [
		['label' => 'Home', 'url' => 'home', 'location_tag' => 'home', 'admin_only' => false],
		// ['type' => 'link', 'label' => 'Contatti', 'url' => 'contacts', 'location_tag' => 'contacts', 'admin_only' => false],
		// ['type' => 'button', 'label' => 'Login', 'url' => 'login', 'location_tag' => 'login', 'auth' => false, 'admin_only' => false],
		// ['type' => 'button', 'label' => '{{ $username }}', 'url' => 'user-dashboard', 'location_tag' => 'user-dashboard', 'auth' => true, 'admin_only' => false],
	],

	'footer_navigation_links' => [
		'Navigazione' => [
			['label' => 'Home', 'url' => 'home',],
		],
		'Legali' => [
			// ['label' => 'Privacy policy', 'url' => 'privacy'],
			// ['label' => 'P. IVA 02392550220'],
		],
		'Contatti' => [
			// ['label' => 'Contattaci', 'url' => 'contacts'],
			['social_media' => 'email',],
			['social_media' => 'phone',],

		],
		'Seguici' => [
			['social_media' => 'instagram', 'icon' => true],
			['social_media' => 'facebook', 'icon' => true],
			['social_media' => 'whatsapp', 'icon' => true],
		],
	],

	/*
    |--------------------------------------------------------------------------
    | Social media
    |--------------------------------------------------------------------------
    |
    |
    */

	'social_media' => [
		'instagram' => ['url' => 'https://www.instagram.com/instagramName', 'label' => '@instagramName',],
		'facebook' => ['url' => '', 'label' => '',],
		'email' => ['url' => 'mailto:info@domain.com', 'label' => 'info@domain.com',],
		'whatsapp' => ['url' => 'https://wa.me/390000000000', 'label' => '000 0000000',],
		'phone' => ['url' => 'tel:000 0000000', 'label' => '000 0000000',],
	],

	/*
    |--------------------------------------------------------------------------
    | Ecommerce data
    |--------------------------------------------------------------------------
    |
    |
	*/

	'ecommerce' => [
		'vat' => '02397230224',
	],
];
