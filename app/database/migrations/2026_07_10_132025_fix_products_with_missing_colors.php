<?php

use App\Models\Ecommerce\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        $productsWithNoColors = Product::doesntHave('colors')
            ->with('type', 'type.colors')
            ->get();

        $productsWithNoColors->each(function (Product $product) {
            if (!$product->type) {
                $product->delete();
                return;
            }

            $typeColors = $product->type->colors;
            $defaultColor = $product->color_id && $typeColors->find($product->color_id) ? $typeColors->find($product->color_id) : $typeColors->first();

            foreach ($typeColors as $color) {
                $product->colors()->attach($color->id);
            }

            $product->color_id = $defaultColor->id;
            $product->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};
