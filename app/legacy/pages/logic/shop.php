<?php

use App\Models\Ecommerce\Design;
use Legacy\Classes\Ecommerce\Shop;

// get slider image data
$slid_des = Design::with('seller', 'main_product')->find(1612);

// setup shop
$shop = new Shop();

\Legacy\System\Classes\LegacyView::setTitle('Negozio');
