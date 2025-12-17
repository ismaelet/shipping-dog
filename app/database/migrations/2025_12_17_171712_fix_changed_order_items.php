<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        $changes = DB::table('changes')->orderBy('id', 'desc')->get();

        foreach ($changes as $change) {
            $changeRequest = DB::table('change_requests')->where('id', $change->change_request_id)->first();
            $newOrderItem = DB::table('order_items')->where('id', $change->new_order_item_id)->first();

            if ($newOrderItem) {
                DB::table('order_items')
                    ->where('id', $newOrderItem->id)
                    ->update([
                        'order_id' => $changeRequest->new_order_id,
                        'size_id' => $change->new_size_id,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};
