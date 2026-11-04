<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('extras_folder', 'logo_groups');
        Schema::rename('extras', 'logos');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('logo_groups', 'extras_folder');
        Schema::rename('logos', 'extras');
    }
};
