<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::table('orders', function (Blueprint $table) {
			$table->index('user_id');
			$table->index('product_id');
			$table->index('seller_id');
			$table->index('design_id');
			$table->index('type_id');
			$table->index('color_id');
			$table->index('size_id');
			$table->index('new_id');
			$table->index('gender_id');
			$table->index('position_id');
			$table->index('logo_id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('orders', function (Blueprint $table) {
			$table->dropIndex('orders_user_id_index');
			$table->dropIndex('orders_product_id_index');
			$table->dropIndex('orders_seller_id_index');
			$table->dropIndex('orders_design_id_index');
			$table->dropIndex('orders_type_id_index');
			$table->dropIndex('orders_color_id_index');
			$table->dropIndex('orders_size_id_index');
			$table->dropIndex('orders_new_id_index');
			$table->dropIndex('orders_gender_id_index');
			$table->dropIndex('orders_position_id_index');
			$table->dropIndex('orders_logo_id_index');
		});
	}
};
