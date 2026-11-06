<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nexi_payments', function (Blueprint $table) {
            $table->string('provider_order_id', 27)->nullable()->unique();
            $table->text('hosted_page_url')->nullable();
            $table->text('security_token')->nullable();
            $table->string('initialization_status', 20)->default('not_started');
            $table->timestamp('initialized_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('nexi_payments', function (Blueprint $table) {
            $table->dropUnique(['provider_order_id']);
            $table->dropColumn([
                'provider_order_id',
                'hosted_page_url',
                'security_token',
                'initialization_status',
                'initialized_at',
            ]);
        });
    }
};
