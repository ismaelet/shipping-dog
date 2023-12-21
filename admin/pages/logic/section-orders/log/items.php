<?php

use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Seller;
use App\Models\Framework\Configuration;

// Get the date from which to start the log
$date = Configuration::where('name', 'log_items_date')->first()->value;

// Get the sellers that have sold orders
$sellers = Seller::join('orders', 'stores.userID', '=', 'orders.sellerId')
	->join('carts', 'orders.cartID', '=', 'carts.ID')
	->where('carts.status', '>', 0)
	->where('carts.time', '>', $date)
	->distinct()
	->orderBy('stores.name', 'asc')
	->get('stores.*');

// Get the carts
$carts = Cart::join('orders', 'carts.ID', '=', 'orders.cartID')
	->where('carts.status', '>', 0)
	->where('carts.time', '>', $date)
	->orderBy('carts.ID', 'asc');

// Get the first cart
$firstCart = $carts->get('carts.ID')->first();


// // Get the sellers that have sold orders
// $sellers = Seller::join('orders', 'stores.userID', '=', 'orders.sellerId')
// 	->join('carts', 'orders.cartID', '=', 'carts.ID')
// 	->where('carts.status', '>', 0)
// 	->where('carts.time', '>', $date)
// 	->distinct()
// 	->get(['stores.userID', 'stores.name']);
