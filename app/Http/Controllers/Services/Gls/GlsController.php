<?php

namespace App\Http\Controllers\Services\Gls;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Services\GlsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GlsController extends Controller {

	public function transmitParcels(Request $request, GlsService $glsService): RedirectResponse {

		$orders = Order::whereIn('id', $request->orders)->get();
		$response = $glsService->addParcels($orders);

		if (!$response) {
			return back()->with('parcels_error', 'Errore durante la trasmissione degli ordini');
		}

		$response = $glsService->transmitParcels($orders);

		if (!$response) {
			return back()->with('parcels_error', 'Errore durante la conferma di trasmissione degli ordini');
		}

		return back()->with('parcels_success', 'Spedizioni trasmesse correttamente');
	}
}
