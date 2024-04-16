<?php

use Ecommerce\Cart;

// setup variables
$cart = new Cart($cart_id);
$codNoga = false;

// add paypal code
$txnID = isset($_POST['txn_id']) ? $_POST['txn_id'] : 0;

DBOld::update('addresses SET txnID = ? WHERE cart_id = ?', [$txnID, $cart->id]);

// PROCESS ORDERS
$cart->confirm();
