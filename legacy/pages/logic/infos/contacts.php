<?php

use Legacy\System\Classes\Email;

$sent = false;
$error = false;

// send email
if (isset($_POST['submit']) && !$sent) {

	$name = trim($_POST['name']);
	$clientEmail = trim($_POST['email']);
	$mailText = trim($_POST['text']);

	$serv = explode('@', $clientEmail)[1];
	$serv = explode('.', $serv)[0];

	if (!in_array($serv, ['tiscali', 'gmail', 'libero', 'yahoo', 'hotemail', 'icloud', 'outlook'])) $error = 'email_bad';
	else if (preg_match("/(sexy|bitcoin|wellness|dollars|https|free|business)/i", $mailText)) $error = 'word_bad';
	else {
		$mailText = '<h1 style="text-align: center; padding-bottom: 16px; font-weight: 600;">Richiesta informazioni</h1>
		<p>Hai ricevuto una richiesta di informazioni da parte di <strong>' . $name . '</strong> (<a href="mailto:' . $clientEmail . '">' . $clientEmail . '</a>) con la seguente domanda:<br><br>"' . $mailText . '"</p>';

		$emailBody = Email::prepareBody($mailText);

		// send email
		$emailData = [
			'debug' => false,
			'from-address' => sys_config('email-username'),
			'from-name' => sys_config('name'),
			'to' => sys_config('contact-email'),
			'reply-address' => $clientEmail,
			'reply-name' => $name,
			'subject' => 'Richiesta informazioni',
			'text' => $emailBody,
		];

		Email::send($emailData);

		$sent = true;
	}
}
