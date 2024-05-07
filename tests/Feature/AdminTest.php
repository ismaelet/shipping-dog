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
			->get(route('admin.log-products'));

		$response->assertStatus(200);
	}

	public function test_log_products_clean_log(): void {
		$date = date('Y-m-d H:i:s');
		$user = User::factory()->create();
		$user->admin = true;

		$response = $this->actingAs($user)
			->post('/admin/log-products', [
				'submit' => 'clean_log',
			]);

		$this->assertDatabaseHas('config', [
			'name' => 'product_log_start_from',
			'value' => $date,
		]);

		$response->assertStatus(302);
	}

	public function test_log_products_start_order(): void {
		$startOrderId = 62914;
		$startTime = Order::find($startOrderId)->created_at;
		$user = User::factory()->create();
		$user->admin = true;

		$response = $this->actingAs($user)
			->post('/admin/log-products', [
				'start_order_id' => $startOrderId,
			]);

		$this->assertDatabaseHas('config', [
			'name' => 'product_log_start_from',
			'value' => $startTime,
		]);

		$response->assertStatus(302);
	}
}
