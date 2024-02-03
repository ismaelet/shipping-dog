<?php

use Ecommerce\Design;
use Ecommerce\Design\LogoGroup;
use Ecommerce\Product;

global $user;

// setup errors
$error = false;

// setup form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Nome design'],
		[
			'name' => 'name',
			'text' => 'Nome design',
			'maxlenght' => 30,
			'required' => true,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Tag'],
		[
			'type' => 'textarea',
			'name' => 'tags',
			'text' => 'Tag (opzionali)',
			'pholder' => 'Puoi inserire fino a 10 tag separati da virgola. I tag rendono il tuo design più facile da trovare.',
			'maxlenght' => 160,
		],
	],
	[
		[
			'type' => 'file',
			'name' => 'file',
			'required' => true,
		],
	],
	[
		[
			'type' => 'lbl',
			'text' => 'File vettoriale (opzionale)',
		],
		[
			'type' => 'info', 'text' => 'Anche se non è obbligatorio, consigliamo di caricare la grafica anche in formato <span class="txt-bold">PDF</span> o <span class="txt-bold">AI</span> per la <span class="text-primary">massima qualità di stampa</span> possibile.',
		],
		[
			'type' => 'file',
			'name' => 'file-vector',
		],
	],
	[
		['type' => 'error', 'error' => 'file-size', 'text' => 'Il file caricato è troppo grande',],
		['type' => 'error', 'error' => 'file-type', 'text' => 'Tipo di file invalido, deve essere un <strong>PNG</strong>',],
		['type' => 'error', 'error' => 'file-reso', 'text' => 'Il file caricato deve avere una risoluzione minore di <strong>10.000px</strong>',],
		['type' => 'error', 'error' => 'error', 'text' => "C'è stato un errore durante il caricamento, se persite contattarci a <strong>" . sys_config('contact-email') . "</strong>",],
	],
	[
		[
			'type' => 'cbx',
			'name' => 'cbx',
			'text' => 'Dichiari di aver letto e accettato le <a class="txt-bold" href="' . client_url('/infos/contract') . '">condizioni di invio</a>',
			'required' => true,
		],
	],
];

// upload design
if (isset($_POST['submit'])) {

	// get inputs data
	$name = trim($_POST['name']);

	// get design ID
	$desID = DB::row('MAX(ID) as max FROM designs')['max'] + 1;

	// upload image
	$file_vector = false;
	$file = '/uploads/designs/' . $desID . '.png';
	$file_m = '/uploads/designs/' . $desID . '_m.png';
	$file_ori = '/uploads/designs_originals/' . $desID . '.png';

	if (!empty($_FILES['file-vector']['name'])) {

		$type = strtolower(strrev(explode('.', strrev($_FILES['file-vector']['name']))[0]));

		if (in_array($type, ['pdf', 'ai'])) {
			$file_vector = '/uploads/designs_vector/' . $desID . '.' . $type;
		} else {
			$file_vector = false;
		}
	}

	$error = upload_img_OLD($file, 'png', '1024');
	$error_m = upload_img_OLD($file_m, 'png', '450');

	$reso = getimagesize($_FILES['file']['tmp_name']);
	upload_file($file_ori);

	if ($file_vector) {
		upload_file($file_vector, 'file-vector');
	}

	// add databese entry
	if (!$error && !$error_m) {

		// add design
		$ratio = round($reso[0] / $reso[1], 2);

		$designId = DB::add('designs (ID, userID, name, status, ratio) VALUES (?, ?, ?, ?, ?)', [$desID, userId(), $name, 1, $ratio]);

		// add logo
		$sellerLogoGroupId = DB::find(LogoGroup::INDEX . ' FROM ' . LogoGroup::TABLE . ' WHERE ' . LogoGroup::SELLER_INDEX . ' = ? ORDER BY ' . LogoGroup::INDEX . ' DESC', [userId()]);

		if ($sellerLogoGroupId) {
			DB::update(Design::TABLE . ' SET ' . Design::LOGO_GROUP_INDEX . ' = ? WHERE ' . Design::INDEX . ' = ?', [$sellerLogoGroupId, $designId]);
		}

		// add items
		$res = userId() != 1 ? DB::select('ID FROM items_types WHERE hidden = 0') : DB::select('ID FROM items_types WHERE ID=16');

		while ($row = $res->fetch_assoc()) {
			Product::new($desID, $row['ID']);
		}

		// make the user an artist if it's a regular user
		DB::update('users SET level = 1 WHERE ID = ? AND level=0', [userId()]);

		// add tags
		update_tags($_POST['tags'], $desID);

		// redirect
		redirect('/manage/portfolio/edit?id=' . $desID);
	} else {
		$inputs[2] = [
			['type' => 'lbl', 'text' => 'Immagine (PNG)'],
			[
				'type' => 'file',
				'name' => 'file',
				'text' => 'Immagine (PNG)',
				'required' => true,
			]
		];
	}
}
