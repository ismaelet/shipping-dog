<?php

use App\Models\Ecommerce\Order;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Order::where('status', '>', 0)->increment('status');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Order::where('status', '>', 1)->decrement('status');
    }
};
