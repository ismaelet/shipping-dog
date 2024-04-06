<?php

use Ecommerce\Order;

use function Ecommerce\cart;

// setup variables
$skip = true;

// skip when there are no active promotions
if ($skip || isset($_POST['submit'])) {
	cart()->advanceAndRedirect();
}

// add item to cart
if (isset($_POST['add-to-cart'])) {

	// fetch order data
	$config['itemId'] = $_POST['id'];
	$config['quantity'] = $_POST['quantity'];
	$config['colorId'] = $_POST['color'];
	$config['sizeId'] = $_POST['size'];

	Order::add($config);

	redirect('/cart?step=2');
}
