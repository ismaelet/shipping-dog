<?php

//setup variables
$error = false;
$done = false;

//recover data
if (isset($_POST['submit'])) {
	$email = $_POST['email'];

	if ($userData = DB::row('ID, username FROM users WHERE email=?', [$email])) {
		if (!isset($_SESSION['recover-sent'])) {
			$_SESSION['recover-sent'] = true;

			$newPassword = bin2hex(random_bytes(6));

			DB::update('users SET password=? WHERE ID=?', [$newPassword, $userData['ID']]);

			$mailText = '<h1 style="text-align: center; padding-bottom: 16px; font-weight: 600;">Recupero dati di accesso</h1>
			<p>Come richiesto, abbiamo reimpostato la tua password di accesso. Dopo il primo accesso potrai cambiarla in una password a tua scelta. I nuovi dati di accesso sono i seguenti:<br><br>
			<span style="font-weight: 700;">Username:</span> ' . $userData['username'] . '<br>
			<span style="font-weight: 700;">Password:</span> ' . $newPassword . '</p>';

			$emailBody = Email::prepareBody($mailText);

			$emailData = [
				'debug' => false,
				'from-address' => 'noreply@shippingdog.com',
				'from-name' => 'Shipping Dog',
				'to' => $email,
				'reply-address' => 'giebapromotion@gmail.com',
				'subject' => 'Recupero dati di accesso',
				'text' => $emailBody
			];

			Email::send($emailData);
		}

		$done = true;
	} else {
		$error = 'no-match';
	}
}
