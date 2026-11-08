<?php

use App\Mail\OrderStatusMail;
use App\Models\Ecommerce\Coupon;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\PaymentMethod;
use App\Models\Ecommerce\Seller;
use App\Models\NexiPayment;
use App\Models\User;
use App\Services\GlsService;
use App\Services\Payments\NexiFulfillment;
use App\Services\Payments\NexiOrderSnapshot;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function () {
    expect(DB::connection()->getDatabaseName())->toBe('shipping_dog_test');
    Http::preventStrayRequests();
    Mail::fake();
    $GLOBALS['nexi_http_fake_installed'] = false;
    URL::forceRootUrl('https://shipping-dog.test');
    URL::forceScheme('https');
    config([
        'services.nexi.external_operations_enabled' => true,
        'services.nexi.sandbox_api_key' => 'test-api-key',
        'services.nexi.sandbox' => true,
    ]);
    fakeNexiApi();
    DB::table('payment_methods')->updateOrInsert(['id' => 2], ['tag' => 'nexi', 'name' => 'Carta', 'desc' => '', 'active' => 1]);
    $this->order = Order::create(['price' => '12.34', 'payment_method_id' => 2, 'shipping_method_id' => 11, 'status' => 0, 'step' => 4]);
    DB::table('addresses')->insert(['order_id' => $this->order->id, 'full_name' => 'Test Buyer', 'address' => 'Test street', 'city' => 'Test', 'email' => 'buyer@example.test']);
    DB::table('order_items')->insert(['order_id' => $this->order->id, 'quantity' => 1, 'total_amount_to_pay' => '12.34']);
    $this->withSession(['cart_id' => $this->order->id]);
    $this->start = fn () => $this->post(route('nexi.orders.store', $this->order));
});

function fakeNexiApi(
    string $operationType = 'CAPTURE',
    string $operationResult = 'EXECUTED',
    string $amount = '1234',
    string $currency = 'EUR',
    int $hppStatus = 200,
    bool $connectionFailure = false
): void {
    $GLOBALS['nexi_operation'] = compact('operationType', 'operationResult', 'amount', 'currency', 'hppStatus', 'connectionFailure');
    if ($GLOBALS['nexi_http_fake_installed']) {
        return;
    }

    $GLOBALS['nexi_http_fake_installed'] = true;
    Http::fake([
        'https://xpaysandbox.nexigroup.com/api/phoenix-0.0/psp/api/v1/orders/hpp' => function (Request $request) {
            return Http::response([
                'hostedPage' => 'https://xpaysandbox.nexigroup.com/checkout/session',
                'securityToken' => 'provider-security-token',
            ], $GLOBALS['nexi_operation']['hppStatus']);
        },
        'https://xpaysandbox.nexigroup.com/api/phoenix-0.0/psp/api/v1/orders/*' => function (Request $request) {
            if ($GLOBALS['nexi_operation']['connectionFailure']) {
                throw new ConnectionException('simulated Nexi timeout');
            }

            $orderId = basename(parse_url($request->url(), PHP_URL_PATH));

            return Http::response([
                'operations' => [[
                    'orderId' => $orderId,
                    'operationType' => $GLOBALS['nexi_operation']['operationType'],
                    'operationResult' => $GLOBALS['nexi_operation']['operationResult'],
                    'operationAmount' => $GLOBALS['nexi_operation']['amount'],
                    'operationCurrency' => $GLOBALS['nexi_operation']['currency'],
                ]],
            ]);
        },
    ]);
}

function nexiNotification(NexiPayment $payment, array $overrides = []): array
{
    return array_replace([
        'operation' => ['orderId' => $payment->provider_order_id],
        'securityToken' => $payment->security_token,
    ], $overrides);
}

