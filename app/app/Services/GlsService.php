<?php

namespace App\Services;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;
use Throwable;

class GlsService {

    /**
     * Endpoints
     */
    private const ADD_PARCEL_ENDPOINT = 'https://labelservice.gls-italy.com/ilswebservice.asmx/AddParcel';
    private const CLOSE_WORK_DAY_BY_SHIPMENT_NUMBER_ENDPOINT = 'https://labelservice.gls-italy.com/ilsWebService.asmx/CloseWorkDayByShipmentNumber';
    private const TRACK_PARCEL_ENDPOINT = 'https://infoweb.gls-italy.com/XML/get_xml_track.php';

    /**
     * Add Parcel
     */
    public function addParcels(Collection|Order $orders): bool {
        $orders = $this->normalizeOrders($orders)->load('address');

        // Set the body
        $body = [
            'XMLInfoParcel' => "<Info>
				" . $this->payloadHeader() . "
				" . $this->addParcelsData($orders) . "
			</Info>"
        ];

        // Send the request
        $response = $this->sendRequest(self::ADD_PARCEL_ENDPOINT, $body);

        if (!$response) {
            return false;
        }

        // Update the orders
        return $this->updateOrdersAfterAdd($orders, $response);
    }

    /**
     * Transmit Parcel
     */
    public function transmitParcels(Collection|Order $orders): array {
        $orders = $this->normalizeOrders($orders)->load('address');

        // Set body
        $body = [
            '_xmlRequest' => "<Info>
			" . $this->payloadHeader() . "
			" . $this->transmitParcelsData($orders) . "
		</Info>"
        ];

        // Send the request
        $response = $this->sendRequest(self::CLOSE_WORK_DAY_BY_SHIPMENT_NUMBER_ENDPOINT, $body);

        if (!$response) {
            return [
                'success' => false,
                'error' => 'Errore nella trasmissione'
            ];
        }

        // Update the orders
        return $this->updateOrdersAfterTransmit($response);
    }

    /**
     * Track parcel
     */
    public function trackParcels(): void {
        $this->trackParcelOrders()->each(
            fn(Order $order) => $this->trackParcel($order)
        );
    }

