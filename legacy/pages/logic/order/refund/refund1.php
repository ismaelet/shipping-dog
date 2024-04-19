<?php

use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\ChangeRequest;

$viewStep = 1;

include_once('setup.php');

/**
 * The form submit
 */
if (isset($_POST['submit'])) {

	// Get the post data
	$cart_id = (int) trim($_POST['cart_id'], '#');
	$email = strtolower(trim($_POST['email']));

	// Check if the cart exists
	$cart = Cart::join('addresses', 'addresses.cart_id', '=', 'carts.id')
		->where('carts.id', $cart_id)
		->where('addresses.email', $email)
		->first();

	if ($cart) {
		$changeRequest = ChangeRequest::where('old_cart_id', $cart_id)->first();

		if (!$changeRequest) {
			// Create the change request
			$changeRequest = ChangeRequest::create([
				'old_cart_id' => $cart_id,
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
		['name' => 'cart_id', 'text' => 'Numero ordine', 'required' => true],
		['type' => 'error', 'text' => 'Nessun ordine trovato', 'error' => 'match'],
	],
];
