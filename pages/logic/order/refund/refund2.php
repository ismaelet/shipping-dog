<?php

use App\Models\Ecommerce\Change;

$viewStep = 2;

include_once('setup.php');

$orders = $changeRequest->cart->orders;

if (isset($_POST['submit'])) {
	if (isset($_POST['order_id'])) {
		foreach ($_POST['order_id'] as $orderKey => $orderId) {
			$is_change = $_POST['is_change'][$orderKey];

			Change::create([
				'order_id' => $orderId,
				'change_request_id' => $changeRequest->id,
				'is_change' => $is_change,
			]);
		}

		$changeRequest->step = $viewStep + 1;
		$changeRequest->save();

		redirect('/refund?step=' . $changeRequest->step);
	} else {
		$error = 'empty';
	}
}

if (isset($_POST['cancel'])) {
	$changeRequest->changes;

	foreach ($changeRequest->changes as $change) {
		$change->delete();
	}

	$changeRequest->delete();

	redirect('/refund');
}
