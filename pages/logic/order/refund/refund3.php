<?php

use App\Models\Ecommerce\Change;

$viewStep = 3;

include_once('setup.php');

/**
 * Remove a change
 */
if (isset($_POST['remove'])) {
	$changeId = $_POST['remove'];
	$change = Change::find($changeId);
	$change->delete();

	if (!$changeRequest->changes()->count()) {
		return_to_previous_step($changeRequest);
	}

	redirect();
}

/**
 * Go to payment
 */
if (isset($_POST['pay']) || isset($_POST['skip_payment']) || isset($_POST['refund'])) {

	$changeRequest->step = $changeRequest->step + 1;
	$changeRequest->save();

	// Store the new size
	foreach ($changeRequest->realChanges as $change) {
		$newSizeId = $_POST[$change->id . '_new_size_id'];

		$change->old_size_id = $change->old_order->sizeID;
		$change->new_size_id = $newSizeId;
		$change->save();
	}

	if (isset($_POST['pay'])) {
		// Redirect to PayPal
		redirect('/refund/paypal');
	} else if (isset($_POST['skip_payment'])) {
		// Mark the change request as payed (used by admins)
		$changeRequest->payed = true;
		$changeRequest->save();

		redirect('/refund?step=' . $changeRequest->step);
	}
}
