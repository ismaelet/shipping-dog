<?php

use App\Models\Ecommerce\Product\ProductColor;
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
        Schema::table('product_colors', function (Blueprint $table) {
            $table->renameColumn('color', 'primary_color_hex');
            $table->renameColumn('secondColor', 'secondary_color_hex');
        });

        Schema::table('product_colors', function (Blueprint $table) {
            $table->string('primary_color_hex')->change();
            $table->string('secondary_color_hex')->nullable()->change();
        });

        ProductColor::query()->each(function (ProductColor $productColor) {
            $productColor->update([
                'primary_color_hex' => '#'.$productColor->primary_color_hex,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        ProductColor::query()->each(function (ProductColor $productColor) {
            $productColor->update([
                'primary_color_hex' => ltrim($productColor->primary_color_hex, '#'),
            ]);
        });

        Schema::table('product_colors', function (Blueprint $table) {
            $table->renameColumn('primary_color_hex', 'color');
            $table->renameColumn('secondary_color_hex', 'secondColor');
        });
    }
};
