<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nexi_payments', function (Blueprint $table) {
            $table->string('fulfillment_status', 20)
                ->default('pending');

            $table->timestamp('fulfillment_started_at')
                ->nullable();

            $table->timestamp('fulfilled_at')
                ->nullable();

            $table->text('fulfillment_error')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('nexi_payments', function (Blueprint $table) {
            $table->dropColumn([
                'fulfillment_status',
                'fulfillment_started_at',
                'fulfilled_at',
                'fulfillment_error',
            ]);
        });
    }
};
