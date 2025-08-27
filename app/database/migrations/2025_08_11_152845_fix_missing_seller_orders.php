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
		$sellers = Seller::with('order_items', 'payments')->get();

		$negativeSellers = $sellers->filter(function ($seller) {
			return $seller->toBePayed() < 0;
		});

		$negativeSellers->each(function ($seller) {
			$thresholdDate = $seller->payments->max('created_at');
			$toReachBalance = ($seller->toBePayed());

			$ordersAfterThreshold = $seller->order_items->filter(function ($orderItem) use ($thresholdDate) {
				return $orderItem->created_at > $thresholdDate;
			});

			$earningsAfterThreshold = $ordersAfterThreshold->sum('gain');
			$toReachBalance -= $earningsAfterThreshold;

			Payment::create([
				'seller_id' => $seller->id,
				'value' => $toReachBalance,
				'description' => 'Per bilanciare ordini eliminati',
			]);

			$seller->updateEarned();

			$seller->updatePaid();
		});

		$humanBodyCollectionSeller = Seller::find(187);
		$humanBodyCollectionSeller->updateEarned();
		$humanBodyCollectionSeller->updatePaid();
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		//
	}
};
