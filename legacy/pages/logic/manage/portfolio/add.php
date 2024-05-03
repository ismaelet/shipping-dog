<?php

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
