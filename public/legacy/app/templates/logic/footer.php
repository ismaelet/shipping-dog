<?php

// elements
$footer['logo'] = false;
$footer['contacts'] = true;

// links
$footer['columns'] = [
	[
		'Esplora',
		[
			['Home', client_url('/')],
			['Prodotti', client_url('/shop')],
			['Vendi', client_url('/sell')],
		]
	],

	[
		'Supporto',
		[
			['Contatti', client_url('/contacts')],
			['FAQ', client_url('/infos/faq')],
			['Cambio taglia', client_url('/refund')],
			['Stato ordine', client_url('/order/status')],
		]
	],

	[
		'Legali',
		[
			['Condizioni di vendita', new_url('/infos/terms')],
			['Condizioni di invio', client_url('/infos/contract')],
			['Privacy policy', client_url('/infos/privacy')],
			['P. IVA ' . sys_config('VAT')],
		]
	]
];

// contacts
$footer['contacts'] = ['instagram', 'facebook', 'email'];
