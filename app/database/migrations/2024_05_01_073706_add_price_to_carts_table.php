<?php

use App\Models\Ecommerce\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'price')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('price')->after('tot')->default(0);
            });
        }

        Order::where('price', 0)->get()->each(function ($order) {
            $order->price = $order->price();
            $order->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
