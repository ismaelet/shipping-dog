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
        Schema::rename('links', 'user_contacts');

        Schema::table('user_contacts', function (Blueprint $table) {
            $table->string('site')->nullable()->after('user_id')->change();
            $table->renameColumn('site', 'website');
            $table->string('facebook')->nullable()->after('website')->change();
            $table->string('instagram')->nullable()->after('facebook')->change();
            $table->string('twitter')->nullable()->after('instagram')->change();
            $table->string('youtube')->nullable()->after('twitter')->change();
            $table->string('tiktok')->nullable()->after('youtube');
            $table->string('bluesky')->nullable()->after('youtube');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_contacts', function (Blueprint $table) {
            $table->renameColumn('website', 'site');
            $table->dropColumn(['tiktok', 'bluesky']);
            $table->dropTimestamps();
        });

        Schema::rename('user_contacts', 'links');
    }
};
