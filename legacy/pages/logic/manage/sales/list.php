<?php

use App\Models\Ecommerce\Seller;
use Legacy\Classes\Ecommerce\Seller as OldSeller;

$seller = Seller::find(auth()->id());
$oldSeller = new OldSeller(auth()->id());

$from = 0;
$j = 1;
$block = 200;
$tot = 0;
$old = '';

$sort = $_GET['sort'] ?? 'time';
$sort = !in_array($sort, ['time', 'designs']) ? 'time' : $sort;
$year = isset($_GET['year']) ? $_GET['year'] : null;

$saleN = \Legacy\System\Classes\LegacyDB::count('order_items WHERE product_id IN (SELECT id FROM items WHERE user_id = ?) AND order_id IN (SELECT id FROM orders WHERE status > 0)', [auth()->id()]);

// send payment request
if (isset($_POST['request-payment'])) {
	redirect_old('/manage/payments/request');
}
