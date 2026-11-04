<?php

use App\Models\Ecommerce\Design;
use App\Models\User\Like;
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
        Schema::table('likes', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('user_id');
        });

        $likes = Like::all();

        foreach ($likes as $like) {
            $product = Design::find($like->design_id)?->main_product;

            if ($product) {
                $like->product_id = $product->id;
                $like->save();
            }
        }

        Schema::table('likes', function (Blueprint $table) {
            $table->dropColumn('design_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->unsignedBigInteger('design_id')->after('user_id');
        });
    }
};
