<?php

namespace Database\Factories\Ecommerce;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductFactory extends Factory {

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		$designIds = Design::pluck('id')->toArray();
		$sellerIds = Seller::pluck('id')->toArray();
		$typeIds = ProductType::pluck('id')->toArray();
		$colorIds = ProductColor::pluck('id')->toArray();
		$colorTags = ProductColor::pluck('tag')->toArray();
		$positionIds = ProductPosition::pluck('id')->toArray();

		return [
			'name' => $this->faker->name,
			'user_id' => $this->faker->unique()->randomElement($sellerIds),
			'design_id' => $this->faker->unique()->randomElement($designIds),
			'type_id' => $this->faker->unique()->randomElement($typeIds),
			'color_id' => $this->faker->randomElement($colorIds),
			'colors' => $this->faker->randomElement($colorTags),
			'positions' => $this->faker->randomElement($positionIds),
		];
	}
}
