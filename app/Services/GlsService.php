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
	 * 
	 * @return bool
	 */
	public function transmitParcels(Collection|Order $orders): bool {
		// If a single order is passed, convert it to a collection
		if ($orders instanceof Order) {
			$orders = new Collection([$orders]);
		}

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
			return false;
		}

		// Update the orders
		return $this->updateOrdersAfterTransmit($orders, $response);

		return true;
	}

	/**
	 * * Track parcel
	 * 
	 * @return void
	 */
	public function trackParcels(): void {
		$orders = $this->trackParcelOrders();
		$orders->each(function ($order) {
			$this->trackParcel($order);
		});
	}

	/**
	 * ~ Helper: Send Request
	 * 
	 * @param string $endpoint
	 * @param array $headers
	 * @param array $body
	 * @return stdClass|false
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

			Log::debug('GLS API add parcel response', ['Response' => $response]);

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
			$serviziAccessori = $address->exchange ? 24 : '';
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
			$serviziAccessori = $address->exchange ? 24 : '';
			$cellulare1 = $address->phone;

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
				<ServiziAccessori>$serviziAccessori</ServiziAccessori>
			</Parcel>";
		});

		return $parcelsXml;
	}

	/**
	 * ~ Transmit Parcel Helper: Update Orders
	 * 
	 * @param Collection $orders
	 * @param stdClass $response
	 * @return bool
	 */
	private function updateOrdersAfterTransmit(Collection $orders, stdClass $response): bool {
		foreach ($response->Parcel as $parcelKey => $parcel) {
			$order = $orders[$parcelKey];

			Log::log('GLS API transmit parcel response', ['Response' => $parcel]);

			$order->update([
				'status' => EcommerceHelper::statusFromTag('awaiting-shipping'),
			]);
		}

		return true;
	}

	/**
	 * ~ Track Parcel Helper: Orders
	 * 
	 * @return Collection
	 */
	private function trackParcelOrders(): Collection {
		return Order::whereIn('status', [EcommerceHelper::statusFromTag('awaiting-shipping'), EcommerceHelper::statusFromTag('shipped')])
			->whereNotNull('tracking')
			->get();
	}

	/**
	 * ~ Track Parcel Helper: Track Parcel
	 * 
	 * @param Order $order
	 * @return bool
	 */
	private function trackParcel(Order $order): bool {
		$headquarters = config('services.gls.headquarters');
		$contractCode = config('services.gls.contract_code');

		$response = Http::get($this->trackParcelEndpoint, [
			'locpartenza' => $headquarters,
			'NumSped' => $order->tracking,
			'CodCli' => $contractCode,
		]);

		if ($response->successful()) {
			$response = $response->getBody()->getContents();

			// If the order hasn't been shipped yet, stop here
			if (strpos($response, 'Spedizione non trovata') !== false) {
				return false;
			}

			// Get the status
			$status = explode('<StatoSpedizione><![CDATA[', $response)[1] ?? '';
			$status = explode(']]></StatoSpedizione>', $status)[0] ?? null;

			// Handle errors
			if (!$status) {
				Log::error('Error tracking a GLS parcel', ['Order' => $order->id, 'Response' => $response]);

				return false;
			}

			// Update the order status
			switch ($status) {
				case 'Non consegnato':
					$order->update([
						'status' => EcommerceHelper::statusFromTag('shipped'),
					]);
					$order->sendStatusEmail();
					return true;

				case 'Consegnato':
					$order->update([
						'status' => EcommerceHelper::statusFromTag('delivered'),
					]);
					$order->sendStatusEmail();
					return true;

				default:
					Log::error('GLS parcel status not recognized', ['Order' => $order->id, 'Status' => $status]);
					return false;
			}
		}

		Log::error('Error while sending a tracking request to GLS', ['Order' => $order->id, 'Response' => $response]);

		return false;
	}
}
