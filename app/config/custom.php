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
        'default_color_scheme' => env('APP_DEFAULT_COLOR_SCHEME', 'light'),
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
            ['contacts' => 'email',],
            ['contacts' => 'phone',],

        ],
        'Seguici' => [
            ['contacts' => 'instagram', 'icon' => true],
            ['contacts' => 'facebook', 'icon' => true],
            ['contacts' => 'whatsapp', 'icon' => true],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Contacts
    |--------------------------------------------------------------------------
    |
    |
    */

    'contacts' => [
        'instagram' => ['url' => 'https://www.instagram.com/shipping.dog/', 'label' => '@shipping.dog',],
        'facebook' => ['url' => 'https://www.facebook.com/shippingdog/', 'label' => '@shippingdog',],
        'email' => ['url' => 'mailto:giebapromotion@gmail.com', 'label' => 'giebapromotion@gmail.com',],
        'whatsapp' => ['url' => 'https://wa.me/3517419263', 'label' => '351 7419263',],
        'phone' => ['url' => 'tel:0462230218', 'label' => '0462 230218',],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Emails
    |--------------------------------------------------------------------------
    |
    | The email addresses to be notified on certain events.
    |
	*/
    'notification_emails' => config('app.env') === 'production'
        ? ['giebapromotion@gmail.com'] : ['ismaele.tuffanelli@gmail.com'],
];
