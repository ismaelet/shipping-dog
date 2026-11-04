<?php

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
        Schema::rename('designs_tags', 'design_tags');

        Schema::table('design_tags', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('design_tags', 'designs_tags');

        Schema::table('designs_tags', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
