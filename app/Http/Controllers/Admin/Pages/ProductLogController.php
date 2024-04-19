<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Order;
use App\Models\Framework\Configuration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductLogController extends Controller {

	/**
	 * Display the page
	 */
	public function index(Request $request): View {

		// Get the date from which to start the log
		$startFromTime = Configuration::where('name', 'product_log_start_from')->value('value');
		$validCarts = Cart::where('time', '>=', $startFromTime)->where('status', '>', 0);
		$startFromCart = $validCarts->count() ? $validCarts->oldest()->first() : null;

		// Get the carts
		if ($validCarts->count()) {
			$filteredOrders = Order::join('carts', 'orders.cart_id', '=', 'carts.id')
				->join('addresses', 'addresses.cart_id', '=', 'carts.id')
				->join('stores', 'stores.user_id', '=', 'orders.seller_id')
				->join('items', 'items.id', '=', 'orders.product_id')
				->join('items_types', 'items_types.id', '=', 'orders.type_id')
				->where('carts.status', '>', 0)
				->where('carts.time', '>=', $startFromTime)
				->select('carts.id as cart_id', 'carts.time as confirmedTime', 'orders.*', 'stores.name as storeName', 'addresses.full_name as customerName', 'items.name as productName', 'items_types.name as productTypeName')
				->get();

			// Get the first cart
			$firstCart = $filteredOrders->sortBy('confirmedTime')->first();
		}

		return view('admin.log-products.index')->with([
			'startFromCart' => $startFromCart,
			'firstCart' => $firstCart ?? null,
			'validCarts' => $validCarts,
			'filteredOrders' => $filteredOrders ?? null,
		]);
	}

	/**
	 * Update the log interval
	 */
	public function update(Request $request): RedirectResponse {
		$startFromConfig = Configuration::where('name', 'product_log_start_from')->first();

		/**
		 * Clean the log
		 */
		if ($request->submit == 'clean_log') {
			$startFromConfig->value = date('Y-m-d H:i:s');
			$startFromConfig->save();

			return redirect()->back();
		}

		/**
		 * Start from a specific cart
		 */
		if ($request->start_cart_id) {
			$startFromId = (int) $request->start_cart_id;
			$startFromCart = Cart::find($startFromId);

			if ($startFromCart) {
				$startFromConfig->value = $startFromCart->created_at;
				$startFromConfig->save();
			}

			return redirect()->back();
		}
	}
}
