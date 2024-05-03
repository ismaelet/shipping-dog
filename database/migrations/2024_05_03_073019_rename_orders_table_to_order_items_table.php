<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::rename('orders', 'order_items');

		Schema::table('order_items', function (Blueprint $table) {
			$table->renameColumn('cart_id', 'order_id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::rename('order_items', 'orders');

		Schema::table('orders', function (Blueprint $table) {
			$table->renameColumn('order_id', 'cart_id');
		});
	}
};
