<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sellers = DB::table('sellers')->get();

        $sellers->each(function ($seller) {
            $credit = $seller->credit;

            if ($credit) {
                DB::table('seller_payments')->insert([
                    'seller_id' => $seller->id,
                    'value' => $credit,
                    'other' => true,
                    'description' => 'Trasferimento del cashback del negozio',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('sellers')->where('id', $seller->id)->update(['credit' => 0, 'other_transfers' => DB::raw('other_transfers + '.$credit)]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $sellers = DB::table('sellers')->get();

        $sellers->each(function ($seller) {
            $payments = DB::table('seller_payments')
                ->where('seller_id', $seller->id)
                ->where('other', true)
                ->where('description', 'Trasferimento del cashback del negozio')
                ->get();

            if ($payments->isNotEmpty()) {
                $credit = $payments->sum('value');

                DB::table('sellers')->where('id', $seller->id)->update(['credit' => DB::raw('credit + '.$credit), 'other_transfers' => DB::raw('other_transfers - '.$credit)]);

                DB::table('seller_payments')
                    ->where('seller_id', $seller->id)
                    ->where('other', true)
                    ->where('description', 'Trasferimento del cashback del negozio')
                    ->delete();
            }
        });
    }
};
