<?php

use App\Helpers\FileHelper;
use App\Models\Ecommerce\Design as EcommerceDesign;
use Ecommerce\Design;
use Ecommerce\Design\LogoGroup;
use Ecommerce\Product;
use Illuminate\Support\Facades\Auth;

global $user;

// setup errors
$error = false;

// setup form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Titolo della grafica'],
		[
			'name' => 'name',
			'text' => 'Titolo della grafica',
			'maxlenght' => 30,
			'required' => true,
		],
		['type' => 'error', 'error' => 'name-empty', 'text' => 'È obbligatario scegliere un nome',],
		['type' => 'error', 'error' => 'name-exists', 'text' => 'Esiste già una grafica con questo nome',],
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
	$error = add_new_design();

	if ($error) {
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

function add_new_design(): string|false {
	// Get the design name
	$name = trim($_POST['name']);

	if (empty($name)) {
		return 'name-empty';
	}

	if (EcommerceDesign::where('name', $name)
		->where('userID', Auth::id())
		->where('status', 1)
		->exists()
	) {
		return 'name-exists';
	}

	// Get design ID
	$desID = DBOld::row('MAX(ID) as max FROM designs')['max'] + 1;

	// Upload image
	$file_vector = false;
	$file = 'designs/' . $desID . '.png';
	$file_m = 'designs/' . $desID . '_m.png';
	$file_ori = 'designs_originals/' . $desID . '.png';

	// Store the original file
	$newFile = FileHelper::uploadFile(request(), $file_ori);

	// Store the usable HD file
	$newFile = FileHelper::uploadImage([
		'path' => $file,
		'size' => 1024,
		'type' => 'png',
	]);

	// Store the usable LD file
	$newFile = FileHelper::uploadImage([
		'path' => $file_m,
		'size' => 512,
		'type' => 'png',
	]);

	// Upload the vector file
	if (!empty($_FILES['file-vector']['name'])) {
		$type = strtolower(strrev(explode('.', strrev($_FILES['file-vector']['name']))[0]));

		if (in_array($type, ['pdf', 'ai'])) {
			$file_vector = 'designs_vector/' . $desID . '.' . $type;

			$newFile = FileHelper::uploadFile(request(), $file_vector);
		}
	}

	$reso = getimagesize($_FILES['file']['tmp_name']);
	// Add databese entry
	$ratio = round($reso[0] / $reso[1], 2);

	$designId = DBOld::add('designs (ID, userID, name, status, ratio) VALUES (?, ?, ?, ?, ?)', [$desID, auth()->id(), $name, 1, $ratio]);

	// Add logo
	$sellerLogoGroupId = DBOld::find(LogoGroup::INDEX . ' FROM ' . LogoGroup::TABLE . ' WHERE ' . LogoGroup::SELLER_INDEX . ' = ? ORDER BY ' . LogoGroup::INDEX . ' DESC', [auth()->id()]);

	if ($sellerLogoGroupId) {
		DBOld::update(Design::TABLE . ' SET ' . Design::LOGO_GROUP_INDEX . ' = ? WHERE ' . Design::INDEX . ' = ?', [$sellerLogoGroupId, $designId]);
	}

	// Add items
	$res = auth()->id() != 1 ? DBOld::select('ID FROM items_types WHERE hidden = 0') : DBOld::select('ID FROM items_types WHERE ID=16');

	while ($row = $res->fetch_assoc()) {
		Product::new($desID, $row['ID']);
	}

	// Make the user an artist if it's a regular user
	DBOld::update('users SET level = 1 WHERE ID = ? AND level=0', [auth()->id()]);

	// Add tags
	update_tags($_POST['tags'], $desID);

	// Redirect
	redirect_old('/manage/portfolio/edit?id=' . $desID);
}
