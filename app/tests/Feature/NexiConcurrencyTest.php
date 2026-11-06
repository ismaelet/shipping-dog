<?php

use App\Models\Ecommerce\Order;
use App\Models\NexiPayment;
use App\Services\Payments\NexiOrderSnapshot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Tests\TestCase;

uses(TestCase::class);

it('serializes two independent fulfillment processes on MariaDB', function () {
    // Committed fixtures are necessary for two independent database connections.
    // Clean up only the exact records created by this test, never reset the schema.
    expect(DB::connection()->getDatabaseName())->toBe('shipping_dog_test');
    $order = Order::create(['price' => '12.34', 'payment_method_id' => 2, 'shipping_method_id' => 11, 'status' => 1]);
    try {
        DB::table('addresses')->insert(['order_id' => $order->id, 'full_name' => 'Concurrency Test', 'address' => 'Test', 'city' => 'Test', 'email' => 'concurrency@example.test']);
        DB::table('order_items')->insert(['order_id' => $order->id]);
        $payment = $order->nexiPayments()->create([
            'transaction_id' => 'SD'.Str::upper(Str::random(24)), 'amount' => 1234, 'currency' => 'EUR',
            'status' => 'paid', 'paid_at' => now(), 'order_fingerprint' => app(NexiOrderSnapshot::class)->fingerprint($order->fresh()),
        ]);
        $environment = ['APP_ENV' => 'testing', 'NEXI_EXTERNAL_OPERATIONS_ENABLED' => 'false'];
        $first = new Process([PHP_BINARY, 'artisan', 'nexi:process', '--payment='.$payment->id], base_path(), $environment);
        $second = new Process([PHP_BINARY, 'artisan', 'nexi:process', '--payment='.$payment->id], base_path(), $environment);
        $first->start();
        $second->start();
        $first->wait();
        $second->wait();
        expect($first->isSuccessful())->toBeTrue($first->getOutput().$first->getErrorOutput());
        expect($second->isSuccessful())->toBeTrue($second->getOutput().$second->getErrorOutput());
        expect($order->fresh()->status)->toBe(2);
        expect($payment->fresh()->fulfillment_status)->toBe('confirmed');
        expect(DB::table('nexi_fulfillment_operations')->where('order_id', $order->id)->count())->toBe(2);
    } finally {
        DB::table('nexi_fulfillment_operations')->where('order_id', $order->id)->delete();
        NexiPayment::where('order_id', $order->id)->delete();
        DB::table('order_items')->where('order_id', $order->id)->delete();
        DB::table('addresses')->where('order_id', $order->id)->delete();
        DB::table('orders')->where('id', $order->id)->delete();
    }
});
