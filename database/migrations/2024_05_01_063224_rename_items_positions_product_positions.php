<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::rename('items_positions', 'product_positions');
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::rename('product_positions', 'items_positions');
	}
};
