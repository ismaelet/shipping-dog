<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nexi_payments', function (Blueprint $table) {
            $table->string('return_token', 64)
                ->nullable()
                ->unique();
        });
    }

    public function down(): void
    {
        Schema::table('nexi_payments', function (Blueprint $table) {
            $table->dropUnique(['return_token']);
            $table->dropColumn('return_token');
        });
    }
};