    /**
     * Helper: Send Request
     */
    private function sendRequest(string $endpoint, array $body): stdClass|false {
        try {
            $response = Http::asForm()
                ->timeout(30)
                ->retry(2, 500)
                ->post($endpoint, $body);

            if (!$response->successful()) {
                Log::error('GLS request failed', [
                    'Endpoint' => $endpoint,
                    'StatusCode' => $response->status(),
                    'Response' => mb_substr($response->body(), 0, 1000),
                ]);

                return false;
            }

            $xml = simplexml_load_string($response->body());

            if ($xml === false) {
                Log::error('Invalid GLS response XML', [
                    'Endpoint' => $endpoint,
                    'Response' => mb_substr($response->body(), 0, 1000),
                ]);

                return false;
            }

            return json_decode(
                json_encode($xml, JSON_THROW_ON_ERROR),
                false,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (Throwable $exception) {
            Log::error('Error sending a request to GLS', [
                'Endpoint' => $endpoint,
                'Exception' => $exception,
            ]);

            return false;
        }
    }

    /**
     * Helper: Payload Header 
     */
    private function payloadHeader(): string {
        $headquarters = $this->escapeXml(config('services.gls.headquarters'));
        $clientId = $this->escapeXml(config('services.gls.client_id'));
        $clientPassword = $this->escapeXml(config('services.gls.client_password'));

        return "<SedeGls>$headquarters</SedeGls>
		<CodiceClienteGls>$clientId</CodiceClienteGls>
		<PasswordClienteGls>$clientPassword</PasswordClienteGls>";
    }

    /**
     * Add Parcel Helper: Add Parcels Data
     */
    private function addParcelsData(Collection $orders): string {
        return $orders
            ->map(fn(Order $order) => $this->parcelXml($order))
            ->implode('');
    }

    /**
     * Add Parcel Helper: Update Order
     */
    private function updateOrdersAfterAdd(Collection $orders, stdClass $response): bool {
        $parcels = $response->Parcel ?? null;

        if (!$parcels) {
            Log::error('GLS AddParcel response contains no parcels', [
                'Response' => $response,
            ]);

            return false;
        }

        $parcels = is_array($parcels) ? $parcels : [$parcels];

        if (count($parcels) !== $orders->count()) {
            Log::error('GLS returned an unexpected parcel count', [
                'Expected' => $orders->count(),
                'Received' => count($parcels),
                'Response' => $response,
            ]);

            return false;
        }

        foreach ($parcels as $parcelKey => $parcel) {
            $order = $orders[$parcelKey];
            $shippingNumber = $parcel->NumeroSpedizione ?? null;

            if (!$shippingNumber) {
                Log::error('Error adding a GLS parcel', ['Order' => $order->id, 'Response' => $response]);

                return false;
            }

            $order->update([
                'tracking' => $shippingNumber
            ]);
        }

        return true;
    }

    /**
     * Transmit Parcel Helper: Transmit Parcels Data
     */
    private function transmitParcelsData(Collection $orders): string {
        return $orders
            ->map(
                fn(Order $order) => $this->parcelXml(
                    $order,
                    includeShippingNumber: true
                )
            )
            ->implode('');
    }

    /**
     * Transmit Parcel Helper: Update Orders
     */
    private function updateOrdersAfterTransmit(stdClass $response): array {
        $parcels = $response->Parcel ?? null;

        if (!$parcels) {
            Log::error('Error transmitting bulk GLS parcel', ['Response' => $response]);

            return [
                'success' => false,
                'error' => 'Errore di trasmissione non specificato'
            ];
        }

        if ($parcels && !is_array($parcels)) {
            $parcels = [$parcels];
        }

        foreach ($parcels as $parcel) {
            if (!$parcel || !isset($parcel->NumeroDiSpedizioneGLSDaConfermare)) {
                Log::error('Error transmitting a GLS parcel', ['Response' => $response]);

                return [
                    'success' => false,
                    'error' => 'Errore di trasmissione non specificato'
                ];
            }

            $order = Order::where('tracking', $parcel->NumeroDiSpedizioneGLSDaConfermare)->first();

            if (!$order) {
                Log::error('GLS order not found after transmission', [
                    'Tracking' => $parcel->NumeroDiSpedizioneGLSDaConfermare,
                    'Response' => $response,
                ]);

                return [
                    'success' => false,
                    'error' => 'Ordine relativo alla spedizione non trovato',
                ];
            }

            $order->update([
                'status' => EcommerceHelper::statusFromTag('awaiting-shipping'),
            ]);
        }

        return [
            'success' => true,
            'error' => false
        ];
    }

    /**
     * Track Parcel Helper: Orders
     */
    private function trackParcelOrders(): Collection {
        return Order::whereIn('status', [
            EcommerceHelper::statusFromTag('awaiting-shipping'),
            EcommerceHelper::statusFromTag('shipped'),
        ])
            ->whereNotNull('tracking')
            ->where('created_at', '>=', now()->subDays(90))
            ->get();
    }

    /**
     * Track Parcel Helper: Track Parcel
     */
    public function trackParcel(Order $order, bool $sendEmail = true): bool {
        $shippedStatus = EcommerceHelper::statusFromTag('shipped');
        $deliveredStatus = EcommerceHelper::statusFromTag('delivered');

        $headquarters = config('services.gls.headquarters');
        $contractCode = config('services.gls.contract_code');

        try {
            $response = Http::timeout(30)
                ->retry(2, 500)
                ->get(self::TRACK_PARCEL_ENDPOINT, [
                    'locpartenza' => $headquarters,
                    'NumSped' => $order->tracking,
                    'CodCli' => $contractCode,
                ]);
        } catch (Throwable $exception) {
            Log::error('Error sending GLS tracking request', [
                'Order' => $order->id,
                'Tracking' => $order->tracking,
                'Exception' => $exception,
            ]);

            return false;
        }

        $body = $response->body();

        if (!$response->successful()) {
            Log::error('Error while sending a tracking request to GLS', [
                'Order' => $order->id,
                'Tracking' => $order->tracking,
                'StatusCode' => $response->status(),
                'Response' => mb_substr($body, 0, 1000),
            ]);

            return false;
        }

        if (str_contains($body, 'Spedizione non trovata')) {
            Log::warning('GLS shipment not found', [
                'Order' => $order->id,
                'Tracking' => $order->tracking,
            ]);

            return false;
        }

        // GLS may return ISO-8859-1 encoded XML.
        // Convert the content to UTF-8 and update the XML declaration.
        $body = mb_convert_encoding(
            $body,
            'UTF-8',
            'UTF-8, ISO-8859-1, Windows-1252'
        );

        $body = preg_replace(
            '/(<\?xml[^>]*encoding=["\'])[^"\']+(["\'])/i',
            '$1UTF-8$2',
            $body
        );

        $previousLibxmlSetting = libxml_use_internal_errors(true);

        try {
            $xml = simplexml_load_string(
                $body,
                'SimpleXMLElement',
                LIBXML_NOCDATA
            );

            if ($xml === false) {
                Log::error('Invalid GLS tracking XML', [
                    'Order' => $order->id,
                    'Tracking' => $order->tracking,
                    'StatusCode' => $response->status(),
                    'ContentType' => $response->header('Content-Type'),
                    'ResponseStart' => mb_substr($body, 0, 1000),
                    'XmlErrors' => collect(libxml_get_errors())
                        ->map(fn($error) => trim($error->message))
                        ->all(),
                ]);

                return false;
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousLibxmlSetting);
        }

        $status = trim((string) $xml->StatoSpedizione);

        if (!$status && isset($xml->SPEDIZIONE)) {
            $status = trim((string) $xml->SPEDIZIONE->StatoSpedizione);
        }

        if (!$status) {
            Log::error('GLS tracking status missing', [
                'Order' => $order->id,
                'Tracking' => $order->tracking,
                'StatusCode' => $response->status(),
                'ContentType' => $response->header('Content-Type'),
                'ParsedResponse' => json_decode(json_encode($xml), true),
                'ResponseStart' => mb_substr($body, 0, 1000),
            ]);

            return false;
        }

        switch ($status) {
            case 'Non consegnato':
                if ($order->status !== $shippedStatus) {
                    $order->update([
                        'status' => $shippedStatus,
                    ]);

                    if ($sendEmail) {
                        $order->sendStatusEmail();
                    }
                }

                return true;

            case 'Consegnato':
                if ($order->status !== $deliveredStatus) {
                    $order->update([
                        'status' => $deliveredStatus,
                    ]);

                    if ($sendEmail) {
                        $order->sendStatusEmail();
                    }
                }

                return true;

            default:
                Log::error('GLS parcel status not recognized', [
                    'Order' => $order->id,
                    'Tracking' => $order->tracking,
                    'Status' => $status,
                    'ParsedResponse' => json_decode(json_encode($xml), true),
                ]);

                return false;
        }
    }

    /**
     * Helper: Generate Parcel XML
     */
    private function parcelXml(
        Order $order,
        bool $includeShippingNumber = false
    ): string {
        $address = $order->address;

        $contractCode = $this->escapeXml(
            config('services.gls.contract_code')
        );

        $shippingNumberXml = '';

        if ($includeShippingNumber) {
            $shippingNumber = $this->escapeXml($order->tracking);

            $shippingNumberXml =
                "<NumeroDiSpedizioneGLSDaConfermare>{$shippingNumber}</NumeroDiSpedizioneGLSDaConfermare>";
        }

        $ragioneSociale = $this->escapeXml($address->full_name);
        $indirizzo = $this->escapeXml(
            "{$address->address}, {$address->number}"
        );
        $localita = $this->escapeXml($address->city);
        $zipCode = $this->escapeXml($address->zip);
        $provincia = $this->escapeXml($address->region);
        $email = $this->escapeXml($address->email);
        $cellulare = $this->escapeXml($address->phone);
        $noteAggiuntive = $this->escapeXml($order->courier_notes);
        $serviziAccessori = $order->exchange ? '24' : '';

        return <<<XML
            <Parcel>
                {$shippingNumberXml}
                <CodiceContrattoGls>{$contractCode}</CodiceContrattoGls>
                <RagioneSociale>{$ragioneSociale}</RagioneSociale>
                <Indirizzo>{$indirizzo}</Indirizzo>
                <Localita>{$localita}</Localita>
                <Zipcode>{$zipCode}</Zipcode>
                <Provincia>{$provincia}</Provincia>
                <Bda>0000000001</Bda>
                <Colli>1</Colli>
                <PesoReale>1</PesoReale>
                <TipoCollo>0</TipoCollo>
                <Email>{$email}</Email>
                <Cellulare1>{$cellulare}</Cellulare1>
                <NoteSpedizione>{$cellulare}</NoteSpedizione>
                <NoteAggiuntive>{$noteAggiuntive}</NoteAggiuntive>
                <ServiziAccessori>{$serviziAccessori}</ServiziAccessori>
            </Parcel>
        XML;
    }

    /**
     * Helper: Normalize Orders
     */
    private function normalizeOrders(Collection|Order $orders): Collection {
        return ($orders instanceof Order
            ? new Collection([$orders])
            : $orders
        )->values();
    }

    /**
     * Helper: Escape XML
     */
    private function escapeXml(mixed $value): string {
        return htmlspecialchars((string) ($value ?? ''), ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
