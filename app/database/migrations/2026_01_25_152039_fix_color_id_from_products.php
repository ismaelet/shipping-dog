<?php

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->removeColumn('colors');
            $table->removeColumn('positions');
            $table->removeColumn('time');
        });

        $productTypes = ProductType::all();

        foreach ($productTypes as $productType) {
            $typeColors = $productType->colors()->pluck('product_colors.id')->toArray();

            if (!empty($typeColors)) {
                $problematicProducts = Product::where('type_id', $productType->id)
                    ->whereNotIn('color_id', $typeColors)
                    ->get();

                foreach ($problematicProducts as $product) {
                    $product->color_id = $typeColors[0];
                    $product->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->json('colors')->nullable();
            $table->json('positions')->nullable();
            $table->integer('time')->nullable();
        });
    }
};
