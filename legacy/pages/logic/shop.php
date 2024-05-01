<?php

use App\Models\Ecommerce\Design;
use Legacy\Classes\Ecommerce\Shop;

// get slider image data
$slid_des = Design::find(1612);

// warnings
$warning = warning_banner();

// setup shop
$shop = new Shop();

\Legacy\System\Classes\LegacyView::setTitle('Negozio');
