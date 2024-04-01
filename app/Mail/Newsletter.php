<?php

namespace App\Mail;

use App\Models\Newsletter\Newsletter as NewsletterNewsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Newsletter extends Mailable {

	use Queueable, SerializesModels;

	public $subject;
	public $content;
	public $subscriberEmail;

	public function __construct($subscriber, $newsletterId) {
		$newsletterRecord = NewsletterNewsletter::find($newsletterId);

		$this->subscriberEmail = $subscriber->email;
		$this->subject = $newsletterRecord->subject;
		$this->content = $newsletterRecord->content;
		$this->content = str_replace('{{ $email }}', $subscriber->email, $newsletterRecord->content);
	}

	public function build() {
		return $this
			->subject($this->subject)
			->view('emails.default-email');
	}
}
