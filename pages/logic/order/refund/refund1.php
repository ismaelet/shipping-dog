<?php

$stepId = 1;

include_once('setup.php');

if (isset($_POST['submit'])) {

	$id = $_POST['id'];
	$email = strtolower(trim($_POST['email']));

	if (DB::exists('addresses WHERE cartID = ? AND email=?', [$id, $email])) {

		$_SESSION['refID'] = $id;

		if ($status = DB::find('status FROM refunds WHERE cartID = ?', $id)) {

			if ($status > 1) redirect('/refund?step=' . $status);

			else {
				DB::update('refunds SET status=2 WHERE cartID = ?', [$id]);
				redirect('/refund?step=2');
			}
		} else {

			DB::add('refunds (cartID, status) VALUES (?, ?)', [$id, 2]);

			redirect('/refund?step=2');
		}
	} else $error = 'match';
}

$inputs = [
	[
		['name' => 'email', 'text' => 'Indirizzo e-email', 'required' => true],
	],
	[
		['name' => 'id', 'text' => 'Numero ordine', 'required' => true],
		['type' => 'error', 'text' => 'Nessun ordine trovato', 'error' => 'match'],
	],
];
