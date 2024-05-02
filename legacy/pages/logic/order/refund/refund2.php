<?php

use App\Models\Ecommerce\Change;

$viewStep = 2;

include_once('setup.php');

$order_items = $changeRequest->old_cart->order_items;

if (isset($_POST['submit'])) {
	if (isset($_POST['order_id'])) {
		foreach ($_POST['order_id'] as $orderItemKey => $orderItemId) {
			$is_change = $_POST['is_change'][$orderItemKey];

			Change::create([
				'old_order_id' => $orderItemId,
				'change_request_id' => $changeRequest->id,
				'is_change' => $is_change,
			]);
		}

		$changeRequest->step = $viewStep + 1;
		$changeRequest->save();

		redirect_old('/refund?step=' . $changeRequest->step);
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

	redirect_old('/refund');
}
