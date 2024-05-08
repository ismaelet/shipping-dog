<?php

namespace Database\Factories\Ecommerce;

use App\Models\Ecommerce\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerFactory extends Factory {

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		$userIds = User::whereNotIn('id', Seller::pluck('user_id'))->pluck('id')->toArray();

		return [
			'name' => $this->faker->name,
			'user_id' => $this->faker->unique()->randomElement($userIds),
			'status' => 1,
		];
	}
}
