<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Order;
use App\Models\Framework\Configuration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderLogController extends Controller {

	/**
	 * Display the page
	 */
	public function index(Request $request): View {
		// fetch date
		$startFromTime = Configuration::where('name', 'log_date')->value('value');
		$carts = Cart::where('created_at', '>=', $startFromTime)
			->where('status', '>', 0)
			->oldest()
			->get();

		return view('admin.log-orders.index')->with([
			'carts' => $carts,
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
