<?php

namespace Tests\Feature;

use App\Models\Ecommerce\Order;
use App\Models\User;
use Tests\TestCase;

class AdminTest extends TestCase {

	/**
	 * Test the product log view.
	 */
	public function test_log_products_view(): void {
		$user = User::factory()->create();
		$user->admin = true;

		$response = $this->actingAs($user)
			->get(route('legacy-admin.log-products'));

		$response->assertStatus(200);
	}
}
