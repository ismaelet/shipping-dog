<?php

use App\Models\Ecommerce\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		$products = Product::where('user_id', 7579)
			->with('color', 'position', 'type')
			->get();

		$products->each(function ($product) {
			$product->previewImage()?->deleteImages();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		//
	}
};
