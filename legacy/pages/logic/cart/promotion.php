<?php


use function Ecommerce\cart_id;
use function Ecommerce\customerId;

$cart = \App\Helpers\App::cartOld();

$_SESSION['payment_type'] = 'buy';
$_SESSION['payment_cart_id'] = $cart_id = $cart->id;

$user_idID = customerId();

ob_start();

require_once(SERVER_ROOT . '/listeners/shared.php');

ob_end_clean();

redirect_old($cart->url);
