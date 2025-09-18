<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::rename('ecommerce_changes', 'changes');

		Schema::table('changes', function (Blueprint $table) {
			$table->dropTimestamps();
		});

		Schema::table('changes', function (Blueprint $table) {
			$table->renameColumn('old_order_id', 'old_order_item_id');
			$table->renameColumn('new_order_id', 'new_order_item_id');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::rename('changes', 'ecommerce_changes');

		Schema::table('ecommerce_changes', function (Blueprint $table) {
			$table->renameColumn('old_order_item_id', 'old_order_id');
			$table->renameColumn('new_order_item_id', 'new_order_id');
		});
	}
};
