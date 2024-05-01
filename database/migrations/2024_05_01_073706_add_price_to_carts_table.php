<?php

use App\Models\Ecommerce\Cart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		if (!Schema::hasColumn('carts', 'price')) {
			Schema::table('carts', function (Blueprint $table) {
				$table->decimal('price')->after('tot')->default(0);
			});
		}

		Cart::where('price', 0)->get()->each(function ($cart) {
			$cart->price = $cart->price();
			$cart->save();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('carts', function (Blueprint $table) {
			$table->dropColumn('price');
		});
	}
};
