<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase {

	public function test_register(): void {
		$user = User::factory()->make([
			'password' => Hash::make('password'),
		]);

		$this->post(route('register.store'), [
			'email' => $user->email,
			'password' => 'password',
			'password_confirmation' => 'password',
		]);

		$this->assertDatabaseHas('users', [
			'email' => $user->email,
		]);
	}

	/**
	 * Test the auth function view.
	 */
	public function test_auth(): void {
		$user = User::factory()->create([
			'password' => Hash::make('password'),
		]);

		$response = $this->post(route('login.auth'), [
			'login' => $user->email,
			'password' => 'password',
		]);

		$response->assertRedirect('/user-dashboard');

		$this->assertAuthenticatedAs($user);
	}
}
