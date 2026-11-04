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
        Schema::table('admin_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->timestamps();
            $table->renameColumn('sort', 'order');
            $table->renameColumn('manager', 'for_supervisors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_categories', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->dropTimestamps();
            $table->renameColumn('order', 'sort');
            $table->renameColumn('for_supervisors', 'manager');
        });
    }
};
