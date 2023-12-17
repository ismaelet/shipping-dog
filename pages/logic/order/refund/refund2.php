<?php

$stepId = 2;

include_once('setup.php');

$res_sel = DB::select('* FROM orders WHERE cartID=?', [$id]);

if (isset($_POST['submit'])) {
	if (isset($_POST['ordID'])) {
		foreach ($_POST['ordID'] as $ordID) {
			$type = $_POST['type' . $ordID];

			if (DB::exists('refunds_ord WHERE ordID=?', [$ordID])) DB::update('refunds_ord SET type=? WHERE ordID=?', [$type, $ordID]);
			else DB::add('refunds_ord (ordID, cartID, type) VALUES (?, ?, ?)', [$ordID, $id, $type]);
		}

		DB::update('refunds SET status=3 WHERE cartID=?', [$id]);

		redirect('/refund?step=3');
	} else {
		$error = 'empty';
	}
}

if (isset($_POST['cancel'])) {
	DB::delete('refunds_ord WHERE cartID=?', [$id]);
	DB::update('refunds SET status=1 WHERE cartID=?', [$id]);

	redirect('/refund');
}
