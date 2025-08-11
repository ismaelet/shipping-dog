<?php

use App\Models\Ecommerce\Seller;
use App\Models\Ecommerce\Seller\Payment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		$thresholdDate = "2024-05-31";
		$sellers = Seller::with('order_items', 'payments')->get();

		$negativeSellers = $sellers->filter(function ($seller) {
			return $seller->toBePayed() < 0;
		});

		$negativeSellers->each(function ($seller) use ($thresholdDate) {
			$toReachBalance = ($seller->toBePayed());

			$ordersAfterThreshold = $seller->order_items->filter(function ($orderItem) use ($thresholdDate) {
				return $orderItem->created_at >= $thresholdDate;
			});

			$earningsAfterThreshold = $ordersAfterThreshold->sum('gain');
			$toReachBalance -= $earningsAfterThreshold;

			$payment = Payment::create([
				'seller_id' => $seller->id,
				'value' => $toReachBalance,
				'description' => 'Per bilanciare ordini eliminati',
			]);

			$seller->updateEarned();

			$seller->updatePaid();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		//
	}
};
