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
	$filteredOrders = Order::join('carts', 'orders.cartID', '=', 'carts.ID')
		->join('addresses', 'addresses.cartID', '=', 'carts.ID')
		->join('stores', 'stores.userID', '=', 'orders.sellerId')
		->join('items', 'items.ID', '=', 'orders.itemID')
		->join('items_types', 'items_types.ID', '=', 'orders.typeID')
		->where('carts.status', '>', 0)
		->where('carts.time', '>=', $startFromTime)
		->select('carts.ID as cartId', 'carts.time as confirmedTime', 'orders.*', 'stores.name as storeName', 'addresses.aname as customerName', 'items.name as productName', 'items_types.name as productTypeName')
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

	redirect();
}

if (!empty($_POST['start_cart_id'])) {
	$startFromId = (int) $_POST['start_cart_id'];
	$startFromCart = Cart::find($startFromId);

	if ($startFromCart) {
		$startFromConfig->value = $startFromCart->time;
		$startFromConfig->save();
	}

	redirect();
}
