<?php

use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        $selfSellerOrderItems = OrderItem::whereColumn('seller_id', 'user_id')
            ->where('gain', '>', 0)
            ->get();

        $selfSellerOrderItems->each(function ($orderItem) {
            $orderItem->gain = 0;
            $orderItem->save();
        });

        $selfSeller = Seller::whereIn('id', $selfSellerOrderItems->pluck('seller_id'))
            ->with('order_items')
            ->get();

        $selfSeller->each(function ($seller) {
            $seller->updateEarned();
            $seller->updateOtherTransfers();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};
