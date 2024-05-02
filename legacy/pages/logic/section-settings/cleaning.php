<?php

use Legacy\Classes\Ecommerce\OrderItem;

// counts
$orderCount = \Legacy\System\Classes\LegacyDB::count(Order::TABLE . ' WHERE status = 0 AND time < NOW() - INTERVAL 30 DAY');

// panels
$panels = [
	['title' => 'carrelli non completati', 'subtitle' => 'da 30 giorni', 'target' => 'orders', 'count' => $orderCount],
];

// cleaning
if (isset($_POST['orders'])) {
	$orderItems = \Legacy\System\Classes\LegacyDB::select(Order::INDEX . ' FROM ' . Order::TABLE . ' WHERE status = 0 AND time < NOW() - INTERVAL 30 DAY');

	while ($orderRow = $orderItems->fetch_assoc()) {
		$order = new Cart($orderRow[Order::INDEX]);
		$order->delete();
	}
}
