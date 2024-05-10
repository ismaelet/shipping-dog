<?php

use App\Models\Ecommerce\Seller;

$tbl = 'payment_requests';
$index = 'seller_id';
$edit = $_GET['edit'] ?? false;
$done = $_GET['edit'] ?? false;

// documents
$documents = [
	[
		'template' => 'recipt',
		'folder' => 'documents/payments',
		'file-name' => 'Ricevuta_' . auth()->id() . '.pdf',
	],
	[
		'template' => 'contract',
		'folder' => 'documents/payments',
		'file-name' => 'Comunicazione_' . auth()->id() . '.pdf',
	]
];

foreach ($documents as $key => $document) {
	$documents[$key]['server-path'] = public_storage($document['folder'] . '/' . $document['file-name']);
	$documents[$key]['client-path'] = public_asset($document['folder'] . '/' . $document['file-name']);
}

// redirect if the user isn't a seller
$seller = Seller::find(auth()->id());

if (!$seller || $seller->toBePayed() <= 31.25) {
	redirect_old('/manage/sales/list');
}

$waitingDays = ceil($seller->toBePayed() / 100);

// fetch the seller data
$data = \Legacy\System\Classes\LegacyDB::row('* FROM ' . $tbl . ' WHERE ' . $index . '=?', [auth()->id()]);

