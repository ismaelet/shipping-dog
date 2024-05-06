<?php

use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\Seller;

$seller = Seller::find(auth()->id());
$seller->updateEarned();

$from = 0;
$j = 1;
$block = 200;
$tot = 0;
$old = '';

$sort = $_GET['sort'] ?? 'time';
$sort = !in_array($sort, ['time', 'designs']) ? 'time' : $sort;
$currentYear = request()->year;

$saleN = OrderItem::where('seller_id', $seller->id)
	->where('status', '>', 0)
	->count();

// send payment request
if (isset($_POST['request-payment'])) {
	redirect_old('/manage/payments/request');
}
