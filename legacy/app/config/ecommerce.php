<?php

$ecommerceConfig = [
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
			'list' => DBOld::select('id, name, names FROM item_types ORDER BY names ASC, name ASC'),
		],
		[
			'title' => 'Birrificio',
			'slug' => 'breweryId',
			'list' => DBOld::select('id, name FROM item_breweries ORDER BY name ASC'),
		],
		[
			'title' => 'Nazione',
			'slug' => 'nationalityId',
			'list' => DBOld::select('id, name FROM item_nationalities ORDER BY name ASC'),
			'sql' => 'breweryId IN (SELECT id FROM item_breweries WHERE nationalityId=?)',
		],
		[
			'title' => 'Stile',
			'slug' => 'styleId',
			'list' => DBOld::select('id, name FROM item_styles ORDER BY name ASC'),
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
		'1' => ['waiting-payment', 'Elaborazione', '#92e9ff', 'waiting'],
		'2' => ['confirmed', 'Pronto', '#ffc677', 'correct'],
		'3' => ['shipped', 'Spedito', '#ff9600', 'shipping'],
		'4' => ['delivered', 'Consegnato', '#44c544', 'delivered'],
		'5' => ['change', 'Cambio', '#44c544', 'change'],
	],

	'cart-admin-statuses' => [
		'-1' => ['canceled', 'Cancellato', '#ff6f6f', 'close'],
		'0' => ['open', 'Non completato', '#ccc', 'pause'],
		'1' => ['waiting-payment', 'Etichetta da fare', '#92e9ff', 'waiting'],
		'2' => ['confirmed', 'Etichetta pronta', '#ffc677', 'correct'],
		'3' => ['shipped', 'Spedito', '#ff9600', 'shipping'],
		'4' => ['delivered', 'Consegnato', '#44c544', 'delivered'],
		'5' => ['change', 'Cambio', '#44c544', 'change'],
	],

	// Payments
	//'paypal-user' => 'pagamenti@beer-shop.it',
];
