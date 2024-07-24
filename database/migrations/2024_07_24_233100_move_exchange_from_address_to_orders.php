<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::table('orders', function (Blueprint $table) {
			$table->boolean('exchange')->default(false);
		});

		DB::table('orders')
			->join('addresses', 'addresses.order_id', '=', 'orders.id')
			->where('addresses.exchange', true)
			->update(['orders.exchange' => DB::raw('addresses.exchange')]);
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('orders', function (Blueprint $table) {
			$table->dropColumn('exchange');
		});
	}
};
