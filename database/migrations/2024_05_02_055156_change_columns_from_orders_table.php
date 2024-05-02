<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::table('order_items', function (Blueprint $table) {
			$table->decimal('fullProductPrice')->change();
			$table->decimal('price_disc')->change();
			$table->decimal('productGain')->change();
			$table->decimal('gain_disc')->change();
		});

		Schema::table('orders', function (Blueprint $table) {
			$table->decimal('credit')->change();
			$table->decimal('shipping')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('order_items', function (Blueprint $table) {
		});
	}
};
