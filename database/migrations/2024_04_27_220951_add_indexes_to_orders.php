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
		Schema::table('order_items', function (Blueprint $table) {
			$table->dropIndex('order_items_user_id_index');
			$table->dropIndex('order_items_product_id_index');
			$table->dropIndex('order_items_seller_id_index');
			$table->dropIndex('order_items_design_id_index');
			$table->dropIndex('order_items_type_id_index');
			$table->dropIndex('order_items_color_id_index');
			$table->dropIndex('order_items_size_id_index');
			$table->dropIndex('order_items_new_id_index');
			$table->dropIndex('order_items_gender_id_index');
			$table->dropIndex('order_items_position_id_index');
			$table->dropIndex('order_items_logo_id_index');
		});
	}
};
