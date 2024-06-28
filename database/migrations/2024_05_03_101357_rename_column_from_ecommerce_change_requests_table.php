<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::table('ecommerce_change_requests', function (Blueprint $table) {
			$table->dropTimestamps();
		});

		Schema::table('ecommerce_change_requests', function (Blueprint $table) {
			$table->timestamps();
		});

		Schema::table('ecommerce_change_requests', function (Blueprint $table) {
			$table->dropTimestamps();
			$table->renameColumn('new_cart_id', 'new_order_id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('ecommerce_change_requests', function (Blueprint $table) {
			$table->renameColumn('new_order_id', 'new_cart_id');
		});
	}
};
