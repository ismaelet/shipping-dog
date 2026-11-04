<?php

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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('fullProductPrice')->default(0)->change();
            $table->decimal('price_disc')->default(0)->change();
            $table->decimal('productGain')->default(0)->change();
            $table->decimal('gain_disc')->default(0)->change();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->decimal('credit')->default(0)->change();
            $table->decimal('shipping')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {});
    }
};
