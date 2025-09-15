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
			$table->boolean('is_printed')->default(false)->after('status');
			$table->boolean('is_in_cart')->default(false)->after('is_printed');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('order_items', function (Blueprint $table) {
			$table->dropColumn(['is_printed', 'is_in_cart']);
		});
	}
};
