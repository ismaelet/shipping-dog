<?php



global $user;

// setup page
$cart = \App\Helpers\App::cartOld();
$user_id = $cart->customer;

$_SESSION['payment_type'] = 'buy';
$_SESSION['payment_cart_id'] = $cart->id;
