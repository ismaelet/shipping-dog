<?php

use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductType;
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
        Schema::create('product_color_product_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_color_id');
            $table->unsignedBigInteger('product_type_id');
        });

        ProductType::all()->each(function (ProductType $productType) {
            $colorTags = $productType->colorList();
            $colors = ProductColor::whereIn('tag', $colorTags)->pluck('id')->toArray();

            foreach ($colors as $color) {
                $productType->colors()->attach($color);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_color_product_type');
    }
};
