<?php

namespace App\Services\Payments;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class NexiService
{
    public function assertConfigured(): void
    {
        $this->apiKey();
    }

    public function createHostedPage(
        string $orderId,
        int $amountInCents,
        string $resultUrl,
        string $cancelUrl,
        string $notificationUrl
    ): array {
        if ($amountInCents <= 0 || $amountInCents > 99999999) {
            throw new InvalidArgumentException('Invalid payment amount.');
        }

        if (! preg_match('/^[a-zA-Z0-9_-]{1,27}$/', $orderId)) {
            throw new InvalidArgumentException('Invalid Nexi order ID.');
        }

        foreach ([$resultUrl, $cancelUrl, $notificationUrl] as $url) {
            if (! filter_var($url, FILTER_VALIDATE_URL) || parse_url($url, PHP_URL_SCHEME) !== 'https'
                || strlen($url) > 2048) {
                throw new InvalidArgumentException('Nexi requires public HTTPS callback URLs within protocol limits.');
            }
        }

        $response = $this->request()->post('/orders/hpp', [
            'order' => [
                'orderId' => $orderId,
                'amount' => (string) $amountInCents,
                'currency' => 'EUR',
            ],
            'paymentSession' => [
                'amount' => (string) $amountInCents,
                'actionType' => 'PAY',
                'captureType' => 'IMPLICIT',
                'resultUrl' => $resultUrl,
                'cancelUrl' => $cancelUrl,
                'notificationUrl' => $notificationUrl,
            ],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Nexi HPP initialization returned HTTP '.$response->status().'.');
        }

        $data = $response->json();
        if (! is_array($data)
            || ! is_string($data['hostedPage'] ?? null)
            || ! is_string($data['securityToken'] ?? null)
            || $data['securityToken'] === ''
            || ! $this->isNexiHostedPage($data['hostedPage'])) {
            throw new RuntimeException('Nexi HPP initialization returned an invalid response.');
        }

        return [
            'provider_order_id' => $orderId,
            'hosted_page_url' => $data['hostedPage'],
            'security_token' => $data['securityToken'],
        ];
    }

    /**
     * Return the provider's current state based only on executed order operations.
     */
    public function orderStatus(string $providerOrderId, int $expectedAmount): string
    {
        $response = $this->request()->get('/orders/'.rawurlencode($providerOrderId));

        if (! $response->successful()) {
            throw new RuntimeException('Nexi order verification returned HTTP '.$response->status().'.');
        }

        $data = $response->json();
        if (! is_array($data) || ! is_array($data['operations'] ?? null)) {
            throw new RuntimeException('Nexi order verification returned an invalid response.');
        }

        $authorized = false;
        $failure = null;
        foreach ($data['operations'] as $operation) {
            if (! is_array($operation)) {
                continue;
            }

            $type = strtoupper((string) ($operation['operationType'] ?? ''));
            $result = strtoupper((string) ($operation['operationResult'] ?? ''));
            if (! $this->operationMatchesExpectedPayment($operation, $providerOrderId, $expectedAmount)) {
                continue;
            }

            if (in_array($type, ['CAPTURE', 'AUTHORIZATION'], true) && $result === 'EXECUTED') {
                return 'paid';
            }

            if ($type === 'AUTHORIZATION' && $result === 'AUTHORIZED') {
                $authorized = true;
            }

            if (in_array($type, ['AUTHORIZATION', 'CAPTURE'], true)
                && in_array($result, ['DECLINED', 'DENIED_BY_RISK', 'THREEDS_FAILED', 'FAILED', 'CANCELED'], true)) {
                $failure = $result === 'CANCELED' ? 'cancelled' : 'failed';
            }
        }

        return $authorized ? 'authorized' : ($failure ?? 'pending');
    }

    public function amountInCents(string $price): int
    {
        if (! preg_match('/^([0-9]{1,6})(?:\.([0-9]{1,2}))?$/', $price, $parts)) {
            throw new InvalidArgumentException('Invalid payment amount.');
        }

        $amount = (int) $parts[1] * 100 + (int) str_pad($parts[2] ?? '', 2, '0');
        if ($amount <= 0 || $amount > 99999999) {
            throw new InvalidArgumentException('Invalid payment amount.');
        }

        return $amount;
    }

    private function request(): PendingRequest
    {
        return Http::acceptJson()
            ->asJson()
            ->withHeaders([
                'X-Api-Key' => $this->apiKey(),
                'Correlation-Id' => (string) Str::uuid(),
            ])
            ->connectTimeout(5)
            ->timeout(15)
            ->baseUrl($this->baseUrl());
    }

    private function apiKey(): string
    {
        $apiKey = config('services.nexi.sandbox')
            ? config('services.nexi.sandbox_api_key')
            : config('services.nexi.api_key');
        if (! is_string($apiKey) || $apiKey === '') {
            throw new InvalidArgumentException('Nexi REST API key is not configured.');
        }

        return $apiKey;
    }

    private function baseUrl(): string
    {
        return config('services.nexi.sandbox')
            ? 'https://xpaysandbox.nexigroup.com/api/phoenix-0.0/psp/api/v1'
            : 'https://xpay.nexigroup.com/api/phoenix-0.0/psp/api/v1';
    }

    private function isNexiHostedPage(string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL) || parse_url($url, PHP_URL_SCHEME) !== 'https') {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return $host === 'nexigroup.com' || str_ends_with($host, '.nexigroup.com');
    }

    private function operationMatchesExpectedPayment(array $operation, string $providerOrderId, int $expectedAmount): bool
    {
        $amount = $operation['operationAmount'] ?? null;
        $currency = $operation['operationCurrency'] ?? null;

        return ($operation['orderId'] ?? null) === $providerOrderId
            && (is_string($amount) || is_int($amount))
            && preg_match('/^[0-9]{1,8}$/', (string) $amount) === 1
            && (int) $amount === $expectedAmount
            && $currency === 'EUR';
    }
}
