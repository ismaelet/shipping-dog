<?php

namespace App\Http\Controllers\Services\Gls;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Services\GlsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GlsController extends Controller {

	public function addSingleParcel(Request $request, GlsService $glsService): RedirectResponse {
		$order = Order::find($request->order_id);

		$response = $glsService->addParcel($order);

		if (!$response) {
			return back()->with('add_single_parcel_error', 'Errore durante la trasmissione dell\'ordine ' . $order->id);
		}

		return back()->with('add_single_parcel_success', 'Spedizione trasmessa correttamente');
	}

	public function addAllParcels(GlsService $glsService): RedirectResponse {
		$orders = Order::unlabelled();

		$orders->each(function ($order) use ($glsService) {
			$response = $glsService->addParcel($order, true);

			if (!$response) {
				return back()->with('add_all_parcels_error', 'Errore durante la trasmissione dell\'ordine ' . $order->id);
			}
		});

		return back()->with('add_all_parcels_success', 'Spedizione trasmessa correttamente');
	}
}
