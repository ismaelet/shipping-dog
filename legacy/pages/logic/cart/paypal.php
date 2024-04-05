<?php



global $user;

// setup page
$cart = cart_old();
$buyer = $cart->customer;

$_SESSION['payment_type'] = 'buy';
$_SESSION['payment_cartID'] = $cart->id;
