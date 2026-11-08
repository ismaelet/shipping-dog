<?php

use App\Services\Payments\NexiService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    config(['services.nexi.sandbox_api_key' => 'sandbox-key', 'services.nexi.sandbox' => true]);
});

it('creates an HPP order with REST credentials, a UUID correlation ID, and implicit capture', function () {
    Http::fake([
        'https://xpaysandbox.nexigroup.com/api/phoenix-0.0/psp/api/v1/orders/hpp' => Http::response([
            'hostedPage' => 'https://xpaysandbox.nexigroup.com/checkout/session',
            'securityToken' => 'provider-security-token',
        ], 200),
    ]);

    $session = app(NexiService::class)->createHostedPage(
        'SD1234567890123456',
        1234,
        'https://merchant.example/result',
        'https://merchant.example/cancel',
        'https://merchant.example/notification',
    );

    expect($session)->toBe([
        'provider_order_id' => 'SD1234567890123456',
        'hosted_page_url' => 'https://xpaysandbox.nexigroup.com/checkout/session',
        'security_token' => 'provider-security-token',
    ]);

    Http::assertSent(function (Request $request) {
        $correlationId = $request->header('Correlation-Id')[0] ?? '';

        return $request->url() === 'https://xpaysandbox.nexigroup.com/api/phoenix-0.0/psp/api/v1/orders/hpp'
            && $request->hasHeader('X-Api-Key', 'sandbox-key')
            && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $correlationId) === 1
            && $request->data() === [
                'order' => [
                    'orderId' => 'SD1234567890123456',
                    'amount' => '1234',
                    'currency' => 'EUR',
                ],
                'paymentSession' => [
                    'amount' => '1234',
                    'actionType' => 'PAY',
                    'captureType' => 'IMPLICIT',
                    'resultUrl' => 'https://merchant.example/result',
                    'cancelUrl' => 'https://merchant.example/cancel',
                    'notificationUrl' => 'https://merchant.example/notification',
                ],
            ];
    });
});

it('uses the production endpoint and production API key when sandbox is disabled', function () {
    config(['services.nexi.sandbox' => false, 'services.nexi.api_key' => 'production-key']);
    Http::fake([
        'https://xpay.nexigroup.com/api/phoenix-0.0/psp/api/v1/orders/hpp' => Http::response([
            'hostedPage' => 'https://xpay.nexigroup.com/checkout/session',
            'securityToken' => 'provider-security-token',
        ], 200),
    ]);

    app(NexiService::class)->createHostedPage(
        'SD1234567890123456',
        1234,
        'https://merchant.example/result',
        'https://merchant.example/cancel',
        'https://merchant.example/notification',
    );

    Http::assertSent(fn (Request $request) => $request->url() === 'https://xpay.nexigroup.com/api/phoenix-0.0/psp/api/v1/orders/hpp'
        && $request->hasHeader('X-Api-Key', 'production-key'));
});

it('verifies a captured operation from the REST order resource', function () {
    Http::fake([
        '*/orders/SD1234567890123456' => Http::response([
            'operations' => [[
                'orderId' => 'SD1234567890123456',
                'operationType' => 'CAPTURE',
                'operationResult' => 'EXECUTED',
                'operationAmount' => '1234',
                'operationCurrency' => 'EUR',
            ]],
        ]),
    ]);

    expect(app(NexiService::class)->orderStatus('SD1234567890123456', 1234))->toBe('paid');

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && $request->hasHeader('X-Api-Key', 'sandbox-key')
        && $request->hasHeader('Correlation-Id'));
});

it('ignores provider operations for a different order reference', function () {
    Http::fake([
        '*/orders/SD1234567890123456' => Http::response([
            'operations' => [[
                'orderId' => 'SDDIFFERENTORDER',
                'operationType' => 'CAPTURE',
                'operationResult' => 'EXECUTED',
                'operationAmount' => '1234',
                'operationCurrency' => 'EUR',
            ]],
        ]),
    ]);

    expect(app(NexiService::class)->orderStatus('SD1234567890123456', 1234))->toBe('pending');
});

