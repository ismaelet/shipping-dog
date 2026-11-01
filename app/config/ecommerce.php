<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ecommerce data
    |--------------------------------------------------------------------------
    |
    |
    */

    'vat' => '02397230224',

    'cart' => [
        'steps' => [
            1 => [
                'tag' => 'access',
                'label' => 'Accedi',
            ],
            2 => [
                'tag' => 'address',
                'label' => 'Spedizione',
            ],
            3 => [
                'tag' => 'promotions',
                'label' => 'Offerte',
            ],
            4 => [
                'tag' => 'payment',
                'label' => 'Pagamento',
            ],
        ],
    ],

    'order' => [
        'statuses' => [
            '-1' => [
                'tag' => 'cancelled',
                'label' => 'Cancellato',
                'color' => '#ff6f6f',
                'icon' => 'close',
            ],
            '0' => [
                'tag' => 'open',
                'label' => 'Non completato',
                'color' => '#ccc',
                'icon' => 'pause',
            ],
            '1' => [
                'tag' => 'received',
                'label' => 'Ricevuto',
                'color' => 'hsl(192, 100%, 65%)',
                'icon' => 'hourglass_empty',
            ],
            '2' => [
                'tag' => 'confirmed',
                'label' => 'Confermato',
                'color' => 'hsl(70, 100%, 40%)',
                'icon' => 'correct',
            ],
            '3' => [
                'tag' => 'awaiting-shipping',
                'label' => 'In preparazione',
                'color' => 'hsl(95, 70%, 50%)',
                'icon' => 'deployed_code_history',
            ],
            '4' => [
                'tag' => 'shipped',
                'label' => 'Spedito',
                'color' => 'hsl(41, 100%, 50%)',
                'icon' => 'shipping',
            ],
            '5' => [
                'tag' => 'delivered',
                'label' => 'Consegnato',
                'color' => 'hsl(30, 100%, 50%)',
                'icon' => 'delivered',
            ],
        ],
    ],
];
