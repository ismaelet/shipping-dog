<?php

namespace App\Services;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

class GlsService {

    /**
     * Endpoints
     * 
     * @var string
     */
    private string $addParcelEndpoint = 'https://labelservice.gls-italy.com/ilswebservice.asmx/AddParcel';
    private string $closeWorkDayByShipmentNumberEndpoint = 'https://labelservice.gls-italy.com/ilsWebService.asmx/CloseWorkDayByShipmentNumber';
    private string $trackParcelEndpoint = 'https://infoweb.gls-italy.com/XML/get_xml_track.php';

    /**
     * * Add Parcel
     * 
     * @param Collection|Order $orders
     * @return bool
     */
    public function addParcels(Collection|Order $orders): bool {
        // If a single order is passed, convert it to a collection
        if ($orders instanceof Order) {
            $orders = new Collection([$orders]);
        }

        // Set headers
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

        // Set the body
        $body = [
            'XMLInfoParcel' => "<Info>
				" . $this->payloadHeader() . "
				" . $this->addParcelsData($orders) . "
			</Info>"
        ];

        // Send the request
        $response = $this->sendRequest($this->addParcelEndpoint, $headers, $body);

        if (!$response) {
            return false;
        }

        // Update the orders
        return $this->updateOrdersAfterAdd($orders, $response);
    }

    /**
     * * Transmit Parcel
     *	
     * CloseWorkDayByShipmentNumber in the GLS API
     */
    public function transmitParcels(Collection|Order $orders): array {
        // If a single order is passed, convert it to a collection
        if ($orders instanceof Order) {
            $orders = new Collection([$orders]);
        }

        $orders = $orders->load('address');

        // Set headers
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

        // Set body
        $body = [
            '_xmlRequest' => "<Info>
			" . $this->payloadHeader() . "
			" . $this->transmitParcelsData($orders) . "
		</Info>"
        ];

        // Send the request
        $response = $this->sendRequest($this->closeWorkDayByShipmentNumberEndpoint, $headers, $body);

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
        $orders = $this->trackParcelOrders();
        $orders->each(function ($order) {
            $this->trackParcel($order);
        });
    }

    /**
     * Helper: Send Request
     */
    private function sendRequest(string $endpoint, array $headers, array $body): stdClass|false {
        try {
            $client = new Client();

            $response = $client->request('POST', $endpoint, [
                'headers' => $headers,
                'form_params' => $body
            ]);

            // Get response body
            $response = simplexml_load_string($response->getBody()->getContents());
            $response = json_encode($response);

            return json_decode($response);
        } catch (Exception $e) {
            Log::error('Error sending a request to GLS ', ['Exception' => $e]);

            return false;
        }
    }

    /**
     * ~ Helper: Payload Header 
     * 
     * @return string
     */
    private function payloadHeader(): string {
        $headquarters = config('services.gls.headquarters');
        $clientId = config('services.gls.client_id');
        $clientPassword = config('services.gls.client_password');

        return "<SedeGls>$headquarters</SedeGls>
		<CodiceClienteGls>$clientId</CodiceClienteGls>
		<PasswordClienteGls>$clientPassword</PasswordClienteGls>";
    }

    /**
     * ~ Add Parcel Helper: Add Parcels Data
     * 
     * @param Collection $orders
     * @return string
     */
    private function addParcelsData(Collection $orders): string {

        $contractCode = config('services.gls.contract_code');

        $parcelsXml = '';

        $orders->each(function ($order) use (&$parcelsXml, $contractCode) {
            $address = $order->address;
            $ragioneSociale = $address->full_name;
            $indirizzo = $address->address . ', ' . $address->number;
            $localita = $address->city;
            $zipCode = $address->zip;
            $provincia = $address->region;
            $email = $address->email;
            $serviziAccessori = $order->exchange ? 24 : '';
            $cellulare1 = $address->phone;

            // XML payload to send to the API
            $parcelsXml .= "
            <Parcel>
				<CodiceContrattoGls>$contractCode</CodiceContrattoGls>
				<RagioneSociale>$ragioneSociale</RagioneSociale>
				<Indirizzo>$indirizzo</Indirizzo>
				<Localita>$localita</Localita>
				<Zipcode>$zipCode</Zipcode>
				<Provincia>$provincia</Provincia>
				<Bda>0000000001</Bda>
				<Colli>1</Colli>
				<PesoReale>1</PesoReale>
				<TipoCollo>0</TipoCollo>
				<Email>$email</Email>
				<Cellulare1>$cellulare1</Cellulare1>
				<ServiziAccessori>$serviziAccessori</ServiziAccessori>
			</Parcel>
        ";
        });

        return $parcelsXml;
    }

