<?php

namespace App\Http\Controllers\Services\PayPal;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\ChangeRequest;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

class PayPalExchangeController extends Controller {

	private float $shippingFee = 8.00;

	/**
	 * Action: Authenticate
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
	 * Action: Create
	 */
	public function create(Request $request, ChangeRequest $changeRequest): RedirectResponse {
		// Set the headers
		$headers = [
			'Content-Type' => 'application/json',
			'Authorization' => $this->authorization(),
			'PayPal-Request-Id' => 'change-' . $changeRequest->id,
		];

		// Set the body
		$body = [
			'intent' => 'CAPTURE',
			'purchase_units' => $this->purchaseUnits($changeRequest),
			'payment_source' => [
				'paypal' => [
					'experience_context' => $this->experienceContext($changeRequest),
					'address' => $this->address($changeRequest->old_order->address),
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
			return $this->error($response, $changeRequest);
		}

		// Store the order token
		$changeRequest->update([
			'token' => $token,
		]);

		// Redirect to the checkout URL
		return redirect($checkoutUrl);
	}

	/**
	 * Action: Capture
	 */
	public function capture(ChangeRequest $changeRequest): RedirectResponse {
		// Set the headers
		$headers = [
			'Content-Type' => 'application/json',
			'Authorization' => $this->authorization(),
		];

		// Set the endpoint
		$endpoint = $this->captureEndpoint($changeRequest);

		// Send the request
		$response = $this->sendRequest(
			headers: $headers,
			endpoint: $endpoint
		);

		// Get the payment status
		$paymentStatus = $response->status ?? null;

		// Error handling
		if ($paymentStatus != 'COMPLETED') {
			return $this->error($response, $changeRequest);
		}

		// Confirm the order
		$changeRequest->confirm();

		return redirect()->route('support.refund.confirm', $changeRequest);
	}

	/**
	 * Action: Cancel
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
	private function error($response, ?ChangeRequest $changeRequest = null): RedirectResponse {
		if (env('APP_DEBUG')) {
			dd($response);
		}

		Log::error(['PayPal error' => [
			array_filter([
				'order' => $changeRequest->id,
				'address' => $changeRequest->old_order?->address->fullAddress(),
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
	private function captureEndpoint(ChangeRequest $changeRequest): string {
		$orderPayPalId = $changeRequest->token;

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
	private function purchaseUnits(ChangeRequest $changeRequest): array {
		$address = $changeRequest->old_order->address;

		return [
			[
				'reference_id' => 'change-' . $changeRequest->id,
				'amount' => [
					'currency_code' => 'EUR',
					'value' => $this->shippingFee,
					'breakdown' => [
						'item_total' => [
							'currency_code' => 'EUR',
							'value' => $this->shippingFee,
						],
						'shipping' => [
							'currency_code' => 'EUR',
							'value' => 0.00,
						]
					]
				],
				'shipping' => [
					'name' => [
						'full_name' => $address->full_name,
					],
					'address' => $this->address($address),
				],
				'items' => $this->items(),
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
	private function items(): array {

		$items[] = [
			'name' => 'Spese di spedizione per cambio merce',
			'unit_amount' => [
				'currency_code' => 'EUR',
				'value' => $this->shippingFee,
			],
			'quantity' => 1,
			'category' => 'PHYSICAL_GOODS',
		];

		return $items;
	}

	/**
	 * ~ Helper: Get the experience context
	 */
	private function experienceContext(ChangeRequest $changeRequest): array {
		return [
			'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
			'brand_name' => config('app.name'),
			'locale' => config('app.locale'),
			'shipping_preference' => 'SET_PROVIDED_ADDRESS',
			'user_action' => 'PAY_NOW',
			'return_url' => route('paypal.exchange.capture', $changeRequest),
			'cancel_url' => route('paypal.exchange.cancel', $changeRequest),
		];
	}
}
