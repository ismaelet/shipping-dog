<?php

// elements

use Illuminate\Support\Facades\Auth;

$header = [];

$header['logo'] = false;
$header['name-bar'] = false;
$header['srch-bar'] = true;
$header['lang-bar'] = true;
$header['login'] = true;
$header['force-mob'] = false;
$header['privacy'] = true;
$header['wishlist'] = true;
$header['light-mode-switcher'] = true;

// theme
$header['floating'] = false;

// name
$header['name'] = 'Shi<span class="txt-c1">pp</span>ing Dog';
$header['name-mob'] = 'Shi<span class="txt-c1">pp</span>ing Dog';

// links
$header['links'] = [];

$header['links'][] = ['title' => 'Home', 'loc' => 'home', 'url' => client_url('/')];
$header['links'][] = ['title' => 'Carrello (' . cart_quantity() . ')', 'loc' => 'cart', 'url' => client_url('/cart'), 'cart' => true, 'mob' => true];
$header['links'][] = ['title' => 'Login', 'loc' => 'login', 'url' => client_url('/login'), 'logged' => false, 'mob' => true];

if (Auth::user()?->seller) {
	$header['links'][] = ['title' => 'Mio negozio', 'loc' => 'artist', 'url' => route('seller.show', Auth::user()->seller), 'drop' => 'artist', 'seller' => true];
}

$header['links'][] = ['title' => 'Profilo', 'loc' => 'profile', 'url' => client_url('/profile'), 'logged' => true, 'mob' => true];
$header['links'][] = ['title' => 'Prodotti', 'loc' => 'shop', 'url' => client_url('/shop'), 'drop' => 'shop'];
// $header['links'][] = ['title' => 'Promozioni', 'loc' => 'promotions', 'url' => client_url('/promotions'), 'new' => true];
$header['links'][] = ['title' => 'Preferiti', 'loc' => 'wishlist', 'url' => client_url('/wishlist'), 'logged' => true, 'mob' => true];
$header['links'][] = ['title' => 'Vendi', 'loc' => 'sell', 'url' => client_url('/sell'), 'seller' => false];
$header['links'][] = ['title' => 'Supporto', 'loc' => 'info', 'url' => client_url('/infos/faq'), 'drop' => 'info'];
$header['links'][] = ['title' => 'Gestione', 'loc' => 'admin', 'url' => '/admin', 'admin' => true];

// dropdowns
$header['dropdowns']['shop'] = [
	['Abbigliamento Donna', client_url('/shop?gender=f')],
	['Abbigliamento Uomo', client_url('/shop?gender=m')],
	['Accessori', client_url('/shop?group=m')],
	['Ufficiali', route('seller.show', 1)],
];

$header['dropdowns']['info'] = [
	['Contatti', client_url('/contacts')],
	['Cambio taglia o reso', client_url('/refund')],
	['Stato ordine', route('orders.find')],
	['Domande frequenti', client_url('/infos/faq')],
];

if (Auth::user()?->seller) {
	$header['dropdowns']['artist'] = [
		['Il mio negozio', route('seller.show', Auth::user()?->seller)],
		['Le mie grafiche', client_url('/manage/portfolio')],
		['Carica una grafica', client_url('/manage/portfolio/add')],
		['Vendite realizzate', client_url('/manage/sales/list')],
		['Modifica prezzi', client_url('/manage/sales/prices')],
		['Gestisci', client_url('/manage')],
	];
}

// login bar
$header['login-link'] = ['Login', client_url('/login')];
$header['logged-link'] = [auth()->check() ? (auth()->user()->username ?: 'Profilo') : '', client_url('/profile')];
