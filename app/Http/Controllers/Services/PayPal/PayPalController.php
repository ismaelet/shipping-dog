<?php

namespace App\Http\Controllers\Services\PayPal;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

class PayPalController extends Controller {

	/**
	 * * Action: Authenticate
	 */
	public function authenticate(): string|RedirectResponse {
		// Set the headers
		$headers = [
			'Content-Type' => 'application/x-www-form-urlencoded',
			'Authorization' => 'Basic ' . base64_encode($this->clientId() . ':' . $this->clientSecret()),
		];

		// Set the body
		$body = 'grant_type=client_credentials';

		// Set the endpoint
		$endpoint = $this->authenticateEndpoint();

		// Send the request
		$response = $this->sendRequest(
			headers: $headers,
			body: $body,
			endpoint: $endpoint
		);

		// Get the access token
		$accessToken = $response->access_token ?? false;

		// Error handling
		if (!$accessToken) {
			return $this->error($response);
		}

		// Return the access token
		return $accessToken;
	}

	/**
	 * * Action: Create
	 */
	public function create(Request $request): RedirectResponse {
		// Get the order
		$order = Order::find($request->order_id);

		// Set the headers
		$headers = [
			'Content-Type' => 'application/json',
			'Authorization' => $this->authorization(),
			'PayPal-Request-Id' => $order->id,
		];

		// Set the body
		$body = [
			'intent' => 'CAPTURE',
			'purchase_units' => $this->purchaseUnits($order),
			'payment_source' => [
				'paypal' => [
					'experience_context' => $this->experienceContext($order),
					'address' => $this->address($order->address),
				]
			]
		];

		// Set the endpoint
		$endpoint = $this->createEndpoint();

		// Send the request
		$response = $this->sendRequest(
			headers: $headers,
			body: $body,
			endpoint: $endpoint
		);

		// Get the response data
		$token = $response->id ?? null;
		$checkoutUrl = $response->links[1]->href ?? null;

		// Error handling
		if (!$token || !$checkoutUrl) {
			return $this->error($response, $order);
		}

		// Store the order token
		$order->update([
			'token' => $token,
		]);

		// Redirect to the checkout URL
		return redirect($checkoutUrl);
	}

	/**
	 * * Action: Capture
	 */
	public function capture(Order $order): RedirectResponse {
		$order->load('order_items.type');

		// Set the headers
		$headers = [
			'Content-Type' => 'application/json',
			'Authorization' => $this->authorization(),
		];

		// Set the endpoint
		$endpoint = $this->captureEndpoint($order);

		// Send the request
		$response = $this->sendRequest(
			headers: $headers,
			endpoint: $endpoint
		);

		// Get the payment status
		$paymentStatus = $response->status ?? null;

		// Error handling
		if ($paymentStatus != 'COMPLETED') {
			if ($response->details[0]->issue != 'INSTRUMENT_DECLINED') {
				return $this->error($response, $order);
			}
		}

		// Confirm the order
		$order->confirm();

		return redirect($order->recapUrlWithToken());
	}

	/**
	 * * Action: Cancel
	 */
	public function cancel(Order $order, Request $request): RedirectResponse {
		// Get the tokens from the session and the request
		$requestToken = $request->token;

		// Set the order status to received if the order is in the correct status and the tokens match
		if ($order->status == EcommerceHelper::statusFromTag('open') && $order->token == $requestToken) {
			// Reopen the order
			$order->update([
				'status' => EcommerceHelper::statusFromTag('received'),
			]);
		}

		// Redirect to the cart
		return redirect($order->recapUrlWithToken());
	}

	/**
	 * ~ Helper: Send the request
	 */
	private function sendRequest(array $headers, string $endpoint, array|string|null $body = null): stdClass {
		// Encode the body
		$body = is_array($body) ? json_encode($body) : $body;

		// Set the header
		$response = Http::withHeaders($headers);

		// Set the body and send the request
		if ($body) {
			$response = $response->withBody($body)->post($endpoint);
		} else {
			$response = $response->post($endpoint, null);
		}

		// Parse the response
		return $response = json_decode($response->body());
	}

