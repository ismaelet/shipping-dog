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
        // Drop the old trigger
        DB::unprepared('DROP TRIGGER IF EXISTS update_orders_status');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
