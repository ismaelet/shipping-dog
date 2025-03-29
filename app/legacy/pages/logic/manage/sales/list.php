<?php

use App\Models\Ecommerce\OrderItem;
use Illuminate\Support\Facades\Auth;

$seller = Auth::user()->seller;
$seller->updateEarned();
$seller->updatePaid();
$seller->updateOtherTransfers();
$seller->updateCredit();

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
