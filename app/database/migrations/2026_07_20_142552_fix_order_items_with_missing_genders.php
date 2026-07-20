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
        $typesWithoutGenders = DB::table('product_types')
            ->whereNull('gender_id')
            ->orWhere('gender_id', 0)
            ->get();

        $orderItems = DB::table('order_items')
            ->whereNotIn('type_id', $typesWithoutGenders->pluck('id'))
            ->where(function ($query) {
                $query->whereNull('gender_id')
                    ->orWhere('gender_id', 0);
            })
            ->get();

        foreach ($orderItems as $item) {
            $product = DB::table('products')->where('id', $item->product_id)->first();

            if ($product) {
                $genderId = DB::table('product_types')->where('id', $product->type_id)->value('gender_id');

                if ($genderId) {
                    DB::table('order_items')
                        ->where('id', $item->id)
                        ->update(['gender_id' => $genderId]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};
