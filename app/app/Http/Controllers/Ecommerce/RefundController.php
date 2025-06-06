<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\ChangeRequest;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundController extends Controller {

	private array $steps = [
		1 => 'search',
		2 => 'select',
		3 => 'confirm',
	];

	/**
	 * Step 1: Display the refund search page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function indexSearch(Request $request): View {
		return view(env('APP_ENV') == 'local' ? 'support.refund.search' : 'under-construction', [
			'title' => 'Cambio/Reso',
			'locationTag' => 'support',
		]);
	}

	/**
	 * Step 1: Search for an order by ID and email.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\RedirectResponse 
	 */
	public function search(Request $request): RedirectResponse {
		// Validate the request input
		$request->validate([
			'order_id' => 'required|string',
			'email' => 'required|email',
		]);

		$orderId = (int) trim($request->order_id, '#');
		$email = $request->email;

		// Check if the cart exists
		$order = Order::join('addresses', 'addresses.order_id', '=', 'orders.id')
			->where('orders.id', $orderId)
			->where('addresses.email', $email)
			->first();

		if (!$order) {
			// If the order is not found, redirect back with an error
			return redirect()
				->back()
				->with(['error' => 'Nessun ordine trovato con l\'ID e l\'email forniti.'])
				->withInput();
		}

		// Fetch the change request
		$changeRequest = ChangeRequest::where('old_order_id', $orderId)->first();

		if (!$changeRequest) {
			$changeRequest = ChangeRequest::create([
				'old_order_id' => $orderId,
				'step' => 2,
			]);
		}

		$_SESSION['changeRequest_id'] = $changeRequest->id;

		// Redirect to the next step
		return redirect()->route('support.refund.' . $this->steps[$changeRequest->step]);
	}
}
