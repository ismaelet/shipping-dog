<?php

use App\Models\Ecommerce\Seller;
use Ecommerce\Seller as OldSeller;

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

$saleN = DBOld::count('orders WHERE itemID IN (SELECT ID FROM items WHERE userID = ?) AND cartID IN (SELECT ID FROM carts WHERE status > 0)', [auth()->id()]);

// send payment request
if (isset($_POST['request-payment'])) {
	redirect_old('/manage/payments/request');
}
