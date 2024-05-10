<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Shuchkin\SimpleXLSXGen;

class LabelGeneratorController extends Controller {

	/**
	 * * Action: Index
	 */
	public function index(): View {
		// Get the orders to be generated
		$orders = Order::unlabelled();

		// Get the order count
		$orderCount = $orders->count();

		// Get the first order to be generated
		$defaultOrder = $orders->first();

		return view('admin.labels.generate', compact('defaultOrder', 'orderCount'));
	}

	/**
	 * * Custom Action: Download
	 */
	public function download(): RedirectResponse|bool {
		$rows = [];

		$orders = Order::unlabelled();

		if (!$orders) {
			return redirect()->back()->with('error', 'Nessun ordine trovato');
		}

		foreach ($orders as $order) {
			$address = $order->address;

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

		$orders->each(function ($order) {
			$order->update(['status' => EcommerceHelper::statusFromTag('label-ready')]);
		});

		return $xlsx->downloadAs('Etichette-Corriere.xlsx');
	}
}
