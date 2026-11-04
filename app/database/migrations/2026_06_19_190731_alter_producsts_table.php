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
        Schema::create('product_product_color', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id');

            $table->unsignedBigInteger('product_color_id');

            $table->timestamps();

            $table->unique(['product_id', 'product_color_id']);
        });

        DB::table('products')
            ->whereNotNull('colors')
            ->where('colors', '!=', '')
            ->orderBy('id')
            ->chunkById(500, function ($products) {
                foreach ($products as $product) {
                    $tags = collect(explode(',', $product->colors))
                        ->map(fn ($tag) => trim($tag))
                        ->filter()
                        ->unique()
                        ->values();

                    if ($tags->isEmpty()) {
                        continue;
                    }

                    $colorIds = DB::table('product_colors')
                        ->whereIn('tag', $tags)
                        ->pluck('id');

                    foreach ($colorIds as $colorId) {
                        DB::table('product_product_color')->updateOrInsert([
                            'product_id' => $product->id,
                            'product_color_id' => $colorId,
                        ], [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('colors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('colors')->nullable();
        });

        $products = DB::table('products')->pluck('id');

        foreach ($products as $productId) {
            $tags = DB::table('product_product_color')
                ->join('product_colors', 'product_product_color.product_color_id', '=', 'product_colors.id')
                ->where('product_product_color.product_id', $productId)
                ->pluck('product_colors.tag')
                ->implode(',');

            DB::table('products')
                ->where('id', $productId)
                ->update(['colors' => $tags ?: null]);
        }

        Schema::dropIfExists('product_product_color');
    }
};
