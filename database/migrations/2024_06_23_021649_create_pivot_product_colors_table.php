<?php

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	/**
	 * Run the migrations.
	 */
	public function up(): void {
		// Schema::create('product_product_color', function (Blueprint $table) {
		// 	$table->id();
		// 	$table->unsignedBigInteger('product_id');
		// 	$table->unsignedBigInteger('product_color_id');
		// });

		// Product::chunk(100, function ($products) {
		// 	foreach ($products as $product) {
		// 		$colors = ProductColor::whereIn('tag', explode(',', $product->colors))->get();

		// 		$colors->each(function (ProductColor $color) use ($product) {
		// 			$product->colors()->attach($color);
		// 		});
		// 	}
		// });
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		// Schema::dropIfExists('product_product_color');
	}
};
