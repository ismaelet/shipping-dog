<?php

namespace Database\Factories\Ecommerce\Seller;

use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentRequestFactory extends Factory {

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		$sellerIds = Seller::pluck('id')->toArray();

		return [
			'first_name' => $this->faker->firstName,
			'last_name' => $this->faker->lastName,
			'birthdate' => $this->faker->date,
			'birthplace' => $this->faker->city,
			'birthprovince' => $this->faker->stateAbbr,
			'cf' => $this->faker->unique()->randomNumber(8),
			'national_id' => $this->faker->unique()->randomNumber(7),
			'address' => $this->faker->address,
			'city' => $this->faker->city,
			'province' => $this->faker->stateAbbr,
			'zip' => $this->faker->randomNumber(5),
			'seller_id' => $this->faker->unique()->randomElement($sellerIds),
			'paypal' => $this->faker->email,
		];
	}
}
