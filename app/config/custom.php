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
        'full_name' => env('APP_FULL_NAME', 'Shipping Dog'),
        'description' => env('APP_DESCRIPTION', 'Capi di abbigliamento personalizzati con stampa di alta qualità, spedizioni internazionali, consegna veloce. Scegli una grafica o caricane una tua. Nuove promozioni ogni settimana.'),
        'short_description' => env('APP_SHORT_DESCRIPTION', 'Abbigliamento personalizzato'),
        'default_color_scheme' => env('APP_DEFAULT_COLOR_SCHEME', 'dark'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Contacts
    |--------------------------------------------------------------------------
    |
    |
    */

    'contacts' => [
        'instagram' => ['url' => 'https://www.instagram.com/shipping.dog/', 'label' => '@shipping.dog'],
        'facebook' => ['url' => 'https://www.facebook.com/shippingdog/', 'label' => '@shippingdog'],
        'email' => ['url' => 'mailto:giebapromotion@gmail.com', 'label' => 'giebapromotion@gmail.com'],
        'whatsapp' => ['url' => 'https://wa.me/3517419263', 'label' => '351 7419263'],
        'phone' => ['url' => 'tel:0462230218', 'label' => '0462 230218'],
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
