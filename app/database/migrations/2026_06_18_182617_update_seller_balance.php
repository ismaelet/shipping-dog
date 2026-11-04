<?php

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $confirmed = EcommerceHelper::statusFromTag('confirmed');

        Seller::query()
            ->whereHas('order_items')
            ->select('id')
            ->chunkById(500, function ($sellers) use ($confirmed) {
                foreach ($sellers as $seller) {
                    $earned = DB::table('order_items')
                        ->where('seller_id', $seller->id)
                        ->where('status', '>=', $confirmed)
                        ->sum('total_seller_earnings_after_reduction');

                    $payed = DB::table('seller_payments')
                        ->where('seller_id', $seller->id)
                        ->where('other', false)
                        ->sum('value');

                    $otherTransfers = DB::table('seller_payments')
                        ->where('seller_id', $seller->id)
                        ->where('other', true)
                        ->sum('value');

                    DB::table('sellers')
                        ->where('id', $seller->id)
                        ->update([
                            'earned' => round($earned, 2),
                            'payed' => round($payed, 2),
                            'other_transfers' => round($otherTransfers, 2),
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
