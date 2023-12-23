<?php

use App\Models\Ecommerce\Order;
use App\Models\Framework\Configuration;

// Get the date from which to start the log
$date = Configuration::where('name', 'log_items_date')->first()->value;

// Get the types
$filteredOrders = Order::join('carts', 'orders.cartID', '=', 'carts.ID')
	->join('addresses', 'addresses.cartID', '=', 'carts.ID')
	->join('stores', 'stores.userID', '=', 'orders.sellerId')
	->join('items', 'items.ID', '=', 'orders.itemID')
	->join('items_types', 'items_types.ID', '=', 'orders.typeID')
	->where('carts.status', '>', 0)
	->where('carts.time', '>', $date)
	->select('carts.ID as cartId', 'orders.*', 'stores.name as storeName', 'addresses.aname as customerName', 'items.name as productName', 'items_types.name as productTypeName')
	->get();
