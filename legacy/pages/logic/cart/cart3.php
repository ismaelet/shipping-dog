<?php

use Ecommerce\Order;



// setup variables
$skip = true;

// skip when there are no active promotions
if ($skip || isset($_POST['submit'])) {
	cart_old()->advanceAndredirect_old();
}

// add item to cart
if (isset($_POST['add-to-cart'])) {

	// fetch order data
	$config['itemId'] = $_POST['id'];
	$config['quantity'] = $_POST['quantity'];
	$config['colorId'] = $_POST['color'];
	$config['sizeId'] = $_POST['size'];

	Order::add($config);

	redirect_old('/cart?step=2');
}
