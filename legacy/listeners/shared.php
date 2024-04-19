<?php

use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Cart;

$cart = Cart::find($cart_id);

// Add the transaction ID
if (isset($_POST['txn_id'])) {
	Address::where('cart_id', $cart_id)->update(['txnID' => $_POST['txn_id']]);
}

// Confirm the cart
$cart->confirm();
