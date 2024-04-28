<?php

use Legacy\Classes\Ecommerce\Seller;

if (!App\Helpers\App::isSeller()) {
	redirect_old('/sell');
}

$seller = new Seller(auth()->id());

if ($seller->status) {
	redirect_old('/artist?id=' . auth()->id());
}

\Legacy\System\Classes\LegacyView::$title = $seller->name;

$title = 'Hai creato: ' . $seller->name;