it('distinguishes authorizations, failures, cancellations, and mismatched amounts', function (array $operation, string $expected) {
    Http::fake([
        '*/orders/SD1234567890123456' => Http::response([
            'operations' => [array_merge(['orderId' => 'SD1234567890123456'], $operation)],
        ]),
    ]);

    expect(app(NexiService::class)->orderStatus('SD1234567890123456', 1234))->toBe($expected);
})->with([
    'authorization' => [[
        'operationType' => 'AUTHORIZATION', 'operationResult' => 'AUTHORIZED', 'operationAmount' => '1234', 'operationCurrency' => 'EUR',
    ], 'authorized'],
    'declined' => [[
        'operationType' => 'AUTHORIZATION', 'operationResult' => 'DECLINED', 'operationAmount' => '1234', 'operationCurrency' => 'EUR',
    ], 'failed'],
    'cancelled' => [[
        'operationType' => 'AUTHORIZATION', 'operationResult' => 'CANCELED', 'operationAmount' => '1234', 'operationCurrency' => 'EUR',
    ], 'cancelled'],
    'wrong amount' => [[
        'operationType' => 'CAPTURE', 'operationResult' => 'EXECUTED', 'operationAmount' => '1', 'operationCurrency' => 'EUR',
    ], 'pending'],
    'wrong currency' => [[
        'operationType' => 'CAPTURE', 'operationResult' => 'EXECUTED', 'operationAmount' => '1234', 'operationCurrency' => 'USD',
    ], 'pending'],
]);

it('rejects unsuccessful HTTP responses and malformed HPP responses', function (array $response, int $httpStatus) {
    Http::fake([
        '*/orders/hpp' => Http::response($response, $httpStatus),
    ]);

    app(NexiService::class)->createHostedPage(
        'SD1234567890123456',
        1234,
        'https://merchant.example/result',
        'https://merchant.example/cancel',
        'https://merchant.example/notification',
    );
})->with([
    'provider error' => [['message' => 'unavailable'], 503],
    'missing token' => [['orderId' => 'SD1234567890123456', 'hostedPage' => 'https://xpaysandbox.nexigroup.com/checkout'], 201],
    'untrusted hosted URL' => [[
        'orderId' => 'SD1234567890123456',
        'hostedPage' => 'https://attacker.example/checkout',
        'securityToken' => 'token',
    ], 201],
])->throws(RuntimeException::class);

it('rejects missing API keys and non-HTTPS callbacks', function () {
    config(['services.nexi.sandbox_api_key' => null]);
    expect(fn () => app(NexiService::class)->createHostedPage(
        'SD1234567890123456',
        1234,
        'https://merchant.example/result',
        'https://merchant.example/cancel',
        'https://merchant.example/notification',
    ))->toThrow(InvalidArgumentException::class);

    config(['services.nexi.sandbox_api_key' => 'sandbox-key']);
    expect(fn () => app(NexiService::class)->createHostedPage(
        'SD1234567890123456',
        1234,
        'http://merchant.example/result',
        'https://merchant.example/cancel',
        'https://merchant.example/notification',
    ))->toThrow(InvalidArgumentException::class);
});

it('converts decimal amounts without floating point', function (string $input, int $expected) {
    expect(app(NexiService::class)->amountInCents($input))->toBe($expected);
})->with([['0.01', 1], ['12.3', 1230], ['999999.99', 99999999], ['1', 100]]);

it('rejects invalid decimal values', function (string $input) {
    app(NexiService::class)->amountInCents($input);
})->with(['0', '-1', '1.001', '1e2', '1000000', 'NaN'])->throws(InvalidArgumentException::class);

it('maps documented operation results without confusing authentication or authorization with capture', function (string $type, string $result, string $expected) {
    Http::fake(['*/orders/SD1234567890123456' => Http::response([
        'operations' => [[
            'orderId' => 'SD1234567890123456', 'operationType' => $type,
            'operationResult' => $result, 'operationAmount' => '1234', 'operationCurrency' => 'EUR',
        ]],
    ])]);
    expect(app(NexiService::class)->orderStatus('SD1234567890123456', 1234))->toBe($expected);
})->with([
    ['AUTHORIZATION', 'EXECUTED', 'paid'],
    ['AUTHORIZATION', 'AUTHORIZED', 'authorized'],
    ['AUTHORIZATION', 'THREEDS_VALIDATED', 'pending'],
    ['AUTHORIZATION', 'THREEDS_FAILED', 'failed'],
    ['AUTHORIZATION', 'DENIED_BY_RISK', 'failed'],
    ['AUTHORIZATION', 'CANCELED', 'cancelled'],
    ['CAPTURE', 'PENDING', 'pending'],
    ['CARD_VERIFICATION', 'EXECUTED', 'pending'],
    ['REFUND', 'EXECUTED', 'pending'],
    ['UNKNOWN', 'EXECUTED', 'pending'],
    ['UNKNOWN', 'DECLINED', 'pending'],
]);

it('rejects malformed order resources', function (array $body) {
    Http::fake(['*/orders/*' => Http::response($body)]);
    app(NexiService::class)->orderStatus('SD1234567890123456', 1234);
})->with([[[]], [['operations' => 'invalid']]])->throws(RuntimeException::class);
