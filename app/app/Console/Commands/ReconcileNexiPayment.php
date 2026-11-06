<?php

namespace App\Console\Commands;

use App\Services\Payments\NexiPaymentReconciliation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReconcileNexiPayment extends Command
{
    protected $signature = 'nexi:reconcile-payment {payment : Local Nexi payment attempt ID}';

    protected $description = 'Verify a Nexi payment against the current REST order status';

    public function handle(NexiPaymentReconciliation $reconciliation): int
    {
        $paymentId = (int) $this->argument('payment');

        try {
            $status = $reconciliation->reconcile($paymentId);
        } catch (Throwable $exception) {
            Log::error('Nexi payment reconciliation failed', [
                'payment_id' => $paymentId,
                'exception' => $exception::class,
            ]);
            $this->error('Nexi payment could not be verified. No new payment or fulfillment was started.');

            return self::FAILURE;
        }

        $this->info("Nexi payment {$paymentId} provider status: {$status}.");

        return self::SUCCESS;
    }
}
