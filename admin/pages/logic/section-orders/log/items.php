<?php

use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Order;
use App\Models\Framework\Configuration;

// Get the date from which to start the log
$startFromConfig = Configuration::where('name', 'product_log_start_from_id')->first();
$startFromId = $startFromConfig->value;
$startFromCart = Cart::find($startFromId);
$validCarts = Cart::where('time', '>', $startFromCart->time)->where('status', '>', 0);
$date = $validCarts->count() ? $validCarts->first()->time : null;

// Get the types
if ($validCarts->count()) {
	$filteredOrders = Order::join('carts', 'orders.cartID', '=', 'carts.ID')
		->join('addresses', 'addresses.cartID', '=', 'carts.ID')
		->join('stores', 'stores.userID', '=', 'orders.sellerId')
		->join('items', 'items.ID', '=', 'orders.itemID')
		->join('items_types', 'items_types.ID', '=', 'orders.typeID')
		->where('carts.status', '>', 0)
		->where('carts.time', '>=', $date)
		->select('carts.ID as cartId', 'carts.time as confirmedTime', 'orders.*', 'stores.name as storeName', 'addresses.aname as customerName', 'items.name as productName', 'items_types.name as productTypeName')
		->get();

	// Get the first cart
	$firstCart = $filteredOrders->sortBy('confirmedTime')->first();
}

/**
 * Update the log start date
 */
if (isset($_POST['clean-log'])) {
	$lastCompletedCart = Cart::where('status', '>', 0)->orderBy('time', 'desc')->first();
	$startFromConfig->value = $lastCompletedCart->ID;
	$startFromConfig->save();

	redirect();
}

if (!empty($_POST['last_cart_id'])) {
	$startFromConfig->value = (int) $_POST['last_cart_id'];
	$startFromConfig->save();

	redirect();
}
