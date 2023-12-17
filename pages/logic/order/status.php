<?php

//setup page
$error = false;
$title = 'Stato ordine';

//form
$inputs = [
	[
		['text' => 'Indirizzo email', 'name' => 'email', 'required' => true],
	],
	[
		['text' => 'Numero ordine', 'name' => 'id', 'required' => true],
		['type' => 'error', 'text' => 'Nessun ordine trovato corrispondente ai dati inseriti', 'error' => 'no_match'],
	],
];

//submit
if (isset($_POST['submit'])) {

	$id = $_POST['id'];

	$email = strtolower(trim($_POST['email']));

	$buyerID = DB::find('buyer FROM addresses WHERE cartID=? AND email=?', [$id, $email]);

	if ($buyerID) {

		redirect('/order?id=' . $id . '&buyer=' . $buyerID);
	} else $error = 'no_match';
}
