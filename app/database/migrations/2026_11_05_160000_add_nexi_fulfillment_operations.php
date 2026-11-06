<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nexi_payments', function (Blueprint $table) {
            $table->char('order_fingerprint', 64)->nullable();
        });
        Schema::create('nexi_fulfillment_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->string('operation', 20);
            $table->string('status', 20)->default('pending')->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('error')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'operation']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nexi_fulfillment_operations');
        Schema::table('nexi_payments', fn (Blueprint $table) => $table->dropColumn('order_fingerprint'));
    }
};
