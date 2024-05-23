<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewsTest extends TestCase {

	/**
	 * Test the home view.
	 */
	public function test_home_view(): void {
		$response = $this->get(route('home'));

		$response->assertStatus(200);
	}

	/**
	 * Test the home view as a logged user.
	 */
	public function test_home_view_auth(): void {
		$user = User::factory()->create();

		$this->actingAs($user);

		$response = $this->get(route('home'));

		$response->assertStatus(200);
	}

	/**
	 * Test the general shop view.
	 */
	public function test_general_shop_view(): void {
		$response = $this->get(route('shop'));

		$response->assertStatus(200);
	}

	/**
	 * Test the shop view.
	 */
	public function test_shop_view(): void {
		$response = $this->get(route('shop'));

		$response->assertStatus(200);
	}
}
