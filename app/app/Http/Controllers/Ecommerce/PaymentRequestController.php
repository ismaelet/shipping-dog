<?php

namespace App\Http\Controllers\Ecommerce;

use Dompdf\Options;
use Dompdf\Dompdf;
use App\Helpers\ArrayHelper;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Seller\PaymentRequest;
use App\Models\Shipping\Province;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use NumberFormatter;

class PaymentRequestController extends Controller {

	/**
	 * Display the payment request index page.
	 *
	 * @param  Request  $request
	 * @return View|RedirectResponse
	 */
	public function index(Request $request): View|RedirectResponse {
		$seller = $request->seller;
		$paymentRequest = $seller->payment_request;
		$toBePayed = $seller->toBePayed();
		$waitingDays = ceil($toBePayed / 100);

		if (!$paymentRequest || !$paymentRequest->national_id) {
			return to_route('seller-dashboard.payment.edit');
		}

		return view('ecommerce.payment-requests.index')
			->with('title', 'Richiesta Pagamento')
			->with('seller', $seller)
			->with('toBePayed', $toBePayed)
			->with('waitingDays', $waitingDays);
	}

	/**
	 * Display the payment request edit page.
	 *
	 * @param  Request $request
	 * @return View
	 */
	public function edit(Request $request): View {
		$seller = $request->seller;
		$paymentRequest = $seller->payment_request;
		$provinces = Province::orderBy('name')->get();

		return view('ecommerce.payment-requests.edit')
			->with([
				'title' => 'Richiesta Pagamento',
				'paymentRequest' => $paymentRequest,
				'provinces' => $provinces
			]);
	}

	/**
	 * Update the payment request.
	 *
	 * @param  Request  $request
	 * @return RedirectResponse
	 */
	public function update(Request $request): RedirectResponse {
		$seller = $request->seller;
		$toBePayed = $seller->toBePayed();
		$reciptFilePath = 'documents/payments/Ricevuta_' . $seller->id . '.pdf';
		$contractFilePath = 'documents/payments/Comunicazione_' . $seller->id . '.pdf';

		PaymentRequest::updateOrCreate(
			['seller_id' => $request->seller->id],
			[
				'first_name' => $request->first_name,
				'last_name' => $request->last_name,
				'birthdate' => $request->birthdate,
				'birthplace' => $request->birthplace,
				'birthprovince' => $request->birthprovince,
				'address' => $request->address,
				'city' => $request->city,
				'province' => $request->province,
				'zip' => $request->zip,
				'cf' => $request->cf,
				'national_id' => $request->national_id,
				'paypal' => $request->paypal,
				'total' => $toBePayed,
			],
		);

		FileHelper::delete($reciptFilePath);
		FileHelper::delete($contractFilePath);

		return to_route('seller-dashboard.payment');
	}

	/**
	 * Download the payment request documents.
	 *
	 * @param  Request  $request
	 * @return RedirectResponse
	 */
	public function download(Request $request): RedirectResponse {
		$seller = $request->seller;
		$toBePayed = $seller->toBePayed();
		$waitingDays = ceil($toBePayed / 100);
		$paymentRequest = $seller->payment_request;
		$reciptFilePath = 'documents/payments/Ricevuta_' . $seller->id . '.pdf';
		$contractFilePath = 'documents/payments/Comunicazione_' . $seller->id . '.pdf';

		// Update the request status
		$seller->update(['request' => 1]);

		// Generate the documents if the total has changed or the files don't exist
		if ($paymentRequest->total != $toBePayed || !FileHelper::exists($reciptFilePath) || !FileHelper::exists($contractFilePath)) {
			// Update the total
			$paymentRequest->update(['total' => $toBePayed]);

			foreach ([$reciptFilePath, $contractFilePath] as $documentKey => $document) {
				// Delete the file if it exists
				FileHelper::delete($document);

				// Get the file name
				$templateName = $documentKey === 0 ? 'recipt' : 'contract';
				$fileDirectory = ArrayHelper::explodeLast('/', $document)[0];

				// Calculate the date
				$dateNow = new DateTimeImmutable();
				$date['start'] = $dateNow->format('d/m/Y');
				$date['end'] = $dateNow->modify('+' . $waitingDays . ' day')->format('d/m/Y');

				// Calculate the payment data
				$formatter = new NumberFormatter('it', NumberFormatter::SPELLOUT);
				$payment['gross'] = $toBePayed;
				$payment['net'] = round($payment['gross'] * .8, 2);
				$payment['netInteger'] = floor($payment['net']);
				$payment['netDecimals'] = ($payment['net'] - $payment['netInteger']) * 100;
				$payment['tax'] = round($payment['gross'] * .2, 2);

				// Format the payment data
				$formattedPayment['gross'] = number_format($payment['gross'], 2, ',');
				$formattedPayment['net'] = number_format($payment['net'], 2, ',');
				$formattedPayment['netInteger'] = $formatter->format($payment['netInteger']);
				$formattedPayment['netDecimals'] = str_pad($payment['netDecimals'], 2, '0', STR_PAD_LEFT);
				$formattedPayment['tax'] = number_format($payment['tax'], 2, ',');

				// Get the signature
				$signature = 'https://shippingdog.com/storage/documents/signature.jpg';

				// Generate the mark
				$mark = $payment['net'] >= 80 ? "Marca da bollo<br>sull'originale" : '';

				// Get the template
				$template = file_get_contents(base_path() . '/resources/views/ecommerce/payment-requests/' . $templateName . '-template.html');

				// Get the template keys
				$templateKeys = ['first_name', 'last_name', 'birthdate', 'birthplace', 'birthprovince', 'address', 'city', 'province', 'zip', 'cf', 'national_id', 'gross', 'net', 'netWord', 'netDecimals', 'tax', 'signature', 'mark', 'date-start', 'date-end'];

				// Get the template values
				$templateValues = [
					$paymentRequest->first_name,
					$paymentRequest->last_name,
					implode('/', array_reverse(explode('-', $paymentRequest->birthdate))),
					$paymentRequest->birthplace,
					$paymentRequest->birthprovince,
					$paymentRequest->address,
					$paymentRequest->city,
					$paymentRequest->province,
					$paymentRequest->zip,
					$paymentRequest->cf,
					$paymentRequest->national_id,
					$formattedPayment['gross'],
					$formattedPayment['net'],
					$formattedPayment['netInteger'],
					$formattedPayment['netDecimals'],
					$formattedPayment['tax'],
					$signature,
					$mark,
					$date['start'],
					$date['end'],
				];

				// Replace the template keys with the template values
				$templateKeys = array_map(fn($key) => '{{' . $key . '}}', $templateKeys);
				$template = str_replace($templateKeys, $templateValues, $template);

				// Generate the PDF
				$options = (new Options())->set('isRemoteEnabled', true);
				$dompdf = new Dompdf($options);
				$dompdf->loadHtml($template);
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				$output = $dompdf->output();

				// Save the PDF
				if (!Storage::directoryExists('public/' . $fileDirectory)) {
					Storage::makeDirectory('public/' . $fileDirectory);
				}

				$result = file_put_contents(public_storage($document), $output);

				if (!$result) {
					return redirect()->back()->with('error', 'Errore durante la generazione dei documenti');
				}
			}
		}

		// Download the receipt
		return to_route('download', ['filename' => $reciptFilePath]);
	}
}
