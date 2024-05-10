<?php

namespace App\Http\Controllers\Services\Gls;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use stdClass;

class GlsController extends Controller {

	public function addSingleParcel(Request $request): RedirectResponse {
		$order = Order::find($request->order_id);

		$this->addParcel($order);

		return back()->with('add_single_parcel_success', 'Spedizione trasmessa correttamente');
	}

	public function addAllParcels(): RedirectResponse {
		$orders = Order::unlabelled();

		$orders->each(function ($order) {
			$this->addParcel($order, true);
		});

		return back()->with('add_all_parcels_success', 'Spedizione trasmessa correttamente');
	}

	/**
	 * * Action: Add Parcel
	 */
	private function addParcel(Order $order, bool $partOfCollection = false): RedirectResponse|true {
		$response = $this->sendRequest($order);
		$shippingNumber = $response?->Parcel?->NumeroSpedizione;

		if (!$shippingNumber) {
			return back()->with($partOfCollection ? 'add_single_parcel_error' : 'add_all_parcels_error', 'Errore durante la trasmissione dell\'ordine ' . $order->id . ': ' . print_r($response, true));
		}

		$order->update([
			'status' => EcommerceHelper::statusFromTag('label-ready'),
		]);

		return true;
	}

	/**
	 * ~ Helper: Send Request
	 */
	private function sendRequest(Order $order): stdClass {
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
		$client = new Client();

		$response = $client->request('POST', $endpoint, [
			'headers' => $headers,
			'form_params' => $body
		]);

		// Get response body
		$response = simplexml_load_string($response->getBody()->getContents());
		$response = json_encode($response);

		return json_decode($response);
	}
}
