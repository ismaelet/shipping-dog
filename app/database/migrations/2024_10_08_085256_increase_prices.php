<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::update('UPDATE prices_default SET price = price + 1');
        DB::update('UPDATE prices_sellers SET price = price + 1');
        DB::update('UPDATE items_prices SET price = price + 1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::update('UPDATE prices_default SET price = price - 1');
        DB::update('UPDATE prices_sellers SET price = price - 1');
        DB::update('UPDATE items_prices SET price = price - 1');
    }
};
