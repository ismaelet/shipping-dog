<?php

use App\Models\Ecommerce\Order;

$_SESSION['payment_type'] = 'buy';
$_SESSION['payment_order_id'] = $order_id = cart()?->id;

$order = Order::find($order_id);

if ($order) {
	require_once(base_path() . '/legacy/listeners/shared.php');

	redirect_old($order->recapUrl());
} else {
	redirect_old('/cart');
}