    /**
     * ~ Add Parcel Helper: Update Order
     * 
     * @param Collection $orders
     * @param stdClass $response
     * @return bool
     */
    private function updateOrdersAfterAdd(Collection $orders, stdClass $response): bool {
        $parcels = $response->Parcel;

        if (!is_array($parcels)) {
            $parcels = [$parcels];
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
     * ~ Transmit Parcel Helper: Transmit Parcels Data
     * 
     * @param Collection $orders
     * @return string
     */
    private function transmitParcelsData(Collection $orders): string {
        $contractCode = config('services.gls.contract_code');

        $parcelsXml = '';

        $orders->each(function ($order) use (&$parcelsXml, $contractCode) {
            $shippingNumber = $order->tracking;
            $address = $order->address;
            $ragioneSociale = $address->full_name;
            $indirizzo = $address->address . ', ' . $address->number;
            $localita = $address->city;
            $zipCode = $address->zip;
            $provincia = $address->region;
            $email = $address->email;
            $serviziAccessori = $order->exchange ? 24 : '';
            $cellulare1 = $address->phone;
            $noteSpedizione = $cellulare1;
            $noteAggiuntive = $order->courier_notes;

            // XML payload to send to the API
            $parcelsXml .= "<Parcel>
				<NumeroDiSpedizioneGLSDaConfermare>$shippingNumber</NumeroDiSpedizioneGLSDaConfermare>
				<CodiceContrattoGls>$contractCode</CodiceContrattoGls>
				<RagioneSociale>$ragioneSociale</RagioneSociale>
				<Indirizzo>$indirizzo</Indirizzo>
				<Localita>$localita</Localita>
				<Zipcode>$zipCode</Zipcode>
				<Provincia>$provincia</Provincia>
				<Bda>0000000001</Bda>
				<Colli>1</Colli>
				<PesoReale>1</PesoReale>
				<TipoCollo>0</TipoCollo>
				<Email>$email</Email>
				<Cellulare1>$cellulare1</Cellulare1>
				<NoteSpedizione>$noteSpedizione</NoteSpedizione>
				<NoteAggiuntive>$noteAggiuntive</NoteAggiuntive>
				<ServiziAccessori>$serviziAccessori</ServiziAccessori>
			</Parcel>";
        });

        return $parcelsXml;
    }

    /**
     * ~ Transmit Parcel Helper: Update Orders
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

            Order::where('tracking', $parcel->NumeroDiSpedizioneGLSDaConfermare)
                ->first()
                ->update([
                    'status' => EcommerceHelper::status('awaiting-shipping'),
                ]);
        }

        return [
            'success' => true,
            'error' => false
        ];
    }

    /**
     * Track Parcel Helper: Orders
     * 
     * @return Collection
     */
    private function trackParcelOrders(): Collection {
        return Order::whereIn('status', [EcommerceHelper::status('awaiting-shipping'), EcommerceHelper::status('shipped')])
            ->whereNotNull('tracking')
            ->get();
    }

    /**
     * Track Parcel Helper: Track Parcel
     */
    public function trackParcel(Order $order): bool {
        $headquarters = config('services.gls.headquarters');
        $contractCode = config('services.gls.contract_code');

        $response = Http::get($this->trackParcelEndpoint, [
            'locpartenza' => $headquarters,
            'NumSped' => $order->tracking,
            'CodCli' => $contractCode,
        ]);

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
            return false;
        }

        // GLS may return non-UTF8 content
        $body = mb_convert_encoding($body, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');

        libxml_use_internal_errors(true);

        $xml = simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);

        if (!$xml) {
            Log::error('Invalid GLS tracking XML', [
                'Order' => $order->id,
                'Tracking' => $order->tracking,
                'StatusCode' => $response->status(),
                'ContentType' => $response->header('Content-Type'),
                'ResponseStart' => mb_substr($body, 0, 1000),
                'XmlErrors' => collect(libxml_get_errors())->map(fn($error) => trim($error->message))->all(),
            ]);

            libxml_clear_errors();

            return false;
        }

        libxml_clear_errors();

        $status = trim((string) ($xml->StatoSpedizione ?? ''));

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
                if ($order->status !== EcommerceHelper::status('shipped')) {
                    $order->update([
                        'status' => EcommerceHelper::status('shipped'),
                    ]);

                    $order->sendStatusEmail();
                }

                return true;

            case 'Consegnato':
                if ($order->status !== EcommerceHelper::status('delivered')) {
                    $order->update([
                        'status' => EcommerceHelper::status('delivered'),
                    ]);

                    $order->sendStatusEmail();
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
}
