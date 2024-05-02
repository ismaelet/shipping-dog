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
		$newOrder = $oldCart->replicate();
		$newOrder->quantity = $changeRequest->realChanges->count();
		$newOrder->code = '';
		$newOrder->status = 1;
		$newOrder->shipping = 8;
		$newOrder->credit_ship = 0;
		$newOrder->tot = 0;
		$newOrder->touch('time');
		$newOrder->save();

		$changeRequest->new_order_id = $newOrder->id;

		// Duplicate the address
		$address = $oldCart->address->replicate();
		$address->order_id = $newOrder->id;
		$address->save();

		// Process the individual changes
		foreach ($changeRequest->realChanges as $change) {
			$oldOrder = $change->old_order;

			// Add the old product to the inventory
			InventoryItem::create([
				'design_id' => $oldOrder->design_id,
				'type_id' => $oldOrder->type_id,
				'color_id' => $oldOrder->color_id,
				'size_id' => $oldOrder->size_id,
				'position_id' => $oldOrder->position_id,
				'logo_id' => $oldOrder->logo_id,
			]);

			// Duplicate the old order
			$newOrder = $oldOrder->replicate();
			$newOrder->order_id = $newOrder->id;
			$newOrder->size_id = $change->new_size_id;
			$newOrder->fullProductPrice = 0;
			$newOrder->price_disc = 0;
			$newOrder->productGain = 0;
			$newOrder->gain_disc = 0;
			$newOrder->credit = 0;
			$newOrder->save();

			$change->new_order_id = $newOrder->id;
		}
	}

	// Mark the change request as processed
	$changeRequest->processed = true;
	$changeRequest->save();
}
