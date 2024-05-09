<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Gls\GlsController;
use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\ShippingMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Shuchkin\SimpleXLSXGen;

class LabelGeneratorController extends Controller {

	/**
	 * Display the page
	 */
	public function index(): View {
		$orderItems = Order::where('status', EcommerceHelper::statusFromTag('confirmed'))
			->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels());

		if ($orderItems->count()) {
			$firstCart = (clone $orderItems)->oldest()->first();
			$lastCart = $orderItems->latest()->first();
		} else {
			$firstCart = null;
			$lastCart = Order::where('status', '>', 0)
				->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
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
	public function generate(Request $request): RedirectResponse|bool {
		// submit
		if ($request->submit == 'generate') {
			// $response = GlsController::addParcel($request);
			// dd($response);

			$rows = [];

			$firstSelectedCart = Order::find($request->first_order_id);
			$lastSelectedCart = Order::find($request->last_order_id);

			if ($firstSelectedCart && $lastSelectedCart) {

				if ($firstSelectedCart->created_at > $lastSelectedCart->created_at) {
					return redirect()->back()->with('error', 'Il primo ordine deve essere precedente al secondo');
				}

				$addresses = Address::join('orders', 'addresses.order_id', '=', 'orders.id')
					->where('orders.status', '>', 0)
					->whereBetween('orders.created_at', [$firstSelectedCart->created_at, $lastSelectedCart->created_at])
					->oldest('orders.created_at')
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
				return $xlsx->downloadAs('Etichette-Corriere.xlsx');
			}
		}

		if ($request->submit == 'process_all') {
			Order::where('status', EcommerceHelper::statusFromTag('confirmed'))
				->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
				->update(['status' => EcommerceHelper::statusFromTag('label-ready')]);

			return redirect()->back()->with('success', 'Tutti gli ordini sono stati processati');
		}
	}
}
