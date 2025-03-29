<?php

namespace Tests\Feature;

use App\Models\Ecommerce\Product;
use Tests\TestCase;

class CartTest extends TestCase {

	/**
	 * Test order item creation.
	 */
	public function test_create_order_item(): void {
		$product = Product::factory()->create();
		$sizeId = $product->type->sizes()->first()->id;
		$positionId = $product->type->positions()->first()->id;
		$logoId = $product->design?->logoGroup?->logos()?->first()->id ?: 0;

		$response = $this->post(route('order-items.store'), [
			'product_id' => $product->id,
			'color_id' => $product->color_id,
			'size_id' => $sizeId,
			'position_id' => $positionId,
			'gender_id' => $product->type->gender_id,
			'logo_id' => $logoId,
			'quantity' => 1
		]);

		// dd($response);

		$this->assertDatabaseHas('order_items', [
			'product_id' => $product->id,
			'color_id' => $product->color_id,
			'size_id' => $sizeId,
			'position_id' => $positionId,
			'gender_id' => $product->type->gender_id,
			'logo_id' => $logoId,
			'quantity' => 1
		]);
	}
}
