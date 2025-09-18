<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Change;
use App\Models\Ecommerce\ChangeRequest;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundController extends Controller {

	public const STEPS = [
		1 => 'search',
		2 => 'select',
		3 => 'confirm',
	];

	/**
	 * Step 1: Display the refund search page
	 */
	public function indexSearch(Request $request): View {
		return view('support.refund.search', [
			'title' => 'Cambio/Reso',
			'locationTag' => 'support',
		]);
	}

	/**
	 * Step 1: Search for an order by ID and email
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

		if ($changeRequest) {
			return redirect()->route('support.refund.' . self::STEPS[$changeRequest->step]);
		}

		$changeRequest = ChangeRequest::create([
			'old_order_id' => $orderId,
			'step' => 2,
		]);

		session(['change_request_id' => $changeRequest->id]);

		// Redirect to the next step
		return redirect()->route('support.refund.' . self::STEPS[$changeRequest->step], $changeRequest);
	}

	/**
	 * Step 2: Display the select order items page
	 */
	public function indexSelect(ChangeRequest $changeRequest): View {
		$order = $changeRequest->old_order;

		return view('support.refund.select', [
			'title' => 'Cambio/Reso',
			'locationTag' => 'support',
			'changeRequest' => $changeRequest,
			'order' => $order,
		]);
	}

	/**
	 * Step 2: Process the selected order items for refund/change
	 */
	public function select(Request $request, ChangeRequest $changeRequest): RedirectResponse|View {
		$order = $changeRequest->old_order;

		foreach ($order->order_items as $orderItem) {
			$action = $request->input('action_' . $orderItem->id);

			if (!$action) {
				Change::where('old_order_item_id', $orderItem->id)
					->delete();
			} else {
				Change::updateOrCreate(
					['old_order_item_id' => $orderItem->id],
					[
						'change_request_id' => $changeRequest->id,
						'old_size_id' => $orderItem->size_id,
						'new_size_id' => $action === 'change' ? $request->input('size_' . $orderItem->id) : null,
						'is_change' => $action === 'change',
					]
				);
			}
		}

		$changeRequest->load('order_items');

		if ($changeRequest->order_items->isEmpty()) {
			return redirect()
				->back()
				->with(['error' => 'Devi selezionare almeno un cambio o reso per poter procedere'])
				->withInput();
		}

		$changeRequest->update([
			'step' => 3,
		]);

		return view('under-construction', [
			'title' => 'In costruzione',
			'locationTag' => 'support',
		]);

		// return redirect()->route('support.refund.' . self::STEPS[$changeRequest->step], $changeRequest);
	}

	/**
	 * Cancel the change/refund request
	 */
	public function cancel(ChangeRequest $changeRequest): RedirectResponse {
		if (!$changeRequest->payed) {
			$changeRequest->delete();
		}

		session()->forget('change_request_id');

		return redirect()->route('support.refund.search');
	}
}