it('uses GET only for a safe landing page and creates one attempt on repeated POST', function () {
    $this->get(route('nexi.orders.create', $this->order))->assertOk();
    expect(NexiPayment::count())->toBe(0);
    ($this->start)()->assertRedirect('https://xpaysandbox.nexigroup.com/checkout/session');
    ($this->start)()->assertRedirect('https://xpaysandbox.nexigroup.com/checkout/session');
    expect(NexiPayment::count())->toBe(1);
    expect($this->order->fresh()->status)->toBe(1);
    expect(NexiPayment::first()->amount)->toBe(1234);
    Http::assertSentCount(1);
});

it('stores the returned order reference and encrypts the notification security token', function () {
    ($this->start)()->assertRedirect('https://xpaysandbox.nexigroup.com/checkout/session');
    $payment = NexiPayment::first();

    expect($payment->provider_order_id)->toBe($payment->transaction_id);
    expect($payment->initialization_status)->toBe('ready');
    expect($payment->security_token)->toBe('provider-security-token');
    expect(DB::table('nexi_payments')->where('id', $payment->id)->value('security_token'))
        ->not->toBe('provider-security-token');
    Http::assertSent(fn (Request $request) => str_ends_with($request->url(), '/orders/hpp')
        && $request->data()['paymentSession']['actionType'] === 'PAY'
        && $request->data()['paymentSession']['captureType'] === 'IMPLICIT'
        && $request->hasHeader('X-Api-Key', 'test-api-key')
        && $request->hasHeader('Correlation-Id'));
});

it('does not automatically retry an HPP request with an uncertain result', function () {
    fakeNexiApi(hppStatus: 503);

    $this->post(route('nexi.orders.store', $this->order))->assertStatus(503);
    expect(NexiPayment::first()->initialization_status)->toBe('review');
    $this->post(route('nexi.orders.store', $this->order))->assertStatus(409);
    Http::assertSentCount(1);
});

it('does not fulfill an authorization and advances only after a captured operation', function () {
    fakeNexiApi('AUTHORIZATION', 'AUTHORIZED');
    ($this->start)();
    $payment = NexiPayment::first();

    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    expect($payment->fresh()->status)->toBe('authorized');
    app(NexiFulfillment::class)->confirm($payment->id);
    expect($this->order->fresh()->status)->toBe(1);
    expect(DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->count())->toBe(0);

    fakeNexiApi('CAPTURE');
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    expect($payment->fresh()->status)->toBe('paid');
});

it('rejects notifications without JSON or a valid security token', function () {
    ($this->start)();
    $payment = NexiPayment::first();

    $this->post(route('nexi.orders.notification'), nexiNotification($payment))->assertStatus(415);
    $this->postJson(route('nexi.orders.notification'), ['orderId' => $payment->provider_order_id])->assertForbidden();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment, ['securityToken' => 'wrong']))
        ->assertForbidden();
    expect($payment->fresh()->status)->toBe('pending');
});

it('does not change payment state when the provider status cannot be verified', function () {
    ($this->start)();
    $payment = NexiPayment::first();
    fakeNexiApi(connectionFailure: true);

    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertStatus(503);
    expect($payment->fresh()->status)->toBe('pending');
    expect($payment->fresh()->fulfillment_status)->toBe('pending');
});

it('reconciles delayed notifications through a read-only provider status check', function () {
    ($this->start)();
    $payment = NexiPayment::first();

    $this->artisan('nexi:reconcile-payment', ['payment' => $payment->id])
        ->expectsOutputToContain('provider status: paid')
        ->assertSuccessful();

    expect($payment->fresh()->status)->toBe('paid');
    expect($this->order->fresh()->status)->toBe(1);
    expect(DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->count())->toBe(0);
});

it('rejects unauthorized guests and authenticated nonowners', function () {
    $this->withSession(['cart_id' => null, 'nexi_order_id' => null]);
    ($this->start)()->assertForbidden();
    $owner = User::factory()->create();
    $this->order->update(['user_id' => $owner->id]);
    $this->actingAs(User::factory()->create())->withSession(['cart_id' => $this->order->id]);
    ($this->start)()->assertForbidden();
});

