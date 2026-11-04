<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admin_sections', function (Blueprint $table) {
            $table->timestamps();
            $table->unsignedBigInteger('category_id');
            $table->renameColumn('sort', 'order');
            $table->renameColumn('manager', 'for_supervisors');
        });

        foreach (DB::table('admin_sections')->get() as $section) {
            $category = DB::table('admin_categories')->where('slug', $section->category)->first();

            DB::table('admin_sections')->where('id', $section->id)->update([
                'category_id' => $category->id ?? 0,
            ]);
        }

        Schema::table('admin_sections', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_sections', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->dropColumn('category_id');
            $table->renameColumn('order', 'sort');
            $table->renameColumn('for_supervisors', 'manager');
        });

        Schema::table('admin_sections', function (Blueprint $table) {
            $table->string('category')->after('id');
        });
    }
};
