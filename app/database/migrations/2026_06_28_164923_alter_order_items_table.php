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
        // Set the logo_id to 0 for all the order items where the logo_id is less than 0
        DB::table('order_items')->where('logo_id', '<', 0)->update(['logo_id' => 0]);

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('size_id')->nullable()->change();
            $table->unsignedBigInteger('position_id')->nullable()->change();
            $table->unsignedBigInteger('logo_id')->nullable()->change();
        });

        // Set the color_id, size_id, position_id, and logo_id to null for all the order items where they are 0
        DB::table('order_items')->where('size_id', 0)->update(['size_id' => null]);
        DB::table('order_items')->where('position_id', 0)->update(['position_id' => null]);
        DB::table('order_items')->where('logo_id', 0)->update(['logo_id' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
