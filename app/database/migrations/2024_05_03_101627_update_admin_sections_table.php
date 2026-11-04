<?php

use App\Models\Admin\AdminSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        AdminSection::where('resource', 'carts')->update([
            'resource' => 'orders',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        AdminSection::where('resource', 'orders')->update([
            'resource' => 'carts',
        ]);
    }
};
