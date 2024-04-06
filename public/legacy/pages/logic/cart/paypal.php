<?php

use function Ecommerce\cart;

global $user;

// setup page
$cart = cart();
$buyer = $cart->customer;

$_SESSION['payment_type'] = 'buy';
$_SESSION['payment_cartID'] = $cart->id;
