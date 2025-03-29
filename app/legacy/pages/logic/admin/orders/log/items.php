<?php

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use App\Models\Framework\Configuration;

// Get the date from which to start the log
$startFromConfig = Configuration::where('name', 'product_log_start_from')->first();
$startFromTime = $startFromConfig->value;
$validorders = Order::where('time', '>=', $startFromTime)->where('status', '>', 0);
$startFromCart = $validorders->count() ? $validorders->orderBy('time', 'asc')->first() : null;

// Get the orders
if ($validorders->count()) {
	$filteredorder_items = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
		->join('addresses', 'addresses.order_id', '=', 'orders.id')
		->join('stores', 'stores.user_id', '=', 'order_items.seller_id')
		->join('items', 'products.id', '=', 'order_items.product_id')
		->join('product_types', 'product_types.id', '=', 'order_items.type_id')
		->where('orders.status', '>', 0)
		->where('orders.time', '>=', $startFromTime)
		->select('orders.id as order_id', 'orders.time as confirmedTime', 'order_items.*', 'stores.name as storeName', 'addresses.full_name as customerName', 'products.name as productName', 'product_types.name as productTypeName')
		->get();

	// Get the first cart
	$firstCart = $filteredorder_items->sortBy('confirmedTime')->first();
}

/**
 * Update the log start date
 */
if (isset($_POST['clean_log'])) {
	$startFromConfig->value = date('Y-m-d H:i:s');
	$startFromConfig->save();

	redirect_old();
}

if (!empty($_POST['start_order_id'])) {
	$startFromId = (int) $_POST['start_order_id'];
	$startFromCart = Order::find($startFromId);

	if ($startFromCart) {
		$startFromConfig->value = $startFromCart->time;
		$startFromConfig->save();
	}

	redirect_old();
}
