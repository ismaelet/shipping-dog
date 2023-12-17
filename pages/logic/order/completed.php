<?php

use Ecommerce\Cart;

$cartId = $_GET['id'] ?? null;
$customerId = $_GET['buyer'] ?? user()->buyerId;
$cart = new Cart($cartId);

View::setTitle('Ordine ' . $cart->idCode);

if (!$cart->id  || !$cart->statusId || $cart->customerId != $customerId) {
	redirect('/order/status');
}

$cart->sendStatusEmail();
