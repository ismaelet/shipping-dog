<?php

namespace App\Services\Payments;

use InvalidArgumentException;

class NexiService
{
    /**
     * Prepare the parameters for a Nexi payment.
     */
    public function paymentParameters(
        string $transactionId,
        int $amountInCents,
        string $returnUrl,
        string $cancelUrl,
        string $notificationUrl
    ): array {
        if ($amountInCents <= 0 || $amountInCents > 99999999) {
            throw new InvalidArgumentException(
                'Invalid payment amount.'
            );
        }

        if (! preg_match('/^[a-zA-Z0-9_-]{2,30}$/', $transactionId)) {
            throw new InvalidArgumentException(
                'Invalid transaction ID.'
            );
        }

        $parameters = [
            'alias' => config('services.nexi.alias'),
            'importo' => (string) $amountInCents,
            'divisa' => 'EUR',
            'codTrans' => $transactionId,
            'url' => $returnUrl,
            'url_back' => $cancelUrl,
            'urlpost' => $notificationUrl,
        ];

        $parameters['mac'] = $this->generateMac(
            $parameters['codTrans'],
            $parameters['divisa'],
            $parameters['importo']
        );

        return $parameters;
    }

    /**
     * Generate the MAC for a payment request.
     */
    private function generateMac(
        string $transactionId,
        string $currency,
        string $amount
    ): string {
        $key = config('services.nexi.mac_key');

        if (! is_string($key) || $key === '') {
            throw new InvalidArgumentException(
                'Nexi MAC key is not configured.'
            );
        }

        $string = 'codTrans='.$transactionId
            .'divisa='.$currency
            .'importo='.$amount
            .$key;

        return sha1($string);
    }

    /**
     * Generate the expected MAC for a payment notification.
     */
    private function generateNotificationMac(array $data): string
    {
        $key = config('services.nexi.mac_key');

        if (! is_string($key) || $key === '') {
            throw new InvalidArgumentException(
                'Nexi MAC key is not configured.'
            );
        }

        $fields = [
            'codTrans',
            'esito',
            'importo',
            'divisa',
            'data',
            'orario',
            'codAut',
        ];

        $string = '';

        foreach ($fields as $field) {
            $string .= $field.'='.$data[$field];
        }

        return sha1($string.$key);
    }

    /**
     * Verify that a notification was signed by Nexi.
     */
    public function verifyNotification(array $data): bool
    {
        $requiredFields = [
            'codTrans',
            'esito',
            'importo',
            'divisa',
            'data',
            'orario',
            'codAut',
            'mac',
        ];

        foreach ($requiredFields as $field) {
            if (
                ! array_key_exists($field, $data)
                || ! is_string($data[$field])
            ) {
                return false;
            }
        }

        if (
            ! preg_match('/^[a-fA-F0-9]{40}$/', $data['mac'])
        ) {
            return false;
        }

        $expectedMac = $this->generateNotificationMac($data);

        return hash_equals(
            $expectedMac,
            strtolower($data['mac'])
        );
    }
}
