<?php

namespace Tests\Feature;

use App\Models\Ecommerce\Seller;
use App\Models\Ecommerce\Seller\PaymentRequest;
use App\Models\User;
use Tests\TestCase;

class PaymentRequestTest extends TestCase {

	/**
	 * Test payment request index without request.
	 */
	public function test_payment_request_index_without_request(): void {
		$user = User::factory()->create();
		$seller = Seller::factory()->create();
		$seller->update(['user_id' => $user->id]);

		// Check the response before creating a payment request
		$response = $this->actingAs($user)
			->get(route('seller.payment'));

		$response->assertStatus(302);
		$response->assertRedirectToRoute('seller.payment.edit');
	}

	/**
	 * Test payment request index with request.
	 */
	public function test_payment_request_index_with_request(): void {
		$user = User::factory()->create();
		$seller = Seller::factory()->create();
		$seller->update(['user_id' => $user->id]);

		// Create a payment request
		$paymentRequest = PaymentRequest::factory()->create();
		$paymentRequest->update(['seller_id' => $seller->id]);

		// Check the response after creating a payment request
		$response = $this->actingAs($user)
			->get(route('seller.payment'));

		$response->assertStatus(200);
		$response->assertViewIs('ecommerce.payment-requests.index');
	}

	/**
	 * Test payment request edit.
	 */
	public function test_payment_request_edit(): void {
		$user = User::factory()->create();
		$seller = Seller::factory()->create();
		$seller->update(['user_id' => $user->id]);

		$response = $this->actingAs($user)
			->get(route('seller.payment.edit'));

		$response->assertStatus(200);
		$response->assertViewIs('ecommerce.payment-requests.edit');
	}
}
