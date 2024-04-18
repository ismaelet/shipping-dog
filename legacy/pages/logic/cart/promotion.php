<?php

$_SESSION['payment_type'] = 'buy';
$_SESSION['payment_cart_id'] = $cart_id = cart()->id;

ob_start();

require_once(SERVER_ROOT . '/listeners/shared.php');

ob_end_clean();

redirect_old($cart->url);
