<?php

use App\Models\Ecommerce\Design\CustomerPhoto;
use App\Models\Ecommerce\Product;
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
        Schema::table('home_pics', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('design_id');
            $table->unsignedBigInteger('type_id')->nullable()->after('product_id');
            $table->timestamps();
        });

        $customerPhotos = CustomerPhoto::with('design')->get();

        foreach ($customerPhotos as $photo) {
            if ($photo->design && $photo->type) {
                $type = ProductType::where('tag', $photo->type)->first() ?: ProductType::find(1);

                $product = Product::where('design_id', $photo->design->id)
                    ->where('type_id', $type ? $type->id : null)
                    ->first();

                $photo->update([
                    'product_id' => $product ? $product->id : null,
                    'type_id' => $type ? $type->id : null,
                ]);
            }
        }

        Schema::table('home_pics', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_pics', function (Blueprint $table) {
            $table->dropColumn(['product_id', 'type_id']);
            $table->dropTimestamps();
            $table->string('type')->nullable()->after('design_id');
        });
    }
};
