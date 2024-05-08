<?php

namespace App\Http\Controllers\Services\Gls;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GlsController extends Controller {

	public static function addParcel(Request $request) {
		$headquarters = 'TT';
		$clientId = 200162;
		$clientPassword = 2001621122;
		$contractCode = 7841;

		$orderId = 62969;
		$order = Order::find($orderId);

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

		// URL of the GLS Italy Label Service API
		$endpoint = 'https://labelservice.gls-italy.com/ilswebservice.asmx/AddParcel';

		// Initialize Guzzle client
		$client = new Client();

		try {
			// Send POST request
			$response = $client->request('POST', $endpoint, [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
				],
				'form_params' => [
					'XMLInfoParcel' => $xmlPayload,
				]
			]);

			// Get response body
			$body = $response->getBody()->getContents();

			// Return response
			return $body;
		} catch (\Exception $e) {
			// Handle exception
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}
}
