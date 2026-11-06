<?php

namespace App\Services\Payments;

use App\Helpers\EcommerceHelper;
use App\Mail\OrderStatusMail;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\ShippingMethod;
use App\Models\NexiPayment;
use App\Services\GlsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class NexiFulfillment
{
    public function confirm(int $paymentId): void
    {
        // Order first everywhere: serialize attempts belonging to the same order.
        $orderId = NexiPayment::findOrFail($paymentId)->order_id;
        try {
            DB::transaction(function () use ($paymentId, $orderId) {
                $order = Order::whereKey($orderId)->lockForUpdate()->firstOrFail();
                $payment = NexiPayment::whereKey($paymentId)->lockForUpdate()->firstOrFail();
                if ($payment->status !== 'paid' || ! in_array($payment->fulfillment_status, ['pending', 'failed'], true)) {
                    return;
                }
                if ((int) $order->status !== EcommerceHelper::statusFromTag('received')
                    || (int) $order->payment_method_id !== 2
                    || ! $payment->order_fingerprint
                    || ! hash_equals($payment->order_fingerprint, app(NexiOrderSnapshot::class)->fingerprint($order))) {
                    $payment->update(['fulfillment_status' => 'review', 'fulfillment_error' => 'Order changed or already processed; reconcile payment manually.']);

                    return;
                }
                // All database effects and durable external operations commit together.
                $payment->update(['fulfillment_started_at' => now()]);
                $order->confirm(sendNotification: false, createShipment: false);
                foreach (['email', 'shipment'] as $operation) {
                    DB::table('nexi_fulfillment_operations')->insert([
                        'order_id' => $order->id, 'operation' => $operation,
                        'status' => $operation === 'shipment' && ! in_array((int) $order->shipping_method_id, ShippingMethod::shippingMethodsWithLabels(), true) ? 'done' : 'pending',
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
                $payment->update(['fulfillment_status' => 'confirmed', 'fulfillment_error' => null]);
            }, 3);
        } catch (Throwable $exception) {
            // The database transaction rolled back, so database confirmation can be retried.
            NexiPayment::whereKey($paymentId)->whereIn('fulfillment_status', ['pending', 'failed'])
                ->update(['fulfillment_status' => 'failed', 'fulfillment_error' => 'Database confirmation failed; retry with nexi:process.']);
            Log::error('Nexi database fulfillment failed', ['payment_id' => $paymentId, 'exception' => $exception::class]);
        }
    }

    public function processOperation(int $id): void
    {
        if (! config('services.nexi.external_operations_enabled')) {
            return;
        }
        // Claim and commit before any network I/O. A crash leaves processing for review.
        if (DB::table('nexi_fulfillment_operations')->where('id', $id)->where('status', 'pending')
            ->update(['status' => 'processing', 'started_at' => now(), 'updated_at' => now()]) !== 1) {
            return;
        }
        $operation = DB::table('nexi_fulfillment_operations')->find($id);
        try {
            $order = Order::findOrFail($operation->order_id);
            if ((int) $order->status !== EcommerceHelper::statusFromTag('confirmed')) {
                throw new RuntimeException('Order status changed.');
            }
            if ($operation->operation === 'email') {
                // Send synchronously inside this durable operation, avoiding a second queue retry loop.
                Mail::to($order->address->email)->send(new OrderStatusMail($order));
            } elseif ($operation->operation === 'shipment') {
                if ($order->tracking || ! app(GlsService::class)->addParcels($order, singleAttempt: true)) {
                    throw new RuntimeException('Shipment outcome requires reconciliation.');
                }
            } else {
                throw new RuntimeException('Unknown operation.');
            }
            DB::table('nexi_fulfillment_operations')->where('id', $id)->update([
                'status' => 'done', 'completed_at' => now(), 'error' => null, 'updated_at' => now(),
            ]);
        } catch (Throwable $exception) {
            DB::table('nexi_fulfillment_operations')->where('id', $id)->update([
                'status' => 'review', 'error' => 'External outcome uncertain. Reconcile before any retry.', 'updated_at' => now(),
            ]);
            Log::error('Nexi external operation requires review', ['operation_id' => $id, 'exception' => $exception::class]);
        }
        if (! DB::table('nexi_fulfillment_operations')->where('order_id', $operation->order_id)->where('status', '!=', 'done')->exists()) {
            NexiPayment::where('order_id', $operation->order_id)->where('fulfillment_status', 'confirmed')
                ->update(['fulfillment_status' => 'fulfilled', 'fulfilled_at' => now()]);
        }
    }
}
