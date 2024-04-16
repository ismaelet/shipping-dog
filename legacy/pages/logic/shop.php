<?php

use App\Models\Ecommerce\Design;
use Ecommerce\Shop;

// get slider image data
$slid_des = Design::find(1612);

// warnings
$warning = warning_banner();

// setup shop
$shop = new Shop();
