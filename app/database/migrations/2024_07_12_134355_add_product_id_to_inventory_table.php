<?php

use App\Models\Ecommerce\InventoryItem;
use App\Models\Ecommerce\Product;
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
        Schema::table('inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
        });

        InventoryItem::all()->each(function (InventoryItem $item) {
            $product = Product::where('design_id', $item->design_id)
                ->where('type_id', $item->type_id)->first();
            $item->product_id = $product->id;
            $item->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }
};
