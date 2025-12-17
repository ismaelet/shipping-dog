<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('design_id')->nullable()->change();
            $table->unsignedBigInteger('type_id')->nullable()->change();
        });

        DB::table('inventory')->where('product_id', null)->get()->each(function ($item) {
            $designId = $item->design_id;
            $typeId = $item->type_id;

            $product = DB::table('products')
                ->where('design_id', $designId)
                ->where('type_id', $typeId)
                ->first();

            if ($product) {
                DB::table('inventory')
                    ->where('id', $item->id)
                    ->update(['product_id' => $product->id]);
            }
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('design_id')->nullable()->change();
        });

        DB::table('products')->where('design_id', 0)->update(['design_id' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
    }
};
