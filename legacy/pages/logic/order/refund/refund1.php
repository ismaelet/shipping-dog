<?php

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\ChangeRequest;

$viewStep = 1;

include_once('setup.php');

/**
 * The form submit
 */
if (isset($_POST['submit'])) {

	// Get the post data
	$order_id = (int) trim($_POST['order_id'], '#');
	$email = strtolower(trim($_POST['email']));

	// Check if the cart exists
	$order = Order::join('addresses', 'addresses.order_id', '=', 'orders.id')
		->where('orders.id', $order_id)
		->where('addresses.email', $email)
		->first();

	if ($order) {
		$changeRequest = ChangeRequest::where('old_order_id', $order_id)->first();

		if (!$changeRequest) {
			// Create the change request
			$changeRequest = ChangeRequest::create([
				'old_order_id' => $order_id,
				'step' => 2,
			]);
		}

		$_SESSION['changeRequestId'] = $changeRequest->id;

		redirect_old('/refund?step=' . $changeRequest->step);
	} else {
		$error = 'match';
	}
}

/**
 * The form inputs
 */
$inputs = [
	[
		['name' => 'email', 'text' => 'Indirizzo e-email', 'required' => true],
	],
	[
		['name' => 'order_id', 'text' => 'Numero ordine', 'required' => true],
		['type' => 'error', 'text' => 'Nessun ordine trovato', 'error' => 'match'],
	],
];
