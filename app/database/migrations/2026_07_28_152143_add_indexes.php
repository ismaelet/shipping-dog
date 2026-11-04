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
        Schema::table('products', function (Blueprint $table) {
            $table->index('type_id', 'products_type_id_index');
        });

        Schema::table('product_product_color', function (Blueprint $table) {
            $table->index(
                ['product_color_id', 'product_id'],
                'product_color_product_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_type_id_index');
        });

        Schema::table('product_product_color', function (Blueprint $table) {
            $table->dropIndex('product_color_product_index');
        });
    }
};
