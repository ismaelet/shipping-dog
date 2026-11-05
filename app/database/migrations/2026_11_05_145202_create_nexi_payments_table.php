<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        DB::table('addresses')->where('order_id', 0)->delete();
        DB::table('order_items')->where('order_id', 0)->delete();
        DB::table('orders')->where('id', 0)->delete();

        DB::statement('
            ALTER TABLE orders
            MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
        ');

        DB::statement('
            ALTER TABLE addresses
            MODIFY order_id BIGINT UNSIGNED NOT NULL DEFAULT 0
        ');

        DB::statement('
            ALTER TABLE order_items
            MODIFY order_id BIGINT UNSIGNED NOT NULL DEFAULT 0
        ');

        Schema::create('nexi_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->restrictOnDelete();

            $table->string('transaction_id', 30)->unique();

            $table->unsignedBigInteger('amount');

            $table->string('currency', 3)->default('EUR');

            $table->string('status', 20)->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nexi_payments');
    }
};
