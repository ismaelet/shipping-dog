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
        Schema::rename('stores', 'sellers');
        Schema::rename('prices_stores', 'prices_sellers');

        Schema::table('sellers', function (Blueprint $table) {
            $table->boolean('activation_request_sent')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('sellers', 'stores');
        Schema::rename('prices_sellers', 'prices_stores');

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('activation_request_sent');
        });
    }
};
