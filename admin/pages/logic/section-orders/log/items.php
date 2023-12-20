<?php

use Ecommerce\Cart;

$logEntries = [];

// theme
StyleLoader::add('section-orders/log/log', 'page');

// setup variables
$first = false;

// clean log
if (isset($_POST['submit'])) DB::update('variables SET value=NOW() WHERE name = ?', ['log_items_date']);

// get last starting date
$date = DB::find('value FROM variables WHERE name = ?', ['log_items_date']);

// fetch first order
$firstID = DB::find('ID FROM carts WHERE status>0 AND time>TIMESTAMP(?) ORDER BY time ASC', [$date]);

if ($firstID) {
	$address = DB::row('cartID, aname FROM addresses WHERE cartID = ?', [$firstID]);
}

// fetch orders number
$ordersN = DB::sum('orders.quantity FROM orders INNER JOIN carts ON orders.cartID = carts.ID WHERE carts.status > 0 AND carts.time > TIMESTAMP(?)', [$date]);

// fetch shops that have orders
$carts = DB::select('ID FROM carts WHERE status > 0 AND time > TIMESTAMP(?)', [$date]);

while ($cart = $carts->fetch_assoc()) {
	$cart = new Cart($cart['ID']);
	$new = true;

	foreach ($cart->orders as $order) {
		$orderName = $order->adminDescription;
		$sellerName = strtoupper($order->seller->name);
		$designName = $order->designId ? $order->design->fullName : 'Prodotto Personalizzato';

		if ($order->inventory == 1) $orderName = '!! UGUALE !! ' . $orderName;
		if ($order->inventory == 2) $orderName = '!! SIMILE !! ' . $orderName;

		if (isset($logEntries[$sellerName][$designName][$orderName])) {
			$logEntries[$sellerName][$designName][$orderName] += $order->quantity;
		} else {
			$logEntries[$sellerName][$designName][$orderName] = $order->quantity;
		}
	}
}

ksort($logEntries);
