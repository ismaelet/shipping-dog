<?php

use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $type = ProductType::where('name', 'Tanga')->first();
        $position = ProductPosition::where('name', 'Retro')->first();

        if ($type && $position) {
            $type->positions()->attach($position->id, ['printable' => false]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $type = ProductType::where('name', 'Tanga')->first();
        $position = ProductPosition::where('name', 'Retro')->first();

        if ($type && $position) {
            $type->positions()->detach($position->id);
        }
    }
};
