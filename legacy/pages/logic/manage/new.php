<?php

use Ecommerce\Seller;

if (!is_seller()) {
	redirect_old('/sell');
}

$seller = new Seller(auth()->id());

if ($seller->status) {
	redirect_old('/artist?id=' . auth()->id());
}

ViewOld::$title = $seller->name;

$title = 'Hai creato: ' . $seller->name;
