<?php

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
        if (! Schema::hasTable('product_position_product_type')) {
            Schema::create('product_position_product_type', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_type_id');
                $table->unsignedBigInteger('product_position_id');

                // Foreign keys
                // $table->foreign('product_type_id')->references('id')->on('product_types')->onDelete('cascade');
                // $table->foreign('product_position_id')->references('id')->on('product_positions')->onDelete('cascade');
            });
        }

        ProductType::all()->each(function (ProductType $productType) {
            $positions = $productType->positions;
            $position = explode(',', $positions);

            foreach ($position as $pos) {
                $productType->positions()->attach($pos);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_position_product_type');
    }
};
