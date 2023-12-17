<?php

// setup variables

use Ecommerce\Seller;

$tbl = 'payment_requests';
$index = 'shopId';
$edit = $_GET['edit'] ?? false;
$done = $_GET['edit'] ?? false;

// documents
$documents = [
	[
		'template' => 'recipt',
		'folder' => '/uploads/documents/payments',
		'file-name' => 'Ricevuta_' . userId() . '.pdf',
	],
	[
		'template' => 'contract',
		'folder' => '/uploads/documents/payments',
		'file-name' => 'Comunicazione_' . userId() . '.pdf',
	]
];

foreach ($documents as $key => $document) {

	$documents[$key]['server-path'] = SERVER_ROOT . $document['folder'] . '/' . $document['file-name'];
	$documents[$key]['client-path'] = $document['folder'] . '/' . $document['file-name'];
}

// redirect if the user isn't a seller
$seller = new Seller(userId());

if (!$seller->id || $seller->toPay <= 31.25) {
	redirect('/manage/sales/list');
}

$waitingDays = ceil($seller->toPay / 100);

// fetch the seller data
$data = DB::row('* FROM ' . $tbl . ' WHERE ' . $index . '=?', [userId()]);

$inputs = [
	[
		['type' => 'lbl', 'text' => 'Nome e cognome'],
		['type' => 'hidden', 'name' => 'shopID', 'value' => userId(), 'required' => true],
		['text' => 'Nome', 'name' => 'firstname', 'value' => $data['firstname'] ?? false, 'size' => 2, 'maxlength' => 100, 'required' => true],
		['text' => 'Cognome', 'name' => 'lastname', 'value' => $data['lastname'] ?? false, 'size' => 2, 'maxlength' => 100, 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Data di nascita'],
		['type' => 'date', 'name' => 'birthdate', 'value' => $data['birthdate'] ?? false, 'min' => date('Y-m-d', strtotime('-100 year')), 'max' => date('Y-m-d'), 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Città e provincia di nascita'],
		['text' => 'Città', 'name' => 'birthplace', 'value' => $data['birthplace'] ?? false, 'size' => 3, 'maxlength' => 50, 'required' => true],
		['text' => 'Provincia', 'name' => 'birthprovince', 'value' => $data['birthprovince'] ?? false, 'custom-class' => 'txt-upp', 'size' => 1, 'maxlength' => 2, 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Indirizzo di residenza'],
		['text' => 'Indirizzo', 'name' => 'address', 'value' => $data['address'] ?? false, 'maxlength' => 200, 'required' => true],
		['text' => 'Città', 'name' => 'city', 'value' => $data['city'] ?? false, 'size' => 2, 'maxlength' => 50, 'required' => true],
		['text' => 'Provincia', 'name' => 'province', 'value' => $data['province'] ?? false, 'custom-class' => 'txt-upp', 'size' => 1, 'maxlength' => 2, 'required' => true],
		['text' => 'CAP', 'name' => 'zip', 'value' => $data['zip'] ?? false, 'size' => 1, 'maxlength' => 5, 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Dati personali'],
		['text' => 'Codice fiscale', 'name' => 'cf', 'value' => $data['cf'] ?? false, 'custom-class' => 'txt-upp', 'maxlength' => 16, 'required' => true],
		['text' => 'Numero della carta di identità', 'name' => 'nationalId', 'value' => $data['nationalId'] ?? false, 'maxlength' => 16, 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Account PayPal'],
		['type' => 'email', 'text' => 'Indirizzo e-email associato al tuo account PayPal', 'name' => 'paypal', 'value' => $data['paypal'] ?? false, 'required' => true],
	],

];

if (isset($_POST['submit'])) {

	if ($data) {

		form_db_update($tbl, $inputs, $index, userId());

		foreach ($documents as $key => $document)
			if (file_exists($document['server-path']))
				unlink($document['server-path']);
	} else
		form_db_add($tbl, $inputs);

	redirect('?edit=0');
}


if (isset($_POST['download'])) {

	DB::update('stores SET request=1 WHERE userID=?', [userId()]);

	if ($data['total'] == $seller->toPay && file_exists($documents[0]['server-path'])) {
		redirect($documents[0]['client-path']);
	} else {
		DB::update('stores SET request=1 WHERE userID=?', [userId()]);
		DB::update('payment_requests SET total=? WHERE shopId=?', [$seller->toPay, userId()]);

		foreach ($documents as $document) {

			if (file_exists($document['server-path'])) unlink($document['server-path']);

			$html = file_get_contents(__DIR__ . '/' . $document['template'] . '-template.html');

			$payment['gross'] = format($seller->toPay, 2);
			$payment['net'] = round($payment['gross'] * .8, 2);
			$payment['netInt'] = floor($payment['net']);
			$payment['netCents'] = ($payment['net'] - $payment['netInt']) * 100;
			$payment['tax'] = round($payment['gross'] * .2, 2);

			$dateNow = new DateTimeImmutable();
			$date['start'] = $dateNow->format('d/m/Y');
			$date['end'] = $dateNow->modify('+' . $waitingDays . ' day');
			$date['end'] = $date['end']->format('d/m/Y');

			$html = str_replace('{{firstname}}', ucfirst(strtolower($data['firstname'])), $html);
			$html = str_replace('{{lastname}}', ucfirst(strtolower($data['lastname'])), $html);
			$html = str_replace('{{birthdate}}', implode('/', array_reverse(explode('-', $data['birthdate']))), $html);
			$html = str_replace('{{birthplace}}', $data['birthplace'], $html);
			$html = str_replace('{{birthprovince}}', strtoupper($data['birthprovince']), $html);
			$html = str_replace('{{address}}', $data['address'], $html);
			$html = str_replace('{{city}}', $data['city'], $html);
			$html = str_replace('{{province}}', strtoupper($data['province']), $html);
			$html = str_replace('{{zip}}', $data['zip'], $html);
			$html = str_replace('{{cf}}', strtoupper($data['cf']), $html);
			$html = str_replace('{{nationalId}}', strtoupper($data['nationalId']), $html);
			$html = str_replace('{{gross}}', formatc($payment['gross']), $html);
			$html = str_replace('{{net}}', formatc($payment['net']), $html);
			$html = str_replace('{{netWord}}', numberToWord($payment['netInt']), $html);
			$html = str_replace('{{netCents}}', str_pad($payment['netCents'], 2, '0', STR_PAD_LEFT), $html);
			$html = str_replace('{{tax}}', formatc($payment['tax']), $html);
			$html = str_replace('{{signature}}', 'https:// shippingdog.com/pages/assets/manage/payments/request/signature.jpg', $html);
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

			$pdfFileName = SERVER_ROOT . $document['folder'] . '/' . $document['file-name'];
			file_put_contents($pdfFileName, $output);
		}

		redirect($documents[0]['client-path']);
	}
}
