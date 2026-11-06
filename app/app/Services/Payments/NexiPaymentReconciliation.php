<?php

namespace App\Services\Payments;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use App\Models\NexiPayment;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class NexiPaymentReconciliation
{
    public function __construct(private readonly NexiService $nexi) {}

    public function reconcile(int $paymentId): string
    {
        $payment = NexiPayment::findOrFail($paymentId);
        if ($payment->initialization_status !== 'ready'
            || ! $payment->provider_order_id
            || ! $payment->security_token
            || $payment->currency !== 'EUR') {
            throw new RuntimeException('Nexi payment is not ready for provider reconciliation.');
        }

        $providerStatus = $this->nexi->orderStatus($payment->provider_order_id, $payment->amount);

        return DB::transaction(function () use ($payment, $providerStatus) {
            $order = Order::whereKey($payment->order_id)->lockForUpdate()->firstOrFail();
            $locked = NexiPayment::whereKey($payment->id)->lockForUpdate()->firstOrFail();

            if ($locked->status === 'paid') {
                return 'paid';
            }

            if (in_array($locked->status, ['failed', 'cancelled'], true) && $providerStatus !== 'paid') {
                return $locked->status;
            }

            if ($providerStatus === 'paid') {
                $compatible = (int) $order->status === EcommerceHelper::statusFromTag('received')
                    && (int) $order->payment_method_id === 2
                    && $locked->order_fingerprint
                    && hash_equals($locked->order_fingerprint, app(NexiOrderSnapshot::class)->fingerprint($order));

                $locked->update([
                    'status' => 'paid',
                    'paid_at' => $locked->paid_at ?? now(),
                    'fulfillment_status' => $compatible ? 'pending' : 'review',
                    'fulfillment_error' => $compatible ? null : 'Order changed; reconcile verified payment manually.',
                ]);

                return 'paid';
            }

            if (in_array($providerStatus, ['authorized', 'failed', 'cancelled'], true)) {
                $locked->update(['status' => $providerStatus]);
            }

            return $providerStatus;
        }, 3);
    }
}