	/**
	 * ~ Helper: Error handling
	 */
	private function error($response, Order $order = null): RedirectResponse {
		if (env('APP_DEBUG')) {
			dd($response);
		}

		Log::error(['PayPal error' => [
			array_filter([
				'order' => $order->id,
				'address' => $order?->address->fullAddress(),
				'response' => $response
			])
		]]);

		return back()->with('error', 'Errore durante il pagamento. Non è stato prevelato nessun importo.');
	}

	/**
	 * ~ Helper: Get the client ID
	 */
	private function clientId(): string {
		return env('APP_ENV') == 'local' ? config('services.paypal.sandbox.client_id') : config('services.paypal.client_id');
	}

	/**
	 * ~ Helper: Get the client secret
	 */
	private function clientSecret(): string {
		return env('APP_ENV') == 'local' ? config('services.paypal.sandbox.client_secret') : config('services.paypal.client_secret');
	}

	/**
	 * ~ Helper: Get the authenticate endpoint
	 */
	private function authenticateEndpoint(): string {
		return env('APP_ENV') == 'local' ? 'https://api-m.sandbox.paypal.com/v1/oauth2/token' : 'https://api-m.paypal.com/v1/oauth2/token';
	}

	/**
	 * ~ Helper: Get the create endpoint
	 */
	private function createEndpoint(): string {
		return env('APP_ENV') == 'local' ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders' : 'https://api-m.paypal.com/v2/checkout/orders';
	}

	/**
	 * ~ Helper: Get the capture endpoint
	 */
	private function captureEndpoint(Order $order): string {
		$orderPayPalId = $order->token;

		return env('APP_ENV') == 'local' ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders/' . $orderPayPalId . '/capture' : 'https://api-m.paypal.com/v2/checkout/orders/' . $orderPayPalId . '/capture';
	}

	/**
	 * ~ Helper: Get the authorization code
	 */
	private function authorization(): string {
		// $accessToken = session('paypal_access_token') ?: $this->authenticate();
		$accessToken = $this->authenticate();

		return 'Bearer ' . $accessToken;
	}

	/**
	 * ~ Helper: Get the purchase units
	 */
	private function purchaseUnits(Order $order): array {
		$address = $order->address;

		return [
			[
				'reference_id' => $order->id,
				'amount' => [
					'currency_code' => 'EUR',
					'value' => number_format($order->price, 2),
					'breakdown' => [
						'item_total' => [
							'currency_code' => 'EUR',
							'value' => number_format($order->orderItemsPrice(), 2),
						],
						'shipping' => [
							'currency_code' => 'EUR',
							'value' => number_format($order->shippingFee(), 2),
						]
					]
				],
				'shipping' => [
					'name' => [
						'full_name' => $address->full_name,
					],
					$order->shipping_method->isLocalDelivery() ? null : 'address' => $this->address($address),
				],
				'items' => $this->items($order),
			]
		];
	}

	/**
	 * ~ Helper: Get the address
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
	 * ~ Helper: Get the items
	 */
	private function items(Order $order): array {
		$order->load('order_items.product_with_trashed');

		$items = [];

		foreach ($order->order_items as $orderItem) {
			$items[] = [
				'name' => $orderItem->product_with_trashed->name,
				'unit_amount' => [
					'currency_code' => 'EUR',
					'value' => number_format($orderItem->price(), 2),
				],
				'quantity' => $orderItem->quantity,
				'category' => 'PHYSICAL_GOODS',
			];
		}

		return $items;
	}

	/**
	 * ~ Helper: Get the experience context
	 */
	private function experienceContext(Order $order): array {
		return [
			'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
			'brand_name' => config('app.name'),
			'locale' => config('app.locale'),
			'shipping_preference' => $order->shipping_method->isLocalDelivery() ? 'NO_SHIPPING' : 'SET_PROVIDED_ADDRESS',
			'user_action' => 'PAY_NOW',
			'return_url' => route('paypal.order.capture', ['order' => $order]),
			'cancel_url' => route('paypal.order.cancel', ['order' => $order]),
		];
	}
}
