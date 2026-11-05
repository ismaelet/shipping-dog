<?php

use App\Models\Ecommerce\PaymentMethod;
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
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->timestamps();
            $table->string('tag', 255)->change();
        });

        PaymentMethod::where('id', 2)->update([
            'name' => 'Carta di credito o debito',
            'tag' => 'nexi',
            'desc' => 'Paga in sicurezza con carta di credito o debito tramite Nexi XPay.',
            'active' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