it('allows an authenticated owner without a cart session', function () {
    $owner = User::factory()->create();
    $this->order->update(['user_id' => $owner->id]);
    $this->actingAs($owner)->withSession(['cart_id' => null]);
    ($this->start)()->assertRedirect();
});

it('rejects ineligible orders', function (array $attributes, int $status) {
    $this->order->update($attributes);
    ($this->start)()->assertStatus($status);
    expect(NexiPayment::count())->toBe(0);
})->with([
    'cancelled' => [['status' => -1], 409],
    'confirmed' => [['status' => 2], 409],
    'wrong method' => [['payment_method_id' => 1], 422],
    'free' => [['price' => '0.00'], 503],
]);

it('rejects inactive Nexi and rolls back initialization on missing configuration', function () {
    DB::table('payment_methods')->where('id', 2)->update(['active' => 0]);
    ($this->start)()->assertStatus(422);
    DB::table('payment_methods')->where('id', 2)->update(['active' => 1]);
    config(['services.nexi.sandbox_api_key' => null]);
    ($this->start)()->assertStatus(503);
    expect(NexiPayment::count())->toBe(0);
    expect($this->order->fresh()->status)->toBe(0);
});

it('does not trust request amounts and detects same-price item mutations', function () {
    $this->post(route('nexi.orders.store', $this->order), ['amount' => 1])->assertRedirect();
    $payment = NexiPayment::first();
    expect($payment->amount)->toBe(1234);
    DB::table('order_items')->where('order_id', $this->order->id)->update(['quantity' => 2]);
    ($this->start)()->assertStatus(409);
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    expect($payment->fresh()->status)->toBe('paid');
    expect($payment->fresh()->fulfillment_status)->toBe('review');
});

it('accepts success durably and confirms only in the processor', function () {
    ($this->start)()->assertRedirect();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    expect($this->order->fresh()->status)->toBe(1);
    expect($payment->fresh()->status)->toBe('paid');
    app(NexiFulfillment::class)->confirm($payment->id);
    app(NexiFulfillment::class)->confirm($payment->id);
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    expect($this->order->fresh()->status)->toBe(2);
    expect(DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->count())->toBe(2);
    Mail::assertNothingSent();
    $this->artisan('nexi:process')->assertSuccessful();
    $this->artisan('nexi:process')->assertSuccessful();
    Mail::assertSent(OrderStatusMail::class, 1);
    expect($payment->fresh()->fulfillment_status)->toBe('fulfilled');
});

it('rejects malformed unsigned and mismatched notifications', function (string $kind, int $status) {
    ($this->start)();
    $payment = NexiPayment::first();
    $data = match ($kind) {
        'unknown' => nexiNotification($payment, ['operation' => ['orderId' => 'SDUNKNOWN']]),
        'missing' => [],
        default => nexiNotification($payment, ['securityToken' => 'invalid-token']),
    };
    $this->postJson(route('nexi.orders.notification'), $data)->assertStatus($status);
    expect($payment->fresh()->status)->toBe('pending');
})->with([['unknown', 404], ['missing', 403], ['invalid token', 403]]);

it('allows a verified capture to advance a failed or cancelled payment without later downgrade', function (string $operationResult) {
    fakeNexiApi('AUTHORIZATION', $operationResult);
    ($this->start)();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    expect($this->order->fresh()->status)->toBe(1);
    ($this->start)()->assertStatus(409);
    fakeNexiApi('CAPTURE', 'EXECUTED');
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    fakeNexiApi('AUTHORIZATION', $operationResult);
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    expect($payment->fresh()->status)->toBe('paid');
    ($this->start)()->assertStatus(409);
})->with(['DECLINED', 'CANCELED']);

