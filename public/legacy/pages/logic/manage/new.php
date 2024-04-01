<?php

use Ecommerce\Seller;

if (!user()->is_seller()) {
	redirect('/sell');
}

$seller = new Seller(userId());

if ($seller->status) {
	redirect('/artist?id=' . userId());
}

View::$title = $seller->name;

$title = 'Hai creato: ' . $seller->name;
