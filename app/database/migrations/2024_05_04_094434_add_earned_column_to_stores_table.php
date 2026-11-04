<?php

use App\Models\Ecommerce\Seller;
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
        Seller::all()->each(function ($seller) {
            $seller->update(['earned' => $seller->order_items->where('status', '>', 1)->sum('gain')]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            Seller::query()->update(['earned' => 0]);
        });
    }
};
