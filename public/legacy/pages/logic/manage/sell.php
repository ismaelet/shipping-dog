<?php

global $user;

// setup page
if ($user->is_seller()) redirect('/manage');

// form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Nome negozio'],
		['text' => 'Nome negozio', 'name' => 'name', 'maxlenght' => 30, 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Descrizione'],
		['type' => 'textarea', 'text' => 'Descrizione del negozio (opzionale)', 'name' => 'desc', 'maxlenght' => 100],
	],
	[
		['type' => 'cbx', 'text' => 'Ho letto e accetto i <a class="txt-med txt-c2" href="' . client_url('/infos/terms') . '">termini di vendita</a>', 'required' => true],
	]
];

// submit
if (isset($_POST['submit'])) {

	$name = trim($_POST['name']);
	$desc = trim($_POST['desc']);

	DB::add('stores (userID, name, description) VALUES (?, ?, ?)', [$user->id, $name, $desc]);

	redirect('/manage/new');
}
