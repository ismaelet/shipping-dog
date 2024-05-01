<?php

use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Order;
use App\Models\Framework\Configuration;

// Get the date from which to start the log
$startFromConfig = Configuration::where('name', 'product_log_start_from')->first();
$startFromTime = $startFromConfig->value;
$validCarts = Cart::where('time', '>=', $startFromTime)->where('status', '>', 0);
$startFromCart = $validCarts->count() ? $validCarts->orderBy('time', 'asc')->first() : null;

// Get the carts
if ($validCarts->count()) {
	$filteredOrders = Order::join('carts', 'orders.cart_id', '=', 'carts.id')
		->join('addresses', 'addresses.cart_id', '=', 'carts.id')
		->join('stores', 'stores.user_id', '=', 'orders.seller_id')
		->join('items', 'items.id', '=', 'orders.product_id')
		->join('product_types', 'product_types.id', '=', 'orders.type_id')
		->where('carts.status', '>', 0)
		->where('carts.time', '>=', $startFromTime)
		->select('carts.id as cart_id', 'carts.time as confirmedTime', 'orders.*', 'stores.name as storeName', 'addresses.full_name as customerName', 'items.name as productName', 'product_types.name as productTypeName')
		->get();

	// Get the first cart
	$firstCart = $filteredOrders->sortBy('confirmedTime')->first();
}

/**
 * Update the log start date
 */
if (isset($_POST['clean_log'])) {
	$startFromConfig->value = date('Y-m-d H:i:s');
	$startFromConfig->save();

	redirect_old();
}

if (!empty($_POST['start_cart_id'])) {
	$startFromId = (int) $_POST['start_cart_id'];
	$startFromCart = Cart::find($startFromId);

	if ($startFromCart) {
		$startFromConfig->value = $startFromCart->time;
		$startFromConfig->save();
	}

	redirect_old();
}
