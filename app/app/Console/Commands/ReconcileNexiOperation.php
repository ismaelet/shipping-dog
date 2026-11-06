<?php

namespace App\Console\Commands;

use App\Models\NexiPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReconcileNexiOperation extends Command
{
    protected $signature = 'nexi:reconcile-operation {id} {resolution : done or retry} {--verified : I checked the external outcome and stopped any worker processing this operation}';

    protected $description = 'Resolve an uncertain Nexi email/shipment operation after external reconciliation';

    public function handle(): int
    {
        if (! $this->option('verified') || ! in_array($this->argument('resolution'), ['done', 'retry'], true)) {
            $this->error('Stop the relevant worker and verify the external outcome first. Use done for completed operations, retry only when non-execution is proven; then add --verified.');

            return self::FAILURE;
        }
        $resolved = DB::transaction(function () {
            $operation = DB::table('nexi_fulfillment_operations')->where('id', $this->argument('id'))->lockForUpdate()->first();
            if (! $operation || ! in_array($operation->status, ['review', 'processing'], true)) {
                return false;
            }
            $done = $this->argument('resolution') === 'done';
            DB::table('nexi_fulfillment_operations')->where('id', $operation->id)->update([
                'status' => $done ? 'done' : 'pending', 'error' => null,
                'started_at' => null, 'completed_at' => $done ? now() : null, 'updated_at' => now(),
            ]);
            if (! DB::table('nexi_fulfillment_operations')->where('order_id', $operation->order_id)->where('status', '!=', 'done')->exists()) {
                NexiPayment::where('order_id', $operation->order_id)->where('fulfillment_status', 'confirmed')->update([
                    'fulfillment_status' => 'fulfilled', 'fulfilled_at' => now(),
                ]);
            }
            Log::notice('Nexi operation reconciled by operator', ['operation_id' => $operation->id, 'resolution' => $this->argument('resolution')]);

            return true;
        });
        if (! $resolved) {
            $this->error('Operation is missing or does not require reconciliation.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
