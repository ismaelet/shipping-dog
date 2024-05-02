<?php

use Legacy\Classes\Ecommerce\OrderItem as LegacyOrderItem;

// setup variables
$skip = true;

// skip when there are no active promotions
if ($skip || isset($_POST['submit'])) {
	cart()->advance();

	redirect_old('/cart/payment');
}

// add item to cart
if (isset($_POST['add-to-cart'])) {
}
