<?php

$ecommerceConfig = [
	/*
	* General settings
	*/
	'currency' => 'EUR',
	'paypal-user' => 'shippingdog.orders@gmail.com',

	// General settings
	'currency' => 'EUR',

	// Product
	'product-faq' => false,
	'product-installments' => false,

	// Shop
	'item-card-info' => false,
	'shop-sticky' => false,
	/*'filters' => [
		[
			'title' => 'Tipo di prodotto',
			'slug' => 'typeId',
			'list' => DB::select('id, name, names FROM item_types ORDER BY names ASC, name ASC'),
		],
		[
			'title' => 'Birrificio',
			'slug' => 'breweryId',
			'list' => DB::select('id, name FROM item_breweries ORDER BY name ASC'),
		],
		[
			'title' => 'Nazione',
			'slug' => 'nationalityId',
			'list' => DB::select('id, name FROM item_nationalities ORDER BY name ASC'),
			'sql' => 'breweryId IN (SELECT id FROM item_breweries WHERE nationalityId=?)',
		],
		[
			'title' => 'Stile',
			'slug' => 'styleId',
			'list' => DB::select('id, name FROM item_styles ORDER BY name ASC'),
		]
	],*/

	// Cart
	/*'cart-steps' => [
		1 => ['Accedi', 'login'],
		2 => ['Spedizione', 'address'],
		3 => ['Pagamento', 'payment'],
		4 => ['Confermato', ''],
	],*/

	'cart-statuses' => [
		'-1' => ['canceled', 'Cancellato', '#ff6f6f', 'close'],
		'0' => ['open', 'Non completato', '#ccc', 'pause'],
		'1' => ['received', 'Elaborazione', '#92e9ff', 'waiting'],
		'2' => ['label-ready', 'Pronto', '#ffc677', 'correct'],
		'3' => ['shipped', 'Spedito', '#ff9600', 'shipping'],
		'4' => ['delivered', 'Consegnato', '#44c544', 'delivered'],
		'5' => ['change', 'Cambio', '#44c544', 'change'],
	],

	'cart-admin-statuses' => [
		'-1' => ['canceled', 'Cancellato', '#ff6f6f', 'close'],
		'0' => ['open', 'Non completato', '#ccc', 'pause'],
		'1' => ['received', 'Etichetta da fare', '#92e9ff', 'waiting'],
		'2' => ['label-ready', 'Etichetta pronta', '#ffc677', 'correct'],
		'3' => ['shipped', 'Spedito', '#ff9600', 'shipping'],
		'4' => ['delivered', 'Consegnato', '#44c544', 'delivered'],
		'5' => ['change', 'Cambio', '#44c544', 'change'],
	],

	// Cart pages
	'cart-page' => [
		'steps' => [
			1 => ['Accedi', 'login'],
			2 => ['Spedizione', 'address'],
			3 => ['Offerte', 'offers'],
			4 => ['Pagamento', 'payment'],
		],
		'header-background' => false,
		'flow-bar' => true,
		'gift-option' => true,
		'coupon-option' => true,
	],

	// Payments
	//'paypal-user' => 'pagamenti@beer-shop.it',
];
