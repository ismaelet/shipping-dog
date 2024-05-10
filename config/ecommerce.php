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
				'tag' => 'offers',
				'label' => 'Offerte',
			],
			4 => [
				'tag' => 'payment',
				'label' => 'Pagamento',
			],
		],
		'statuses' => [
			'-1' => [
				'tag' => 'canceled',
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
				'icon' => 'waiting',
			],
			'2' => [
				'tag' => 'confirmed',
				'label' => 'Confermato',
				'color' => 'hsl(120, 70%, 50%)',
				'icon' => 'correct',
			],
			'3' => [
				'tag' => 'label-ready',
				'label' => 'In preparazione',
				'color' => 'hsl(40, 100%, 50%)',
				'icon' => 'correct',
			],
			'4' => [
				'tag' => 'shipped',
				'label' => 'Spedito',
				'color' => '#ff9600',
				'icon' => 'shipping',
			],
			'5' => [
				'tag' => 'delivered',
				'label' => 'Consegnato',
				'color' => '#44c544',
				'icon' => 'delivered',
			],
			'6' => [
				'tag' => 'change',
				'label' => 'Cambio',
				'color' => '#44c544',
				'icon' => 'change',
			],
		],
	],
];