it('validates return capabilities without trusting browser outcomes or exposing the order token', function () {
    ($this->start)();
    $payment = NexiPayment::first();
    $this->order->update(['token' => 'private-reusable-order-token']);
    $this->get(route('nexi.orders.result', $this->order))->assertForbidden();
    $url = route('nexi.orders.result', ['order' => $this->order->id, 'return_token' => $payment->return_token, 'esito' => 'OK']);
    $this->get($url)->assertOk()->assertSee('In attesa')->assertDontSee('private-reusable-order-token');
    expect($payment->fresh()->status)->toBe('pending');
    $this->get($this->order->recapUrl())->assertOk()->assertDontSee('private-reusable-order-token');
    $this->get(route('nexi.orders.cancel', ['order' => $this->order->id, 'return_token' => $payment->return_token]))->assertOk();
    expect($this->order->fresh()->status)->toBe(1);
});

it('sends ambiguous GLS outcomes to review without retrying', function () {
    $this->order->update(['shipping_method_id' => 2]);
    ($this->start)();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    app(NexiFulfillment::class)->confirm($payment->id);
    $this->mock(GlsService::class)->shouldReceive('addParcels')->once()->andReturn(false);
    $id = DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->where('operation', 'shipment')->value('id');
    app(NexiFulfillment::class)->processOperation($id);
    app(NexiFulfillment::class)->processOperation($id);
    expect(DB::table('nexi_fulfillment_operations')->find($id)->status)->toBe('review');
    expect($payment->fresh()->fulfillment_status)->toBe('confirmed');
});

it('does not retry claimed operations or repeat confirmation after email errors', function () {
    ($this->start)();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment));
    app(NexiFulfillment::class)->confirm($payment->id);
    $id = DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->where('operation', 'email')->value('id');
    Mail::swap(Mockery::mock(Mailer::class, function ($mock) {
        $mock->shouldReceive('to')->once()->andThrow(new RuntimeException('Transport failure'));
    }));
    app(NexiFulfillment::class)->processOperation($id);
    app(NexiFulfillment::class)->processOperation($id);
    app(NexiFulfillment::class)->confirm($payment->id);
    expect(DB::table('nexi_fulfillment_operations')->find($id)->status)->toBe('review');
    expect($this->order->fresh()->status)->toBe(2);
});

it('rolls back database confirmation and safely retries it after a database failure', function () {
    ($this->start)();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment));
    $snapshot = Mockery::mock(NexiOrderSnapshot::class);
    $snapshot->shouldReceive('fingerprint')->once()->andThrow(new RuntimeException('Database failure'));
    app()->instance(NexiOrderSnapshot::class, $snapshot);
    app(NexiFulfillment::class)->confirm($payment->id);
    expect($payment->fresh()->fulfillment_status)->toBe('failed');
    expect($this->order->fresh()->status)->toBe(1);
    expect(DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->count())->toBe(0);
    app()->forgetInstance(NexiOrderSnapshot::class);
    app(NexiFulfillment::class)->confirm($payment->id);
    expect($this->order->fresh()->status)->toBe(2);
});

it('restricts payment methods for free shipping pickup and disabled gateways', function () {
    foreach ([1, 3, 4] as $id) {
        DB::table('payment_methods')->updateOrInsert(['id' => $id], ['active' => 1, 'tag' => 'test', 'name' => 'Test', 'desc' => '']);
    }
    expect(PaymentMethod::allowedFor($this->order)->pluck('id')->all())->toEqual([1, 2, 3]);
    $this->order->update(['shipping_method_id' => 2]);
    expect(PaymentMethod::allowedFor($this->order)->pluck('id')->all())->toEqual([1, 2]);
    DB::table('payment_methods')->where('id', 2)->update(['active' => 0]);
    expect(PaymentMethod::allowedFor($this->order)->pluck('id')->all())->toEqual([1]);
    $this->order->update(['price' => '0.00']);
    expect(PaymentMethod::allowedFor($this->order)->pluck('id')->all())->toEqual([4]);
});

