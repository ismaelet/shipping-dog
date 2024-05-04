<?php

use Legacy\Classes\Ecommerce\Seller;

global $user, $seller;

$seller = new Seller(auth()->id());

if (!$seller->id) {
	redirect_old('/profile');
}

// setup navigation bar
$this_path = str_replace('manage/', '', \Legacy\System\Classes\LegacyView::$name);
$this_cat = substr($this_path, 0, strpos($this_path, '/'));
$this_page = substr($this_path, strpos($this_path, '/') + 1);

$nav_categories = [
	[
		'title' => 'Impostazioni',
		'category' => 'settings',
	],
	[
		'title' => 'Grafiche',
		'category' => 'portfolio',
	],
	[
		'title' => 'Vendite',
		'category' => 'sales',
	],
	[
		'title' => 'Foto clienti',
		'category' => 'pics',
	],
];

$nav_settings = [
	[
		'title' => 'Generali',
		'file' => 'general',
		'file_show' => '',
	],
];

$nav_portfolio = [
	[
		'title' => 'Lista grafiche',
		'file' => 'list',
		'file_show' => '',
	],
	[
		'title' => 'Carica una grafica',
		'file' => 'add',
	],
];


$nav_sales = [
	[
		'title' => 'Lista vendite',
		'file' => 'list',
		'file_show' => '',
	],
	[
		'title' => 'Modifica prezzi',
		'file' => 'prices',
	],
];

$nav_pics = [
	[
		'title' => 'Lista foto',
		'file' => 'list',
		'file_show' => '',
	],
	[
		'title' => 'Carica una foto',
		'file' => 'add',
	],
];
