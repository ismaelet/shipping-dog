<?php

use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Order;

$order = Order::find($order_id);

// Add the transaction ID
if (isset($_POST['txn_id'])) {
	Address::where('order_id', $order_id)->update(['txnID' => $_POST['txn_id']]);
}

// Confirm the cart
$order->confirm();
