<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Legacy\Classes\Ecommerce\Order as LegacyOrder;

class OrderItemController extends Controller {

	/**
	 * * Create a new order
	 */
	public function store(Request $request): RedirectResponse {
		// Fetch the product
		$product = Product::find($request->product_id);

		// Set the default position ID
		if (!$request->position_id) {
			$request->merge(['position_id', $product->type->positions()->first()->id]);
		}

		// Set the default quantity
		if (!$request->quantity) {
			$request->merge(['quantity', 1]);
		}

		// Validate the request
		$request->validate([
			'product_id' => ['required', 'integer'],
			'color_id' => ['required', 'integer'],
			'size_id' => ['required', 'integer'],
			'position_id' => ['integer'],
			'gender_id' => ['integer'],
			'logo_id' => ['integer'],
			'quantity' => ['integer'],
		]);

		// Set the logo and size to calculate an accurate price
		$product->logo_id = $request->logo_id ?: 0;
		$product->size_id = $request->size_id;

		// Get the cart
		if (cart()) {
			$order = cart();
		} else {
			if (Auth::check()) {
				// If the user is logged in
				$order = Order::create(['user_id' => Auth::id()]);
			} else {
				// If the user is a guest
				$order = Order::create();

				session(['cart_id' => $order->id]);
			}
		}

		// Create the order
		OrderItem::create([
			...$request->all(),
			'user_id' => Auth::id(),
			'order_id' => $order->id,
			'seller_id' => $product->user_id,
			'design_id' => $product->design_id,
			'type_id' => $product->type->id,
			'fullProductPrice' => $product->fullPrice(),
			'price_disc' => $product->customerDiscount(),
			'productGain' => $product->sellerGain()
		]);

		// Update the cart data
		$order->updateOrderItems();

		// ! Legacy support
		$LegacyOrder = new LegacyOrder($order->id);

		if ($LegacyOrder->couponId) {
			$couponId = $LegacyOrder->couponId;
			$LegacyOrder->removeCoupon();
			$LegacyOrder->addCoupon($couponId);
		}
		// ! Legacy support

		return to_route('cart');
	}
}
