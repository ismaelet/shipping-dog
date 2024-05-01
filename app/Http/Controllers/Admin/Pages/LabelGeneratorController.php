<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\ShippingMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Shuchkin\SimpleXLSXGen;

class LabelGeneratorController extends Controller {

	/**
	 * Display the page
	 */
	public function index(Request $request): View {
		$carts = Cart::where('status', 1)
			->whereNotIn('shipping_method_id', ShippingMethod::NO_DELIVERY_SHIPPING_METHODS);

		if ($carts->count()) {
			$firstCart = (clone $carts)->oldest()->first();
			$lastCart = $carts->latest()->first();
		} else {
			$firstCart = null;
			$lastCart = Cart::where('status', '>', 0)
				->whereNotIn('shipping_method_id', ShippingMethod::NO_DELIVERY_SHIPPING_METHODS)
				->orderByDesc('created_at')
				->first();
		}

		return view('admin.labels.generate')->with([
			'firstCart' => $firstCart,
			'lastCart' => $lastCart,
		]);
	}

	/**
	 * Generate the labels
	 */
	public function generate(Request $request): RedirectResponse {
		// submit
		if ($request->submit == 'generate') {
			$rows = [];

			$firstSelectedCart = Cart::find($request->first_cart_id);
			$lastSelectedCart = Cart::find($request->last_cart_id);

			if ($firstSelectedCart && $lastSelectedCart) {

				$addresses = Address::join('carts', 'addresses.cart_id', '=', 'carts.id')
					->where('carts.status', '>', 0)
					->whereBetween('carts.created_at', [$firstSelectedCart->created_at, $lastSelectedCart->created_at])
					->oldest('carts.created_at')
					->get('addresses.*');

				foreach ($addresses as $address) {
					$rows[] = [
						$address->full_name,
						$address->address . ', ' . $address->number,
						$address->city,
						$address->zip,
						$address->region,
						$address->email,
						'Tel: ' . $address->phone,
						1,
						1,
						$address->exchange ? 24 : ''
					];
				}

				$xlsx = SimpleXLSXGen::fromArray($rows);
				$xlsx->downloadAs('Etichette-Corriere.xlsx');
			}
		}

		if ($request->submit == 'process_all') {
			// Cart::where('status', 1)->where('shipping_method_id', '!=', ShippingMethod::NO_DELIVERY_SHIPPING_METHOD_ID)->update(['status' => 2]);

			return redirect()->back()->with('success', 'Tutti gli ordini sono stati processati');
		}
	}
}
