<?php

namespace App\Console\Commands;

use App\Models\NexiPayment;
use App\Services\Payments\NexiFulfillment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessNexiPayments extends Command
{
    protected $signature = 'nexi:process {--payment= : Process only this payment and its order operations}';

    protected $description = 'Confirm verified Nexi payments and process pending external operations';

    public function handle(NexiFulfillment $fulfillment): int
    {
        $paymentId = $this->option('payment');
        $orderId = $paymentId ? NexiPayment::findOrFail($paymentId)->order_id : null;
        NexiPayment::when($paymentId, fn ($query) => $query->whereKey($paymentId))->where('status', 'paid')->whereIn('fulfillment_status', ['pending', 'failed'])
            ->eachById(fn ($payment) => $fulfillment->confirm($payment->id));
        DB::table('nexi_fulfillment_operations')->when($orderId, fn ($query) => $query->where('order_id', $orderId))->where('status', 'pending')->orderBy('id')
            ->eachById(fn ($operation) => $fulfillment->processOperation($operation->id));
        // Recover a crash after the final operation commits but before the payment summary updates.
        NexiPayment::where('fulfillment_status', 'confirmed')
            ->whereExists(fn ($query) => $query->selectRaw('1')->from('nexi_fulfillment_operations')->whereColumn('order_id', 'nexi_payments.order_id'))
            ->whereNotExists(fn ($query) => $query->selectRaw('1')->from('nexi_fulfillment_operations')->whereColumn('order_id', 'nexi_payments.order_id')->where('status', '!=', 'done'))
            ->update(['fulfillment_status' => 'fulfilled', 'fulfilled_at' => now()]);
        if (! config('services.nexi.external_operations_enabled')) {
            $this->comment('External Nexi operations are disabled; pending email/shipment operations remain recorded.');
        }
        $review = DB::table('nexi_fulfillment_operations')->whereIn('status', ['review', 'processing'])->count()
            + NexiPayment::where('status', 'paid')->whereIn('fulfillment_status', ['review', 'failed'])->count();
        if ($review) {
            $this->warn("{$review} Nexi records require inspection; see docs/nexi-xpay.md.");
        }

        return self::SUCCESS;
    }
}
