<?php

namespace App\Legacy\System;

/**
 * Requires: phpmailer/phpmailer
 */

/*
* Parameters:
*	? (bool) debug: to show a log
*	? (string) host: something like email.domain.com, required but it's taken from the config file if not defined
*	? (string) email-username: required but it's taken from the config file if not defined
*	? (string) email-passsword: required but it's taken from the config file if not defined
*	? (string) secure: usually is not defined and it's set automatically to ssl
*	? (string) from-address: if not defined it's the email-username
*	? (string) from-name: can be set to have a proper sender name instead of just an email address
*	? (string) reply-address: can be set to have the reciver reply to a different email address than the from-address
*	? (string) reply-name: can be set to have a more complete reply-address
*	? (array) cc: additional receivers
*	(string) to: receiver's email address
*	(string) subject: the subject of the email
*	(string) text: the body of the email
*/

use PHPMailer\PHPMailer\PHPMailer;

class Email {

	// send the email
	static function send($data) {

		$email = new PHPMailer();

		// email setup
		$data['from-address'] = $data['from-address'] ?? sys_config('email-username');
		$data['from-name'] = $data['from-name'] ?? sys_config('name');

		// SMTP setup
		$email->CharSet = 'UTF-8';
		$email->SMTPDebug = !empty($data['debug']);
		$email->isSMTP();
		$email->Host = $data['host'] ?? sys_config('email-host');
		$email->SMTPAuth = true;
		$email->Username = $data['username'] ?? sys_config('email-username');
		$email->Password = $data['password'] ?? sys_config('email-passsword');
		$email->SMTPSecure = $data['secure'] ?? 'ssl';
		$email->Port = $data['port'] ?? 465;
		$email->setFrom($data['from-address'], $data['from-name']);

		// recipient
		$email->addAddress($data['to']);

		if (isset($data['cc'])) {
			$ccs = (array) $data['cc'];

			foreach ($ccs as $cc) $email->AddCC($cc);
		}

		// reply
		if (isset($data['reply-address']) && isset($data['reply-name'])) $email->addReplyTo($data['reply-address'], $data['reply-name']);
		else if (isset($data['reply-address'])) $email->addReplyTo($data['reply-address']);

		// content
		$email->isHTML(true);
		$email->Subject = $data['subject'];
		$email->Body = $data['text'];

		// send
		$email->Send();
	}

	// prepare a standard email body
	static function prepareBody($content) {
		$logo = CLIENT_URL . sys_config('logo');
		$instagramLink = sys_config('contact-instagram-link') ? '
			<tr>
				<td style="text-align: center">
					<a class="no-link" href="' . sys_config('contact-instagram-link') . '">
						<img
							style="display: inline-block; vertical-align: middle; margin-right: 5px; max-width: 18px; max-height: 18px;"
							src="' . CLIENT_URL . '/system/assets/icons/contacts/instagram-32.png"
							alt=""
						/>
						<p style="width: 103px; display: inline-block; vertical-align: middle; font-size: 12px; text-align: left">' . sys_config('contact-instagram') . '</p>
					</a>
				</td>
			</tr>' : '';

		$facebookLink = sys_config('contact-facebook-link') ? '
			<tr>
				<td style="text-align: center">
					<a class="no-link" href="' . sys_config('contact-facebook-link') . '">
						<img
							style="display: inline-block; vertical-align: middle; margin-right: 5px; max-width: 18px; max-height: 18px;"
							src="' . CLIENT_URL . '/system/assets/icons/contacts/facebook-32.png"
							alt=""
						/>
						<p style="width: 103px; display: inline-block; vertical-align: middle; font-size: 12px; text-align: left">' . sys_config('contact-facebook') . '</p>
					</a>
				</td>
			</tr>' : '';

		$emailLink = sys_config('contact-email-link') ? '
				<tr>
					<td style="text-align: center">
						<a class="no-link" href="' . sys_config('contact-email-link') . '">
							<img
								style="display: inline-block; vertical-align: middle; margin-right: 5px; max-width: 18px; max-height: 18px;"
								src="' . CLIENT_URL . '/system/assets/icons/contacts/email-32.png"
								alt=""
							/>
							<p style="width: 103px; display: inline-block; vertical-align: middle; font-size: 12px; text-align: left">' . sys_config('contact-email') . '</p>
						</a>
					</td>
				</tr>' : '';

		$body = file_get_contents(base_path() . '/public/templates/assets/template-emails/default-framework.html');
		$body = str_replace('{{ logo }}', $logo, $body);
		$body = str_replace('{{ content }}', $content, $body);
		$body = str_replace('{{ instagram-link }}', $instagramLink, $body);
		$body = str_replace('{{ facebook-link }}', $facebookLink, $body);
		$body = str_replace('{{ email-link }}', $emailLink, $body);

		return $body;
	}
}
