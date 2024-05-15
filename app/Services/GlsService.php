<?php

namespace App\Services;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use stdClass;

class GlsService {

	/**
	 * * Add Parcel
	 */
	public function addParcel(Order $order): bool {
		$response = $this->sendRequest($order);
		$shippingNumber = $response?->Parcel?->NumeroSpedizione;

		if (!$shippingNumber) {
			Log::error('Error adding a GLS parcel', ['Order' => $order->id, 'Response' => $response]);

			return false;
		}

		$order->update([
			'status' => EcommerceHelper::statusFromTag('label-ready'),
			'tracking' => $shippingNumber
		]);

		return true;
	}

	/**
	 * ~ Helper: Send Request
	 */
	private function sendRequest(Order $order): stdClass|false {
		$headquarters = config('services.gls.headquarters');
		$clientId = config('services.gls.client_id');
		$clientPassword = config('services.gls.client_password');
		$contractCode = config('services.gls.contract_code');
		$endpoint = 'https://labelservice.gls-italy.com/ilswebservice.asmx/AddParcel';

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
		$xmlPayload = "
        <Info>
            <SedeGls>$headquarters</SedeGls>
            <CodiceClienteGls>$clientId</CodiceClienteGls>
            <PasswordClienteGls>$clientPassword</PasswordClienteGls>
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
        </Info>
        ";

		// Set headers
		$headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

		// Set body
		$body = [
			'XMLInfoParcel' => $xmlPayload
		];

		// Send the request
		try {
			$client = new Client();

			$response = $client->request('POST', $endpoint, [
				'headers' => $headers,
				'form_params' => $body
			]);

			// Get response body
			$response = simplexml_load_string($response->getBody()->getContents());
			$response = json_encode($response);
		} catch (Exception $e) {
			Log::error('Error sending a request to GLS ', ['Order' => $order->id, 'Exception' => $e]);

			return false;
		}

		return json_decode($response);
	}
}
