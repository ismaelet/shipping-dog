<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::table('order_items')
            ->where('design_id', '<', 0)
            ->update(['design_id' => 0]);

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('design_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('design_id')->nullable(false)->change();
        });
    }
};
