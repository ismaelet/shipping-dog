<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\OrderItem;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller {

	/**
	 * Handled by the Middleware
	 */
	public function index(): void {
	}

	/**
	 * Remove an item from the cart
	 */
	public function removeOrderItem(OrderItem $orderItem): RedirectResponse {
		if ($orderItem) {
			$orderItem->delete();
		}

		return back()
			->with([
				'message' => 'L\'articolo è stato rimosso dal carrello.',
				'message_type' => 'success',
			]);
	}
}
