<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Models\Framework\Configuration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderLogController extends Controller {

	/**
	 * Display the page
	 */
	public function index(): View {
		// fetch date
		$startFromTime = Configuration::where('name', 'order_log_start_from')->value('value');

		$orders = Order::where('created_at', '>=', $startFromTime)
			->where('status', '>', EcommerceHelper::statusFromTag('received'))
			->with('user', 'user.seller', 'address', 'shipping_method', 'coupon', 'change_request', 'order_items', 'order_items.product', 'order_items.product.type', 'order_items.product.type.sizes', 'order_items.product.type.colors', 'order_items.color', 'order_items.size', 'order_items.position', 'order_items.logo')
			->oldest()
			->get();

		return view('admin.log-orders.index')->with([
			'orders' => $orders,
			'ordersFilter' => $orders->first() ? [['orders.created_at', '>=', $orders->first()->created_at]] : [],
			'startFromTime' => $startFromTime,
		]);
	}

	/**
	 * Update the log interval
	 */
	public function update(Request $request): RedirectResponse {
		$startFromConfig = Configuration::where('name', 'order_log_start_from')->first();

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
