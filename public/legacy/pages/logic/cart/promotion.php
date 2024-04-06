<?php

use function Ecommerce\cart;
use function Ecommerce\cartId;
use function Ecommerce\customerId;

$cart = cart();

$_SESSION['payment_type'] = 'buy';
$_SESSION['payment_cartID'] = $cartID = $cart->id;

$buyerID = customerId();

ob_start();

require_once(SERVER_ROOT . '/listeners/shared.php');

ob_end_clean();

redirect($cart->url);
