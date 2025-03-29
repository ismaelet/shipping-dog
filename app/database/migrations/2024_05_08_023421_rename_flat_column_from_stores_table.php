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
		Schema::table('stores', function (Blueprint $table) {
			$table->renameColumn('flat', 'other_transfers');
		});

		// Update earned
		DB::update('UPDATE stores SET earned = IFNULL((SELECT SUM(gain) as tot FROM order_items WHERE seller_id = stores.id AND status > 1), 0)');

		// Update paied
		DB::update('UPDATE stores SET payed = IFNULL((SELECT SUM(value) as tot FROM stores_payments WHERE seller_id = stores.id AND other = 0), 0)');

		// Update other transferences
		DB::update('UPDATE stores SET other_transfers = IFNULL((SELECT SUM(value) as tot FROM stores_payments WHERE seller_id = stores.id AND other = 1), 0)');

		// Update shipping price
		DB::update('UPDATE orders SET shipping = (CASE WHEN quantity = 1 THEN 5.9 ELSE 7.4 END) WHERE credit_ship = 1 AND shipping = 0');

		// Update credit
		DB::update('UPDATE stores SET credit = (
			earned * 0.1 - 
				IFNULL((SELECT SUM((fullProductPrice - price_disc) * quantity) as orderItemsCredit FROM order_items WHERE user_id = stores.user_id AND status != -1 AND credit = 1), 0) -
				IFNULL((SELECT SUM(shipping) as shippingCredit FROM orders WHERE user_id = stores.user_id AND status != -1 AND credit_ship = 1),0)
			)');
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('stores', function (Blueprint $table) {
			$table->renameColumn('other_transfers', 'flat');
		});
	}
};
