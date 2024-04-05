<?php

use Ecommerce\Cart;

// counts
$cartCount = DBOld::count(Cart::TABLE . ' WHERE status = 0 AND time < NOW() - INTERVAL 30 DAY');

// panels
$panels = [
	['title' => 'carrelli non completati', 'subtitle' => 'da 30 giorni', 'target' => 'carts', 'count' => $cartCount],
];

// cleaning
if (isset($_POST['carts'])) {
	$carts = DBOld::select(Cart::INDEX . ' FROM ' . Cart::TABLE . ' WHERE status = 0 AND time < NOW() - INTERVAL 30 DAY');

	while ($cartRow = $carts->fetch_assoc()) {
		$cart = new Cart($cartRow[Cart::INDEX]);
		$cart->delete();
	}
}