it('does not duplicate seller credit coupon consumption inventory or seller balances', function () {
    $owner = User::factory()->create();
    $seller = Seller::create(['user_id' => $owner->id, 'name' => 'Nexi seller', 'status' => 1]);
    $coupon = Coupon::create(['code' => 'NEXITEST', 'permanent' => 0, 'one_per_acccount' => 1]);
    $this->order->update(['user_id' => $owner->id, 'code' => $coupon->code]);
    $this->actingAs($owner);
    DB::table('order_items')->where('order_id', $this->order->id)->update([
        'seller_id' => $seller->id, 'credit' => 1, 'total_amount_after_discount' => '5.00', 'size_id' => 1,
        'total_seller_earnings_after_reduction' => '2.00', 'position_id' => 0,
    ]);
    $inventoryId = DB::table('inventory')->insertGetId(['product_id' => 0, 'color_id' => 0, 'size_id' => 1, 'position_id' => 0]);
    ($this->start)()->assertRedirect();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    app(NexiFulfillment::class)->confirm($payment->id);
    expect($payment->fresh()->fulfillment_status)->toBe('confirmed');
    $balance = $seller->fresh()->getRawOriginal();
    app(NexiFulfillment::class)->confirm($payment->id);
    expect(DB::table('seller_payments')->where('seller_id', $seller->id)->where('value', -5)->count())->toBe(1);
    expect(DB::table('used_coupons')->where('coupon_id', $coupon->id)->count())->toBe(1);
    expect(DB::table('coupons')->where('id', $coupon->id)->exists())->toBeFalse();
    expect(DB::table('inventory')->where('id', $inventoryId)->exists())->toBeFalse();
    expect($seller->fresh()->getRawOriginal())->toBe($balance);
});

it('does not acknowledge a callback whose durable write failed', function () {
    ($this->start)();
    $payment = NexiPayment::first();
    $fail = true;
    DB::listen(function ($query) use (&$fail) {
        if ($fail && str_starts_with($query->sql, 'update `nexi_payments`')) {
            $fail = false;
            throw new RuntimeException('Simulated durable write failure');
        }
    });
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertStatus(503);
    expect($payment->fresh()->status)->toBe('pending');
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    expect($payment->fresh()->status)->toBe('paid');
});

it('does not repeat an operation already claimed by another worker and permits explicit reconciliation', function () {
    ($this->start)();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment));
    app(NexiFulfillment::class)->confirm($payment->id);
    $id = DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->where('operation', 'email')->value('id');
    DB::table('nexi_fulfillment_operations')->where('id', $id)->update(['status' => 'processing']);
    app(NexiFulfillment::class)->processOperation($id);
    Mail::assertNothingSent();
    $this->artisan('nexi:reconcile-operation', ['id' => $id, 'resolution' => 'retry'])->assertFailed();
    $this->artisan('nexi:reconcile-operation', ['id' => $id, 'resolution' => 'retry', '--verified' => true])->assertSuccessful();
    app(NexiFulfillment::class)->processOperation($id);
    app(NexiFulfillment::class)->processOperation($id);
    Mail::assertSent(OrderStatusMail::class, 1);
});

it('does not confirm a provider-side failed payment', function () {
    fakeNexiApi('AUTHORIZATION', 'DECLINED');
    ($this->start)();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment))->assertOk();
    expect($payment->fresh()->status)->toBe('failed');
    expect($payment->fresh()->fulfillment_status)->toBe('pending');
});

it('keeps the existing PayPal capture and confirmation workflow', function () {
    config(['services.paypal.client_id' => 'test-client', 'services.paypal.client_secret' => 'test-secret']);
    $this->order->update(['payment_method_id' => 1, 'token' => 'PAYPALTEST']);
    Http::fake([
        '*/v1/oauth2/token' => Http::response(['access_token' => 'test-token']),
        '*/v2/checkout/orders/PAYPALTEST/capture' => Http::response(['status' => 'COMPLETED']),
        '*' => Http::response([]),
    ]);
    $this->get(route('paypal.order.capture', $this->order))->assertRedirect($this->order->recapUrlWithToken());
    expect($this->order->fresh()->status)->toBe(2);
    expect(NexiPayment::count())->toBe(0);
    Mail::assertQueued(OrderStatusMail::class, 1);
});

