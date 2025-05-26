<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductLogController extends Controller {

	/**
	 * Display the page
	 */
	public function index(): View {
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
