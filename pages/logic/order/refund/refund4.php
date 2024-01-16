<?php

use App\Models\Ecommerce\InventoryItem;

$viewStep = 4;

include_once('setup.php');

// Process the change request
if (!$changeRequest->processed && $changeRequest->payed) {

	// Process the changes in case of at least a real change
	if ($changeRequest->realChanges->count()) {
		// Duplicate the old cart
		$oldCart = $changeRequest->old_cart;
		$newCart = $oldCart->replicate();
		$newCart->quantity = $changeRequest->realChanges->count();
		$newCart->code = '';
		$newCart->status = 1;
		$newCart->shipping = 8;
		$newCart->credit_ship = 0;
		$newCart->tot = 0;
		$newCart->touch('time');
		$newCart->save();

		$changeRequest->new_cart_id = $newCart->ID;

		// Duplicate the address
		$address = $oldCart->address->replicate();
		$address->cartID = $newCart->ID;
		$address->save();

		// Process the individual changes
		foreach ($changeRequest->realChanges as $change) {
			$oldOrder = $change->old_order;

			// Add the old product to the inventory
			InventoryItem::create([
				'designId' => $oldOrder->designId,
				'typeId' => $oldOrder->typeID,
				'colorId' => $oldOrder->colorID,
				'sizeId' => $oldOrder->sizeID,
				'positionId' => $oldOrder->posID,
				'logoId' => $oldOrder->extID,
			]);

			// Duplicate the old order
			$newOrder = $oldOrder->replicate();
			$newOrder->cartID = $newCart->ID;
			$newOrder->sizeID = $change->new_size_id;
			$newOrder->fullProductPrice = 0;
			$newOrder->price_disc = 0;
			$newOrder->productGain = 0;
			$newOrder->gain_disc = 0;
			$newOrder->credit = 0;
			$newOrder->save();

			$change->new_order_id = $newOrder->ID;
		}
	}

	// Mark the change request as processed
	$changeRequest->processed = true;
	$changeRequest->save();
}