it('continues checkout with PayPal or Nexi and rejects disabled or disallowed methods', function () {
    DB::table('payment_methods')->where('id', 1)->update(['active' => 1]);
    $this->post(route('cart.payment'), ['payment_method_id' => 1])->assertRedirect(route('paypal.orders.create', ['order_id' => $this->order->id]));
    $this->post(route('cart.payment'), ['payment_method_id' => 2])->assertRedirect(route('nexi.orders.create', $this->order));
    DB::table('payment_methods')->where('id', 2)->update(['active' => 0]);
    $this->post(route('cart.payment'), ['payment_method_id' => 2])->assertSessionHasErrors('payment_method_id');
    $this->order->update(['shipping_method_id' => 2]);
    $this->post(route('cart.payment'), ['payment_method_id' => 3])->assertSessionHasErrors('payment_method_id');
    $this->post(route('cart.payment'), ['payment_method_id' => 4])->assertSessionHasErrors('payment_method_id');
});

it('keeps promotional and pickup confirmation available', function (int $method, string $price) {
    DB::table('payment_methods')->where('id', $method)->update(['active' => 1]);
    $this->order->update(['payment_method_id' => $method, 'price' => $price]);
    $response = $this->post(route('cart.payment'), ['payment_method_id' => $method]);
    $response->assertRedirect();
    Http::fake(['*' => Http::response([])]);
    $this->get($response->headers->get('Location'))->assertRedirect();
    expect($this->order->fresh()->status)->toBe(2);
})->with([[3, '12.34'], [4, '0.00']]);

it('leaves external operations pending when their environment switch is off', function () {
    ($this->start)();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment));
    app(NexiFulfillment::class)->confirm($payment->id);
    config(['services.nexi.external_operations_enabled' => false]);
    $this->artisan('nexi:process')->assertSuccessful();
    Mail::assertNothingSent();
    expect(DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->where('operation', 'email')->value('status'))->toBe('pending');
});

it('rolls back confirmation when durable operation insertion fails after the order update', function () {
    ($this->start)();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment));
    $fail = true;
    DB::listen(function ($query) use (&$fail) {
        if ($fail && str_starts_with($query->sql, 'insert into `nexi_fulfillment_operations`')) {
            $fail = false;
            throw new RuntimeException('Simulated operation insertion failure');
        }
    });
    app(NexiFulfillment::class)->confirm($payment->id);
    expect($this->order->fresh()->status)->toBe(1);
    expect($payment->fresh()->fulfillment_status)->toBe('failed');
    expect(DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->count())->toBe(0);
    app(NexiFulfillment::class)->confirm($payment->id);
    expect($this->order->fresh()->status)->toBe(2);
});

it('makes only one GLS HTTP attempt on an ambiguous server error', function () {
    config(['services.gls.client_id' => 'test', 'services.gls.client_password' => 'test', 'services.gls.contract_code' => 'test', 'services.gls.headquarters' => 'test']);
    $this->order->update(['shipping_method_id' => 2]);
    ($this->start)();
    $payment = NexiPayment::first();
    $this->postJson(route('nexi.orders.notification'), nexiNotification($payment));
    app(NexiFulfillment::class)->confirm($payment->id);
    Http::fake(['*' => Http::response('uncertain', 500)]);
    $id = DB::table('nexi_fulfillment_operations')->where('order_id', $this->order->id)->where('operation', 'shipment')->value('id');
    app(NexiFulfillment::class)->processOperation($id);
    app(NexiFulfillment::class)->processOperation($id);
    Http::assertSentCount(1);
    expect(DB::table('nexi_fulfillment_operations')->find($id)->status)->toBe('review');
});
