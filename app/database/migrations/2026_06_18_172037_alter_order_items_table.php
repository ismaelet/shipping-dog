<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('gain');

            $table->renameColumn('productGain', 'unit_seller_earnings_before_reduction');

            $table->renameColumn('gain_disc', 'unit_seller_reduction');

            $table->decimal('unit_seller_earnings_before_reduction', 10, 2)->default(0)->change();
            $table->decimal('total_seller_earnings_before_reduction', 10, 2)->default(0)->after('unit_seller_earnings_before_reduction');

            $table->decimal('unit_seller_earnings_after_reduction', 10, 2)->default(0)->after('total_seller_earnings_before_reduction');
            $table->decimal('total_seller_earnings_after_reduction', 10, 2)->default(0)->after('unit_seller_earnings_after_reduction');

            $table->decimal('unit_seller_reduction', 10, 2)->default(0)->change();
            $table->decimal('total_seller_reduction', 10, 2)->default(0)->after('unit_seller_reduction');
        });

        DB::table('order_items')
            ->orderBy('id')
            ->chunkById(500, function ($orderItems) {
                foreach ($orderItems as $orderItem) {
                    $quantity = max((int) $orderItem->quantity, 1);

                    $unitSellerEarningsBeforeReduction = $orderItem->unit_seller_earnings_before_reduction;
                    $unitSellerReduction = min($orderItem->unit_seller_reduction, $unitSellerEarningsBeforeReduction);

                    $totalSellerEarningsBeforeReduction = $unitSellerEarningsBeforeReduction * $quantity;
                    $totalSellerReduction = $unitSellerReduction * $quantity;

                    $unitSellerEarningsAfterReduction = $unitSellerEarningsBeforeReduction - $unitSellerReduction;
                    $totalSellerEarningsAfterReduction = $totalSellerEarningsBeforeReduction - $totalSellerReduction;

                    DB::table('order_items')
                        ->where('id', $orderItem->id)
                        ->update([
                            'unit_seller_earnings_before_reduction' => $unitSellerEarningsBeforeReduction,
                            'total_seller_earnings_before_reduction' => $totalSellerEarningsBeforeReduction,
                            'unit_seller_earnings_after_reduction' => $unitSellerEarningsAfterReduction,
                            'total_seller_earnings_after_reduction' => $totalSellerEarningsAfterReduction,
                            'unit_seller_reduction' => $unitSellerReduction,
                            'total_seller_reduction' => $totalSellerReduction,
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->renameColumn('unit_seller_earnings_before_reduction', 'productGain');
            $table->renameColumn('unit_seller_reduction', 'gain_disc');

            $table->dropColumn(['total_seller_earnings_before_reduction', 'unit_seller_earnings_after_reduction', 'total_seller_earnings_after_reduction', 'total_seller_reduction']);

            $table->decimal('gain', 10, 2)->default(0)->after('gain_disc');
        });
    }
};
