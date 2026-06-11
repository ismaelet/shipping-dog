<?php

use App\Models\Ecommerce\OrderItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('unit_amount_before_discount', 10, 2)->default(0)->after('logo_id');
            $table->renameColumn('fullProductPrice', 'total_amount_before_discount');
            $table->decimal('total_amount_before_discount', 10, 2)->default(0)->change();

            $table->decimal('unit_amount_after_discount', 10, 2)->default(0)->after('total_amount_before_discount');
            $table->decimal('total_amount_after_discount', 10, 2)->default(0)->after('unit_amount_after_discount');

            $table->decimal('unit_amount_to_pay', 10, 2)->default(0)->after('total_amount_after_discount');
            $table->decimal('total_amount_to_pay', 10, 2)->default(0)->after('unit_amount_to_pay');

            $table->decimal('unit_discount', 10, 2)->default(0)->after('total_amount_to_pay');
            $table->renameColumn('price_disc', 'total_discount');
            $table->decimal('total_discount', 10, 2)->default(0)->change();
        });

        DB::table('order_items')
            ->orderBy('id')
            ->chunkById(500, function ($orderItems) {
                foreach ($orderItems as $orderItem) {
                    $quantity = max((int) $orderItem->quantity, 1);

                    $unitAmountBeforeDiscount = $orderItem->total_amount_before_discount / $quantity;
                    $totalAmountAfterDiscount = $orderItem->total_amount_before_discount - $orderItem->total_discount;
                    $unitAmountAfterDiscount = $totalAmountAfterDiscount / $quantity;

                    DB::table('order_items')
                        ->where('id', $orderItem->id)
                        ->update([
                            'unit_amount_before_discount' => $unitAmountBeforeDiscount,
                            'unit_amount_after_discount' => $unitAmountAfterDiscount,
                            'total_amount_after_discount' => $totalAmountAfterDiscount,
                            'unit_amount_to_pay' => $orderItem->credit ? 0 : $unitAmountAfterDiscount,
                            'total_amount_to_pay' => $orderItem->credit ? 0 : $totalAmountAfterDiscount,
                            'unit_discount' => $orderItem->total_discount / $quantity,
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->renameColumn('total_amount_before_discount', 'fullProductPrice');
            $table->renameColumn('total_discount', 'price_disc');

            $table->dropColumn(['unit_amount_before_discount', 'unit_discount', 'unit_amount_after_discount', 'total_amount_after_discount', 'unit_amount_to_pay', 'total_amount_to_pay']);
        });
    }
};
