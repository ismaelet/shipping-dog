<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use App\Models\Framework\Configuration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductLogController extends Controller {

	/**
	 * Display the page
	 */
	public function index(): View {

		// Get the date from which to start the log
		$startFromTime = Configuration::where('name', 'product_log_start_from')->value('value');
		$validorders = Order::where('created_at', '>=', $startFromTime)->where('status', '>', 0);
		$startFromCart = $validorders->exists() ? $validorders->oldest()->first() : null;

		// Get the orders
		if ($startFromCart) {
			$filteredorder_items = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
				->join('addresses', 'addresses.order_id', '=', 'orders.id')
				->join('stores', 'stores.user_id', '=', 'order_items.seller_id')
				->join('items', 'items.id', '=', 'order_items.product_id')
				->join('product_types', 'product_types.id', '=', 'order_items.type_id')
				->where('orders.status', '>', 0)
				->where('orders.created_at', '>=', $startFromTime)
				->select('orders.id as order_id', 'orders.created_at as confirmedTime', 'order_items.*', 'stores.name as storeName', 'addresses.full_name as customerName', 'items.name as productName', 'product_types.name as productTypeName')
				->get();

			// Get the first cart
			$firstCart = $filteredorder_items->sortBy('confirmedTime')->first();
		}

		return view('admin.log-products.index')->with([
			'startFromCart' => $startFromCart,
			'firstCart' => $firstCart ?? null,
			'validorders' => $validorders,
			'filteredorder_items' => $filteredorder_items ?? null,
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
		if ($request->start_order_id) {
			$startFromId = (int) $request->start_order_id;
			$startFromCart = Order::find($startFromId);

			if ($startFromCart) {
				$startFromConfig->value = $startFromCart->created_at;
				$startFromConfig->save();
			}

			return redirect()->back();
		}

		return redirect()->back();
	}
}
