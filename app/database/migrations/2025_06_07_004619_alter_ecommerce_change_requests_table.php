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
        Schema::table('ecommerce_change_requests', function (Blueprint $table) {
            $table->rename('change_requests');
        });

        Schema::table('change_requests', function (Blueprint $table) {
            $table->renameColumn('old_cart_id', 'old_order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->rename('ecommerce_change_requests');
        });

        Schema::table('ecommerce_change_requests', function (Blueprint $table) {
            $table->renameColumn('old_order_id', 'old_cart_id');
            $table->dropTimestamps();
        });
    }
};
