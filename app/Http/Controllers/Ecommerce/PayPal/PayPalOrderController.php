<?php

namespace App\Http\Controllers\Ecommerce\PayPal;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalOrderController extends Controller {

	/**
	 * Create action
	 */
	public function create(): RedirectResponse {
		$order = cart();

		if (!$order) {
			return redirect()->route('cart.empty');
		}

		$endpoint = env('APP_ENV') == 'local' ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders' : 'https://api-m.paypal.com/v2/checkout/orders';

		$response = Http::withHeaders([
			'Content-Type' => 'application/json',
			'Authorization' => 'Basic ' . $this->base64Client(),
		])
			->post($endpoint, [
				'intent' => 'CAPTURE',
				'purchase_units' => $this->purchaseUnits($order),
				'payment_source' => [
					'paypal' => [
						'experience_context' => $this->experienceContext($order),
						'address' => $this->address($order->address),
					]
				]
			]);

		$responseData = $response->json();
		$redirectUrl = $responseData['links'][1]['href'] ?? false;

		if ($redirectUrl) {
			$order->update([
				'status' => EcommerceHelper::statusFromTag('received'),
				'token' => $responseData['id'],
			]);

			$order->save();

			return redirect($redirectUrl);
		} else {
			Log::error(
				[
					'PayPal order creation error' => [
						'order_id' => $order->id,
						'response' => $responseData
					]
				]
			);

			if (is_admin()) {
				dd($responseData);
			}

			return back()->with('error', 'Errore durante la creazione dell\'ordine');
		}
	}

	/**
	 * Get the base64 client
	 */
	private function base64Client(): string {
		$paypalClientId = env('APP_ENV') == 'local' ? config('services.paypal.sandbox.client_id') : config('services.paypal.client_id');
		$paypalClientSecret = env('APP_ENV') == 'local' ? config('services.paypal.sandbox.client_secret') : config('services.paypal.client_secret');

		return base64_encode($paypalClientId . ':' . $paypalClientSecret);
	}

	/**
	 * Get the purchase units
	 */
	private function purchaseUnits(Order $order): array {
		$address = $order->address;

		return [
			[
				'reference_id' => $order->id,
				'amount' => [
					'currency_code' => 'EUR',
					'value' => $order->price,
					'breakdown' => [
						'item_total' => [
							'currency_code' => 'EUR',
							'value' => $order->orderItemsPrice(),
						],
						'shipping' => [
							'currency_code' => 'EUR',
							'value' => $order->shippingFee(),
						]
					]
				],
				'shipping' => [
					'name' => [
						'full_name' => $address->full_name,
					],
					'address' => $this->address($address),
				],
				'items' => $this->items($order),
			]
		];
	}

	/**
	 * Get the address
	 */
	private function address(Address $address): array {
		return [
			'address_line_1' => $address->address . ', ' . $address->number,
			'admin_area_2' => $address->city,
			'admin_area_1' => $address->region,
			'postal_code' => $address->zip,
			'country_code' => 'IT',
		];
	}

	/**
	 * Get the items
	 */
	private function items(Order $order): array {
		$items = [];

		foreach ($order->order_items as $orderItem) {
			$items[] = [
				'name' => $orderItem->product->name,
				'unit_amount' => [
					'currency_code' => 'EUR',
					'value' => $orderItem->price(),
				],
				'quantity' => $orderItem->quantity,
				'category' => 'PHYSICAL_GOODS',
			];
		}

		return $items;
	}

	/**
	 * Get the experience context
	 */
	private function experienceContext(Order $order): array {
		return [
			'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
			'brand_name' => config('app.name'),
			'locale' => config('app.locale'),
			'landing_page' => 'LOGIN',
			'shipping_preference' => 'SET_PROVIDED_ADDRESS',
			'user_action' => 'PAY_NOW',
			'return_url' => route('paypal.order.success', ['order' => $order]),
			'cancel_url' => route('paypal.order.reopen', ['order' => $order]),
		];
	}

	/**
	 * Success method
	 *
	 * This method is responsible for handling the successful payment callback from PayPal.
	 * It confirms the order, forgets the PayPal token from the session, and redirects the user to the order recap page.
	 *
	 * @param Order $order The order object
	 * @param Request $request The HTTP request object
	 * @return RedirectResponse The redirect response
	 */
	public function success(Order $order, Request $request): RedirectResponse {
		// Get the token from the request
		$requestToken = $request->token;

		// If the order is not in the correct status or the tokens don't match, redirect to the cart
		if ($order->status != EcommerceHelper::statusFromTag('received') || $order->token != $requestToken) {
			return redirect()->route('cart');
		}

		// Confirm the order
		$order->confirm();

		// Redirect to the order recap page
		return redirect($order->recapUrl());
	}

	/**
	 * Reopen an order
	 *
	 * @param Order $order The order to reopen
	 * @param Request $request The HTTP request object
	 * @return RedirectResponse Redirects to the cart page
	 */
	public function reopen(Order $order, Request $request): RedirectResponse {
		// Get the tokens from the session and the request
		$requestToken = $request->token;

		// If the order is not in the correct status or the tokens don't match, redirect to the cart
		if ($order->status == EcommerceHelper::statusFromTag('received') && $order->token == $requestToken) {
			// Reopen the order
			$order->update([
				'status' => EcommerceHelper::statusFromTag('open'),
			]);
		}

		// Redirect to the cart
		return redirect()->route('cart');
	}
}
