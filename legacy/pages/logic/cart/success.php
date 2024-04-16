<?php

global $con;

$id = 0;

if (isset($_SESSION['payment_cart_id'])) $id = $_SESSION['payment_cart_id'];

//process normal payment
if (isset($_SESSION['payment_type']) && $_SESSION['payment_type'] == 'buy') {

	if (DBOld::exists('carts WHERE id = ? AND (step=3 OR step=4) AND (status=0 OR status = 1)', [$id])) {

		DBOld::update('carts SET step=5, status = 1 WHERE id = ?', [$id]);

		$_SESSION['completed'] = true;

		redirect_old('/order?id=' . $id);
	} else redirect_old('/cart');

	//process change
} else if (isset($_SESSION['payment_type']) && $_SESSION['payment_type'] == 'change') {

	if (DBOld::exists('refunds WHERE cart_id = ? AND (status=3 OR status=4)', [$id])) {

		DBOld::update('refunds SET status=4, payed=1 WHERE cart_id = ?', [$id]);

		redirect_old('/refund?step=4');
	} else redirect_old('/refund');

	//invalid session
} else redirect_old('/cart');
