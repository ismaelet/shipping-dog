<?php

$stepId = 3;

include_once('setup.php');

//remove an article
if (isset($_POST['remove'])) {

	$ordID = $_POST['remove'];

	DB::delete('refunds_ord WHERE ordID = ?', [$ordID]);

	if (!DB::exists('refunds_ord WHERE cartID = ?', [$id])) DB::update('refunds SET status=2 WHERE cartID = ?', [$id]);

	redirect();
}

//cancel the request
if (isset($_POST['cancel'])) {
	DB::update('refunds SET status = 1, processed=0 WHERE cartID = ?', [$id]);
	DB::delete('refunds_ord WHERE cartID = ?', [$id]);

	unset($_SESSION['refID']);

	redirect('/refund');
}

//go to payment
if (isset($_POST['pay']) || isset($_POST['free']) || isset($_POST['refund'])) {

	$res_ref = DB::select('ordID FROM refunds_ord WHERE type=1 AND cartID = ?', [$id]);

	while ($row_ref = $res_ref->fetch_assoc()) {

		$ordID = $row_ref['ordID'];

		$row_ord = DB::row('sizeID, colorID FROM orders ', [$ordID]);

		$color_old = $row_ord['colorID'];
		$color_new = $row_ord['colorID'];
		$size_old = $row_ord['sizeID'];
		$size_new = $_POST['size_new' . $ordID];

		DB::update('refunds_ord SET size_new = ?, size_old = ?, color_new = ?, color_old = ? WHERE ordID = ?', [$size_new, $size_old, $color_new, $color_old, $ordID]);
	}

	if (isset($_POST['pay'])) {
		redirect('/refund/paypal');
	} else {
		DB::update('refunds SET status=4, payed=1 WHERE cartID = ?', [$id]);

		$_SESSION['payment_type'] = 'change';
		$_SESSION['payment_cartID'] = $id;

		redirect('/cart/success');
	}
}
