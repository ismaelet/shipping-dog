<?php

use App\Models\Ecommerce\Product\ProductSize;
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
        Schema::create('product_size_product_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_size_id');
            $table->unsignedBigInteger('product_type_id');
        });

        ProductType::all()->each(function (ProductType $productType) {
            $sizeTags = $productType->sizeList();
            $sizes = ProductSize::whereIn('tag', $sizeTags)->pluck('id')->toArray();

            foreach ($sizes as $size) {
                $productType->sizes()->attach($size);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_size_product_type');
    }
};
