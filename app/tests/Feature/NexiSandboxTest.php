<?php

use App\Models\Ecommerce\Order;
use App\Services\Payments\NexiSandbox;
use Database\Seeders\NexiSandboxSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

it('refuses sandbox fixture writes outside the isolated local database', function () {
    $this->app->instance('env', 'local');
    config(['services.nexi.local_sandbox' => true, 'services.nexi.sandbox' => true]);
    expect(DB::connection()->getDatabaseName())->toBe('shipping_dog_test');
    $count = Order::count();
    expect(fn () => $this->seed(NexiSandboxSeeder::class))->toThrow(RuntimeException::class);
    expect(Order::count())->toBe($count);
});

it('creates usable synthetic orders without replacing existing attempts', function () {
    // Only bypass the isolation guard; fixture writes still use the test DB transaction.
    $this->mock(NexiSandbox::class)->shouldReceive('assertIsolated')->twice();
    $count = Order::count();
    $this->seed(NexiSandboxSeeder::class);
    $this->seed(NexiSandboxSeeder::class);
    expect(Order::count())->toBe($count + 4);
    $orders = Order::with(['address', 'order_items'])->latest('id')->take(4)->get();
    foreach ($orders as $order) {
        expect($order->address)->not->toBeNull();
        expect($order->isEmpty())->toBeFalse();
        expect((string) $order->getRawOriginal('price'))->toBe('12.34');
    }
    expect($orders->whereNull('user_id')->count())->toBe(2);
    expect($orders->whereNotNull('user_id')->count())->toBe(2);
});

it('restricts guest fixture access and accepts only valid signed links', function () {
    $order = Order::create(['price' => '12.34', 'payment_method_id' => 2, 'status' => 0]);
    $url = URL::temporarySignedRoute('nexi.sandbox.guest', now()->addHour(), ['order' => $order->id]);
    $this->get($url)->assertNotFound();
    $this->mock(NexiSandbox::class)->shouldReceive('assertIsolated')->andReturnNull();
    $this->get(route('nexi.sandbox.guest', $order))->assertForbidden();
    $this->get(URL::temporarySignedRoute('nexi.sandbox.guest', now()->subMinute(), ['order' => $order->id]))->assertForbidden();
    $this->get($url)->assertRedirect(route('nexi.orders.create', $order))->assertSessionHas('cart_id', $order->id);
});

it('trusts HTTPS forwarding only from the tunnel proxy when generating callback URLs', function (string $remote, string $scheme) {
    $request = Request::create('http://sandbox.example/services/nexi/create/1', 'GET', [], [], [], [
        'REMOTE_ADDR' => $remote, 'HTTP_X_FORWARDED_PROTO' => 'https', 'HTTP_X_FORWARDED_PORT' => '443',
    ]);
    TrustProxies::at(['172.30.89.10']);
    try {
        (new TrustProxies)->handle($request, function ($request) use ($scheme) {
            $generator = clone app('url');
            $generator->forceRootUrl(null);
            $generator->forceScheme(null);
            $generator->setRequest($request);
            foreach (['result', 'cancel', 'notification'] as $route) {
                $url = $generator->route('nexi.orders.'.$route, $route === 'notification' ? [] : ['order' => 1]);
                expect(parse_url($url, PHP_URL_SCHEME))->toBe($scheme);
            }
        });
    } finally {
        TrustProxies::flushState();
        Request::setTrustedProxies([], Request::HEADER_X_FORWARDED_PROTO);
    }
})->with([['172.30.89.10', 'https'], ['172.30.89.11', 'http']]);

it('reports preflight failures without provider traffic or secret output', function () {
    Http::preventStrayRequests();
    config(['services.nexi.sandbox_api_key' => 'redacted-test-key', 'services.nexi.sandbox' => true]);
    $this->artisan('nexi:sandbox-preflight')
        ->expectsOutput('FAIL Isolated local sandbox database and mode')
        ->expectsOutput('PASS Sandbox API key configured')
        ->doesntExpectOutputToContain('redacted-test-key')
        ->assertFailed();
    Http::assertNothingSent();
});
