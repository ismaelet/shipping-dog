<?php

use App\Models\Ecommerce\Seller;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        $sellers = Seller::with('order_items')
            ->with('all_transfers')
            ->get();

        $sellers->each(function ($seller) {
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
