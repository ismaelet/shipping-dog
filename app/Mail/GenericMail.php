<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable {

	use Queueable, SerializesModels;

	public $subject;
	public $content;

	/**
	 * Create a new message instance
	 */
	public function __construct(string $subject, string $content, array $parameters = []) {
		// Set the subject and content
		$this->subject = $subject;
		$this->content = $content;

		// Replace parameters in the content
		foreach ($parameters as $key => $value) {
			$this->content = str_replace('{{ ' . $key . ' }}', $value, $this->content);
		}
	}

	/**
	 * Build the message
	 */
	public function build(): self {
		return $this
			->subject($this->subject)
			->view('emails.default-email');
	}
}