$inputs = [
	[
		['type' => 'lbl', 'text' => 'Nome e cognome'],
		['type' => 'hidden', 'name' => 'seller_id', 'value' => auth()->id(), 'required' => true],
		['text' => 'Nome', 'name' => 'first_name', 'value' => $data['first_name'] ?? false, 'size' => 2, 'maxlength' => 100, 'required' => true],
		['text' => 'Cognome', 'name' => 'last_name', 'value' => $data['last_name'] ?? false, 'size' => 2, 'maxlength' => 100, 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Data di nascita'],
		['type' => 'date', 'name' => 'birthdate', 'value' => $data['birthdate'] ?? false, 'min' => date('Y-m-d', strtotime('-100 year')), 'max' => date('Y-m-d'), 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Città e provincia di nascita'],
		['text' => 'Città', 'name' => 'birthplace', 'value' => $data['birthplace'] ?? false, 'size' => 3, 'maxlength' => 50, 'required' => true],
		['text' => 'Provincia', 'name' => 'birthprovince', 'value' => $data['birthprovince'] ?? false, 'custom-class' => 'uppercase', 'size' => 1, 'maxlength' => 2, 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Indirizzo di residenza'],
		['text' => 'Indirizzo', 'name' => 'address', 'value' => $data['address'] ?? false, 'maxlength' => 200, 'required' => true],
		['text' => 'Città', 'name' => 'city', 'value' => $data['city'] ?? false, 'size' => 2, 'maxlength' => 50, 'required' => true],
		['text' => 'Provincia', 'name' => 'province', 'value' => $data['province'] ?? false, 'custom-class' => 'uppercase', 'size' => 1, 'maxlength' => 2, 'required' => true],
		['text' => 'CAP', 'name' => 'zip', 'value' => $data['zip'] ?? false, 'size' => 1, 'maxlength' => 5, 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Dati personali'],
		['text' => 'Codice fiscale', 'name' => 'cf', 'value' => $data['cf'] ?? false, 'custom-class' => 'uppercase', 'maxlength' => 16, 'required' => true],
		['text' => 'Numero della carta di identità', 'name' => 'national_id', 'value' => $data['national_id'] ?? false, 'maxlength' => 16, 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Account PayPal'],
		['type' => 'email', 'text' => 'Indirizzo e-email associato al tuo account PayPal', 'name' => 'paypal', 'value' => $data['paypal'] ?? false, 'required' => true],
	],

];

if (isset($_POST['submit'])) {

	if ($data) {
		form_db_update($tbl, $inputs, $index, auth()->id());

		foreach ($documents as $key => $document) {
			if (file_exists($document['server-path'])) {
				unlink($document['server-path']);
			}
		}
	} else {
		form_db_add($tbl, $inputs);
	}

	redirect_old('?edit=0');
}

if (isset($_POST['download'])) {

	\Legacy\System\Classes\LegacyDB::update('stores SET request = 1 WHERE user_id = ?', [auth()->id()]);

	if ($data['total'] != $seller->toBePayed() || !file_exists($documents[0]['server-path'])) {
		\Legacy\System\Classes\LegacyDB::update('stores SET request = 1 WHERE user_id = ?', [auth()->id()]);
		\Legacy\System\Classes\LegacyDB::update('payment_requests SET total = ? WHERE seller_id = ?', [$seller->toBePayed(), auth()->id()]);

		foreach ($documents as $document) {
			if (file_exists($document['server-path'])) {
				unlink($document['server-path']);
			}

			$html = file_get_contents(__DIR__ . '/' . $document['template'] . '-template.html');

			$payment['gross'] = format($seller->toBePayed(), 2);
			$payment['net'] = round($payment['gross'] * .8, 2);
			$payment['netInt'] = floor($payment['net']);
			$payment['netDecimals'] = ($payment['net'] - $payment['netInt']) * 100;
			$payment['tax'] = round($payment['gross'] * .2, 2);

			$dateNow = new DateTimeImmutable();
			$date['start'] = $dateNow->format('d/m/Y');
			$date['end'] = $dateNow->modify('+' . $waitingDays . ' day');
			$date['end'] = $date['end']->format('d/m/Y');

			$html = str_replace('{{first_name}}', ucfirst(strtolower($data['first_name'])), $html);
			$html = str_replace('{{last_name}}', ucfirst(strtolower($data['last_name'])), $html);
			$html = str_replace('{{birthdate}}', implode('/', array_reverse(explode('-', $data['birthdate']))), $html);
			$html = str_replace('{{birthplace}}', $data['birthplace'], $html);
			$html = str_replace('{{birthprovince}}', strtoupper($data['birthprovince']), $html);
			$html = str_replace('{{address}}', $data['address'], $html);
			$html = str_replace('{{city}}', $data['city'], $html);
			$html = str_replace('{{province}}', strtoupper($data['province']), $html);
			$html = str_replace('{{zip}}', $data['zip'], $html);
			$html = str_replace('{{cf}}', strtoupper($data['cf']), $html);
			$html = str_replace('{{national_id}}', strtoupper($data['national_id']), $html);
			$html = str_replace('{{gross}}', formatc($payment['gross']), $html);
			$html = str_replace('{{net}}', formatc($payment['net']), $html);
			$html = str_replace('{{netWord}}', numberToWord($payment['netInt']), $html);
			$html = str_replace('{{netDecimals}}', str_pad($payment['netDecimals'], 2, '0', STR_PAD_LEFT), $html);
			$html = str_replace('{{tax}}', formatc($payment['tax']), $html);
			$html = str_replace('{{signature}}', '/pages/assets/manage/payments/request/signature.jpg', $html);
			$html = str_replace('{{mark}}', ($payment['net'] >= 80 ? "Marca da bollo<br>sull'originale" : ''), $html);
			$html = str_replace('{{date-start}}', $date['start'], $html);
			$html = str_replace('{{date-end}}', $date['end'], $html);

			$options = new Dompdf\Options();
			$options->set('isRemoteEnabled', true);
			$dompdf = new Dompdf\Dompdf($options);

			$dompdf->loadHtml($html);

			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$output = $dompdf->output();

			if (!file_exists(public_storage($document['folder']))) {
				mkdir(public_storage($document['folder']), 0777, true);
			}

			$pdfFileName = public_storage($document['folder'] . '/' . $document['file-name']);

			file_put_contents($pdfFileName, $output);
		}
	}

	redirect_old(route('download', ['filename' => $document['folder'] . '/' . $documents[0]['file-name']]));
}
